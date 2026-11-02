<?php

namespace App\Services\Social;

use App\Models\Post;
use App\Models\SocialAccount;
use RuntimeException;

class SocialPublisher
{
    public function publish(
        SocialAccount $account,
        Post $post
    ): array {
        return match (
            strtolower(trim((string) $account->platform))
        ) {
            'facebook' =>
                app(FacebookProvider::class)
                    ->publish($account, $post),

            'instagram' =>
                app(InstagramProvider::class)
                    ->publish($account, $post),

            'youtube' =>
                app(YouTubeProvider::class)
                    ->publish($account, $post),

            'tiktok' =>
                app(TikTokProvider::class)
                    ->publish($account, $post),

            'linkedin' =>
                app(LinkedInProvider::class)
                    ->publish($account, $post),

            'twitter' =>
                app(TwitterProvider::class)
                    ->publish($account, $post),

            'website' =>
                app(WebsiteProvider::class)
                    ->publish($account, $post),

            default =>
                throw new RuntimeException(
                    'Unsupported platform: ' .
                    $account->platform
                ),
        };
    }
}