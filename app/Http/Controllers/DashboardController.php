<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPosts = Post::count();

        $publishedPosts = Post::where('status', 'published')
            ->count();

        $scheduledPosts = Post::where('status', 'scheduled')
            ->count();

        $failedPosts = Post::where('status', 'failed')
            ->count();

        $recentPosts = Post::query()
            ->latest()
            ->take(7)
            ->get();

        $socialAccounts = SocialAccount::query()
            ->where('is_active', true)
            ->get();

        $successRate = $totalPosts > 0
            ? round(($publishedPosts / $totalPosts) * 100)
            : 0;

        $platforms = [
            'facebook' => [
                'name' => 'Facebook',
                'type' => 'Page',
                'bg' => 'bg-blue-50',
                'text' => 'text-blue-600',
            ],
            'instagram' => [
                'name' => 'Instagram',
                'type' => 'Business',
                'bg' => 'bg-pink-50',
                'text' => 'text-pink-600',
            ],
            'youtube' => [
                'name' => 'YouTube',
                'type' => 'Channel',
                'bg' => 'bg-red-50',
                'text' => 'text-red-600',
            ],
            'tiktok' => [
                'name' => 'TikTok',
                'type' => 'Account',
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-900',
            ],
            'linkedin' => [
                'name' => 'LinkedIn',
                'type' => 'Organization',
                'bg' => 'bg-sky-50',
                'text' => 'text-sky-700',
            ],
            'twitter' => [
                'name' => 'X / Twitter',
                'type' => 'Account',
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-900',
            ],
            'website' => [
                'name' => 'Website',
                'type' => 'Website',
                'bg' => 'bg-emerald-50',
                'text' => 'text-emerald-700',
            ],
        ];

        $connectedPlatforms = $socialAccounts->keyBy('platform');

        $connectedCount = $connectedPlatforms->count();
        $totalPlatforms = count($platforms);

        return view('dashboard.index', compact(
            'totalPosts',
            'publishedPosts',
            'scheduledPosts',
            'failedPosts',
            'recentPosts',
            'socialAccounts',
            'successRate',
            'platforms',
            'connectedPlatforms',
            'connectedCount',
            'totalPlatforms',
        ));
    }
}
