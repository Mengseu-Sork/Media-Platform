<?php

namespace App\Services\Social;

use App\Models\Post;
use App\Models\SocialAccount;

interface SocialProviderInterface
{
    /**
     * Publish one post to one social account.
     *
     * @return array{
     *     success: bool,
     *     status: int|null,
     *     external_id: string|null,
     *     response: array
     * }
     */
    public function publish(
        SocialAccount $account,
        Post $post
    ): array;
}