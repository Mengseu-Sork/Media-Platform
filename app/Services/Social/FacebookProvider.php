<?php

namespace App\Services\Social;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\SocialAccount;
use App\Services\Social\PostContentFormatter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class FacebookProvider implements SocialProviderInterface
{
    public function publish(
        SocialAccount $account,
        Post $post
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Facebook credentials
        |--------------------------------------------------------------------------
        */

        $token =
            (string) $account->access_token;

        $pageId =
            (string) $account->account_id;


        if (
            $token === '' ||
            $pageId === ''
        ) {

            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Facebook token or Page ID is missing.',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Graph API version
        |--------------------------------------------------------------------------
        */

        $version =
            config(
                'services.meta.graph_version',
                'v19.0'
            );


        /*
        |--------------------------------------------------------------------------
        | Message
        |--------------------------------------------------------------------------
        */

        $message =
            app(
                PostContentFormatter::class
            )->bilingual($post);


        /*
        |--------------------------------------------------------------------------
        | Media
        |--------------------------------------------------------------------------
        */

        $media =
            $post->media()
            ->orderBy('sort_order')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TEXT ONLY
        |--------------------------------------------------------------------------
        */

        if (
            $media->isEmpty()
        ) {

            $response =
                Http::asForm()
                ->timeout(60)
                ->post(
                    "https://graph.facebook.com/{$version}/{$pageId}/feed",
                    [
                        'message' =>
                        $message,

                        'access_token' =>
                        $token,
                    ]
                );

            return $this->result(
                $response
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Separate images and videos
        |--------------------------------------------------------------------------
        */

        $images =
            $media
            ->filter(
                fn($item) =>
                str_starts_with(
                    strtolower(
                        (string) $item->mime_type
                    ),
                    'image/'
                )
            )
            ->values();


        $videos =
            $media
            ->filter(
                fn($item) =>
                str_starts_with(
                    strtolower(
                        (string) $item->mime_type
                    ),
                    'video/'
                )
            )
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Do not mix images and video
        |--------------------------------------------------------------------------
        */

        if (
            $images->isNotEmpty() &&
            $videos->isNotEmpty()
        ) {

            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Facebook publishing supports images or one video, not images and video together.',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | VIDEO
        |--------------------------------------------------------------------------
        */

        if (
            $videos->isNotEmpty()
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
                        'Facebook supports one video per post in this implementation.',
                    ],
                ];
            }


            return $this->publishVideo(
                $token,
                $pageId,
                $videos->first(),
                $message,
                $version
            );
        }


        /*
        |--------------------------------------------------------------------------
        | IMAGES
        |--------------------------------------------------------------------------
        */

        if (
            $images->isEmpty()
        ) {

            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'No supported Facebook media was found.',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Maximum 30 images
        |--------------------------------------------------------------------------
        */

        if (
            $images->count() > 30
        ) {

            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'A maximum of 30 images can be processed by this Facebook publisher.',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | ONE IMAGE
        |--------------------------------------------------------------------------
        */

        if (
            $images->count() === 1
        ) {

            return $this->publishSingleImage(
                $token,
                $pageId,
                $images->first(),
                $message,
                $version
            );
        }


        /*
        |--------------------------------------------------------------------------
        | MULTIPLE IMAGES
        |--------------------------------------------------------------------------
        */

        return $this->publishMultipleImages(
            $token,
            $pageId,
            $images,
            $message,
            $version
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SINGLE IMAGE
    |--------------------------------------------------------------------------
    */

    protected function publishSingleImage(
        string $token,
        string $pageId,
        PostMedia $media,
        string $message,
        string $version
    ): array {

        $disk =
            Storage::disk('public');


        /*
        |--------------------------------------------------------------------------
        | Check file exists
        |--------------------------------------------------------------------------
        */

        if (
            ! $disk->exists(
                $media->file_path
            )
        ) {

            return [
                'success' => false,
                'status' => 404,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Facebook image file was not found.',
                    'file' =>
                    $media->file_path,
                ],
            ];
        }


        $path =
            $disk->path(
                $media->file_path
            );


        if (
            ! is_file($path)
        ) {

            return [
                'success' => false,
                'status' => 404,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Facebook image path is invalid.',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Open file
        |--------------------------------------------------------------------------
        */

        $handle =
            fopen(
                $path,
                'rb'
            );


        if (
            $handle === false
        ) {

            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Unable to read Facebook image.',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Upload directly to Facebook
        |--------------------------------------------------------------------------
        */

        $response =
            Http::attach(
                'source',
                $handle,
                $media->file_name
                    ?: basename($path)
            )
            ->timeout(120)
            ->post(
                "https://graph.facebook.com/{$version}/{$pageId}/photos",
                [
                    'caption' =>
                    $message,

                    'published' =>
                    'true',

                    'access_token' =>
                    $token,
                ]
            );


        fclose(
            $handle
        );


        return $this->result(
            $response
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MULTIPLE IMAGES
    |--------------------------------------------------------------------------
    */

    protected function publishMultipleImages(
        string $token,
        string $pageId,
        $images,
        string $message,
        string $version
    ): array {

        $disk =
            Storage::disk('public');


        $attachedMedia = [];


        /*
        |--------------------------------------------------------------------------
        | Upload each image as unpublished
        |--------------------------------------------------------------------------
        */

        foreach (
            $images as $image
        ) {

            if (
                ! $disk->exists(
                    $image->file_path
                )
            ) {

                return [
                    'success' => false,
                    'status' => 404,
                    'external_id' => null,
                    'response' => [
                        'message' =>
                        'Facebook image file was not found.',
                        'file' =>
                        $image->file_path,
                    ],
                ];
            }


            $path =
                $disk->path(
                    $image->file_path
                );


            if (
                ! is_file($path)
            ) {

                return [
                    'success' => false,
                    'status' => 404,
                    'external_id' => null,
                    'response' => [
                        'message' =>
                        'Facebook image path is invalid.',
                    ],
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Open local file
            |--------------------------------------------------------------------------
            */

            $handle =
                fopen(
                    $path,
                    'rb'
                );


            if (
                $handle === false
            ) {

                return [
                    'success' => false,
                    'status' => 422,
                    'external_id' => null,
                    'response' => [
                        'message' =>
                        'Unable to read Facebook image: ' .
                            (
                                $image->file_name
                                ?: basename($path)
                            ),
                    ],
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Upload as unpublished photo
            |--------------------------------------------------------------------------
            */

            $upload =
                Http::attach(
                    'source',
                    $handle,
                    $image->file_name
                        ?: basename($path)
                )
                ->timeout(120)
                ->post(
                    "https://graph.facebook.com/{$version}/{$pageId}/photos",
                    [
                        'published' =>
                        'false',

                        'access_token' =>
                        $token,
                    ]
                );


            fclose(
                $handle
            );


            /*
            |--------------------------------------------------------------------------
            | Facebook upload failed
            |--------------------------------------------------------------------------
            */

            if (
                $upload->failed()
            ) {

                return $this->result(
                    $upload
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Get photo ID
            |--------------------------------------------------------------------------
            */

            $photoId =
                $upload->json('id');


            if (
                ! $photoId
            ) {

                return [
                    'success' => false,
                    'status' => 422,
                    'external_id' => null,
                    'response' => [
                        'message' =>
                        'Facebook did not return an image ID.',
                        'facebook_response' =>
                        $upload->json()
                            ?: $upload->body(),
                    ],
                ];
            }


            $attachedMedia[] = [
                'media_fbid' =>
                $photoId,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Nothing uploaded
        |--------------------------------------------------------------------------
        */

        if (
            empty($attachedMedia)
        ) {

            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Facebook did not accept any images.',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Create final feed post
        |--------------------------------------------------------------------------
        */

        $payload = [

            'message' =>
            $message,

            'access_token' =>
            $token,
        ];


        foreach (
            $attachedMedia as $index => $attachment
        ) {

            $payload["attached_media[{$index}]"] =
                json_encode(
                    $attachment,
                    JSON_UNESCAPED_SLASHES
                );
        }


        $response =
            Http::asForm()
            ->timeout(120)
            ->post(
                "https://graph.facebook.com/{$version}/{$pageId}/feed",
                $payload
            );


        return $this->result(
            $response
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VIDEO
    |--------------------------------------------------------------------------
    */

    protected function publishVideo(
        string $token,
        string $pageId,
        PostMedia $media,
        string $message,
        string $version
    ): array {

        $disk =
            Storage::disk('public');


        if (
            ! $disk->exists(
                $media->file_path
            )
        ) {

            return [
                'success' => false,
                'status' => 404,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Facebook video file was not found.',
                ],
            ];
        }


        $path =
            $disk->path(
                $media->file_path
            );


        if (
            ! is_file($path)
        ) {

            return [
                'success' => false,
                'status' => 404,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Facebook video path is invalid.',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Open video
        |--------------------------------------------------------------------------
        */

        $handle =
            fopen(
                $path,
                'rb'
            );


        if (
            $handle === false
        ) {

            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'Unable to read Facebook video.',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Upload video directly
        |--------------------------------------------------------------------------
        */

        $response =
            Http::attach(
                'source',
                $handle,
                $media->file_name
                    ?: basename($path)
            )
            ->timeout(300)
            ->post(
                "https://graph.facebook.com/{$version}/{$pageId}/videos",
                [
                    'description' =>
                    $message,

                    'access_token' =>
                    $token,
                ]
            );


        fclose(
            $handle
        );


        return $this->result(
            $response
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Result
    |--------------------------------------------------------------------------
    */

    protected function result(
        $response
    ): array {

        return [

            'success' =>
            $response->successful(),

            'status' =>
            $response->status(),

            'external_id' =>
            $response->json('id'),

            'response' =>
            $response->json()
                ?: [
                    'body' =>
                    $response->body(),
                ],

        ];
    }
}
