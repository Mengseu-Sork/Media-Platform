@extends('layouts.app')

@section('content')
    <div class="min-h-[calc(100vh-4rem)] bg-[#f5f8f6]">

        <div class="mx-auto max-w-[1700px] px-6 py-6">

            @if (session('success'))
                <div
                    class="mb-5 flex items-start gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 shadow-sm">
                    <div
                        class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-[11px] font-black uppercase tracking-wide text-blue-800">
                            Success
                        </p>

                        <p class="mt-0.5 text-xs font-semibold text-blue-700">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm">
                    <div
                        class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M10.29 3.86l-8.82 15A2 2 0 0 0 3.2 22h17.6a2 2 0 0 0 1.73-3.14l-8.82-15a2 2 0 0 0-3.42 0Z" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-[11px] font-black uppercase tracking-wide text-red-800">
                            Error
                        </p>

                        <p class="mt-0.5 text-xs font-semibold text-red-700">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            @endif


            <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex flex-col gap-5 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">

                    {{-- LEFT: PAGE TITLE --}}
                    <div class="flex min-w-0 items-center gap-4">

                        {{-- ICON --}}
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-lg shadow-blue-200/60">
                            <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 8h10M7 12h7m-9 8h8l5 2v-5a7 7 0 0 0 4-6.5A7 7 0 0 0 15 4H9a7 7 0 0 0-7 7 7 7 0 0 0 3 5.8V20l2-2z" />
                            </svg>
                        </div>

                        {{-- TITLE / DESCRIPTION --}}
                        <div class="min-w-0">

                            <div class="flex flex-wrap items-center gap-2">

                                <h1 class="text-xl font-black tracking-tight text-slate-950 sm:text-2xl">
                                    Social Media Posts
                                </h1>

                                <span
                                    class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-2 py-0.5 text-[8px] font-black uppercase tracking-[0.12em] text-blue-700">
                                    Content Management
                                </span>

                            </div>

                            <p class="mt-1 text-xs font-medium leading-5 text-slate-500 sm:text-[13px]">
                                Manage, schedule and publish your NGO Forum content across connected platforms.
                            </p>

                        </div>

                    </div>


                    {{-- RIGHT: ACTION --}}
                    <div class="flex shrink-0 items-center gap-2">

                        <form action="{{ route('posts.export.excel') }}" method="GET" id="exportForm"
                            class="flex flex-wrap items-end gap-3">

                            {{-- PERIOD --}}
                            <div>
                                <label for="exportPeriod"
                                    class="mb-1.5 block text-[9px] font-black uppercase tracking-wide text-slate-500">
                                    Period
                                </label>
                                <select name="period" id="exportPeriod"
                                    class="w-40 rounded-xl px-4 py-2.5 border-slate-200 bg-white text-xs font-semibold shadow-sm focus:border-blue-500 focus:ring-blue-500">

                                    <option value="1">1 Month</option>
                                    <option value="3">3 Months</option>
                                    <option value="6">6 Months</option>
                                    <option value="custom">Custom Range</option>

                                </select>
                            </div>


                            {{-- FROM --}}
                            <div id="fromMonthWrapper" class="hidden">

                                <label for="fromMonth"
                                    class="mb-1.5 block text-[9px] font-black uppercase tracking-wide text-slate-500">
                                    From Month
                                </label>

                                <input type="month" name="from_month" id="fromMonth"
                                    class="rounded-xl border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold shadow-sm focus:border-blue-500 focus:ring-blue-500">

                            </div>


                            {{-- TO --}}
                            <div id="toMonthWrapper" class="hidden">

                                <label for="toMonth"
                                    class="mb-1.5 block text-[9px] font-black uppercase tracking-wide text-slate-500">
                                    To Month
                                </label>

                                <input type="month" name="to_month" id="toMonth"
                                    class="rounded-xl border-slate-200 bg-white text-xs px-4 py-2.5 font-semibold shadow-sm focus:border-blue-500 focus:ring-blue-500">

                            </div>


                            {{-- EXPORT EXCEL --}}
                            <button type="submit"
                                class="inline-flex h-[38px] items-center gap-2 rounded-xl bg-blue-700 px-5 text-xs font-black text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-800">

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4" />

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5 21h14a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2" />

                                </svg>

                                Export Excel
                            </button>

                        </form>

                        {{-- CREATE POST --}}
                        <a href="{{ route('posts.create') }}"
                            class="group inline-flex mt-5 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-black text-white shadow-md shadow-blue-200/50 transition duration-200 hover:-translate-y-0.5 hover:bg-blue-800 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-md bg-white/15 transition group-hover:bg-white/20">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                                </svg>
                            </span>

                            <span>
                                Create Post
                            </span>

                        </a>

                    </div>

                </div>

                {{-- BOTTOM ACCENT --}}
                <div class="h-1 bg-gradient-to-r from-blue-600 via-blue-500 to-transparent"></div>

            </div>


            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div
                    class="border-b border-slate-100 bg-gradient-to-r from-slate-50 via-white to-blue-50/30 px-5 py-4 sm:px-6">

                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                        {{-- LEFT --}}
                        <div class="min-w-0">

                            <div class="flex items-center gap-2.5">

                                <span class="relative flex h-2.5 w-2.5">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-40"></span>

                                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                                </span>

                                <h2 class="text-xs font-black uppercase tracking-[0.15em] text-slate-700">
                                    Post Management
                                </h2>

                            </div>

                            <p class="mt-1.5 text-[10px] font-medium text-slate-400">
                                {{ $posts->total() }}
                                {{ $posts->total() === 1 ? 'post' : 'posts' }}
                                in your account
                            </p>

                        </div>


                        {{-- RIGHT --}}
                        @if ($posts->count())
                            <div class="flex flex-wrap items-center gap-2">

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1.5 text-[9px] font-black text-slate-500 shadow-sm">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    {{ $posts->count() }} shown
                                </span>

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-[9px] font-black text-blue-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    Connected publishing
                                </span>

                            </div>
                        @endif

                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-[1180px] w-full">

                        <thead class="border-b border-slate-100 bg-blue-100">

                            <tr>

                                <th class="w-[34%] px-5 py-3.5 text-left">
                                    <span class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                        Post
                                    </span>
                                </th>


                                <th class="w-[20%] px-5 py-3.5 text-left">
                                    <span class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                        Publishing Accounts
                                    </span>
                                </th>


                                <th class="w-[16%] px-5 py-3.5 text-left">
                                    <span class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                        Schedule
                                    </span>
                                </th>


                                <th class="w-[14%] px-5 py-3.5 text-left">
                                    <span class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                        Status
                                    </span>
                                </th>


                                <th class="w-[16%] px-5 py-3.5 text-center">
                                    <span class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                        Action
                                    </span>
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @forelse ($posts as $post)
                                @php

                                    $status = strtolower((string) $post->status);

                                    $title = $post->title ?: $post->title_Kh ?: 'Untitled Post';

                                    $targetCount = $post->targets->count();

                                    $mediaCount = $post->media->count();

                                    $firstImage = $post->media->first(
                                        fn($media) => str_starts_with(strtolower((string) $media->mime_type), 'image/'),
                                    );

                                    $firstImageUrl = $firstImage
                                        ? route('posts.media', [
                                            'media' => $firstImage->id,
                                        ])
                                        : null;

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

                                <tr class="group transition duration-200 hover:bg-blue-50/20">

                                    <td class="px-5 py-4">

                                        <div class="flex min-w-0 items-center gap-3.5">

                                            <div
                                                class="relative h-12 w-12 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-100 shadow-sm transition duration-200 group-hover:border-blue-300 group-hover:shadow-md">

                                                @if ($firstImageUrl)
                                                    <a href="{{ route('posts.show', $post) }}" class="block h-full w-full"
                                                        title="View post">

                                                        <img src="{{ $firstImageUrl }}"
                                                            alt="{{ $firstImage->file_name ?? 'Post image' }}"
                                                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                                            loading="lazy"
                                                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">


                                                        {{-- Image fallback --}}
                                                        <div
                                                            class="hidden h-full w-full items-center justify-center bg-slate-100 text-slate-400">

                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                                stroke-width="1.7" viewBox="0 0 24 24">
                                                                <rect x="3" y="3" width="18" height="18"
                                                                    rx="2" />

                                                                <circle cx="8.5" cy="8.5" r="1.5" />

                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M21 15l-5-5L5 21" />
                                                            </svg>

                                                        </div>

                                                    </a>
                                                @else
                                                    {{-- No image --}}
                                                    <div
                                                        class="flex h-full w-full items-center justify-center bg-slate-50">

                                                        @if ($mediaCount > 0)
                                                            <svg class="h-5 w-5 text-slate-400" fill="none"
                                                                stroke="currentColor" stroke-width="1.7"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" />

                                                                <path stroke-linecap="round" d="M14 3v6h6" />
                                                            </svg>
                                                        @else
                                                            <svg class="h-5 w-5 text-slate-300" fill="none"
                                                                stroke="currentColor" stroke-width="1.7"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h10" />
                                                            </svg>
                                                        @endif

                                                    </div>
                                                @endif

                                                @if ($mediaCount > 1)
                                                    <span
                                                        class="absolute bottom-1 right-1 inline-flex min-w-[20px] items-center justify-center rounded-md border border-white/20 bg-black/75 px-1.5 py-0.5 text-[8px] font-black text-white shadow-sm backdrop-blur">
                                                        +{{ $mediaCount - 1 }}
                                                    </span>
                                                @endif

                                            </div>


                                            <div class="min-w-0 flex-1">

                                                <a href="{{ route('posts.show', $post) }}"
                                                    class="block max-w-[440px] truncate text-xs font-black leading-5 text-slate-900 transition hover:text-blue-700"
                                                    title="{{ $title }}">
                                                    {{ $title }}
                                                </a>


                                                {{-- Khmer title --}}
                                                @if ($post->title_Kh)
                                                    <p class="mt-1 max-w-[460px] truncate text-[9px] font-medium text-slate-400"
                                                        title="{{ $post->title_Kh }}">
                                                        {{ $post->title_Kh }}
                                                    </p>
                                                @endif


                                                <div class="mt-1.5 flex flex-wrap items-center gap-x-2.5 gap-y-1">

                                                    {{-- Created time --}}
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[9px] font-medium text-slate-400">

                                                        <svg class="h-3 w-3 shrink-0" fill="none"
                                                            stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="9" />

                                                            <path stroke-linecap="round" d="M12 7v5l3 2" />
                                                        </svg>

                                                        <span>
                                                            {{ $post->created_at?->timezone('Asia/Phnom_Penh')->format('d M Y, h:i A') }}
                                                        </span>

                                                    </span>


                                                    <span class="text-slate-200">
                                                        •
                                                    </span>


                                                    {{-- Posted by --}}
                                                    <span
                                                        class="inline-flex max-w-[160px] items-center gap-1 truncate text-[9px] font-bold text-slate-400"
                                                        title="{{ $post->user?->name ?? ($post->user?->email ?? 'Unknown user') }}">

                                                        <svg class="h-3 w-3 shrink-0" fill="none"
                                                            stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                            <circle cx="9" cy="8" r="3" />

                                                            <path stroke-linecap="round" d="M3 19c.5-3.3 2.5-5 6-5" />

                                                            <path stroke-linecap="round" d="M15 11a3 3 0 1 0 0-6" />

                                                            <path stroke-linecap="round" d="M13 14c4 0 6 1.7 6.5 5" />
                                                        </svg>

                                                        <span class="truncate">
                                                            {{ $post->user?->name ?? ($post->user?->email ?? 'Unknown user') }}
                                                        </span>

                                                    </span>


                                                    <span class="text-slate-200">
                                                        •
                                                    </span>


                                                    {{-- Account count --}}
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[9px] font-bold text-slate-400">

                                                        <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" viewBox="0 0 24 24">
                                                            <circle cx="8" cy="8" r="3" />

                                                            <path stroke-linecap="round" d="M2.5 19c.5-3.2 2.3-5 5.5-5" />

                                                            <circle cx="17" cy="9" r="2.5" />

                                                            <path stroke-linecap="round" d="M14 14c3.3.2 5 1.8 5.5 4.5" />
                                                        </svg>

                                                        {{ $targetCount }}
                                                        {{ $targetCount === 1 ? 'account' : 'accounts' }}

                                                    </span>


                                                    @if ($mediaCount > 0)
                                                        <span class="text-slate-200">
                                                            •
                                                        </span>

                                                        {{-- Media count --}}
                                                        <span
                                                            class="inline-flex items-center gap-1 text-[9px] font-bold text-slate-400">

                                                            <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                                stroke-width="1.8" viewBox="0 0 24 24">
                                                                <rect x="3" y="3" width="18" height="18"
                                                                    rx="2" />

                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M7 15l3-3 2 2 3-4 3 4" />
                                                            </svg>

                                                            {{ $mediaCount }}
                                                            {{ $mediaCount === 1 ? 'media' : 'media files' }}

                                                        </span>
                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    <td class="px-5 py-4">

                                        @if ($post->targets->count())
                                            <div class="flex items-center gap-2">

                                                @foreach ($post->targets as $target)
                                                    @php
                                                        $platform = strtolower(
                                                            (string) ($target->socialAccount?->platform ?? ''),
                                                        );

                                                        $platformConfig = match ($platform) {
                                                            'facebook' => [
                                                                'label' => 'Facebook',
                                                                'class' =>
                                                                    'border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100',
                                                            ],

                                                            'instagram' => [
                                                                'label' => 'Instagram',
                                                                'class' =>
                                                                    'border-pink-200 bg-pink-50 text-pink-600 hover:bg-pink-100',
                                                            ],

                                                            'youtube' => [
                                                                'label' => 'YouTube',
                                                                'class' =>
                                                                    'border-red-200 bg-red-50 text-red-600 hover:bg-red-100',
                                                            ],

                                                            'tiktok' => [
                                                                'label' => 'TikTok',
                                                                'class' =>
                                                                    'border-slate-300 bg-slate-100 text-slate-800 hover:bg-slate-200',
                                                            ],

                                                            'linkedin' => [
                                                                'label' => 'LinkedIn',
                                                                'class' =>
                                                                    'border-sky-200 bg-sky-50 text-sky-600 hover:bg-sky-100',
                                                            ],

                                                            'twitter' => [
                                                                'label' => 'X / Twitter',
                                                                'class' =>
                                                                    'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100',
                                                            ],

                                                            'website' => [
                                                                'label' => 'Website',
                                                                'class' =>
                                                                    'border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100',
                                                            ],

                                                            default => [
                                                                'label' =>
                                                                    $target->socialAccount?->platformName ?? 'Unknown',
                                                                'class' =>
                                                                    'border-slate-200 bg-slate-50 text-slate-500 hover:bg-slate-100',
                                                            ],
                                                        };
                                                    @endphp

                                                    <span
                                                        title="{{ $platformConfig['label'] }} — {{ $target->socialAccount?->display_name ?? 'Unknown account' }}"
                                                        class="group/platform inline-flex h-8 w-8 items-center justify-center rounded-xl border {{ $platformConfig['class'] }} transition duration-200 hover:-translate-y-0.5 hover:shadow-sm">

                                                        @switch($platform)
                                                            {{-- Facebook --}}
                                                            @case('facebook')
                                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"
                                                                    aria-hidden="true">
                                                                    <path
                                                                        d="M13.5 21v-8h2.8l.4-3h-3.2V8.1c0-.9.3-1.5 1.6-1.5h1.8V4c-.3 0-1.3-.1-2.5-.1-2.5 0-4.1 1.5-4.1 4.2V10H8v3h2.3v8h3.2Z" />
                                                                </svg>
                                                            @break

                                                            {{-- Instagram --}}
                                                            @case('instagram')
                                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                                    <rect x="3" y="3" width="18" height="18"
                                                                        rx="5" />
                                                                    <circle cx="12" cy="12" r="4" />
                                                                    <circle cx="17.4" cy="6.6" r="1"
                                                                        fill="currentColor" stroke="none" />
                                                                </svg>
                                                            @break

                                                            {{-- YouTube --}}
                                                            @case('youtube')
                                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"
                                                                    aria-hidden="true">
                                                                    <path
                                                                        d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.7V8.3l6.3 3.7-6.3 3.7Z" />
                                                                </svg>
                                                            @break

                                                            {{-- TikTok --}}
                                                            @case('tiktok')
                                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"
                                                                    aria-hidden="true">
                                                                    <path
                                                                        d="M16.7 2h3.1c.2 1.9 1.3 3.5 3.2 4.2v3.2a8.7 8.7 0 0 1-3.2-1v6.7A6.9 6.9 0 1 1 13 8.2v3.4a3.5 3.5 0 1 0 3.5 3.5V2.9h.2V2Zm0 3.1v10a3.5 3.5 0 1 1-3.5-3.5c.3 0 .6 0 .9.1V8.3c-3.6-.2-6.5 2.7-6.5 6.3a6.9 6.9 0 1 0 13.8 0V9.8c1 .5 2.1.8 3.2.8V7.9c-1.9-.7-3-2.3-3.2-4.2h-3.1v1.4Z" />
                                                                </svg>
                                                            @break

                                                            {{-- LinkedIn --}}
                                                            @case('linkedin')
                                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"
                                                                    aria-hidden="true">
                                                                    <path
                                                                        d="M5.2 8.3A2.3 2.3 0 1 0 5.2 3.7a2.3 2.3 0 0 0 0 4.6ZM3 9.6h4.4V21H3V9.6Zm7 0h4.2v1.6h.1c.6-1.1 2.1-2 4.1-2 4.4 0 5.2 2.9 5.2 6.7V21h-4.4v-4.5c0-1.1 0-2.6-1.6-2.6s-1.8 1.2-1.8 2.5V21H10V9.6Z" />
                                                                </svg>
                                                            @break

                                                            {{-- X / Twitter --}}
                                                            @case('twitter')
                                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"
                                                                    aria-hidden="true">
                                                                    <path
                                                                        d="M18.244 2.25h3.308l-7.227 8.26L22.827 21.75h-6.59l-5.157-6.736-5.9 6.736H1.87l7.73-8.835L1.254 2.25H8.01l4.663 6.167 5.571-6.167Zm-1.16 17.52h1.833L7.084 4.126H5.117L17.084 19.77Z" />
                                                                </svg>
                                                            @break

                                                            {{-- Website --}}
                                                            @case('website')
                                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                                    <circle cx="12" cy="12" r="9" />
                                                                    <path stroke-linecap="round" d="M3 12h18" />
                                                                    <path stroke-linecap="round"
                                                                        d="M12 3c2.2 2.4 3.3 5.4 3.3 9S14.2 18.6 12 21" />
                                                                    <path stroke-linecap="round"
                                                                        d="M12 3c-2.2 2.4-3.3 5.4-3.3 9S9.8 18.6 12 21" />
                                                                </svg>
                                                            @break

                                                            {{-- Unknown --}}

                                                            @default
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                                    stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                                                    <circle cx="12" cy="12" r="9" />
                                                                    <path stroke-linecap="round" d="M12 8v4M12 16h.01" />
                                                                </svg>
                                                            @break
                                                        @endswitch

                                                    </span>
                                                @endforeach

                                            </div>
                                        @else
                                            <span
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-300"
                                                title="No connected accounts">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="9" />
                                                    <path stroke-linecap="round" d="M8 12h8" />
                                                </svg>
                                            </span>
                                        @endif

                                    </td>


                                    <td class="px-5 py-4">

                                        @if ($post->scheduled_at)
                                            <div class="inline-flex items-center gap-2.5">

                                                <div
                                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-amber-100 bg-amber-50 text-amber-600">

                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" />

                                                        <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18" />

                                                    </svg>

                                                </div>


                                                <div class="min-w-0">

                                                    <p class="text-[11px] font-black text-slate-700">
                                                        {{ $post->scheduled_at->timezone('Asia/Phnom_Penh')->format('d M Y') }}
                                                    </p>

                                                    <p class="mt-0.5 text-[9px] font-bold text-amber-600">
                                                        {{ $post->scheduled_at->timezone('Asia/Phnom_Penh')->format('h:i A') }}
                                                    </p>

                                                    <p
                                                        class="mt-0.5 text-[7px] font-black uppercase tracking-wide text-slate-300">
                                                        Cambodia Time
                                                    </p>

                                                </div>

                                            </div>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[9px] font-bold text-slate-400">

                                                <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                    stroke-width="1.8" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="9" />

                                                    <path stroke-linecap="round" d="M12 8v4l2.5 2.5" />

                                                </svg>

                                                Not scheduled

                                            </span>
                                        @endif

                                    </td>


                                    <td class="px-5 py-4">

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 {{ $statusConfig['class'] }}">

                                            <span class="relative flex h-1.5 w-1.5">

                                                @if ($status === 'publishing')
                                                    <span
                                                        class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $statusConfig['dot'] }} opacity-60"></span>
                                                @endif

                                                <span
                                                    class="relative inline-flex h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>

                                            </span>

                                            <span class="text-[9px] font-black uppercase tracking-wide">
                                                {{ $statusConfig['label'] }}
                                            </span>

                                        </span>


                                        {{-- Failed error preview --}}
                                        @if ($status === 'failed')
                                            @php
                                                $failedTargets = $post->targets->filter(
                                                    fn($target) => $target->status === 'failed',
                                                );
                                            @endphp

                                            @if ($failedTargets->isNotEmpty())
                                                <p class="mt-1.5 max-w-[190px] truncate text-[8px] font-medium text-red-400"
                                                    title="{{ $failedTargets->pluck('error_message')->filter()->implode(' | ') }}">
                                                    {{ $failedTargets->pluck('error_message')->filter()->first() ?: 'Publishing failed' }}
                                                </p>
                                            @endif
                                        @endif

                                    </td>

                                    <td class="px-5 py-4">

                                        <div class="flex items-center justify-end gap-2">

                                            @if (in_array($status, ['draft', 'failed']))
                                                <form method="POST" action="{{ route('posts.publish', $post) }}">
                                                    @csrf

                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 rounded-xl bg-blue-700 px-3 py-2.5 text-[9px] font-black text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">

                                                        @if ($status === 'failed')
                                                            <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                                stroke-width="2.2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M4 4v5h5M20 20v-5h-5M5.5 16.5A7.5 7.5 0 0 0 19 14M18.5 7.5A7.5 7.5 0 0 0 5 10" />
                                                            </svg>

                                                            Retry
                                                        @else
                                                            <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                                stroke-width="2.2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 12h14M13 6l6 6-6 6" />
                                                            </svg>

                                                            Publish
                                                        @endif

                                                    </button>

                                                </form>
                                            @elseif ($status === 'publishing')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2.5 text-[9px] font-black text-blue-700">

                                                    <svg class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="9" class="opacity-25"
                                                            stroke="currentColor" stroke-width="3" />

                                                        <path d="M21 12a9 9 0 0 1-9 9" stroke="currentColor"
                                                            stroke-width="3" stroke-linecap="round" />

                                                    </svg>

                                                    Publishing

                                                </span>
                                            @elseif ($status === 'scheduled')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-[9px] font-black text-amber-700">

                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="8.5" />

                                                        <path stroke-linecap="round" d="M12 8v4l2.5 2.5" />

                                                    </svg>

                                                    Scheduled

                                                </span>
                                            @elseif ($status === 'published')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2.5 text-[9px] font-black text-blue-700">

                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                        stroke-width="2.3" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m5 12 4 4L19 7" />
                                                    </svg>

                                                    Published

                                                </span>
                                            @else
                                                <span class="text-[10px] font-medium text-slate-300">
                                                    —
                                                </span>
                                            @endif

                                            {{-- VIEW --}}
                                            <a href="{{ route('posts.show', $post) }}" title="View post"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-green-500 shadow-sm transition duration-200 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 hover:shadow">

                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    stroke-width="1.9" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" />

                                                    <circle cx="12" cy="12" r="2.5" />

                                                </svg>

                                            </a>
                                            

                                            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                                class="delete-post-form inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button" title="Delete post"
                                                    data-post-title="{{ $post->title ?: $post->title_Kh ?: 'Untitled post' }}"
                                                    class="delete-post-button inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-100 bg-red-50 text-red-500 shadow-sm transition duration-200 hover:border-red-200 hover:bg-red-100 hover:text-red-700 hover:shadow">

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        stroke-width="1.9" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 6h18" />

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6" />

                                                        <path stroke-linecap="round" d="M10 11v6" />

                                                        <path stroke-linecap="round" d="M14 11v6" />
                                                    </svg>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                                @empty

                                    <tr>

                                        <td colspan="5" class="px-6 py-20">

                                            <div class="mx-auto max-w-md text-center">

                                                <div
                                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 text-blue-600 shadow-sm">

                                                    <svg class="h-7 w-7" fill="none" stroke="currentColor"
                                                        stroke-width="1.7" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M7 8h10M7 12h7m-9 8h8l5 2v-5a7 7 0 0 0 4-6.5A7 7 0 0 0 15 4H9a7 7 0 0 0-7 7 7 7 0 0 0 3 5.8V20l2-2z" />
                                                    </svg>

                                                </div>


                                                <h3 class="mt-5 text-sm font-black text-slate-900">
                                                    No posts yet
                                                </h3>


                                                <p class="mx-auto mt-1.5 max-w-sm text-xs leading-5 text-slate-400">
                                                    Create your first post and publish it to your connected social media
                                                    accounts or NGO Forum website.
                                                </p>


                                                <a href="{{ route('posts.create') }}"
                                                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-black text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-800 hover:shadow-md">

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        stroke-width="2.3" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                                                    </svg>

                                                    Create Your First Post

                                                </a>

                                            </div>

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    @if ($posts->hasPages())
                        <div
                            class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                            <p class="text-[10px] font-semibold text-slate-400">

                                Showing

                                <span class="font-black text-slate-600">
                                    {{ $posts->firstItem() }}
                                </span>

                                to

                                <span class="font-black text-slate-600">
                                    {{ $posts->lastItem() }}
                                </span>

                                of

                                <span class="font-black text-slate-600">
                                    {{ $posts->total() }}
                                </span>

                                posts

                            </p>


                            <div>
                                {{ $posts->links() }}
                            </div>

                        </div>
                    @endif

                </div>

            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const period = document.getElementById('exportPeriod');

                const fromWrapper =
                    document.getElementById('fromMonthWrapper');

                const toWrapper =
                    document.getElementById('toMonthWrapper');

                const fromMonth =
                    document.getElementById('fromMonth');

                const toMonth =
                    document.getElementById('toMonth');

                const form =
                    document.getElementById('exportForm');


                function updateRangeFields() {

                    const custom =
                        period.value === 'custom';

                    fromWrapper.classList.toggle(
                        'hidden',
                        !custom
                    );

                    toWrapper.classList.toggle(
                        'hidden',
                        !custom
                    );

                    fromMonth.required = custom;
                    toMonth.required = custom;
                }


                period.addEventListener(
                    'change',
                    updateRangeFields
                );


                form.addEventListener(
                    'submit',
                    function(event) {

                        if (period.value !== 'custom') {
                            return;
                        }

                        if (
                            !fromMonth.value ||
                            !toMonth.value
                        ) {

                            event.preventDefault();

                            Swal.fire({
                                icon: 'warning',
                                title: 'Month required',
                                text: 'Please select both From Month and To Month.',
                                confirmButtonColor: '#059669',
                            });

                            return;
                        }


                        if (fromMonth.value > toMonth.value) {

                            event.preventDefault();

                            Swal.fire({
                                icon: 'warning',
                                title: 'Invalid range',
                                text: 'From Month cannot be later than To Month.',
                                confirmButtonColor: '#059669',
                            });

                            return;
                        }

                    }
                );


                updateRangeFields();

            });


            document.addEventListener('DOMContentLoaded', function() {

                document
                    .querySelectorAll('.delete-post-button')
                    .forEach(function(button) {

                        button.addEventListener('click', function() {

                            const form =
                                button.closest('.delete-post-form');

                            const title =
                                button.dataset.postTitle ||
                                'Untitled post';


                            Swal.fire({

                                title: 'Delete this post?',

                                html: `
                            <div class="text-left">

                                <div class="rounded-xl border border-red-100 bg-red-50 px-4 py-3">

                                    <div class="flex items-start gap-3">

                                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">

                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M12 9v4"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M12 17h.01"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M10.3 3.8 2.4 17a2 2 0 0 0 1.7 3h15.8a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0Z"
                                                />
                                            </svg>

                                        </div>

                                        <div class="min-w-0">

                                            <p class="text-[9px] font-black uppercase tracking-wider text-red-500">
                                                Post to delete
                                            </p>

                                            <p class="mt-1 break-words text-sm font-black text-slate-800">
                                                ${escapeHtml(title)}
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                <div class="mt-4 rounded-xl bg-slate-50 px-4 py-3">

                                    <p class="text-xs leading-5 text-slate-500">
                                        This action will permanently delete:
                                    </p>

                                    <div class="mt-2 space-y-1">

                                        <p class="text-[10px] font-bold text-slate-600">
                                            • Post data
                                        </p>

                                        <p class="text-[10px] font-bold text-slate-600">
                                            • Publishing targets
                                        </p>

                                        <p class="text-[10px] font-bold text-slate-600">
                                            • Publishing logs
                                        </p>

                                        <p class="text-[10px] font-bold text-slate-600">
                                            • Media records
                                        </p>

                                        <p class="text-[10px] font-bold text-slate-600">
                                            • Uploaded media files
                                        </p>

                                    </div>

                                </div>


                                <p class="mt-4 text-center text-[10px] font-bold text-red-500">
                                    This action cannot be undone.
                                </p>

                            </div>
                        `,

                                icon: 'warning',

                                showCancelButton: true,

                                confirmButtonText: 'Yes, Delete',

                                cancelButtonText: 'Cancel',

                                reverseButtons: true,

                                focusCancel: true,

                                buttonsStyling: false,

                                customClass: {

                                    popup: 'rounded-2xl',

                                    title: 'text-lg font-black text-slate-800',

                                    htmlContainer: 'mt-2',

                                    confirmButton: 'rounded-xl bg-red-600 px-5 py-2.5 text-xs font-black text-white ml-3 transition hover:bg-red-700',

                                    cancelButton: 'rounded-xl bg-amber-400 px-5 py-2.5 text-xs font-black text-white transition hover:bg-amber-500'
                                }

                            }).then(function(result) {

                                if (
                                    result.isConfirmed &&
                                    form
                                ) {

                                    const deleteButton =
                                        form.querySelector(
                                            '.delete-post-button'
                                        );

                                    if (deleteButton) {

                                        deleteButton.disabled =
                                            true;

                                        deleteButton.classList.add(
                                            'opacity-50',
                                            'cursor-not-allowed'
                                        );
                                    }

                                    form.submit();
                                }

                            });

                        });

                    });


                function escapeHtml(value) {

                    return String(value)
                        .replace(
                            /&/g,
                            '&amp;'
                        )
                        .replace(
                            /</g,
                            '&lt;'
                        )
                        .replace(
                            />/g,
                            '&gt;'
                        )
                        .replace(
                            /"/g,
                            '&quot;'
                        )
                        .replace(
                            /'/g,
                            '&#039;'
                        );
                }

            });
        </script>
    @endsection
