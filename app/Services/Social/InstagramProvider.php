<?php

namespace App\Services\Social;

use App\Models\Post;
use App\Models\SocialAccount;
use App\Services\Social\PostContentFormatter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class InstagramProvider implements SocialProviderInterface
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

        $token =
            $account->access_token;

        $instagramId =
            $account->account_id;

        $version =
            config(
                'services.meta.graph_version',
                'v24.0'
            );


        /*
        |--------------------------------------------------------------------------
        | Load Media
        |--------------------------------------------------------------------------
        */

        $media =
            $post->media()
            ->orderBy('sort_order')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Instagram requires media
        |--------------------------------------------------------------------------
        */

        if (
            $media->isEmpty()
        ) {

            return [

                'success' =>
                false,

                'status' =>
                422,

                'external_id' =>
                null,

                'response' => [

                    'message' =>
                    'Instagram requires image or video media.',

                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Khmer FIRST + English SECOND
        |--------------------------------------------------------------------------
        */

        $caption =
            $this->message(
                $post
            );


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
        | Video
        |--------------------------------------------------------------------------
        */

        if (
            $videos->count() > 0
        ) {

            if (
                $media->count() !== 1
            ) {

                return [

                    'success' =>
                    false,

                    'status' =>
                    422,

                    'external_id' =>
                    null,

                    'response' => [

                        'message' =>
                        'Instagram video publishing currently supports one video per post.',

                    ],
                ];
            }


            $item =
                $videos->first();


            return $this->publishReel(

                $instagramId,

                $token,

                $this->publicMediaUrl(
                    $item
                ),

                $caption,

                $version
            );
        }


        /*
        |--------------------------------------------------------------------------
        | One image
        |--------------------------------------------------------------------------
        */

        if (
            $images->count() === 1
        ) {

            return $this->publishImage(

                $instagramId,

                $token,

                $this->publicMediaUrl(
                    $images->first()
                ),

                $caption,

                $version
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Maximum 10 carousel items
        |--------------------------------------------------------------------------
        */

        $images = $images->take(10)->values();


        /*
        |--------------------------------------------------------------------------
        | Carousel
        |--------------------------------------------------------------------------
        */

        return $this->publishCarousel(

            $instagramId,

            $token,

            $images,

            $caption,

            $version
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SINGLE IMAGE
    |--------------------------------------------------------------------------
    */

    protected function publishImage(
        string $instagramId,
        string $token,
        string $imageUrl,
        string $caption,
        string $version
    ): array {

        $container =
            Http::asForm()
            ->timeout(120)
            ->post(
                "https://graph.facebook.com/{$version}/{$instagramId}/media",
                [
                    'image_url' =>
                    $imageUrl,

                    'caption' =>
                    $caption,

                    'access_token' =>
                    $token,
                ]
            );


        if (
            $container->failed()
        ) {

            return $this->result(
                $container
            );
        }


        $creationId =
            $container->json('id');


        if (
            ! $creationId
        ) {

            return [

                'success' =>
                false,

                'status' =>
                422,

                'external_id' =>
                null,

                'response' => [

                    'message' =>
                    'Instagram did not return a creation ID.',

                ],
            ];
        }


        $publish =
            Http::asForm()
            ->timeout(120)
            ->post(
                "https://graph.facebook.com/{$version}/{$instagramId}/media_publish",
                [
                    'creation_id' =>
                    $creationId,

                    'access_token' =>
                    $token,
                ]
            );


        return $this->result(
            $publish
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REEL
    |--------------------------------------------------------------------------
    */

    protected function publishReel(
        string $instagramId,
        string $token,
        string $videoUrl,
        string $caption,
        string $version
    ): array {

        $container =
            Http::asForm()
            ->timeout(120)
            ->post(
                "https://graph.facebook.com/{$version}/{$instagramId}/media",
                [
                    'media_type' =>
                    'REELS',

                    'video_url' =>
                    $videoUrl,

                    'caption' =>
                    $caption,

                    'access_token' =>
                    $token,
                ]
            );


        if (
            $container->failed()
        ) {

            return $this->result(
                $container
            );
        }


        $creationId =
            $container->json('id');


        if (
            ! $creationId
        ) {

            return [

                'success' =>
                false,

                'status' =>
                422,

                'external_id' =>
                null,

                'response' => [

                    'message' =>
                    'Instagram did not return a Reel creation ID.',

                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Wait for Reel processing
        |--------------------------------------------------------------------------
        */

        $finished =
            false;


        for (
            $attempt = 0;
            $attempt < 20;
            $attempt++
        ) {

            sleep(2);


            $status =
                Http::get(
                    "https://graph.facebook.com/{$version}/{$creationId}",
                    [
                        'fields' =>
                        'status_code',

                        'access_token' =>
                        $token,
                    ]
                );


            if (
                $status->failed()
            ) {

                return $this->result(
                    $status
                );
            }


            $statusCode =
                $status->json(
                    'status_code'
                );


            if (
                $statusCode === 'FINISHED'
            ) {

                $finished =
                    true;

                break;
            }


            if (
                in_array(
                    $statusCode,
                    [
                        'ERROR',
                        'EXPIRED',
                    ],
                    true
                )
            ) {

                return [

                    'success' =>
                    false,

                    'status' =>
                    422,

                    'external_id' =>
                    null,

                    'response' =>
                    $status->json(),

                ];
            }
        }


        if (
            ! $finished
        ) {

            return [

                'success' =>
                false,

                'status' =>
                408,

                'external_id' =>
                null,

                'response' => [

                    'message' =>
                    'Instagram Reel processing timed out.',

                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Publish Reel
        |--------------------------------------------------------------------------
        */

        $publish =
            Http::asForm()
            ->timeout(120)
            ->post(
                "https://graph.facebook.com/{$version}/{$instagramId}/media_publish",
                [
                    'creation_id' =>
                    $creationId,

                    'access_token' =>
                    $token,
                ]
            );


        return $this->result(
            $publish
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CAROUSEL
    |--------------------------------------------------------------------------
    */

    protected function publishCarousel(
        string $instagramId,
        string $token,
        $images,
        string $caption,
        string $version
    ): array {

        $children = [];


        foreach (
            $images as $image
        ) {

            $container =
                Http::asForm()
                ->timeout(120)
                ->post(
                    "https://graph.facebook.com/{$version}/{$instagramId}/media",
                    [

                        'image_url' =>
                        $this->publicMediaUrl(
                            $image
                        ),

                        'is_carousel_item' =>
                        true,

                        'access_token' =>
                        $token,

                    ]
                );


            if (
                $container->failed()
            ) {

                return $this->result(
                    $container
                );
            }


            $childId =
                $container->json('id');


            if (
                ! $childId
            ) {

                return [

                    'success' =>
                    false,

                    'status' =>
                    422,

                    'external_id' =>
                    null,

                    'response' => [

                        'message' =>
                        'Instagram did not return a carousel child ID.',

                    ],
                ];
            }


            $children[] =
                $childId;
        }


        /*
        |--------------------------------------------------------------------------
        | Create carousel parent
        |--------------------------------------------------------------------------
        */

        $parent =
            Http::asForm()
            ->timeout(120)
            ->post(
                "https://graph.facebook.com/{$version}/{$instagramId}/media",
                [

                    'media_type' =>
                    'CAROUSEL',

                    'children' =>
                    implode(
                        ',',
                        $children
                    ),

                    'caption' =>
                    $caption,

                    'access_token' =>
                    $token,

                ]
            );


        if (
            $parent->failed()
        ) {

            return $this->result(
                $parent
            );
        }


        $parentId =
            $parent->json('id');


        if (
            ! $parentId
        ) {

            return [

                'success' =>
                false,

                'status' =>
                422,

                'external_id' =>
                null,

                'response' => [

                    'message' =>
                    'Instagram did not return a carousel parent ID.',

                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Publish carousel
        |--------------------------------------------------------------------------
        */

        $publish =
            Http::asForm()
            ->timeout(120)
            ->post(
                "https://graph.facebook.com/{$version}/{$instagramId}/media_publish",
                [

                    'creation_id' =>
                    $parentId,

                    'access_token' =>
                    $token,

                ]
            );


        return $this->result(
            $publish
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Public Media URL
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Public Media URL
    |--------------------------------------------------------------------------
    */
    protected function publicMediaUrl($media): string
    {
        $baseUrl = rtrim(
            (string) config('services.instagram.public_media_base_url'),
            '/'
        );

        $relativePath = ltrim(
            str_replace('\\', '/', (string) $media->file_path),
            '/'
        );

        if ($baseUrl === '') {
            throw new RuntimeException(
                'Instagram public media URL is not configured.'
            );
        }

        if (!Storage::disk('public')->exists($relativePath)) {
            throw new RuntimeException(
                "Instagram media file does not exist: {$relativePath}"
            );
        }

        // Encode each path segment without encoding directory separators.
        $encodedPath = implode(
            '/',
            array_map('rawurlencode', explode('/', $relativePath))
        );

        return $baseUrl . '/storage/' . $encodedPath;
    }


    /*
    |--------------------------------------------------------------------------
    | MESSAGE
    |--------------------------------------------------------------------------
    |
    | Khmer FIRST
    | English SECOND
    |
    */

    protected function message(
        Post $post
    ): string {

        return app(
            PostContentFormatter::class
        )->englishTitle(
            $post
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
