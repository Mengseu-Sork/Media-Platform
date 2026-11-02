@extends('layouts.app')

@section('content')

    <div class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8">

        @php
            use Illuminate\Support\Facades\Storage;
        @endphp

        <div class="mb-6 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

            <div class="flex items-center gap-3">

                <a href="{{ route('posts.index') }}"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-amber-400 text-white shadow-sm transition hover:border-blue-200 hover:bg-amber-500"
                    title="Back to posts">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7-7-7 7-7" />
                    </svg>
                </a>

                <div>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>

                        <span class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-500">
                            Social Media Post
                        </span>
                    </div>

                    <h1 class="mt-1 max-w-5xl text-xl font-black tracking-tight text-blue-600">
                        {{ $post->title ?: $post->title_Kh ?: 'Untitled Post' }}
                    </h1>
                </div>

            </div>

            <div class="flex items-center gap-2">

                @if (in_array($post->status, ['draft', 'failed']))
                    <form method="POST" action="{{ route('posts.publish', $post) }}">
                        @csrf

                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-[10px] font-black text-white shadow-sm transition hover:bg-blue-800 hover:shadow-md">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>

                            {{ $post->status === 'failed' ? 'Retry Publishing' : 'Publish Now' }}
                        </button>
                    </form>
                @elseif($post->status === 'publishing')
                    <span
                        class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-[10px] font-black text-blue-700">
                        <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" class="opacity-25" stroke="currentColor"
                                stroke-width="3" />
                            <path d="M21 12a9 9 0 0 1-9 9" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                        </svg>

                        Publishing
                    </span>
                @elseif($post->status === 'scheduled')
                    <span
                        class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-[10px] font-black text-amber-700">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" d="M12 7v5l3 2" />
                        </svg>

                        Scheduled
                    </span>
                @elseif($post->status === 'published')
                    <span
                        class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-[10px] font-black text-blue-700">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 7" />
                        </svg>

                        Published
                    </span>
                @endif

            </div>

        </div>


        @if (session('success'))
            <div class="mb-5 flex items-start gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 7" />
                    </svg>
                </div>

                <div>
                    <p class="text-[10px] font-black uppercase tracking-wide text-blue-800">
                        Success
                    </p>

                    <p class="mt-0.5 text-xs font-semibold text-blue-700">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700">
                    !
                </div>

                <div>
                    <p class="text-[10px] font-black uppercase tracking-wide text-red-800">
                        Error
                    </p>

                    <p class="mt-0.5 text-xs font-semibold text-red-700">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        @endif


        <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">


            <div class="space-y-5 xl:col-span-8">

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-black text-slate-950">
                                    Post Content
                                </h2>

                                <p class="mt-1 text-[10px] font-medium text-slate-400">
                                    Content that will be sent to the selected publishing platforms.
                                </p>
                            </div>

                            <span
                                class="rounded-lg bg-slate-50 px-2.5 py-1 text-[9px] font-black uppercase tracking-wide text-slate-400">
                                ID #{{ $post->id }}
                            </span>
                        </div>
                    </div>


                    <div class="space-y-5 p-5">

                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">

                            <div class="mb-3 flex items-center gap-2">

                                <span
                                    class="rounded-md bg-blue-50 px-2 py-1 text-[9px] font-black uppercase tracking-wide text-blue-700">
                                    English
                                </span>

                                @if ($post->title)
                                    <span class="text-[9px] font-semibold text-slate-400">
                                        English content
                                    </span>
                                @endif

                            </div>

                            @if ($post->title)
                                <h3 class="text-base font-black leading-6 text-slate-900">
                                    {{ $post->title }}
                                </h3>
                            @else
                                <p class="text-xs italic text-slate-400">
                                    No English title
                                </p>
                            @endif

                            @if ($post->content)
                                <div class="mt-3 border-t border-slate-200 pt-3">
                                    <p class="whitespace-pre-line text-sm leading-6 text-slate-600">
                                        {{ $post->content }}
                                    </p>
                                </div>
                            @else
                                <p class="mt-3 text-xs italic text-slate-400">
                                    No English context/description
                                </p>
                            @endif

                        </div>


                        @if ($post->title_Kh || $post->content_Kh)
                            <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">

                                <div class="mb-3 flex items-center gap-2">

                                    <span
                                        class="rounded-md bg-amber-50 px-2 py-1 text-[9px] font-black uppercase tracking-wide text-amber-700">
                                        ខ្មែរ
                                    </span>

                                    <span class="text-[9px] font-semibold text-slate-400">
                                        Khmer content
                                    </span>

                                </div>

                                @if ($post->title_Kh)
                                    <h3 class="text-base font-black leading-7 text-slate-900">
                                        {{ $post->title_Kh }}
                                    </h3>
                                @else
                                    <p class="text-xs italic text-slate-400">
                                        No Khmer title
                                    </p>
                                @endif

                                @if ($post->content_Kh)
                                    <div class="mt-3 border-t border-slate-200 pt-3">
                                        <p class="whitespace-pre-line text-sm leading-7 text-slate-600">
                                            {{ $post->content_Kh }}
                                        </p>
                                    </div>
                                @else
                                    <p class="mt-3 text-xs italic text-slate-400">
                                        No Khmer context/description
                                    </p>
                                @endif

                            </div>
                        @endif

                    </div>

                </section>


                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">

                        <div>
                            <h2 class="text-sm font-black text-slate-950">
                                Media
                            </h2>

                            <p class="mt-1 text-[10px] font-medium text-slate-400">
                                {{ $post->media->count() }}
                                {{ $post->media->count() === 1 ? 'file' : 'files' }}
                                attached to this post.
                            </p>
                        </div>

                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[9px] font-black text-slate-500">
                            {{ $post->media->count() }}
                        </span>

                    </div>


                    <div class="p-5">

                        @if ($post->media->isNotEmpty())
                            <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">

                                @foreach ($post->media as $media)
                                    @php
                                        $mime = strtolower((string) $media->mime_type);

                                        $isImage = str_starts_with($mime, 'image/');
                                        $isVideo = str_starts_with($mime, 'video/');

                                        // Serve media through Laravel instead of /storage/...
                                        $mediaUrl = route('posts.media', ['media' => $media->id]);
                                    @endphp

                                    <div class="group overflow-hidden rounded-xl border border-slate-200 bg-slate-50">

                                        {{-- IMAGE --}}
                                        @if ($isImage)
                                            <a href="{{ $mediaUrl }}" target="_blank" class="block">
                                                <img src="{{ $mediaUrl }}"
                                                    alt="{{ $media->file_name ?? 'Post image' }}"
                                                    class="block aspect-square w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                                                    loading="lazy"
                                                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                            </a>

                                            {{-- Image error message --}}
                                            <div
                                                class="hidden aspect-square w-full items-center justify-center bg-red-50 px-4 text-center">
                                                <div>
                                                    <svg class="mx-auto h-8 w-8 text-red-300" fill="none"
                                                        stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 9v4m0 4h.01" />
                                                        <circle cx="12" cy="12" r="9" />
                                                    </svg>

                                                    <p class="mt-2 text-[9px] font-black text-red-500">
                                                        Image unavailable
                                                    </p>

                                                    <p class="mt-1 break-all text-[8px] text-red-400">
                                                        {{ $media->file_path }}
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- VIDEO --}}
                                        @elseif ($isVideo)
                                            <video controls preload="metadata"
                                                class="aspect-square w-full bg-black object-contain">
                                                <source src="{{ $mediaUrl }}" type="{{ $media->mime_type }}">

                                                Your browser does not support video playback.
                                            </video>

                                            {{-- OTHER FILE --}}
                                        @else
                                            <a href="{{ $mediaUrl }}" target="_blank"
                                                class="flex aspect-square items-center justify-center p-4 text-center transition hover:bg-slate-100">
                                                <div>

                                                    <svg class="mx-auto h-8 w-8 text-slate-400" fill="none"
                                                        stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" />

                                                        <path stroke-linecap="round" d="M14 3v6h6" />
                                                    </svg>

                                                    <p class="mt-2 break-all text-[9px] font-bold text-slate-500">
                                                        {{ $media->file_name }}
                                                    </p>

                                                </div>
                                            </a>
                                        @endif


                                        {{-- FILE INFORMATION --}}
                                        <div class="border-t border-slate-200 bg-white px-3 py-2">

                                            <p class="truncate text-[9px] font-bold text-slate-600"
                                                title="{{ $media->file_name }}">
                                                {{ $media->file_name }}
                                            </p>

                                            <p class="mt-0.5 text-[8px] text-slate-400">
                                                {{ $media->file_size ? number_format($media->file_size / 1024 / 1024, 2) . ' MB' : 'Unknown size' }}
                                            </p>

                                        </div>

                                    </div>
                                @endforeach

                            </div>
                        @else
                            <div
                                class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-5 py-10 text-center">

                                <svg class="mx-auto h-8 w-8 text-slate-300" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />

                                    <path stroke-linecap="round" d="M8 15l2-2 2 2 3-4 4 5" />
                                </svg>

                                <p class="mt-3 text-xs font-black text-slate-500">
                                    No media attached
                                </p>

                                <p class="mt-1 text-[10px] text-slate-400">
                                    This post contains text only.
                                </p>

                            </div>
                        @endif

                    </div>

                </section>

            </div>



            <div class="space-y-5 xl:col-span-4">

                @php
                    $status = strtolower((string) $post->status);

                    $statusConfig = match ($status) {
                        'published' => [
                            'label' => 'Published',
                            'class' => 'border-blue-200 bg-blue-50 text-blue-700',
                            'dot' => 'bg-blue-500',
                        ],

                        'scheduled' => [
                            'label' => 'Scheduled',
                            'class' => 'border-amber-200 bg-amber-50 text-amber-700',
                            'dot' => 'bg-amber-500',
                        ],

                        'publishing' => [
                            'label' => 'Publishing',
                            'class' => 'border-blue-200 bg-blue-50 text-blue-700',
                            'dot' => 'bg-blue-500',
                        ],

                        'failed' => [
                            'label' => 'Failed',
                            'class' => 'border-red-200 bg-red-50 text-red-700',
                            'dot' => 'bg-red-500',
                        ],

                        default => [
                            'label' => 'Draft',
                            'class' => 'border-slate-200 bg-slate-50 text-slate-600',
                            'dot' => 'bg-slate-400',
                        ],
                    };
                @endphp


                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <h2 class="text-xs font-black uppercase tracking-[0.12em] text-slate-500">
                            Post Status
                        </h2>

                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 {{ $statusConfig['class'] }}">

                            <span class="h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>

                            <span class="text-[9px] font-black uppercase tracking-wide">
                                {{ $statusConfig['label'] }}
                            </span>

                        </span>

                    </div>


                    <div class="mt-5 space-y-3">

                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 text-[10px]">

                            <div class="flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="8" r="4" />
                                    <path stroke-linecap="round" d="M4 21c.7-4 3.3-6 8-6s7.3 2 8 6" />
                                </svg>

                                <span>Posted by</span>

                                <span class="font-black text-slate-700">
                                    {{ $post->user?->name ?? ($post->user?->email ?? 'Unknown user') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" d="M12 7v5l3 2" />
                                </svg>

                                <span>
                                    {{ $post->created_at?->timezone('Asia/Phnom_Penh')->format('d M Y, h:i A') }}
                                </span>

                            </div>

                        </div>

                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-[10px] font-medium text-slate-400">
                                Accounts
                            </span>

                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-black text-slate-600">
                                {{ $post->targets->count() }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-medium text-slate-400">
                                Media
                            </span>

                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-black text-slate-600">
                                {{ $post->media->count() }}
                            </span>
                        </div>

                    </div>

                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="17" rx="2" />
                                <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-xs font-black text-slate-800">
                                Schedule
                            </h2>

                            <p class="text-[9px] text-slate-400">
                                Publishing time
                            </p>
                        </div>

                    </div>

                    @if ($post->scheduled_at)
                        <div class="mt-4 rounded-xl border border-amber-100 bg-amber-50/70 p-4">

                            <p class="text-sm font-black text-amber-800">
                                {{ $post->scheduled_at->timezone('Asia/Phnom_Penh')->format('d M Y') }}
                            </p>

                            <p class="mt-1 text-xs font-bold text-amber-600">
                                {{ $post->scheduled_at->timezone('Asia/Phnom_Penh')->format('h:i A') }}
                            </p>

                        </div>
                    @else
                        <div class="mt-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs font-bold text-slate-500">
                                No schedule
                            </p>

                            <p class="mt-1 text-[9px] text-slate-400">
                                This post is not waiting for a future publishing time.
                            </p>
                        </div>
                    @endif

                </section>


                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-100 px-5 py-4">

                        <h2 class="text-xs font-black uppercase tracking-[0.12em] text-slate-500">
                            Publishing Targets
                        </h2>

                        <p class="mt-1 text-[9px] text-slate-400">
                            Status for each connected account.
                        </p>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse($post->targets as $target)
                            @php
                                $platform = strtolower((string) ($target->socialAccount?->platform ?? ''));

                                $targetStatus = strtolower((string) $target->status);

                                $platformConfig = match ($platform) {
                                    'facebook' => [
                                        'name' => 'Facebook',
                                        'class' => 'bg-blue-50 text-blue-700',
                                        'icon' => 'f',
                                    ],

                                    'instagram' => [
                                        'name' => 'Instagram',
                                        'class' => 'bg-pink-50 text-pink-700',
                                        'icon' => '◎',
                                    ],

                                    'youtube' => [
                                        'name' => 'YouTube',
                                        'class' => 'bg-red-50 text-red-700',
                                        'icon' => '▶',
                                    ],

                                    'tiktok' => [
                                        'name' => 'TikTok',
                                        'class' => 'bg-slate-100 text-slate-800',
                                        'icon' => '♪',
                                    ],

                                    'linkedin' => [
                                        'name' => 'LinkedIn',
                                        'class' => 'bg-sky-50 text-sky-700',
                                        'icon' => 'in',
                                    ],

                                    'twitter' => [
                                        'name' => 'X / Twitter',
                                        'class' => 'bg-slate-100 text-slate-700',
                                        'icon' => '𝕏',
                                    ],

                                    'website' => [
                                        'name' => 'Website',
                                        'class' => 'bg-blue-50 text-blue-700',
                                        'icon' => '◉',
                                    ],

                                    default => [
                                        'name' => $target->socialAccount?->platformName ?? 'Unknown',
                                        'class' => 'bg-slate-100 text-slate-600',
                                        'icon' => '•',
                                    ],
                                };

                                $targetStatusClass = match ($targetStatus) {
                                    'published' => 'bg-blue-50 text-blue-700',
                                    'publishing' => 'bg-blue-50 text-blue-700',
                                    'failed' => 'bg-red-50 text-red-700',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                            @endphp


                            <div class="px-5 py-4">

                                <div class="flex items-start gap-3">

                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $platformConfig['class'] }} text-[10px] font-black">
                                        {{ $platformConfig['icon'] }}
                                    </div>


                                    <div class="min-w-0 flex-1">

                                        <div class="flex items-center justify-between gap-2">

                                            <div class="min-w-0">

                                                <p class="truncate text-[11px] font-black text-slate-800">
                                                    {{ $platformConfig['name'] }}
                                                </p>

                                                @if ($target->socialAccount?->display_name)
                                                    <p class="mt-0.5 truncate text-[9px] font-medium text-slate-400">
                                                        {{ $target->socialAccount->display_name }}
                                                    </p>
                                                @endif

                                            </div>


                                            <span
                                                class="shrink-0 rounded-full px-2 py-1 text-[8px] font-black uppercase tracking-wide {{ $targetStatusClass }}">
                                                {{ $targetStatus }}
                                            </span>

                                        </div>


                                        @if ($targetStatus === 'published')
                                            @if ($target->published_at)
                                                <p class="mt-2 text-[9px] font-medium text-slate-400">
                                                    Published {{ $target->published_at->timezone('Asia/Phnom_Penh')->format('d M Y, h:i A') }}
                                                </p>
                                            @endif

                                            @if ($target->external_post_id)
                                                <p class="mt-1 truncate text-[8px] font-mono text-slate-300">
                                                    External ID: {{ $target->external_post_id }}
                                                </p>
                                            @endif
                                        @elseif($targetStatus === 'failed')
                                            @if ($target->error_message)
                                                <div class="mt-2 rounded-lg bg-red-50 px-3 py-2">
                                                    <p class="break-words text-[9px] leading-4 text-red-600">
                                                        {{ $target->error_message }}
                                                    </p>
                                                </div>
                                            @endif
                                        @elseif($targetStatus === 'publishing')
                                            <p class="mt-2 text-[9px] font-medium text-blue-500">
                                                Publishing is currently in progress...
                                            </p>
                                        @else
                                            <p class="mt-2 text-[9px] font-medium text-slate-400">
                                                Waiting to be published.
                                            </p>
                                        @endif

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="px-5 py-8 text-center">

                                <p class="text-xs font-bold text-slate-500">
                                    No publishing targets
                                </p>

                            </div>
                        @endforelse

                    </div>

                </section>

                @if ($post->website_department)
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round"
                                        d="M3 12h18M12 3c2.2 2.5 3.4 5.5 3.4 9s-1.2 6.5-3.4 9c-2.2-2.5-3.4-5.5-3.4-9S9.8 5.5 12 3Z" />
                                </svg>
                            </div>

                            <div>
                                <p class="text-xs font-black text-slate-800">
                                    Website Department
                                </p>

                                <p class="text-[9px] text-slate-400">
                                    Selected publishing destination
                                </p>
                            </div>

                        </div>

                        <div class="mt-4">
                            <span
                                class="inline-flex rounded-lg bg-blue-50 px-3 py-2 text-[10px] font-black uppercase tracking-wide text-blue-700">
                                {{ $post->website_department }}
                            </span>
                        </div>

                    </section>
                @endif

            </div>

        </div>

    </div>

@endsection
