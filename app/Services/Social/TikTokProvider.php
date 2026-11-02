<?php

namespace App\Services\Social;

use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class TikTokProvider implements SocialProviderInterface
{
    public function publish(
        SocialAccount $account,
        Post $post
    ): array {

        $accessToken =
            $this->ensureAccessToken($account);

        $media = $post->media()
            ->orderBy('sort_order')
            ->get();

        if ($media->isEmpty()) {

            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' =>
                    'TikTok requires an image or video.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Creator info
        |--------------------------------------------------------------------------
        */

        $creator = Http::withToken($accessToken)
            ->acceptJson()
            ->get(
                'https://open.tiktokapis.com/v2/post/publish/creator_info/query/'
            );

        if ($creator->failed()) {
            return $this->result($creator);
        }

        $privacyOptions =
            $creator->json(
                'data.privacy_level_options',
                []
            );

        $privacy =
            $privacyOptions[0]
            ?? 'SELF_ONLY';

        /*
        |--------------------------------------------------------------------------
        | Existing video
        |--------------------------------------------------------------------------
        */

        $video = $media->first(
            fn($item) =>
            str_starts_with(
                (string) $item->mime_type,
                'video/'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Images -> vertical slideshow
        |--------------------------------------------------------------------------
        */

        if (!$video) {

            $images = $media->filter(
                fn($item) =>
                str_starts_with(
                    (string) $item->mime_type,
                    'image/'
                )
            );

            if ($images->isEmpty()) {

                return [
                    'success' => false,
                    'status' => 422,
                    'external_id' => null,
                    'response' => [
                        'message' =>
                        'TikTok has no supported media.',
                    ],
                ];
            }

            $slideshowPath =
                app(SlideshowVideoService::class)
                ->create(
                    $post,
                    'portrait'
                );

            $video = (object) [
                'file_path' =>
                $slideshowPath,

                'file_name' =>
                "post-{$post->id}-tiktok-slideshow.mp4",

                'mime_type' =>
                'video/mp4',
            ];
        }

        return $this->publishVideo(
            $post,
            $video,
            $accessToken,
            $privacy
        );
    }

    protected function publishVideo(
        Post $post,
        $video,
        string $accessToken,
        string $privacy
    ): array {

        $url = url(
            Storage::disk('public')
                ->url($video->file_path)
        );

        /*
        |--------------------------------------------------------------------------
        | TikTok = English only
        |--------------------------------------------------------------------------
        */

        $title =
            app(PostContentFormatter::class)
            ->english($post);

        $init = Http::withToken($accessToken)
            ->acceptJson()
            ->timeout(60)
            ->post(
                'https://open.tiktokapis.com/v2/post/publish/video/init/',
                [
                    'post_info' => [

                        'title' =>
                        mb_substr(
                            $title,
                            0,
                            2200
                        ),

                        'privacy_level' =>
                        $privacy,

                        'disable_duet' => false,

                        'disable_comment' =>
                        false,

                        'disable_stitch' =>
                        false,
                    ],

                    'source_info' => [

                        'source' =>
                        'PULL_FROM_URL',

                        'video_url' =>
                        $url,
                    ],
                ]
            );

        return $this->result($init);
    }

    protected function ensureAccessToken(
        SocialAccount $account
    ): string {

        if (
            $account->token_expires_at
            &&
            now()->lt(
                $account->token_expires_at
                    ->subMinutes(2)
            )
        ) {
            return $account->access_token;
        }

        if (!$account->refresh_token) {
            return $account->access_token;
        }

        $response = Http::asForm()
            ->post(
                'https://open.tiktokapis.com/v2/oauth/token/',
                [
                    'client_key' =>
                    config(
                        'services.tiktok.client_key'
                    ),

                    'client_secret' =>
                    config(
                        'services.tiktok.client_secret'
                    ),

                    'grant_type' =>
                    'refresh_token',

                    'refresh_token' =>
                    $account->refresh_token,
                ]
            );

        if (
            $response->failed()
            || !$response->json('access_token')
        ) {
            throw new RuntimeException(
                'Unable to refresh TikTok access token.'
            );
        }

        $account->update([
            'access_token' =>
            $response->json('access_token'),

            'refresh_token' =>
            $response->json(
                'refresh_token'
            )
                ?: $account->refresh_token,

            'token_expires_at' =>
            $response->json('expires_in')
                ? now()->addSeconds(
                    $response->json('expires_in')
                )
                : null,
        ]);

        return $response->json(
            'access_token'
        );
    }

    protected function result($response): array
    {
        return [
            'success' =>
            $response->successful(),

            'status' =>
            $response->status(),

            'external_id' =>
            $response->json(
                'data.publish_id'
            ),

            'response' =>
            $response->json()
                ?: [
                    'body' =>
                    $response->body(),
                ],
        ];
    }
}
