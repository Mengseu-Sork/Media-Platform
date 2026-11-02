<?php

namespace App\Jobs;

use App\Models\PostTarget;
use App\Models\PublishingLog;
use App\Services\Social\SocialPublisher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class PublishPostJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public int $timeout = 900;

    public function __construct(
        public int $postTargetId
    ) {
    }

    public function handle(
        SocialPublisher $publisher
    ): void {
        $target = PostTarget::with([
            'post.media',
            'socialAccount',
        ])->findOrFail(
            $this->postTargetId
        );

        if (
            $target->status === 'published'
            || ! $target->socialAccount
            || ! $target->socialAccount->is_active
        ) {
            return;
        }

        $target->update([
            'status' => 'publishing',
            'error_message' => null,
        ]);

        try {

            /*
             * EXACT ORDER:
             * account first, post second
             */
            $result = $publisher->publish(
                $target->socialAccount,
                $target->post
            );

            PublishingLog::create([
                'post_target_id' => $target->id,
                'action' => 'publish',
                'http_status' => $result['status'] ?? null,
                'response' => json_encode(
                    $result['response'] ?? $result,
                    JSON_UNESCAPED_UNICODE
                ),
                'error' => $result['success']
                    ? null
                    : json_encode(
                        $result['response'] ?? [],
                        JSON_UNESCAPED_UNICODE
                    ),
            ]);

            if ($result['success']) {

                $target->update([
                    'status' => 'published',
                    'external_post_id' =>
                        $result['external_id'] ?? null,
                    'published_at' => now(),
                    'error_message' => null,
                ]);

            } else {

                $target->update([
                    'status' => 'failed',
                    'error_message' =>
                        json_encode(
                            $result['response'] ?? [],
                            JSON_UNESCAPED_UNICODE
                        ),
                ]);
            }

            $this->syncPostStatus(
                $target->fresh()
            );

        } catch (Throwable $e) {

            $target->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            PublishingLog::create([
                'post_target_id' => $target->id,
                'action' => 'publish_exception',
                'http_status' => null,
                'response' => null,
                'error' => $e->getMessage(),
            ]);

            $this->syncPostStatus(
                $target->fresh()
            );

            report($e);
        }
    }

    protected function syncPostStatus(
        PostTarget $target
    ): void {
        $post = $target
            ->post()
            ->with('targets')
            ->first();

        if (! $post) {
            return;
        }

        $statuses = $post->targets
            ->pluck('status')
            ->values();

        if ($statuses->isEmpty()) {
            $post->update([
                'status' => 'draft',
            ]);

            return;
        }

        if (
            $statuses->contains('pending')
            || $statuses->contains('publishing')
        ) {
            $post->update([
                'status' => 'publishing',
            ]);

            return;
        }

        if (
            $statuses->every(
                fn ($status) =>
                    $status === 'published'
            )
        ) {
            $post->update([
                'status' => 'published',
                'scheduled_at' => null,
            ]);

            return;
        }

        $post->update([
            'status' => 'failed',
        ]);
    }
}