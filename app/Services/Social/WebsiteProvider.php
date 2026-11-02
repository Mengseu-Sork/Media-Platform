<?php

namespace App\Services\Social;

use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class WebsiteProvider implements SocialProviderInterface
{
    public function publish(SocialAccount $account, Post $post): array
    {
        $endpoint = config('services.website.endpoint')
            ?? data_get($account->metadata, 'endpoint');

        if (!$endpoint) {
            throw new RuntimeException(
                'Website API endpoint is not configured.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

        $title = trim((string) $post->title);
        $description = trim((string) $post->content);
        $department = trim((string) $post->website_department);

        if ($title === '') {
            throw new RuntimeException(
                'Website title is empty. Check posts.title.'
            );
        }

        if ($description === '') {
            throw new RuntimeException(
                'Website description is empty. Check posts.content.'
            );
        }

        if ($department === '') {
            throw new RuntimeException(
                'Website department is empty.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PUBLISHED DATE
        |--------------------------------------------------------------------------
        */

        $publishedAt = ($post->scheduled_at ?? now())
            ->timezone('Asia/Phnom_Penh')
            ->format('Y-m-d H:i:s');

        /*
        |--------------------------------------------------------------------------
        | WEBSITE API PAYLOAD
        |--------------------------------------------------------------------------
        */

        $payload = [
            'title' => $title,
            'description' => $description,
            'department' => $department,
            'link' => null,
            'published_at' => $publishedAt,
        ];

        /*
        |--------------------------------------------------------------------------
        | REQUEST
        |--------------------------------------------------------------------------
        */

        $apiToken = config('services.website.token');

        if (!$apiToken) {
            throw new RuntimeException(
                'Website API token is not configured.'
            );
        }

        $request = Http::withToken($apiToken)
            ->acceptJson()
            ->timeout(60);

        /*
        |--------------------------------------------------------------------------
        | IMAGES
        |--------------------------------------------------------------------------
        */

        $media = $post->media()
            ->orderBy('sort_order')
            ->get();

        $imagesUploaded = 0;

        foreach ($media as $item) {

            $mimeType = strtolower(
                (string) $item->mime_type
            );

            // Website API expects images only
            if (!str_starts_with($mimeType, 'image/')) {
                continue;
            }

            $path = Storage::disk('public')
                ->path($item->file_path);

            if (!is_file($path)) {
                throw new RuntimeException(
                    'Image does not exist: ' . $item->file_path
                );
            }

            $handle = fopen($path, 'rb');

            if ($handle === false) {
                throw new RuntimeException(
                    'Unable to open image: ' . $path
                );
            }

            $request = $request->attach(
                'images[]',
                $handle,
                $item->file_name ?: basename($path),
                [
                    'Content-Type' =>
                    $mimeType ?: 'image/jpeg',
                ]
            );

            $imagesUploaded++;
        }

        /*
        |--------------------------------------------------------------------------
        | SEND
        |--------------------------------------------------------------------------
        */

        /** @var Response $response */
        $response = $request->post(
            $endpoint,
            $payload
        );

        /*
        |--------------------------------------------------------------------------
        | ERROR
        |--------------------------------------------------------------------------
        */

        if ($response->failed()) {

            throw new RuntimeException(
                'Website API failed: '
                    . $response->status()
                    . ' - '
                    . $response->body()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        $body = $response->json();

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'external_id' =>
            data_get($body, 'id')
                ?? data_get($body, 'post_id'),
            'response' => $body ?: [
                'body' => $response->body(),
            ],
            'endpoint' => $endpoint,
            'images_uploaded' => $imagesUploaded,
        ];
    }
}
