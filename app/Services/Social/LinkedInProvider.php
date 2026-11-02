<?php

namespace App\Services\Social;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\SocialAccount;
use App\Services\Social\PostContentFormatter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class LinkedInProvider implements SocialProviderInterface
{
    /*
    |--------------------------------------------------------------------------
    | Publish
    |--------------------------------------------------------------------------
    */

    public function publish(
        SocialAccount $account,
        Post $post
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Validate account
        |--------------------------------------------------------------------------
        */

        if (
            strtolower(
                (string) $account->platform
            ) !== 'linkedin'
        ) {
            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Invalid social account. This provider requires a LinkedIn account.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Access token
        |--------------------------------------------------------------------------
        */

        $token = (string) $account->access_token;

        if ($token === '') {
            return [
                'success' => false,
                'status' => 401,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'LinkedIn access token is missing.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | LinkedIn API version
        |--------------------------------------------------------------------------
        */

        $version = config(
            'services.linkedin.version',
            '202608'
        );

        /*
        |--------------------------------------------------------------------------
        | Organization ID
        |--------------------------------------------------------------------------
        */

        $organizationId =
            data_get(
                $account->metadata,
                'organization_id'
            )
            ?? config(
                'services.linkedin.organization_id'
            )
            ?? $account->account_id;

        if (! $organizationId) {
            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'LinkedIn organization ID is not configured.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Organization URN
        |--------------------------------------------------------------------------
        */

        $author = str_starts_with(
            (string) $organizationId,
            'urn:li:organization:'
        )
            ? (string) $organizationId
            : 'urn:li:organization:' . $organizationId;

        /*
        |--------------------------------------------------------------------------
        | Build LinkedIn text
        |--------------------------------------------------------------------------
        |
        | Same behavior as your Python application:
        |
        | title + "\n\n" + description
        |
        | Your PostContentFormatter::english() already does this.
        |
        */

        $commentary = $this->commentary(
            $post
        );

        /*
        |--------------------------------------------------------------------------
        | Validate text BEFORE any upload
        |--------------------------------------------------------------------------
        */

        $validationError =
            $this->validateCommentary(
                $commentary
            );

        if ($validationError !== null) {
            return $validationError;
        }

        /*
        |--------------------------------------------------------------------------
        | Load media
        |--------------------------------------------------------------------------
        */

        $media = $post->media()
            ->orderBy('sort_order')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TEXT ONLY
        |--------------------------------------------------------------------------
        */

        if ($media->isEmpty()) {
            return $this->createPost(
                token: $token,
                author: $author,
                commentary: $commentary,
                content: null,
                version: $version
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Separate images and videos
        |--------------------------------------------------------------------------
        */

        $images = $media
            ->filter(
                function (PostMedia $item) {

                    return str_starts_with(
                        strtolower(
                            (string) $item->mime_type
                        ),
                        'image/'
                    );
                }
            )
            ->values();

        $videos = $media
            ->filter(
                function (PostMedia $item) {

                    return str_starts_with(
                        strtolower(
                            (string) $item->mime_type
                        ),
                        'video/'
                    );
                }
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Unsupported mixed media
        |--------------------------------------------------------------------------
        */

        if (
            $images->isNotEmpty()
            &&
            $videos->isNotEmpty()
        ) {
            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'LinkedIn does not support combining images and video in the same post through this implementation. Please use images only or one video.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MULTIPLE IMAGES
        |--------------------------------------------------------------------------
        |
        | LinkedIn MultiImage:
        | minimum 2 images
        | maximum 20 images
        |
        */

        if (
            $images->count() >= 2
        ) {

            if (
                $images->count() > 20
            ) {
                return [
                    'success' => false,
                    'status' => 422,
                    'external_id' => null,
                    'response' => [
                        'message' =>
                        'LinkedIn supports a maximum of 20 images per MultiImage post.',
                    ],
                ];
            }

            $imageUrns = [];

            foreach (
                $images as $image
            ) {

                $imageUrn =
                    $this->uploadImage(
                        token: $token,
                        owner: $author,
                        media: $image,
                        version: $version
                    );

                $imageUrns[] = [
                    'id' => $imageUrn,

                    'altText' =>
                    $this->imageAltText(
                        $post,
                        $image
                    ),
                ];
            }

            return $this->createMultiImagePost(
                token: $token,
                author: $author,
                commentary: $commentary,
                imageUrns: $imageUrns,
                version: $version
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ONE IMAGE
        |--------------------------------------------------------------------------
        */

        if (
            $images->count() === 1
        ) {

            $image =
                $images->first();

            $imageUrn =
                $this->uploadImage(
                    token: $token,
                    owner: $author,
                    media: $image,
                    version: $version
                );

            return $this->createPost(
                token: $token,
                author: $author,
                commentary: $commentary,
                content: [
                    'media' => [
                        'id' => $imageUrn,

                        'altText' =>
                        $this->imageAltText(
                            $post,
                            $image
                        ),
                    ],
                ],
                version: $version
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VIDEO
        |--------------------------------------------------------------------------
        */

        if (
            $videos->count() >= 1
        ) {

            if (
                $videos->count() > 1
            ) {
                return [
                    'success' => false,
                    'status' => 422,
                    'external_id' => null,
                    'response' => [
                        'message' =>
                        'LinkedIn supports one video per post in this implementation.',
                    ],
                ];
            }

            $video =
                $videos->first();

            $videoUrn =
                $this->uploadVideo(
                    token: $token,
                    owner: $author,
                    media: $video,
                    version: $version
                );

            return $this->createPost(
                token: $token,
                author: $author,
                commentary: $commentary,
                content: [
                    'media' => [
                        'title' =>
                        $video->file_name
                            ?: 'NGO Forum Video',

                        'id' =>
                        $videoUrn,
                    ],
                ],
                version: $version
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Unsupported media
        |--------------------------------------------------------------------------
        */

        return [
            'success' => false,
            'status' => 422,
            'external_id' => null,
            'response' => [
                'message' =>
                'LinkedIn supports text, image, MultiImage, and video posts.',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Commentary
    |--------------------------------------------------------------------------
    |
    | Python equivalent:
    |
    | text = (title or "") + ("\n\n" + description if description else "")
    |
    */

    protected function commentary(
        Post $post
    ): string {

        $text = app(
            PostContentFormatter::class
        )->english(
            $post
        );

        $text = trim(
            $text
        );

        Log::info(
            'LINKEDIN COMMENTARY',
            [
                'post_id' =>
                $post->id,

                'characters' =>
                mb_strlen(
                    $text,
                    'UTF-8'
                ),

                'first_200' =>
                mb_substr(
                    $text,
                    0,
                    200,
                    'UTF-8'
                ),

                'last_500' =>
                mb_substr(
                    $text,
                    -500,
                    null,
                    'UTF-8'
                ),
            ]
        );

        return $text;
    }

    /**
     * Escape reserved characters for LinkedIn's Little Text Format.
     *
     * This treats these characters as literal text. If you later add native
     * LinkedIn mentions or hashtags, handle those entities separately.
     */
    protected function escapeLittleText(string $text): string
    {
        return strtr($text, [
            '\\' => '\\\\',
            '`'   => '\\`',
            '|'   => '\\|',
            '{'   => '\\{',
            '}'   => '\\}',
            '@'   => '\\@',
            '['   => '\\[',
            ']'   => '\\]',
            '('   => '\\(',
            ')'   => '\\)',
            '<'   => '\\<',
            '>'   => '\\>',
            '#'   => '\\#',
            '*'   => '\\*',
            '_'   => '\\_',
            '~'   => '\\~',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Commentary
    |--------------------------------------------------------------------------
    */

    protected function validateCommentary(
        string $commentary
    ): ?array {

        $maxCharacters = (int) config(
            'services.linkedin.max_post_characters',
            3000
        );

        $characterCount =
            mb_strlen(
                $commentary,
                'UTF-8'
            );

        /*
        |--------------------------------------------------------------------------
        | Do NOT silently cut the post
        |--------------------------------------------------------------------------
        */

        if (
            $characterCount > $maxCharacters
        ) {

            Log::warning(
                'LINKEDIN COMMENTARY TOO LONG',
                [
                    'characters' =>
                    $characterCount,

                    'maximum' =>
                    $maxCharacters,

                    'first_200' =>
                    mb_substr(
                        $commentary,
                        0,
                        200,
                        'UTF-8'
                    ),
                ]
            );

            return [
                'success' => false,

                'status' => 422,

                'external_id' => null,

                'response' => [
                    'message' =>
                    "LinkedIn post is {$characterCount} characters. The maximum is {$maxCharacters} characters.",

                    'characters' =>
                    $characterCount,

                    'maximum' =>
                    $maxCharacters,
                ],
            ];
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Text / Single Media Post
    |--------------------------------------------------------------------------
    */

    protected function createPost(
        string $token,
        string $author,
        string $commentary,
        ?array $content,
        string $version
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Validate again
        |--------------------------------------------------------------------------
        */

        $validationError =
            $this->validateCommentary(
                $commentary
            );

        if (
            $validationError !== null
        ) {
            return $validationError;
        }

        /*
        |--------------------------------------------------------------------------
        | Payload
        |--------------------------------------------------------------------------
        */

        $body = [
            'author' =>
            $author,

            'commentary' =>
            $this->escapeLittleText($commentary),

            'visibility' =>
            'PUBLIC',

            'distribution' => [
                'feedDistribution' =>
                'MAIN_FEED',

                'targetEntities' =>
                [],

                'thirdPartyDistributionChannels' =>
                [],
            ],

            'lifecycleState' =>
            'PUBLISHED',

            'isReshareDisabledByAuthor' =>
            false,
        ];

        /*
        |--------------------------------------------------------------------------
        | Optional media
        |--------------------------------------------------------------------------
        */

        if (
            is_array($content)
            &&
            ! empty($content)
        ) {
            $body['content'] =
                $content;
        }

        /*
        |--------------------------------------------------------------------------
        | Log exact outgoing text
        |--------------------------------------------------------------------------
        */

        Log::info(
            'LINKEDIN CREATE POST',
            [
                'author' =>
                $author,

                'characters' =>
                mb_strlen(
                    $commentary,
                    'UTF-8'
                ),

                'maximum' =>
                config(
                    'services.linkedin.max_post_characters',
                    3000
                ),

                'has_media' =>
                ! empty($content),

                'first_100' =>
                mb_substr(
                    $commentary,
                    0,
                    100,
                    'UTF-8'
                ),

                'last_300' =>
                mb_substr(
                    $commentary,
                    -300,
                    null,
                    'UTF-8'
                ),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | API Request
        |--------------------------------------------------------------------------
        */

        $response =
            Http::withToken(
                $token
            )
            ->withHeaders([
                'Linkedin-Version' =>
                $version,

                'X-Restli-Protocol-Version' =>
                '2.0.0',

                'Content-Type' =>
                'application/json',
            ])
            ->connectTimeout(30)
            ->timeout(120)
            ->post(
                'https://api.linkedin.com/rest/posts',
                $body
            );

        /*
        |--------------------------------------------------------------------------
        | External Post ID
        |--------------------------------------------------------------------------
        */

        $externalId =
            $response->header(
                'x-restli-id'
            )
            ??
            $response->header(
                'X-RestLi-Id'
            );

        /*
        |--------------------------------------------------------------------------
        | Log API response
        |--------------------------------------------------------------------------
        */

        Log::info(
            'LINKEDIN API RESPONSE',
            [
                'status' =>
                $response->status(),

                'external_id' =>
                $externalId,

                'characters_sent' =>
                mb_strlen(
                    $commentary,
                    'UTF-8'
                ),

                'response' =>
                $response->json()
                    ?: $response->body(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Error
        |--------------------------------------------------------------------------
        */

        if (
            $response->failed()
        ) {
            return [
                'success' => false,

                'status' =>
                $response->status(),

                'external_id' =>
                $externalId,

                'response' =>
                $response->json()
                    ?: [
                        'body' =>
                        $response->body(),
                    ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return [
            'success' => true,

            'status' =>
            $response->status(),

            'external_id' =>
            $externalId,

            'response' =>
            $response->json()
                ?: [
                    'body' =>
                    $response->body(),
                ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create MultiImage Post
    |--------------------------------------------------------------------------
    */

    protected function createMultiImagePost(
        string $token,
        string $author,
        string $commentary,
        array $imageUrns,
        string $version
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Validate images
        |--------------------------------------------------------------------------
        */

        $count =
            count($imageUrns);

        if ($count < 2) {
            throw new RuntimeException(
                'LinkedIn MultiImage requires at least 2 images.'
            );
        }

        if ($count > 20) {
            throw new RuntimeException(
                'LinkedIn MultiImage supports a maximum of 20 images.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate commentary
        |--------------------------------------------------------------------------
        */

        $validationError =
            $this->validateCommentary(
                $commentary
            );

        if (
            $validationError !== null
        ) {
            return $validationError;
        }

        /*
        |--------------------------------------------------------------------------
        | Payload
        |--------------------------------------------------------------------------
        */

        $body = [
            'author' =>
            $author,

            'commentary' =>
            $this->escapeLittleText($commentary),

            'visibility' =>
            'PUBLIC',

            'distribution' => [
                'feedDistribution' =>
                'MAIN_FEED',

                'targetEntities' =>
                [],

                'thirdPartyDistributionChannels' =>
                [],
            ],

            'lifecycleState' =>
            'PUBLISHED',

            'isReshareDisabledByAuthor' =>
            false,

            'content' => [
                'multiImage' => [
                    'images' =>
                    $imageUrns,
                ],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Log
        |--------------------------------------------------------------------------
        */

        Log::info(
            'LINKEDIN MULTI IMAGE POST',
            [
                'characters' =>
                mb_strlen(
                    $commentary,
                    'UTF-8'
                ),

                'images' =>
                $count,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Request
        |--------------------------------------------------------------------------
        */

        $response =
            Http::withToken(
                $token
            )
            ->withHeaders([
                'Linkedin-Version' =>
                $version,

                'X-Restli-Protocol-Version' =>
                '2.0.0',

                'Content-Type' =>
                'application/json',
            ])
            ->connectTimeout(30)
            ->timeout(120)
            ->post(
                'https://api.linkedin.com/rest/posts',
                $body
            );

        $externalId =
            $response->header(
                'x-restli-id'
            )
            ??
            $response->header(
                'X-RestLi-Id'
            );

        Log::info(
            'LINKEDIN MULTI IMAGE RESPONSE',
            [
                'status' =>
                $response->status(),

                'external_id' =>
                $externalId,

                'response' =>
                $response->json()
                    ?: $response->body(),
            ]
        );

        return [
            'success' =>
            $response->successful(),

            'status' =>
            $response->status(),

            'external_id' =>
            $externalId,

            'response' =>
            $response->json()
                ?: [
                    'body' =>
                    $response->body(),
                ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Image
    |--------------------------------------------------------------------------
    */

    protected function uploadImage(
        string $token,
        string $owner,
        PostMedia $media,
        string $version
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Disk
        |--------------------------------------------------------------------------
        */

        $disk =
            Storage::disk(
                'public'
            );

        /*
        |--------------------------------------------------------------------------
        | Verify file
        |--------------------------------------------------------------------------
        */

        if (
            ! $disk->exists(
                $media->file_path
            )
        ) {
            throw new RuntimeException(
                'LinkedIn image file not found: '
                    . $media->file_path
            );
        }

        $path =
            $disk->path(
                $media->file_path
            );

        if (
            ! is_file($path)
        ) {
            throw new RuntimeException(
                'LinkedIn image file does not exist: '
                    . $path
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Initialize upload
        |--------------------------------------------------------------------------
        */

        $init =
            Http::withToken(
                $token
            )
            ->withHeaders([
                'Linkedin-Version' =>
                $version,

                'X-Restli-Protocol-Version' =>
                '2.0.0',

                'Content-Type' =>
                'application/json',
            ])
            ->timeout(60)
            ->post(
                'https://api.linkedin.com/rest/images?action=initializeUpload',
                [
                    'initializeUploadRequest' => [
                        'owner' =>
                        $owner,
                    ],
                ]
            );

        if (
            $init->failed()
        ) {
            throw new RuntimeException(
                'LinkedIn image upload initialization failed: '
                    . (
                        $init->body()
                        ?: 'unknown error'
                    )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Read upload response
        |--------------------------------------------------------------------------
        */

        $uploadUrl =
            $init->json(
                'value.uploadUrl'
            );

        $imageUrn =
            $init->json(
                'value.image'
            );

        if (
            ! $uploadUrl
            ||
            ! $imageUrn
        ) {
            throw new RuntimeException(
                'LinkedIn did not return a valid image upload URL or image URN.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Read image
        |--------------------------------------------------------------------------
        */

        $binary =
            file_get_contents(
                $path
            );

        if (
            $binary === false
        ) {
            throw new RuntimeException(
                'Unable to read LinkedIn image.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload binary
        |--------------------------------------------------------------------------
        */

        $mimeType =
            $media->mime_type
            ?: mime_content_type($path)
            ?: 'image/jpeg';

        $upload =
            Http::withBody(
                $binary,
                $mimeType
            )
            ->connectTimeout(30)
            ->timeout(120)
            ->put(
                $uploadUrl
            );

        if (
            $upload->failed()
        ) {
            throw new RuntimeException(
                'LinkedIn image upload failed: '
                    . $upload->body()
            );
        }

        Log::info(
            'LINKEDIN IMAGE UPLOADED',
            [
                'post_media_id' =>
                $media->id,

                'image_urn' =>
                $imageUrn,

                'size' =>
                filesize($path),
            ]
        );

        return $imageUrn;
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Video
    |--------------------------------------------------------------------------
    */

    protected function uploadVideo(
        string $token,
        string $owner,
        PostMedia $media,
        string $version
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Disk
        |--------------------------------------------------------------------------
        */

        $disk =
            Storage::disk(
                'public'
            );

        /*
        |--------------------------------------------------------------------------
        | Verify file
        |--------------------------------------------------------------------------
        */

        if (
            ! $disk->exists(
                $media->file_path
            )
        ) {
            throw new RuntimeException(
                'LinkedIn video file not found: '
                    . $media->file_path
            );
        }

        $path =
            $disk->path(
                $media->file_path
            );

        if (
            ! is_file($path)
        ) {
            throw new RuntimeException(
                'LinkedIn video file does not exist: '
                    . $path
            );
        }

        $fileSize =
            filesize(
                $path
            );

        if (
            $fileSize === false
        ) {
            throw new RuntimeException(
                'Unable to determine LinkedIn video size.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Initialize video upload
        |--------------------------------------------------------------------------
        */

        $init =
            Http::withToken(
                $token
            )
            ->withHeaders([
                'Linkedin-Version' =>
                $version,

                'X-Restli-Protocol-Version' =>
                '2.0.0',

                'Content-Type' =>
                'application/json',
            ])
            ->connectTimeout(30)
            ->timeout(60)
            ->post(
                'https://api.linkedin.com/rest/videos?action=initializeUpload',
                [
                    'initializeUploadRequest' => [
                        'owner' =>
                        $owner,

                        'fileSizeBytes' =>
                        $fileSize,

                        'uploadCaptions' =>
                        false,

                        'uploadThumbnail' =>
                        false,
                    ],
                ]
            );

        if (
            $init->failed()
        ) {
            throw new RuntimeException(
                'LinkedIn video upload initialization failed: '
                    . $init->body()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Response fields
        |--------------------------------------------------------------------------
        */

        $videoUrn =
            $init->json(
                'value.video'
            );

        $uploadToken =
            $init->json(
                'value.uploadToken',
                ''
            );

        $instructions =
            $init->json(
                'value.uploadInstructions',
                []
            );

        if (
            ! $videoUrn
            ||
            empty($instructions)
        ) {
            throw new RuntimeException(
                'LinkedIn returned no valid video upload instructions.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload every part
        |--------------------------------------------------------------------------
        */

        $uploadedPartIds = [];

        $stream =
            fopen(
                $path,
                'rb'
            );

        if (
            $stream === false
        ) {
            throw new RuntimeException(
                'Unable to open LinkedIn video.'
            );
        }

        try {

            foreach (
                $instructions as $index => $instruction
            ) {

                $firstByte =
                    (int) (
                        $instruction['firstByte']
                        ?? 0
                    );

                $lastByte =
                    (int) (
                        $instruction['lastByte']
                        ?? 0
                    );

                $length =
                    $lastByte
                    -
                    $firstByte
                    +
                    1;

                if (
                    $length <= 0
                ) {
                    throw new RuntimeException(
                        'Invalid LinkedIn video upload byte range.'
                    );
                }

                if (
                    fseek(
                        $stream,
                        $firstByte
                    ) !== 0
                ) {
                    throw new RuntimeException(
                        'Unable to seek LinkedIn video stream.'
                    );
                }

                $chunk =
                    fread(
                        $stream,
                        $length
                    );

                if (
                    $chunk === false
                    ||
                    $chunk === ''
                ) {
                    throw new RuntimeException(
                        'Unable to read LinkedIn video chunk.'
                    );
                }

                $uploadResponse =
                    Http::withBody(
                        $chunk,
                        $media->mime_type
                            ?: 'video/mp4'
                    )
                    ->connectTimeout(30)
                    ->timeout(300)
                    ->put(
                        $instruction['uploadUrl']
                    );

                if (
                    $uploadResponse->failed()
                ) {
                    throw new RuntimeException(
                        'LinkedIn video chunk upload failed: '
                            . $uploadResponse->body()
                    );
                }

                $etag =
                    trim(
                        (string) $uploadResponse->header(
                            'ETag'
                        ),
                        '"'
                    );

                if (
                    $etag === ''
                ) {
                    throw new RuntimeException(
                        "LinkedIn did not return ETag for video upload part {$index}."
                    );
                }

                $uploadedPartIds[] =
                    $etag;

                Log::info(
                    'LINKEDIN VIDEO PART UPLOADED',
                    [
                        'post_media_id' =>
                        $media->id,

                        'part' =>
                        $index + 1,

                        'total_parts' =>
                        count($instructions),

                        'first_byte' =>
                        $firstByte,

                        'last_byte' =>
                        $lastByte,

                        'etag' =>
                        $etag,
                    ]
                );
            }
        } finally {

            fclose(
                $stream
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Finalize
        |--------------------------------------------------------------------------
        */

        $finalize =
            Http::withToken(
                $token
            )
            ->withHeaders([
                'Linkedin-Version' =>
                $version,

                'X-Restli-Protocol-Version' =>
                '2.0.0',

                'Content-Type' =>
                'application/json',
            ])
            ->connectTimeout(30)
            ->timeout(120)
            ->post(
                'https://api.linkedin.com/rest/videos?action=finalizeUpload',
                [
                    'finalizeUploadRequest' => [

                        'video' =>
                        $videoUrn,

                        'uploadToken' =>
                        $uploadToken,

                        'uploadedPartIds' =>
                        $uploadedPartIds,
                    ],
                ]
            );

        if (
            $finalize->failed()
        ) {
            throw new RuntimeException(
                'LinkedIn video finalize failed: '
                    . $finalize->body()
            );
        }

        Log::info(
            'LINKEDIN VIDEO UPLOAD FINISHED',
            [
                'post_media_id' =>
                $media->id,

                'video_urn' =>
                $videoUrn,

                'parts' =>
                count($uploadedPartIds),
            ]
        );

        return $videoUrn;
    }

    /*
    |--------------------------------------------------------------------------
    | Image Alt Text
    |--------------------------------------------------------------------------
    */

    protected function imageAltText(
        Post $post,
        PostMedia $media
    ): string {

        $title =
            trim(
                (string) (
                    $post->title
                    ?:
                    $post->title_Kh
                    ?:
                    ''
                )
            );

        if (
            $title !== ''
        ) {
            return mb_substr(
                $title,
                0,
                120,
                'UTF-8'
            );
        }

        return
            $media->file_name
            ?:
            'NGO Forum image';
    }
}
