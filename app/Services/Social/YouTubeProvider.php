<?php

namespace App\Services\Social;

use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class YouTubeProvider implements SocialProviderInterface
{
    public function __construct(
        protected YouTubeTokenService $tokenService
    ) {}

    /**
     * Publish a post to YouTube.
     *
     * Behaviour:
     * - Existing video => upload it.
     * - Images only => create landscape slideshow.
     * - Access token => automatically refreshed by YouTubeTokenService.
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

        if (strtolower((string) $account->platform) !== 'youtube') {
            throw new RuntimeException(
                'Invalid social account. This provider requires a YouTube account.'
            );
        }

        if (!$account->is_active) {
            throw new RuntimeException(
                'The YouTube account is inactive.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Find the browser-generated slideshow or an uploaded video
        |--------------------------------------------------------------------------
        | Do not call SlideshowVideoService here: FFmpeg is not installed on
        | the user's cPanel hosting. The browser converter creates the MP4
        | before the post is submitted, and PostController stores its path on
        | the posts table as youtube_slideshow_path.
        */

        $media = $post->media()
            ->orderBy('sort_order')
            ->get();

        $disk = Storage::disk('public');
        $video = null;

        $browserSlideshowPath = trim(
            (string) ($post->youtube_slideshow_path ?? '')
        );

        if (
            $browserSlideshowPath !== '' &&
            $disk->exists($browserSlideshowPath)
        ) {
            $video = (object) [
                'file_path' => $browserSlideshowPath,
                'file_name' => "post-{$post->id}-youtube-slideshow.mp4",
                'mime_type' => 'video/mp4',
            ];
        }

        // If the user uploaded a normal video, use it when no browser MP4 exists.
        if (! $video) {
            $video = $media->first(
                fn($item) => str_starts_with(
                    strtolower((string) $item->mime_type),
                    'video/'
                )
            );
        }

        // Image-only posts must have a browser-generated MP4; never run FFmpeg here.
        if (! $video) {
            $hasImages = $media->contains(
                fn($item) => str_starts_with(
                    strtolower((string) $item->mime_type),
                    'image/'
                )
            );

            return [
                'success' => false,
                'status' => 422,
                'external_id' => null,
                'response' => [
                    'message' => $hasImages
                        ? 'The browser-generated YouTube slideshow is missing. Re-create the post and wait until the slideshow is ready before publishing.'
                        : 'YouTube requires an uploaded video or images converted into an MP4 slideshow.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Verify the selected video exists before requesting an access token
        |--------------------------------------------------------------------------
        */

        if (! $disk->exists($video->file_path)) {
            throw new RuntimeException(
                'YouTube video file does not exist: ' . $video->file_path
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GET VALID ACCESS TOKEN
        |
        | IMPORTANT:
        | YouTubeTokenService handles:
        | - existing valid access token
        | - expired access token
        | - refresh_token
        | - saving new access token
        | - preserving refresh token
        |--------------------------------------------------------------------------
        */

        $accessToken = $this->tokenService->getAccessToken(
            $account
        );

        if (!$accessToken) {
            throw new RuntimeException(
                'YouTube access token is unavailable.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve physical video path
        |--------------------------------------------------------------------------
        */

        $disk = Storage::disk('public');

        if (!$disk->exists($video->file_path)) {
            throw new RuntimeException(
                'YouTube video file does not exist: ' .
                    $video->file_path
            );
        }

        $path = $disk->path(
            $video->file_path
        );

        if (!is_file($path)) {
            throw new RuntimeException(
                'YouTube video file does not exist: ' .
                    $path
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get file size
        |--------------------------------------------------------------------------
        */

        $fileSize = filesize($path);

        if ($fileSize === false) {
            throw new RuntimeException(
                'Unable to determine YouTube video file size.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Format title and description
        |--------------------------------------------------------------------------
        */

        $formatter = app(
            PostContentFormatter::class
        );

        /*
        |--------------------------------------------------------------------------
        | YouTube TITLE
        |
        | Title only.
        | Does NOT include content/context.
        |--------------------------------------------------------------------------
        */

        $title = $formatter->youtubeTitle(
            $post
        );

        /*
        |--------------------------------------------------------------------------
        | YouTube DESCRIPTION
        |
        | Content/context only.
        | Does NOT include title.
        |--------------------------------------------------------------------------
        */

        $description = $formatter->youtubeDescription(
            $post
        );

        /*
        |--------------------------------------------------------------------------
        | YouTube metadata
        |--------------------------------------------------------------------------
        */

        $metadata = [
            'snippet' => [
                'title' => mb_substr(
                    $title ?: 'NGO Forum',
                    0,
                    100
                ),

                'description' => $description,

                'categoryId' => (string) config(
                    'services.google.youtube_category',
                    '22'
                ),
            ],

            'status' => [
                'privacyStatus' => config(
                    'services.google.youtube_privacy',
                    'public'
                ),
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Start resumable upload
        |--------------------------------------------------------------------------
        */

        $init = Http::withToken(
            $accessToken
        )
            ->withHeaders([
                'X-Upload-Content-Length' =>
                (string) $fileSize,

                'X-Upload-Content-Type' =>
                $video->mime_type ?: 'video/mp4',

                'Content-Type' =>
                'application/json',
            ])
            ->timeout(60)
            ->post(
                'https://www.googleapis.com/upload/youtube/v3/videos?uploadType=resumable&part=snippet,status',
                $metadata
            );

        /*
        |--------------------------------------------------------------------------
        | Resumable upload initialization failed
        |--------------------------------------------------------------------------
        */

        if ($init->failed()) {
            return [
                'success' => false,

                'status' =>
                $init->status(),

                'external_id' => null,

                'response' =>
                $init->json()
                    ?: [
                        'body' => $init->body(),
                    ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Get upload URL
        |--------------------------------------------------------------------------
        */

        $uploadUrl = $init->header(
            'Location'
        );

        if (!$uploadUrl) {
            return [
                'success' => false,

                'status' => 502,

                'external_id' => null,

                'response' => [
                    'message' =>
                    'YouTube did not return the resumable upload URL.',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Read video
        |--------------------------------------------------------------------------
        */

        $binary = file_get_contents(
            $path
        );

        if ($binary === false) {
            throw new RuntimeException(
                'Unable to read YouTube video.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload video
        |--------------------------------------------------------------------------
        */

        $upload = Http::withToken(
            $accessToken
        )
            ->withHeaders([
                'Content-Type' =>
                $video->mime_type ?: 'video/mp4',

                'Content-Length' =>
                (string) strlen($binary),
            ])
            ->withBody(
                $binary,
                $video->mime_type ?: 'video/mp4'
            )
            ->timeout(600)
            ->put(
                $uploadUrl
            );

        /*
        |--------------------------------------------------------------------------
        | Return result
        |--------------------------------------------------------------------------
        */

        return [
            'success' =>
            $upload->successful(),

            'status' =>
            $upload->status(),

            'external_id' =>
            $upload->json('id'),

            'response' =>
            $upload->json()
                ?: [
                    'body' =>
                    $upload->body(),
                ],
        ];
    }
}
