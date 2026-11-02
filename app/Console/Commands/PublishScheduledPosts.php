<?php

namespace App\Console\Commands;

use App\Jobs\PublishPostJob;
use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';

    protected $description =
        'Queue scheduled social media posts that are due';

    public function handle(): int
    {
        Post::query()
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->whereHas('targets', function ($query) {
                $query->whereIn('status', ['pending', 'failed']);
            })
            ->with([
                'targets' => function ($query) {
                    $query->whereIn('status', ['pending', 'failed']);
                },
            ])
            ->chunkById(50, function ($posts) {
                foreach ($posts as $post) {
                    $post->update([
                        'status' => 'publishing',
                        'scheduled_at' => null,
                    ]);

                    foreach ($post->targets as $target) {
                        $target->update([
                            'status' => 'pending',
                            'error_message' => null,
                        ]);

                        PublishPostJob::dispatch($target->id);
                    }
                }
            });

        return self::SUCCESS;
    }
}
