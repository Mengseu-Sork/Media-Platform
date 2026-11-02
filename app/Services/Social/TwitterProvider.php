<?php

namespace App\Services\Social;

use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use App\Services\Social\PostContentFormatter;

class TwitterProvider implements SocialProviderInterface
{
    public function publish(
        SocialAccount $account,
        Post $post
    ): array {
        $token = $this->ensureAccessToken($account);
        $media = $post->media()
            ->orderBy('sort_order')
            ->get();

        $mediaIds = [];

        foreach ($media as $item) {
            $mediaIds[] = $this->uploadMedia(
                $token,
                $item
            );
        }

        $text = app(PostContentFormatter::class)
            ->english($post);

        /*
         * X's v2 Create Post endpoint uses POST /2/tweets.
         */
        $payload = [
            'text' => mb_substr(
                $text,
                0,
                280
            ),
        ];

        if (! empty($mediaIds)) {
            $payload['media'] = [
                'media_ids' => array_slice($mediaIds, 0, 4),
            ];
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->post(
                'https://api.x.com/2/tweets',
                $payload
            );

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'external_id' => $response->json('data.id'),
            'response' => $response->json() ?: [
                'body' => $response->body(),
            ],
        ];
    }


    protected function ensureAccessToken(
        SocialAccount $account
    ): string {
        if (
            $account->token_expires_at &&
            now()->lt($account->token_expires_at->subMinutes(2))
        ) {
            return $account->access_token;
        }

        if (! $account->refresh_token) {
            return $account->access_token;
        }

        $payload = [
            'refresh_token' => $account->refresh_token,
            'grant_type' => 'refresh_token',
            'client_id' => config('services.twitter.client_id'),
        ];

        $response = Http::asForm()->post(
            'https://api.x.com/2/oauth2/token',
            $payload
        );

        if ($response->failed() || ! $response->json('access_token')) {
            throw new RuntimeException(
                'Unable to refresh X access token: ' . $response->body()
            );
        }

        $account->update([
            'access_token' => $response->json('access_token'),
            'refresh_token' =>
            $response->json('refresh_token')
                ?: $account->refresh_token,
            'token_expires_at' =>
            $response->json('expires_in')
                ? now()->addSeconds($response->json('expires_in'))
                : null,
        ]);

        return $response->json('access_token');
    }

    protected function uploadMedia(
        string $token,
        $media
    ): string {
        $path = Storage::disk('public')->path($media->file_path);
        $size = filesize($path);

        if ($size === false) {
            throw new RuntimeException('Unable to determine X media size.');
        }

        $mime = $media->mime_type ?: 'application/octet-stream';
        $isVideo = str_starts_with($mime, 'video/');

        /*
         * Current X media API supports INIT / APPEND / FINALIZE /
         * STATUS for video and large media.
         */
        $init = Http::withToken($token)
            ->asMultipart()
            ->post(
                'https://api.x.com/2/media/upload',
                [
                    [
                        'name' => 'command',
                        'contents' => 'INIT',
                    ],
                    [
                        'name' => 'media_type',
                        'contents' => $mime,
                    ],
                    [
                        'name' => 'total_bytes',
                        'contents' => (string) $size,
                    ],
                    [
                        'name' => 'media_category',
                        'contents' => $isVideo
                            ? 'tweet_video'
                            : 'tweet_image',
                    ],
                ]
            );

        if ($init->failed()) {
            throw new RuntimeException(
                'X media initialization failed: ' .
                    $init->body()
            );
        }

        $mediaId = $init->json('data.id');

        if (! $mediaId) {
            throw new RuntimeException('X did not return a media ID.');
        }

        $stream = fopen($path, 'rb');

        if ($stream === false) {
            throw new RuntimeException('Unable to open X media file.');
        }

        $segmentIndex = 0;
        $chunkSize = 4 * 1024 * 1024;

        while (! feof($stream)) {
            $chunk = fread($stream, $chunkSize);

            if ($chunk === false || $chunk === '') {
                break;
            }

            $append = Http::withToken($token)
                ->attach(
                    'media',
                    $chunk,
                    $media->file_name ?: basename($path)
                )
                ->asMultipart()
                ->post(
                    'https://api.x.com/2/media/upload',
                    [
                        'command' => 'APPEND',
                        'media_id' => $mediaId,
                        'segment_index' => $segmentIndex,
                    ]
                );

            if ($append->failed()) {
                fclose($stream);

                throw new RuntimeException(
                    'X media append failed: ' .
                        $append->body()
                );
            }

            $segmentIndex++;
        }

        fclose($stream);

        $finalize = Http::withToken($token)
            ->asMultipart()
            ->post(
                'https://api.x.com/2/media/upload',
                [
                    'command' => 'FINALIZE',
                    'media_id' => $mediaId,
                ]
            );

        if ($finalize->failed()) {
            throw new RuntimeException(
                'X media finalize failed: ' .
                    $finalize->body()
            );
        }

        $processing = $finalize->json('data.processing_info');

        if ($processing) {
            $state = $processing['state'] ?? 'pending';

            for ($attempt = 0; $attempt < 30 && in_array(
                $state,
                ['pending', 'in_progress'],
                true
            ); $attempt++) {
                sleep((int) ($processing['check_after_secs'] ?? 2));

                $status = Http::withToken($token)
                    ->get(
                        'https://api.x.com/2/media/upload',
                        [
                            'command' => 'STATUS',
                            'media_id' => $mediaId,
                        ]
                    );

                if ($status->failed()) {
                    throw new RuntimeException(
                        'X media status check failed: ' .
                            $status->body()
                    );
                }

                $processing =
                    $status->json('data.processing_info');

                $state =
                    $processing['state'] ?? 'succeeded';
            }

            if ($state !== 'succeeded') {
                throw new RuntimeException(
                    'X media processing did not complete successfully.'
                );
            }
        }

        return (string) $mediaId;
    }
}
