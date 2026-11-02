@extends('layouts.app')

@section('content')

    <div class="min-h-[calc(100vh-4rem)] bg-slate-50">

        <section class="border-b border-slate-200 bg-gradient-to-r from-white via-white to-blue-50/40">

            <div class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    {{-- LEFT --}}
                    <div class="flex min-w-0 items-start gap-4">

                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-lg shadow-blue-200/60">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                            </svg>
                        </div>

                        <div class="min-w-0">

                            <div class="flex flex-wrap items-center gap-2">

                                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-blue-600">
                                    NGO Forum Social Publisher
                                </p>

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-2 py-0.5 text-[8px] font-black uppercase tracking-wide text-blue-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    New Post
                                </span>

                            </div>

                            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-950">
                                Create Post
                            </h1>

                            <p class="mt-1 max-w-3xl text-xs font-medium leading-5 text-slate-500">
                                Select your publishing accounts, add media, and enter content
                                according to the publishing rules.
                            </p>

                        </div>

                    </div>


                    {{-- RIGHT --}}
                    <div class="flex shrink-0 items-center gap-2">

                        <div
                            class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2.5 shadow-sm sm:flex">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-40"></span>

                                <span class="relative h-2 w-2 rounded-full bg-blue-500"></span>
                            </span>

                            <span class="text-[9px] font-bold uppercase tracking-wide text-slate-500">
                                Publishing Ready
                            </span>
                        </div>

                        <a href="{{ route('posts.index') }}"
                            class="group inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-black text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 hover:shadow-md">
                            <svg class="h-3.5 w-3.5 transition-transform group-hover:-translate-x-0.5" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7-7-7 7-7" />
                            </svg>

                            Back to Posts
                        </a>

                    </div>

                </div>

            </div>

            <div class="h-1 bg-gradient-to-r from-blue-600 via-blue-500 to-transparent"></div>

        </section>

        <main class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8">


            @if ($errors->any())
                <div class="mb-5 overflow-hidden rounded-2xl border border-red-200 bg-white shadow-sm">

                    <div class="border-l-4 border-red-500 bg-red-50 px-5 py-4">

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                                !
                            </div>

                            <div class="min-w-0">

                                <p class="text-xs font-black text-red-800">
                                    Please correct the following
                                </p>

                                <div class="mt-2 space-y-1">

                                    @foreach ($errors->all() as $error)
                                        <p class="text-[10px] leading-4 text-red-700">
                                            • {{ $error }}
                                        </p>
                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            @endif


            @if (session('error'))
                <div class="mb-5 overflow-hidden rounded-2xl border border-red-200 bg-white shadow-sm">

                    <div class="border-l-4 border-red-500 bg-red-50 px-5 py-4">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                                !
                            </div>

                            <p class="text-xs font-bold text-red-700">
                                {{ session('error') }}
                            </p>

                        </div>

                    </div>

                </div>
            @endif

            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">

                @csrf


                <div class="space-y-5">

                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 bg-gradient-to-r from-white to-blue-50/40 px-5 py-4">

                            <div class="flex items-center justify-between gap-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-[10px] font-black text-blue-700">
                                        01
                                    </div>

                                    <div>

                                        <h2 class="text-sm font-black text-slate-950">
                                            Publishing Accounts
                                        </h2>

                                        <p class="mt-0.5 text-[10px] text-slate-400">
                                            Select one or more connected accounts.
                                        </p>

                                    </div>

                                </div>


                                <button type="button" id="selectAll"
                                    class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-[9px] font-black text-blue-700 transition hover:bg-blue-100">
                                    Select All
                                </button>

                            </div>

                        </div>


                        <div class="p-5 grid grid-cols-1 gap-6 md:grid-cols-2">

                            @php

                                $groupedAccounts = $accounts->groupBy(
                                    fn($account) => strtolower((string) $account->platform),
                                );

                                $platformNames = [
                                    'facebook' => 'Facebook',
                                    'instagram' => 'Instagram',
                                    'youtube' => 'YouTube',
                                    'tiktok' => 'TikTok',
                                    'linkedin' => 'LinkedIn',
                                    'twitter' => 'X / Twitter',
                                    'website' => 'Website',
                                ];

                            @endphp


                            @forelse ($groupedAccounts as $platform => $platformAccounts)
                                <div class="mb-6 last:mb-0">

                                    <div class="mb-2.5 flex items-center justify-between">

                                        <div class="flex items-center gap-2">

                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                                            <p class="text-[9px] font-black uppercase tracking-[0.16em] text-slate-400">
                                                {{ $platformNames[$platform] ?? ucfirst($platform) }}
                                            </p>

                                        </div>

                                        <span class="text-[8px] font-bold text-red-500">
                                            {{ $platformAccounts->count() }}
                                            {{ $platformAccounts->count() === 1 ? 'account' : 'accounts' }}
                                        </span>

                                    </div>


                                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                                        @foreach ($platformAccounts as $account)
                                            <label class="group cursor-pointer">

                                                <input type="checkbox" name="social_accounts[]" value="{{ $account->id }}"
                                                    data-platform="{{ $platform }}"
                                                    class="account-checkbox peer sr-only"
                                                    {{ in_array($account->id, old('social_accounts', [])) ? 'checked' : '' }}>

                                                <div
                                                    class="relative flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-sm peer-checked:border-blue-500 peer-checked:bg-blue-50/60 peer-checked:shadow-sm">

                                                    <div
                                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                                            bg-slate-100 text-slate-600
                                                            transition duration-200
                                                            peer-checked:bg-blue-600
                                                            peer-checked:text-white">

                                                        @switch($platform)
                                                            {{-- Facebook --}}
                                                            @case('facebook')
                                                                <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24"
                                                                    fill="currentColor" aria-hidden="true">
                                                                    <path
                                                                        d="M13.5 21v-8h2.8l.4-3h-3.2V8.1c0-.9.3-1.5 1.6-1.5h1.8V4c-.3 0-1.3-.1-2.5-.1-2.5 0-4.1 1.5-4.1 4.2V10H8v3h2.3v8h3.2Z" />
                                                                </svg>
                                                            @break

                                                            {{-- Instagram --}}
                                                            @case('instagram')
                                                                <svg class="h-5 w-5 text-red-600" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                                    <rect x="3" y="3" width="18" height="18"
                                                                        rx="5" />
                                                                    <circle cx="12" cy="12" r="4" />
                                                                    <circle cx="17.4" cy="6.6" r="1" fill="currentColor"
                                                                        stroke="none" />
                                                                </svg>
                                                            @break

                                                            {{-- YouTube --}}
                                                            @case('youtube')
                                                                <svg class="h-5 w-5 text-red-600" viewBox="0 0 24 24"
                                                                    fill="currentColor" aria-hidden="true">
                                                                    <path
                                                                        d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.7V8.3l6.3 3.7-6.3 3.7Z" />
                                                                </svg>
                                                            @break

                                                            {{-- TikTok --}}
                                                            @case('tiktok')
                                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"
                                                                    aria-hidden="true">
                                                                    <path
                                                                        d="M16.7 2h3.1c.2 1.9 1.3 3.5 3.2 4.2v3.2a8.7 8.7 0 0 1-3.2-1v6.7A6.9 6.9 0 1 1 13 8.2v3.4a3.5 3.5 0 1 0 3.5 3.5V2.9h.2V2Zm0 3.1v10a3.5 3.5 0 1 1-3.5-3.5c.3 0 .6 0 .9.1V8.3c-3.6-.2-6.5 2.7-6.5 6.3a6.9 6.9 0 1 0 13.8 0V9.8c1 .5 2.1.8 3.2.8V7.9c-1.9-.7-3-2.3-3.2-4.2h-3.1v1.4Z" />
                                                                </svg>
                                                            @break

                                                            {{-- LinkedIn --}}
                                                            @case('linkedin')
                                                                <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24"
                                                                    fill="currentColor" aria-hidden="true">
                                                                    <path
                                                                        d="M5.2 8.3A2.3 2.3 0 1 0 5.2 3.7a2.3 2.3 0 0 0 0 4.6ZM3 9.6h4.4V21H3V9.6Zm7 0h4.2v1.6h.1c.6-1.1 2.1-2 4.1-2 4.4 0 5.2 2.9 5.2 6.7V21h-4.4v-4.5c0-1.1 0-2.6-1.6-2.6s-1.8 1.2-1.8 2.5V21H10V9.6Z" />
                                                                </svg>
                                                            @break

                                                            {{-- X / Twitter --}}
                                                            @case('twitter')
                                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"
                                                                    aria-hidden="true">
                                                                    <path
                                                                        d="M18.244 2.25h3.308l-7.227 8.26L22.827 21.75h-6.59l-5.157-6.736-5.9 6.736H1.87l7.73-8.835L1.254 2.25H8.01l4.663 6.167 5.571-6.167Zm-1.16 17.52h1.833L7.084 4.126H5.117L17.084 19.77Z" />
                                                                </svg>
                                                            @break

                                                            {{-- Website --}}
                                                            @case('website')
                                                                <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24"
                                                                    fill="none" stroke="currentColor" stroke-width="1.8"
                                                                    aria-hidden="true">
                                                                    <circle cx="12" cy="12" r="9" />
                                                                    <path d="M3 12h18" />
                                                                    <path d="M12 3c2.2 2.4 3.3 5.4 3.3 9S14.2 18.6 12 21" />
                                                                    <path d="M12 3c-2.2 2.4-3.3 5.4-3.3 9S9.8 18.6 12 21" />
                                                                </svg>
                                                            @break

                                                            @default
                                                                <span class="text-sm font-black">
                                                                    {{ strtoupper(substr($platform, 0, 1)) }}
                                                                </span>
                                                        @endswitch
                                                    </div>


                                                    <div class="min-w-0 flex-1">

                                                        <p class="truncate text-xs font-black text-slate-800">
                                                            {{ $account->display_name ?: 'Unnamed Account' }}
                                                        </p>

                                                        <p class="mt-1 truncate text-[9px] text-slate-400">

                                                            {{ $account->account_type ?: $platformNames[$platform] ?? ucfirst($platform) }}

                                                            @if ($account->username)
                                                                <span class="text-slate-300">·</span>

                                                                {{ $account->username }}
                                                            @endif

                                                        </p>

                                                    </div>


                                                    <div
                                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-slate-300 bg-white text-[10px] font-black text-transparent transition peer-checked:border-blue-600 peer-checked:bg-blue-600 peer-checked:text-white">
                                                        ✓
                                                    </div>

                                                </div>

                                            </label>
                                        @endforeach

                                    </div>

                                </div>

                                @empty

                                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">

                                        <div class="flex items-start gap-3">

                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                                !
                                            </div>

                                            <div>

                                                <p class="text-xs font-black text-amber-800">
                                                    No connected accounts
                                                </p>

                                                <p class="mt-1 text-[10px] leading-4 text-amber-700">
                                                    Connect Facebook, Instagram, YouTube, TikTok,
                                                    LinkedIn, X or Website before publishing.
                                                </p>

                                                @if (Route::has('social-accounts.create'))
                                                    <a href="{{ route('social-accounts.create') }}"
                                                        class="mt-2 inline-block text-[10px] font-black text-amber-800 underline">
                                                        Connect Media Account
                                                    </a>
                                                @endif

                                            </div>

                                        </div>

                                    </div>
                                @endforelse

                            </div>

                            <div id="accountSummary"
                                class="mt-4 rounded-xl border border-slate-100 bg-slate-50 px-5 py-5 text-[10px] font-bold text-slate-500">
                                0 accounts selected
                            </div>

                        </section>

                        <section id="websiteDepartmentField"
                            class="hidden overflow-hidden rounded-2xl border border-blue-200 bg-blue-50/40 shadow-sm">

                            <div class="border-b border-blue-100 bg-white/70 px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                        ◉
                                    </div>

                                    <div>

                                        <h2 class="text-sm font-black text-blue-950">
                                            Website Department
                                        </h2>

                                        <p class="mt-0.5 text-[10px] text-slate-500">
                                            This field appears automatically when Website is selected.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="p-5">

                                <label for="website_department"
                                    class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                    Department
                                </label>

                                <select name="website_department" id="website_department"
                                    class="mt-2 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold shadow-sm focus:border-blue-500 focus:ring-blue-500">

                                    <option value="">
                                        Select department
                                    </option>

                                    <option value="PALI" {{ old('website_department') === 'PALI' ? 'selected' : '' }}>
                                        PALI
                                    </option>

                                    <option value="RITI" {{ old('website_department') === 'RITI' ? 'selected' : '' }}>
                                        RITI
                                    </option>

                                    <option value="SACHAS" {{ old('website_department') === 'SACHAS' ? 'selected' : '' }}>
                                        SACHAS
                                    </option>

                                    <option value="MACOR" {{ old('website_department') === 'MACOR' ? 'selected' : '' }}>
                                        MACOR
                                    </option>

                                </select>


                                <p id="websiteDepartmentHint" class="mt-2 hidden text-[9px] font-bold text-red-600">
                                    Website department is required when Website is selected.
                                </p>

                            </div>

                        </section>


                        {{-- MEDIA --}}
                        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                            <div class="border-b border-slate-100 bg-gradient-to-r from-white to-violet-50/40 px-5 py-4">

                                <div class="flex items-center justify-between gap-3">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100 text-[10px] font-black text-violet-700">
                                            02
                                        </div>

                                        <div>
                                            <h2 class="text-sm font-black text-slate-950">
                                                Media
                                            </h2>

                                            <p class="mt-0.5 text-[10px] text-slate-400">
                                                Add images and one video to your post.
                                            </p>
                                        </div>

                                    </div>

                                    <span id="mediaCounterBadge"
                                        class="hidden rounded-full bg-violet-50 px-2.5 py-1 text-[8px] font-black text-violet-700">
                                        0/15 images · 0/1 video
                                    </span>

                                </div>

                            </div>


                            <div class="p-5">

                                {{-- Upload box --}}
                                <div id="imageUploadArea"
                                    class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/80 p-8 text-center transition hover:border-blue-300 hover:bg-blue-50/30">

                                    <div
                                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm ring-1 ring-slate-200">

                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.7"
                                            viewBox="0 0 24 24">

                                            <rect x="3" y="3" width="18" height="18" rx="2" />

                                            <circle cx="8.5" cy="8.5" r="1.5" />

                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21" />

                                        </svg>

                                    </div>


                                    <p class="mt-4 text-sm font-black text-slate-800">
                                        Add post media
                                    </p>

                                    <p class="mt-1 text-[10px] text-slate-400">
                                        JPG, JPEG, PNG, WEBP, GIF · MP4, MOV, WEBM, MKV, AVI
                                    </p>

                                    <p class="mt-1 text-[9px] font-bold text-slate-400">
                                        Maximum 15 images + 1 video · 200 MB per file
                                    </p>


                                    <input type="file" id="media" name="media[]"
                                        accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime,video/x-matroska,video/x-msvideo"
                                        multiple class="hidden">

                                    <input type="file" id="youtube_slideshow" name="youtube_slideshow" accept="video/mp4"
                                        class="hidden">


                                    <button type="button" id="addImagesButton"
                                        class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-5 py-2.5 text-[10px] font-black text-white shadow-sm transition hover:bg-blue-800 hover:shadow-md">

                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.3"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" d="M12 5v14M5 12h14" />

                                        </svg>

                                        Add Media

                                    </button>

                                </div>


                                {{-- Media summary --}}
                                <div id="mediaCount"
                                    class="mt-3 hidden rounded-xl border border-violet-100 bg-violet-50 px-3.5 py-2.5 text-[10px] font-bold text-violet-700">
                                </div>


                                {{-- Preview --}}
                                <div id="mediaPreview" class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                                </div>


                                {{-- Add more --}}
                                <div class="mt-4 flex justify-end">

                                    <button type="button" id="addMoreImagesButton"
                                        class="hidden items-center gap-1.5 rounded-lg border border-dashed border-blue-300 bg-blue-50/40 px-3 py-2 text-xs font-black text-blue-700 transition hover:border-blue-400 hover:bg-blue-50">

                                        + Add More Media

                                    </button>

                                </div>

                            </div>

                        </section>


                        <section id="publishingRules"
                            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                            <div class="border-b border-slate-100 bg-gradient-to-r from-white to-blue-50/40 px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-[10px] font-black text-blue-700">
                                        03
                                    </div>

                                    <div>

                                        <h2 class="text-sm font-black text-slate-950">
                                            Publishing Rules
                                        </h2>

                                        <p class="mt-0.5 text-[10px] text-slate-400">
                                            Content fields appear according to the selected accounts.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="p-5">

                                <div id="publishingRuleStatus"
                                    class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">

                                    <div class="flex items-center gap-2">

                                        <span id="publishingRuleDot" class="h-2 w-2 rounded-full bg-slate-300"></span>

                                        <p id="publishingRuleText" class="text-[10px] font-black text-slate-500">
                                            Select a publishing account to determine the content fields.
                                        </p>

                                    </div>

                                </div>


                                <div id="englishRule" class="mt-3 hidden rounded-xl border border-blue-100 bg-blue-50/60 p-3">

                                    <div class="flex items-center justify-between gap-3">

                                        <div class="flex items-center gap-2">

                                            <span class="rounded-lg bg-blue-100 px-2 py-1 text-[8px] font-black text-blue-700">
                                                EN
                                            </span>

                                            <span class="text-[10px] font-black text-blue-800">
                                                English Content
                                            </span>

                                        </div>

                                        <span class="text-[8px] font-bold text-blue-500">
                                            Enabled
                                        </span>

                                    </div>

                                    <p class="mt-1 text-[9px] leading-4 text-blue-600/80">
                                        English title and description will be shown.
                                    </p>

                                </div>


                                <div id="khmerRule" class="mt-3 hidden rounded-xl border border-amber-100 bg-amber-50/60 p-3">

                                    <div class="flex items-center justify-between gap-3">

                                        <div class="flex items-center gap-2">

                                            <span
                                                class="rounded-lg bg-amber-100 px-2 py-1 text-[8px] font-black text-amber-700">
                                                KH
                                            </span>

                                            <span class="text-[10px] font-black text-amber-800">
                                                Khmer Content
                                            </span>

                                        </div>

                                        <span class="text-[8px] font-bold text-amber-500">
                                            Enabled
                                        </span>

                                    </div>

                                    <p class="mt-1 text-[9px] leading-4 text-amber-600/80">
                                        Khmer title and description will be shown because Facebook or YouTube is selected.
                                    </p>

                                </div>

                            </div>

                        </section>


                        <section id="englishContentSection"
                            class="hidden overflow-hidden rounded-2xl border border-blue-100 bg-white shadow-sm">

                            <div class="border-b border-blue-100 bg-blue-50/40 px-5 py-4">

                                <div class="flex items-center justify-between">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-[10px] font-black text-blue-700">
                                            04
                                        </div>

                                        <div>

                                            <h2 class="text-sm font-black text-slate-950">
                                                English Content
                                            </h2>

                                            <p class="mt-0.5 text-[10px] text-slate-400">
                                                Enter the English title and description.
                                            </p>

                                        </div>

                                    </div>

                                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[8px] font-black text-blue-700">
                                        EN
                                    </span>

                                </div>

                            </div>


                            <div class="space-y-5 p-5">

                                <div>

                                    <div class="flex items-center justify-between">

                                        <label for="title"
                                            class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                            Title
                                        </label>

                                        <span id="englishTitleCount" class="text-[8px] font-bold text-slate-400">
                                            0 / 255
                                        </span>

                                    </div>


                                    <input name="title" id="title" type="text" value="{{ old('title') }}"
                                        maxlength="255" disabled
                                        class="mt-2 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold shadow-sm placeholder:text-slate-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-slate-50 disabled:text-slate-400"
                                        placeholder="Enter English title">

                                </div>


                                <div>

                                    <div class="flex items-center justify-between">

                                        <label for="content"
                                            class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                            Description
                                        </label>

                                        <span id="englishContentCount" class="text-[8px] font-bold text-slate-400">
                                            0 characters
                                        </span>

                                    </div>


                                    <textarea name="content" id="content" rows="9" disabled
                                        class="mt-2 w-full resize-y rounded-xl border-slate-200 bg-white text-sm leading-6 shadow-sm placeholder:text-slate-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-slate-50 disabled:text-slate-400"
                                        placeholder="Write your English content...">{{ old('content') }}</textarea>

                                </div>

                            </div>

                        </section>

                        <section id="khmerContentSection"
                            class="hidden overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-sm">

                            <div class="border-b border-amber-100 bg-amber-50/40 px-5 py-4">

                                <div class="flex items-center justify-between">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-[10px] font-black text-amber-700">
                                            05
                                        </div>

                                        <div>

                                            <h2 class="text-sm font-black text-slate-950">
                                                Khmer Content
                                            </h2>

                                            <p class="mt-0.5 text-[10px] text-slate-400">
                                                Enter the Khmer title and description.
                                            </p>

                                        </div>

                                    </div>

                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[8px] font-black text-amber-700">
                                        KH
                                    </span>

                                </div>

                            </div>


                            <div class="space-y-5 p-5">

                                <div>

                                    <label for="title_Kh"
                                        class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                        Title
                                    </label>

                                    <input name="title_Kh" id="title_Kh" type="text" value="{{ old('title_Kh') }}"
                                        maxlength="255" disabled
                                        class="mt-2 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold shadow-sm placeholder:text-slate-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-slate-50 disabled:text-slate-400"
                                        placeholder="សរសេរចំណងជើងជាភាសាខ្មែរ">

                                </div>


                                <div>

                                    <div class="flex items-center justify-between">

                                        <label for="content_Kh"
                                            class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                            Description
                                        </label>

                                        <span id="khmerContentCount" class="text-[8px] font-bold text-slate-400">
                                            0 តួអក្សរ
                                        </span>

                                    </div>


                                    <textarea name="content_Kh" id="content_Kh" rows="9" disabled
                                        class="mt-2 w-full resize-y rounded-xl border-slate-200 bg-white text-sm leading-7 shadow-sm placeholder:text-slate-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-slate-50 disabled:text-slate-400"
                                        placeholder="សរសេរខ្លឹមសារជាភាសាខ្មែរ...">{{ old('content_Kh') }}</textarea>

                                </div>

                            </div>

                        </section>

                        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                            {{-- PUBLISHING --}}
                            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                                <div class="border-b border-slate-100 bg-gradient-to-r from-white to-amber-50/30 px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                            06
                                        </div>

                                        <div>
                                            <h2 class="text-sm font-black text-slate-950">
                                                Publishing
                                            </h2>

                                            <p class="mt-0.5 text-[10px] text-slate-400">
                                                Publish now or schedule for later.
                                            </p>
                                        </div>

                                    </div>

                                </div>

                                <div class="p-5">

                                    <div class="grid grid-cols-1 gap-3">

                                        {{-- Publish Now --}}
                                        <label
                                            class="group flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-blue-300 hover:bg-blue-50/30">

                                            <input type="radio" name="publish_now" value="1"
                                                {{ old('publish_now', '1') == '1' ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500">

                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" d="M5 12h14M13 6l6 6-6 6" />
                                                </svg>

                                            </div>

                                            <div>
                                                <p class="text-xs font-black text-slate-800">
                                                    Publish Now
                                                </p>

                                                <p class="mt-0.5 text-[9px] text-slate-400">
                                                    Start publishing after submission.
                                                </p>
                                            </div>

                                        </label>


                                        {{-- Schedule --}}
                                        <label
                                            class="group flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-amber-300 hover:bg-amber-50/30">

                                            <input type="radio" name="publish_now" value="0" id="scheduleOption"
                                                {{ old('publish_now') === '0' ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500">

                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">

                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                                    viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="9" />
                                                    <path stroke-linecap="round" d="M12 7v5l3 2" />
                                                </svg>

                                            </div>

                                            <div>
                                                <p class="text-xs font-black text-slate-800">
                                                    Schedule
                                                </p>

                                                <p class="mt-0.5 text-[9px] text-slate-400">
                                                    Publish automatically later.
                                                </p>
                                            </div>

                                        </label>

                                    </div>


                                    {{-- Schedule Field --}}
                                    <div id="scheduleField"
                                        class="{{ old('publish_now') === '0' ? '' : 'hidden' }} mt-4 rounded-2xl border border-amber-100 bg-amber-50/40 p-4">

                                        <label for="scheduled_at"
                                            class="text-[9px] font-black uppercase tracking-[0.14em] text-slate-500">
                                            Date & Time
                                        </label>

                                        <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                            value="{{ old('scheduled_at') }}"
                                            min="{{ now('Asia/Phnom_Penh')->format('Y-m-d\TH:i') }}"
                                            class="mt-2 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold shadow-sm focus:border-blue-500 focus:ring-blue-500">

                                        <div class="mt-2 flex items-center gap-2 rounded-xl bg-white px-3 py-2 shadow-sm">

                                            <svg class="h-3.5 w-3.5 text-amber-600" fill="none" stroke="currentColor"
                                                stroke-width="1.8" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="9" />
                                                <path stroke-linecap="round" d="M12 7v5l3 2" />
                                            </svg>

                                            <p class="text-[9px] font-semibold text-amber-700">
                                                Cambodia Time — Asia/Phnom_Penh (UTC+7)
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </section>


                            {{-- READY TO PUBLISH --}}
                            <section
                                class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-700 via-blue-800 to-blue-900 p-6 text-white shadow-xl">

                                <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-white/5"></div>
                                <div class="absolute -bottom-16 -left-16 h-40 w-40 rounded-full bg-white/5"></div>

                                <div class="relative">

                                    <div class="flex items-center gap-2">

                                        <span class="h-2 w-2 rounded-full bg-blue-300"></span>

                                        <p class="text-[8px] font-black uppercase tracking-[0.22em] text-blue-200">
                                            Ready to Publish
                                        </p>

                                    </div>

                                    <h2 class="mt-2 text-xl font-black">
                                        Publish content
                                    </h2>

                                    <p class="mt-2 max-w-3xl text-[10px] leading-5 text-blue-100">
                                        The selected accounts determine which content fields are displayed.
                                        Images will be handled according to each platform's publishing rules.
                                    </p>

                                    <div id="submitAccountStatus"
                                        class="mt-4 rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-[10px] font-bold text-blue-50 backdrop-blur">
                                        No accounts selected.
                                    </div>

                                    <button type="submit" id="submitButton"
                                        class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-white px-5 py-3.5 text-xs font-black text-blue-800 shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-50 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-60">

                                        <svg id="submitSpinner" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24"
                                            fill="none">
                                            <circle cx="12" cy="12" r="9" stroke="currentColor"
                                                stroke-width="3" class="opacity-25" />

                                            <path d="M21 12a9 9 0 0 1-9 9" stroke="currentColor" stroke-width="3"
                                                stroke-linecap="round" />
                                        </svg>

                                        <span id="submitText">
                                            Publish Post
                                        </span>

                                    </button>

                                </div>

                            </section>

                        </div>

                    </div>

                </form>

            </main>

        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /*
                |--------------------------------------------------------------------------
                | ELEMENTS
                |--------------------------------------------------------------------------
                */

                const form =
                    document.getElementById('postForm');

                const checkboxes =
                    Array.from(
                        document.querySelectorAll('.account-checkbox')
                    );

                const selectAll =
                    document.getElementById('selectAll');

                const accountSummary =
                    document.getElementById('accountSummary');

                const submitAccountStatus =
                    document.getElementById('submitAccountStatus');

                const media =
                    document.getElementById('media');

                const mediaPreview =
                    document.getElementById('mediaPreview');

                const mediaCount =
                    document.getElementById('mediaCount');

                const mediaCounterBadge =
                    document.getElementById('mediaCounterBadge');

                const addImagesButton =
                    document.getElementById('addImagesButton');

                const addMoreImagesButton =
                    document.getElementById('addMoreImagesButton');

                const title =
                    document.getElementById('title');

                const content =
                    document.getElementById('content');

                const titleKh =
                    document.getElementById('title_Kh');

                const contentKh =
                    document.getElementById('content_Kh');

                const englishContentSection =
                    document.getElementById('englishContentSection');

                const khmerContentSection =
                    document.getElementById('khmerContentSection');

                const englishRule =
                    document.getElementById('englishRule');

                const khmerRule =
                    document.getElementById('khmerRule');

                const publishingRuleText =
                    document.getElementById('publishingRuleText');

                const publishingRuleDot =
                    document.getElementById('publishingRuleDot');

                const scheduleOption =
                    document.getElementById('scheduleOption');

                const scheduleField =
                    document.getElementById('scheduleField');

                const scheduledAt =
                    document.getElementById('scheduled_at');

                const websiteDepartmentField =
                    document.getElementById('websiteDepartmentField');

                const websiteDepartment =
                    document.getElementById('website_department');

                const websiteDepartmentHint =
                    document.getElementById('websiteDepartmentHint');

                const submitButton =
                    document.getElementById('submitButton');

                const submitSpinner =
                    document.getElementById('submitSpinner');

                const submitText =
                    document.getElementById('submitText');

                const englishTitleCount =
                    document.getElementById('englishTitleCount');

                const englishContentCount =
                    document.getElementById('englishContentCount');

                const khmerContentCount =
                    document.getElementById('khmerContentCount');


                /*
                |--------------------------------------------------------------------------
                | PLATFORM DATA
                |--------------------------------------------------------------------------
                */

                const platformNames = {
                    facebook: 'Facebook',
                    instagram: 'Instagram',
                    youtube: 'YouTube',
                    tiktok: 'TikTok',
                    linkedin: 'LinkedIn',
                    twitter: 'X / Twitter',
                    website: 'Website'
                };


                /*
                |--------------------------------------------------------------------------
                | MEDIA STORAGE
                |--------------------------------------------------------------------------
                |
                | Maximum:
                | - 15 images
                | - 1 video
                | - 16 total files
                | - 200 MB per file
                |
                */

                let selectedMedia = [];

                const MAX_IMAGES = 15;
                const MAX_VIDEOS = 1;
                const MAX_TOTAL_MEDIA = 16;
                const MAX_FILE_SIZE = 200 * 1024 * 1024;


                /*
                |--------------------------------------------------------------------------
                | MEDIA COUNTS
                |--------------------------------------------------------------------------
                */

                function getMediaCounts() {

                    const images =
                        selectedMedia.filter(
                            file => file.type.startsWith('image/')
                        );

                    const videos =
                        selectedMedia.filter(
                            file => file.type.startsWith('video/')
                        );

                    return {
                        images: images.length,
                        videos: videos.length,
                        total: selectedMedia.length
                    };
                }


                /*
                |--------------------------------------------------------------------------
                | UPDATE MEDIA STATUS
                |--------------------------------------------------------------------------
                */

                function updateMediaStatus() {

                    const counts =
                        getMediaCounts();

                    if (mediaCounterBadge) {

                        mediaCounterBadge.textContent =
                            `${counts.images}/${MAX_IMAGES} images · ` +
                            `${counts.videos}/${MAX_VIDEOS} video · ` +
                            `${counts.total}/${MAX_TOTAL_MEDIA} total`;

                        mediaCounterBadge.classList.remove('hidden');
                    }

                    if (mediaCount) {

                        if (counts.total === 0) {

                            mediaCount.classList.add('hidden');

                        } else {

                            mediaCount.classList.remove('hidden');

                            mediaCount.innerHTML = `
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex flex-wrap items-center gap-2">

                                <span class="inline-flex items-center rounded-lg bg-violet-50 px-2.5 py-1 text-[9px] font-black text-violet-700">
                                    ${counts.images}/${MAX_IMAGES} Images
                                </span>

                                <span class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1 text-[9px] font-black text-blue-700">
                                    ${counts.videos}/${MAX_VIDEOS} Video
                                </span>

                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-[9px] font-black text-slate-600">
                                    ${counts.total}/${MAX_TOTAL_MEDIA} Total
                                </span>

                            </div>

                            <button
                                type="button"
                                id="clearAllMedia"
                                class="font-black text-red-500 hover:text-red-600"
                            >
                                Clear All
                            </button>

                        </div>
                    `;
                        }
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | CAMBODIA CURRENT TIME
                |--------------------------------------------------------------------------
                */

                const cambodiaNow =
                    @json(now('Asia/Phnom_Penh')->format('Y-m-d\TH:i'));


                /*
                |--------------------------------------------------------------------------
                | UPDATE ACCOUNTS
                |--------------------------------------------------------------------------
                */

                function updateAccounts() {

                    const selected =
                        checkboxes.filter(
                            checkbox => checkbox.checked
                        );

                    const count =
                        selected.length;


                    /*
                    |--------------------------------------------------------------------------
                    | Summary
                    |--------------------------------------------------------------------------
                    */

                    if (accountSummary) {

                        accountSummary.textContent =
                            `${count} account${count === 1 ? '' : 's'} selected`;

                        accountSummary.className =
                            count > 0 ?
                            'mt-4 rounded-xl border border-blue-100 bg-blue-50 px-3.5 py-3 text-[10px] font-bold text-blue-700' :
                            'mt-4 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-3 text-[10px] font-bold text-slate-500';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Website department
                    |--------------------------------------------------------------------------
                    */

                    const websiteSelected =
                        selected.some(
                            checkbox =>
                            (
                                checkbox.dataset.platform || ''
                            ).toLowerCase() === 'website'
                        );


                    if (websiteSelected) {

                        websiteDepartmentField
                            ?.classList
                            .remove('hidden');

                        if (websiteDepartment) {
                            websiteDepartment.required = true;
                        }

                    } else {

                        websiteDepartmentField
                            ?.classList
                            .add('hidden');

                        if (websiteDepartment) {

                            websiteDepartment.required = false;
                            websiteDepartment.value = '';
                        }

                        websiteDepartmentHint
                            ?.classList
                            .add('hidden');
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Submit status
                    |--------------------------------------------------------------------------
                    */

                    if (submitAccountStatus) {

                        submitAccountStatus.textContent =
                            count === 0 ?
                            'No accounts selected.' :
                            `${count} publishing account${count === 1 ? '' : 's'} selected.`;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Select all label
                    |--------------------------------------------------------------------------
                    */

                    if (
                        selectAll &&
                        checkboxes.length > 0
                    ) {

                        const allSelected =
                            checkboxes.every(
                                checkbox => checkbox.checked
                            );

                        selectAll.textContent =
                            allSelected ?
                            'Clear All' :
                            'Select All';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Publishing rules
                    |--------------------------------------------------------------------------
                    */

                    updatePublishingRules();
                }


                /*
                |--------------------------------------------------------------------------
                | PUBLISHING RULES
                |--------------------------------------------------------------------------
                */

                function updatePublishingRules() {

                    const selected =
                        checkboxes.filter(
                            checkbox => checkbox.checked
                        );

                    const platforms =
                        selected.map(
                            checkbox =>
                            (
                                checkbox.dataset.platform || ''
                            ).toLowerCase()
                        );

                    const hasFacebook =
                        platforms.includes('facebook');

                    const hasYouTube =
                        platforms.includes('youtube');

                    const needsKhmer =
                        hasFacebook ||
                        hasYouTube;

                    const hasEnglish =
                        selected.length > 0;


                    /*
                    |--------------------------------------------------------------------------
                    | Hide all sections
                    |--------------------------------------------------------------------------
                    */

                    englishContentSection
                        ?.classList
                        .add('hidden');

                    khmerContentSection
                        ?.classList
                        .add('hidden');

                    englishRule
                        ?.classList
                        .add('hidden');

                    khmerRule
                        ?.classList
                        .add('hidden');


                    /*
                    |--------------------------------------------------------------------------
                    | Disable fields
                    |--------------------------------------------------------------------------
                    */

                    if (title) {
                        title.disabled = true;
                    }

                    if (content) {
                        content.disabled = true;
                    }

                    if (titleKh) {
                        titleKh.disabled = true;
                    }

                    if (contentKh) {
                        contentKh.disabled = true;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | No account
                    |--------------------------------------------------------------------------
                    */

                    if (!hasEnglish) {

                        if (publishingRuleText) {

                            publishingRuleText.textContent =
                                'Select a publishing account to determine the content fields.';
                        }

                        if (publishingRuleDot) {

                            publishingRuleDot.className =
                                'h-2 w-2 rounded-full bg-slate-300';
                        }

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | English
                    |--------------------------------------------------------------------------
                    */

                    englishContentSection
                        ?.classList
                        .remove('hidden');

                    englishRule
                        ?.classList
                        .remove('hidden');

                    if (title) {
                        title.disabled = false;
                    }

                    if (content) {
                        content.disabled = false;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Khmer
                    |--------------------------------------------------------------------------
                    */

                    if (needsKhmer) {

                        khmerContentSection
                            ?.classList
                            .remove('hidden');

                        khmerRule
                            ?.classList
                            .remove('hidden');

                        if (titleKh) {
                            titleKh.disabled = false;
                        }

                        if (contentKh) {
                            contentKh.disabled = false;
                        }


                        if (publishingRuleText) {

                            publishingRuleText.textContent =
                                'English + Khmer content is enabled because Facebook or YouTube is selected.';
                        }

                        if (publishingRuleDot) {

                            publishingRuleDot.className =
                                'h-2 w-2 rounded-full bg-amber-500';
                        }

                    } else {

                        if (publishingRuleText) {

                            publishingRuleText.textContent =
                                'English content is enabled for the selected publishing platforms.';
                        }

                        if (publishingRuleDot) {

                            publishingRuleDot.className =
                                'h-2 w-2 rounded-full bg-blue-500';
                        }
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | SELECT ALL
                |--------------------------------------------------------------------------
                */

                selectAll?.addEventListener(
                    'click',
                    function() {

                        const shouldSelect = !checkboxes.every(
                            checkbox => checkbox.checked
                        );

                        checkboxes.forEach(
                            checkbox => {
                                checkbox.checked = shouldSelect;
                            }
                        );

                        updateAccounts();
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | ACCOUNT CHANGE
                |--------------------------------------------------------------------------
                */

                checkboxes.forEach(
                    checkbox => {

                        checkbox.addEventListener(
                            'change',
                            updateAccounts
                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | MEDIA PICKER
                |--------------------------------------------------------------------------
                */

                addImagesButton?.addEventListener(
                    'click',
                    function() {

                        media?.click();
                    }
                );


                addMoreImagesButton?.addEventListener(
                    'click',
                    function() {

                        media?.click();
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | MEDIA CHANGE
                |--------------------------------------------------------------------------
                */

                media?.addEventListener(
                    'change',
                    function() {

                        const files =
                            Array.from(
                                media.files || []
                            );

                        if (files.length === 0) {
                            return;
                        }


                        files.forEach(
                            function(file) {

                                const isImage =
                                    file.type.startsWith('image/');

                                const isVideo =
                                    file.type.startsWith('video/');


                                /*
                                |--------------------------------------------------------------------------
                                | Supported type
                                |--------------------------------------------------------------------------
                                */

                                if (!isImage && !isVideo) {

                                    showAlert(
                                        `${file.name} is not a supported image or video.`,
                                        'warning'
                                    );

                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Maximum 200 MB
                                |--------------------------------------------------------------------------
                                */

                                if (file.size > MAX_FILE_SIZE) {

                                    showAlert(
                                        `${file.name} is larger than 200 MB.`,
                                        'warning'
                                    );

                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Duplicate detection
                                |--------------------------------------------------------------------------
                                */

                                const exists =
                                    selectedMedia.some(
                                        existing =>
                                        existing.name === file.name &&
                                        existing.size === file.size &&
                                        existing.lastModified === file.lastModified
                                    );

                                if (exists) {

                                    showAlert(
                                        `${file.name} has already been selected.`,
                                        'warning'
                                    );

                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Current counts
                                |--------------------------------------------------------------------------
                                */

                                let counts =
                                    getMediaCounts();


                                /*
                                |--------------------------------------------------------------------------
                                | Maximum total files
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    counts.total >= MAX_TOTAL_MEDIA
                                ) {

                                    showAlert(
                                        `You can upload a maximum of ${MAX_TOTAL_MEDIA} files per post.`,
                                        'warning'
                                    );

                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Maximum images
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    isImage &&
                                    counts.images >= MAX_IMAGES
                                ) {

                                    showAlert(
                                        `You can upload a maximum of ${MAX_IMAGES} images per post.`,
                                        'warning'
                                    );

                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Maximum video
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    isVideo &&
                                    counts.videos >= MAX_VIDEOS
                                ) {

                                    showAlert(
                                        `You can upload only ${MAX_VIDEOS} video per post.`,
                                        'warning'
                                    );

                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Add file
                                |--------------------------------------------------------------------------
                                */

                                selectedMedia.push(file);

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Reset input
                        |--------------------------------------------------------------------------
                        |
                        | Allows the user to select the same file again
                        | after deleting it.
                        |
                        */

                        media.value = '';


                        /*
                        |--------------------------------------------------------------------------
                        | Sync + Render
                        |--------------------------------------------------------------------------
                        */

                        syncMediaInput();

                        renderMedia();

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | SYNC FILE INPUT
                |--------------------------------------------------------------------------
                */

                function syncMediaInput() {

                    if (
                        !media ||
                        typeof DataTransfer === 'undefined'
                    ) {
                        return;
                    }

                    const dataTransfer =
                        new DataTransfer();

                    selectedMedia.forEach(
                        function(file) {

                            dataTransfer.items.add(file);

                        }
                    );

                    media.files =
                        dataTransfer.files;
                }


                /*
                |--------------------------------------------------------------------------
                | RENDER MEDIA
                |--------------------------------------------------------------------------
                */

                function renderMedia() {

                    if (!mediaPreview) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Clear previous preview
                    |--------------------------------------------------------------------------
                    */

                    mediaPreview.innerHTML = '';


                    const counts =
                        getMediaCounts();


                    /*
                    |--------------------------------------------------------------------------
                    | Empty state
                    |--------------------------------------------------------------------------
                    */

                    if (counts.total === 0) {

                        mediaCount
                            ?.classList
                            .add('hidden');

                        mediaCounterBadge
                            ?.classList
                            .add('hidden');

                        addMoreImagesButton
                            ?.classList
                            .add('hidden');

                        updateMediaStatus();

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Show counters
                    |--------------------------------------------------------------------------
                    */

                    mediaCount
                        ?.classList
                        .remove('hidden');

                    mediaCounterBadge
                        ?.classList
                        .remove('hidden');

                    addMoreImagesButton
                        ?.classList
                        .remove('hidden');


                    /*
                    |--------------------------------------------------------------------------
                    | Update status
                    |--------------------------------------------------------------------------
                    */

                    updateMediaStatus();


                    /*
                    |--------------------------------------------------------------------------
                    | Create media cards
                    |--------------------------------------------------------------------------
                    */

                    selectedMedia.forEach(
                        function(file, index) {

                            const url =
                                URL.createObjectURL(file);


                            const wrapper =
                                document.createElement('div');

                            wrapper.className =
                                'group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm';


                            /*
                            |--------------------------------------------------------------------------
                            | Preview
                            |--------------------------------------------------------------------------
                            */

                            if (
                                file.type.startsWith('video/')
                            ) {

                                const video =
                                    document.createElement('video');

                                video.src = url;

                                video.controls = true;

                                video.preload = 'metadata';

                                video.className =
                                    'h-48 w-full bg-black object-cover';

                                wrapper.appendChild(video);

                            } else {

                                const img =
                                    document.createElement('img');

                                img.src = url;

                                img.alt = file.name;

                                img.className =
                                    'h-48 w-full object-cover transition duration-300 group-hover:scale-105';

                                wrapper.appendChild(img);
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Number
                            |--------------------------------------------------------------------------
                            */

                            const position =
                                document.createElement('span');

                            position.className =
                                'absolute left-2 top-2 rounded-lg bg-black/70 px-2 py-1 text-[8px] font-black text-white';

                            position.textContent =
                                `#${index + 1}`;

                            wrapper.appendChild(
                                position
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | File type badge
                            |--------------------------------------------------------------------------
                            */

                            const typeBadge =
                                document.createElement('span');

                            typeBadge.className =
                                'absolute left-2 bottom-[54px] rounded-lg bg-black/70 px-2 py-1 text-[8px] font-black uppercase text-white';

                            typeBadge.textContent =
                                file.type.startsWith('video/') ?
                                'VIDEO' :
                                'IMAGE';

                            wrapper.appendChild(
                                typeBadge
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | Remove
                            |--------------------------------------------------------------------------
                            */

                            const remove =
                                document.createElement('button');

                            remove.type = 'button';

                            remove.className =
                                'absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-lg bg-black/70 text-sm font-bold text-white hover:bg-red-600';

                            remove.innerHTML = '×';

                            remove.title =
                                'Remove media';

                            remove.addEventListener(
                                'click',
                                function() {

                                    selectedMedia.splice(
                                        index,
                                        1
                                    );

                                    try {
                                        URL.revokeObjectURL(url);
                                    } catch (error) {
                                        // Ignore
                                    }

                                    syncMediaInput();

                                    renderMedia();

                                }
                            );

                            wrapper.appendChild(
                                remove
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | File information
                            |--------------------------------------------------------------------------
                            */

                            const info =
                                document.createElement('div');

                            info.className =
                                'border-t border-slate-100 bg-white px-3 py-2.5';


                            const name =
                                document.createElement('p');

                            name.className =
                                'truncate text-[9px] font-black text-slate-700';

                            name.textContent =
                                file.name;


                            const size =
                                document.createElement('p');

                            size.className =
                                'mt-0.5 text-[8px] text-slate-400';

                            size.textContent =
                                `${formatFileSize(file.size)} · ${
                            file.type.startsWith('video/')
                                ? 'Video'
                                : 'Image'
                        }`;


                            info.appendChild(name);

                            info.appendChild(size);

                            wrapper.appendChild(info);


                            mediaPreview.appendChild(
                                wrapper
                            );

                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CLEAR ALL MEDIA
                |--------------------------------------------------------------------------
                */

                document.addEventListener(
                    'click',
                    function(event) {

                        if (
                            event.target?.id !== 'clearAllMedia'
                        ) {
                            return;
                        }

                        selectedMedia = [];

                        if (media) {
                            media.value = '';
                        }

                        syncMediaInput();

                        renderMedia();

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | CONTENT COUNTERS
                |--------------------------------------------------------------------------
                */

                function updateContentCounters() {

                    if (englishTitleCount) {

                        englishTitleCount.textContent =
                            `${title?.value?.length || 0} / 100`;
                    }


                    if (englishContentCount) {

                        englishContentCount.textContent =
                            `${content?.value?.length || 0} / 5000`;
                    }


                    if (khmerContentCount) {

                        khmerContentCount.textContent =
                            `${contentKh?.value?.length || 0} តួអក្សរ`;
                    }
                }


                title?.addEventListener(
                    'input',
                    updateContentCounters
                );


                content?.addEventListener(
                    'input',
                    updateContentCounters
                );


                titleKh?.addEventListener(
                    'input',
                    updateContentCounters
                );


                contentKh?.addEventListener(
                    'input',
                    updateContentCounters
                );


                /*
                |--------------------------------------------------------------------------
                | SCHEDULE
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll('[name="publish_now"]')
                    .forEach(
                        radio => {

                            radio.addEventListener(
                                'change',
                                function() {

                                    if (
                                        scheduleOption?.checked
                                    ) {

                                        scheduleField
                                            ?.classList
                                            .remove('hidden');

                                        if (scheduledAt) {

                                            scheduledAt.required =
                                                true;
                                        }

                                    } else {

                                        scheduleField
                                            ?.classList
                                            .add('hidden');

                                        if (scheduledAt) {

                                            scheduledAt.required =
                                                false;

                                            scheduledAt.value =
                                                '';
                                        }
                                    }

                                }
                            );

                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | FORM SUBMIT
                |--------------------------------------------------------------------------
                */

                form?.addEventListener(
                    'submit',
                    function(event) {

                        const selected =
                            checkboxes.filter(
                                checkbox => checkbox.checked
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Account required
                        |--------------------------------------------------------------------------
                        */

                        if (
                            selected.length === 0
                        ) {

                            event.preventDefault();

                            showAlert(
                                'Please select at least one publishing account.',
                                'warning'
                            );

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Website department
                        |--------------------------------------------------------------------------
                        */

                        const websiteSelected =
                            selected.some(
                                checkbox =>
                                (
                                    checkbox.dataset.platform || ''
                                ).toLowerCase() === 'website'
                            );


                        if (
                            websiteSelected &&
                            (
                                !websiteDepartment ||
                                !websiteDepartment.value
                            )
                        ) {

                            event.preventDefault();

                            websiteDepartmentField
                                ?.classList
                                .remove('hidden');

                            websiteDepartmentHint
                                ?.classList
                                .remove('hidden');

                            websiteDepartment?.focus();

                            showAlert(
                                'Please select a Website Department.',
                                'warning'
                            );

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | English content
                        |--------------------------------------------------------------------------
                        */

                        const englishTitle =
                            title?.value?.trim() || '';

                        const englishContent =
                            content?.value?.trim() || '';

                        const khmerTitle =
                            titleKh?.value?.trim() || '';

                        const khmerContent =
                            contentKh?.value?.trim() || '';


                        if (
                            !englishTitle &&
                            !khmerTitle
                        ) {

                            event.preventDefault();

                            showAlert(
                                'Please enter at least an English or Khmer title.',
                                'warning'
                            );

                            if (
                                !title?.disabled &&
                                title
                            ) {
                                title.focus();
                            }

                            return;
                        }


                        if (
                            !englishContent &&
                            !khmerContent
                        ) {

                            event.preventDefault();

                            showAlert(
                                'Please enter at least English or Khmer content.',
                                'warning'
                            );

                            if (
                                !content?.disabled &&
                                content
                            ) {
                                content.focus();
                            }

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Media validation
                        |--------------------------------------------------------------------------
                        */

                        const counts =
                            getMediaCounts();


                        if (
                            counts.images > MAX_IMAGES
                        ) {

                            event.preventDefault();

                            showAlert(
                                `You can upload a maximum of ${MAX_IMAGES} images per post.`,
                                'warning'
                            );

                            return;
                        }


                        if (
                            counts.videos > MAX_VIDEOS
                        ) {

                            event.preventDefault();

                            showAlert(
                                `You can upload only ${MAX_VIDEOS} video per post.`,
                                'warning'
                            );

                            return;
                        }


                        if (
                            counts.total > MAX_TOTAL_MEDIA
                        ) {

                            event.preventDefault();

                            showAlert(
                                `You can upload a maximum of ${MAX_TOTAL_MEDIA} files per post.`,
                                'warning'
                            );

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Schedule
                        |--------------------------------------------------------------------------
                        */

                        if (
                            scheduleOption?.checked
                        ) {

                            if (
                                !scheduledAt?.value
                            ) {

                                event.preventDefault();

                                showAlert(
                                    'Please select a scheduled date and time.',
                                    'warning'
                                );

                                scheduledAt?.focus();

                                return;
                            }


                            if (
                                scheduledAt.value <= cambodiaNow
                            ) {

                                event.preventDefault();

                                showAlert(
                                    'Scheduled time must be in the future in Cambodia Time.',
                                    'warning'
                                );

                                scheduledAt?.focus();

                                return;
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Make sure files are synchronized
                        |--------------------------------------------------------------------------
                        */

                        syncMediaInput();


                        /*
                        |--------------------------------------------------------------------------
                        | Confirmation
                        |--------------------------------------------------------------------------
                        */

                        event.preventDefault();


                        const isScheduled =
                            scheduleOption?.checked;


                        const titleValue =
                            title?.value?.trim() ||
                            titleKh?.value?.trim() ||
                            'Untitled post';


                        const currentMediaCounts =
                            getMediaCounts();


                        const mediaSummary = `
                    <div class="mt-3 rounded-xl bg-violet-50 px-3 py-3">

                        <p class="text-[8px] font-black uppercase tracking-wide text-violet-500">
                            Media
                        </p>

                        <div class="mt-2 flex flex-wrap gap-2">

                            <span class="rounded-lg bg-white px-2.5 py-1.5 text-[9px] font-bold text-violet-700">
                                ${currentMediaCounts.images} Image${currentMediaCounts.images === 1 ? '' : 's'}
                            </span>

                            <span class="rounded-lg bg-white px-2.5 py-1.5 text-[9px] font-bold text-blue-700">
                                ${currentMediaCounts.videos} Video${currentMediaCounts.videos === 1 ? '' : 's'}
                            </span>

                            <span class="rounded-lg bg-white px-2.5 py-1.5 text-[9px] font-bold text-slate-600">
                                ${currentMediaCounts.total} Total
                            </span>

                        </div>

                    </div>
                `;


                        const scheduleSummary =
                            isScheduled ?
                            `
                            <div class="mt-3 rounded-xl bg-amber-50 px-3 py-3">

                                <p class="text-[8px] font-black uppercase tracking-wide text-amber-600">
                                    Scheduled For
                                </p>

                                <p class="mt-1 font-black text-amber-800">
                                    ${escapeHtml(
                                        formatCambodiaDateTime(
                                            scheduledAt.value
                                        )
                                    )}
                                </p>

                                <p class="mt-1 text-[8px] font-bold text-amber-500">
                                    Cambodia Time
                                </p>

                            </div>
                        ` :
                            '';


                        Swal.fire({

                            title: isScheduled ?
                                'Schedule this post?' : 'Publish this post?',

                            html: `
                        <div class="text-left text-sm text-slate-500">

                            <div class="rounded-xl bg-slate-50 px-3 py-3">

                                <p class="text-[8px] font-black uppercase tracking-wide text-slate-400">
                                    Title
                                </p>

                                <p class="mt-1 text-sm font-black text-slate-800">
                                    ${escapeHtml(titleValue)}
                                </p>

                            </div>

                            <p class="mt-3">
                                ${selected.length}
                                publishing account${selected.length === 1 ? '' : 's'}
                                selected.
                            </p>

                            ${mediaSummary}

                            ${scheduleSummary}

                        </div>
                    `,

                            icon: isScheduled ?
                                'info' : 'question',

                            showCancelButton: true,

                            confirmButtonText: isScheduled ?
                                'Schedule Post' : 'Publish Post',

                            cancelButtonText: 'Cancel',

                            reverseButtons: true,

                            focusCancel: true,

                            buttonsStyling: false,

                            customClass: {

                                popup: 'rounded-2xl',

                                confirmButton: 'rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-bold text-white ml-4',

                                cancelButton: 'rounded-xl bg-red-400 px-5 py-2.5 text-xs font-bold text-white'
                            }

                        }).then(
                            async function(result) {

                                if (
                                    result.isConfirmed
                                ) {

                                    if (submitButton) {

                                        submitButton.disabled =
                                            true;
                                    }


                                    submitSpinner
                                        ?.classList
                                        .remove('hidden');


                                    if (submitText) {

                                        submitText.textContent =
                                            isScheduled ?
                                            'Scheduling...' :
                                            'Publishing...';
                                    }



                                    /*
                                    |--------------------------------------------------------------------------
                                    | CONVERT IMAGES TO YOUTUBE MP4 BEFORE SUBMITTING
                                    |--------------------------------------------------------------------------
                                    */

                                    try {
                                        const youtubeSelected = selected.some(
                                            checkbox =>
                                            checkbox.dataset.platform?.toLowerCase() === 'youtube'
                                        );

                                        const images = selectedMedia.filter(file =>
                                            file.type.toLowerCase().startsWith('image/')
                                        );

                                        const videos = selectedMedia.filter(file =>
                                            file.type.toLowerCase().startsWith('video/')
                                        );

                                        // Convert only when YouTube is selected and images were uploaded
                                        // without an existing video.
                                        if (youtubeSelected && images.length > 0 && videos.length === 0) {
                                            if (typeof window.createYoutubeSlideshow !== 'function') {
                                                throw new Error(
                                                    'The slideshow converter did not load. Refresh the page and try again.'
                                                );
                                            }

                                            submitButton.disabled = true;
                                            submitSpinner?.classList.remove('hidden');

                                            if (submitText) {
                                                submitText.textContent = 'Converting images to MP4...';
                                            }

                                            // Generate the MP4 in the browser.
                                            const generatedVideo = await window.createYoutubeSlideshow(
                                                images,
                                                message => {
                                                    console.log('[YouTube Slideshow]', message);

                                                    if (submitText) {
                                                        submitText.textContent = message;
                                                    }
                                                }
                                            );

                                            if (
                                                !(generatedVideo instanceof File) ||
                                                generatedVideo.size === 0 ||
                                                generatedVideo.type !== 'video/mp4'
                                            ) {
                                                throw new Error(
                                                    'The slideshow conversion did not produce a valid MP4 file.'
                                                );
                                            }

                                            // Attach the generated MP4 to the form.
                                            const slideshowInput =
                                                document.getElementById('youtube_slideshow');

                                            if (!slideshowInput) {
                                                throw new Error(
                                                    'The youtube_slideshow input is missing from the form.'
                                                );
                                            }

                                            const transfer = new DataTransfer();
                                            transfer.items.add(generatedVideo);
                                            slideshowInput.files = transfer.files;
                                        }

                                        // Preserve the original uploaded media.
                                        syncMediaInput();

                                        if (submitText) {
                                            submitText.textContent = 'Uploading and publishing...';
                                        }

                                        // Submit only after conversion has completed.
                                        form.submit();

                                    } catch (error) {
                                        console.error('[YouTube Slideshow] Submission failed:', error);

                                        if (submitButton) {
                                            submitButton.disabled = false;
                                        }

                                        submitSpinner?.classList.add('hidden');

                                        if (submitText) {
                                            submitText.textContent = 'Publish Post';
                                        }

                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Slideshow conversion failed',
                                            text: error.message ||
                                                'Unable to create the YouTube slideshow. Please try again.'
                                        });
                                    }
                                }

                            }
                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | ALERT
                |--------------------------------------------------------------------------
                */

                function showAlert(
                    message,
                    icon = 'info'
                ) {

                    if (
                        typeof Swal !== 'undefined'
                    ) {

                        Swal.fire({

                            icon: icon,

                            text: message,

                            confirmButtonText: 'OK',

                            buttonsStyling: false,

                            customClass: {

                                confirmButton: 'rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-bold text-white'
                            }

                        });

                    } else {

                        alert(message);

                    }
                }


                /*
                |--------------------------------------------------------------------------
                | FILE SIZE
                |--------------------------------------------------------------------------
                */

                function formatFileSize(bytes) {

                    if (
                        bytes === 0
                    ) {

                        return '0 Bytes';
                    }


                    const units = [
                        'Bytes',
                        'KB',
                        'MB',
                        'GB'
                    ];


                    const index =
                        Math.floor(
                            Math.log(bytes) /
                            Math.log(1024)
                        );


                    return (
                        parseFloat(
                            (
                                bytes /
                                Math.pow(
                                    1024,
                                    index
                                )
                            ).toFixed(2)
                        ) +
                        ' ' +
                        units[index]
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | ESCAPE HTML
                |--------------------------------------------------------------------------
                */

                function escapeHtml(value) {

                    return String(value)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }


                /*
                |--------------------------------------------------------------------------
                | CAMBODIA DATE/TIME
                |--------------------------------------------------------------------------
                */

                function formatCambodiaDateTime(value) {

                    if (!value) {
                        return '';
                    }


                    const parts =
                        value.split('T');


                    if (
                        parts.length !== 2
                    ) {

                        return value;
                    }


                    const dateParts =
                        parts[0].split('-');

                    const timeParts =
                        parts[1].split(':');


                    if (
                        dateParts.length !== 3 ||
                        timeParts.length < 2
                    ) {

                        return value;
                    }


                    const months = [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'May',
                        'Jun',
                        'Jul',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dec'
                    ];


                    let hour =
                        parseInt(
                            timeParts[0],
                            10
                        );


                    const minute =
                        timeParts[1];


                    const period =
                        hour >= 12 ?
                        'PM' :
                        'AM';


                    let displayHour =
                        hour % 12;


                    if (
                        displayHour === 0
                    ) {

                        displayHour = 12;
                    }


                    const monthIndex =
                        parseInt(
                            dateParts[1],
                            10
                        ) - 1;


                    const monthName =
                        months[monthIndex] ||
                        dateParts[1];


                    return (
                        `${dateParts[2]} ` +
                        `${monthName} ` +
                        `${dateParts[0]}, ` +
                        `${String(displayHour).padStart(2, '0')}:${minute} ${period}`
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | INITIALIZE
                |--------------------------------------------------------------------------
                */

                updateAccounts();

                updateContentCounters();

                renderMedia();

            });
        </script>

    @endsection
