@extends('layouts.app')

@section('content')

<div class="min-h-[calc(100vh-4rem)] bg-slate-50">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="relative overflow-hidden border-b border-emerald-900/20 bg-gradient-to-br from-blue-600 via-blue-800 to-blue-900 text-white">

        {{-- Background decoration --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-white/5 blur-2xl"></div>
            <div class="absolute -bottom-32 left-1/3 h-80 w-80 rounded-full bg-emerald-400/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-full px-6 py-7">

            <div class="flex items-center justify-between gap-6">

                <div class="min-w-0">

                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-300 shadow-[0_0_12px_rgba(167,243,208,0.8)]"></span>

                        <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-blue-200">
                            NGO Forum Social Publisher
                        </p>
                    </div>

                    <h1 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl">
                        Social Publishing Dashboard
                    </h1>

                    <p class="mt-2 max-w-2xl text-xs leading-5 text-blue-100">
                        Manage connected media accounts, create content, publish across platforms,
                        and monitor your publishing activity.
                    </p>

                </div>

                <div class="shrink-0">

                    <a
                        href="{{ route('posts.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-xs font-extrabold text-blue-800 shadow-lg shadow-black/10 transition hover:-translate-y-0.5 hover:bg-emerald-50"
                    >
                        <span class="text-base leading-none">＋</span>
                        Create Post
                    </a>

                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================
        MAIN
    ========================================================== --}}
    <main class="mx-auto max-w-full px-6 py-6">


        {{-- =====================================================
            KPI CARDS
        ====================================================== --}}
        @php
            $stats = [
                [
                    'label' => 'Total Posts',
                    'value' => $totalPosts,
                    'icon' => 'document',
                    'bg' => 'bg-slate-100',
                    'text' => 'text-slate-700',
                ],
                [
                    'label' => 'Published',
                    'value' => $publishedPosts,
                    'icon' => 'check',
                    'bg' => 'bg-blue-50',
                    'text' => 'text-blue-700',
                ],
                [
                    'label' => 'Scheduled',
                    'value' => $scheduledPosts,
                    'icon' => 'calendar',
                    'bg' => 'bg-amber-50',
                    'text' => 'text-amber-700',
                ],
                [
                    'label' => 'Failed',
                    'value' => $failedPosts,
                    'icon' => 'warning',
                    'bg' => 'bg-red-50',
                    'text' => 'text-red-700',
                ],
            ];
        @endphp


        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            @foreach($stats as $stat)

                <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                                {{ $stat['label'] }}
                            </p>

                            <p class="mt-2 text-3xl font-black tracking-tight text-slate-950">
                                {{ number_format($stat['value']) }}
                            </p>

                            <p class="mt-1 text-[10px] text-slate-400">
                                Content activity
                            </p>

                        </div>


                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $stat['bg'] }} {{ $stat['text'] }} transition group-hover:scale-105">

                            @if($stat['icon'] === 'document')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3.75h7.25L19 8.5V20a.25.25 0 01-.25.25H7A1.25 1.25 0 015.75 19V5A1.25 1.25 0 017 3.75z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 3.75V8.5h4.75"/>
                                    <path stroke-linecap="round" d="M8.75 12h6.5M8.75 15.25h6.5"/>
                                </svg>

                            @elseif($stat['icon'] === 'check')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 12.5 4.2 4.2L19 7"/>
                                </svg>

                            @elseif($stat['icon'] === 'calendar')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3.75" y="5.25" width="16.5" height="15" rx="2"/>
                                    <path stroke-linecap="round" d="M7.5 3.75v3M16.5 3.75v3M3.75 9.25h16.5"/>
                                </svg>

                            @else
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.25 20 18a1 1 0 01-.87 1.5H4.87A1 1 0 014 18l8-13.75z"/>
                                    <path stroke-linecap="round" d="M12 9v4.25M12 16.25v.1"/>
                                </svg>
                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- =====================================================
            MAIN GRID
        ====================================================== --}}
        <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-12">


            {{-- =================================================
                RECENT POSTS
            ================================================== --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-8">

                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">

                    <div>
                        <div class="flex items-center gap-2">

                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-700">

                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.75h12A1.25 1.25 0 0119.25 6v12A1.25 1.25 0 0118 19.25H6A1.25 1.25 0 014.75 18V6A1.25 1.25 0 016 4.75z"/>
                                    <path stroke-linecap="round" d="M8 9h8M8 12h8M8 15h5"/>
                                </svg>

                            </div>

                            <div>
                                <h2 class="text-sm font-black text-slate-950">
                                    Recent Posts
                                </h2>

                                <p class="mt-0.5 text-[10px] text-slate-400">
                                    Latest content and publishing activity
                                </p>
                            </div>

                        </div>
                    </div>


                    <a
                        href="{{ route('posts.index') }}"
                        class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-[10px] font-extrabold text-blue-700 transition hover:bg-emerald-50"
                    >
                        View all
                        <span>→</span>
                    </a>

                </div>


                @if($recentPosts->count())

                    <div class="divide-y divide-slate-100">

                        @foreach($recentPosts as $post)

                            @php
                                $statusClass = match($post->status) {
                                    'published' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                                    'scheduled' => 'bg-amber-50 text-amber-700 ring-amber-100',
                                    'publishing' => 'bg-blue-50 text-blue-700 ring-blue-100',
                                    'failed' => 'bg-red-50 text-red-700 ring-red-100',
                                    default => 'bg-slate-100 text-slate-600 ring-slate-200',
                                };

                                $statusDot = match($post->status) {
                                    'published' => 'bg-emerald-500',
                                    'scheduled' => 'bg-amber-500',
                                    'publishing' => 'bg-blue-500',
                                    'failed' => 'bg-red-500',
                                    default => 'bg-slate-400',
                                };
                            @endphp


                            <div class="group flex items-center gap-4 px-5 py-4 transition hover:bg-slate-50">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-[11px] font-black text-emerald-700 ring-1 ring-emerald-100">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </div>


                                <div class="min-w-0 flex-1">

                                    <p class="truncate text-sm font-extrabold text-slate-800 group-hover:text-emerald-700">
                                        {{ $post->title ?: ($post->title_Kh ?: 'Untitled Post') }}
                                    </p>

                                    <div class="mt-1 flex items-center gap-2 text-[10px] text-slate-400">

                                        <span>
                                            {{ $post->created_at?->format('d M Y') }}
                                        </span>

                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>

                                        <span>
                                            {{ $post->created_at?->format('H:i') }}
                                        </span>

                                    </div>

                                </div>


                                <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-[9px] font-extrabold ring-1 {{ $statusClass }}">

                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>

                                    {{ ucfirst($post->status) }}

                                </span>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="px-6 py-16 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-blue-400">

                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.75h12A1.25 1.25 0 0119.25 6v12A1.25 1.25 0 0118 19.25H6A1.25 1.25 0 014.75 18V6A1.25 1.25 0 016 4.75z"/>
                                <path stroke-linecap="round" d="M8 9h8M8 12h5"/>
                            </svg>

                        </div>

                        <p class="mt-4 text-sm font-black text-slate-800">
                            No posts yet
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Create your first post to start publishing.
                        </p>

                        <a
                            href="{{ route('posts.create') }}"
                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:bg-emerald-800"
                        >
                            <span>＋</span>
                            Create Post
                        </a>

                    </div>

                @endif

            </section>


            {{-- =================================================
                MEDIA ACCOUNTS
            ================================================== --}}
            <aside class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-4">

                <div class="border-b border-slate-100 px-5 py-4">

                    <div class="flex items-start justify-between gap-3">

                        <div>

                            <div class="flex items-center gap-2">

                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-700">

                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 6.75h9A2.75 2.75 0 0119.25 9.5v7A2.75 2.75 0 0116.5 19.25h-9A2.75 2.75 0 014.75 16.5v-7A2.75 2.75 0 017.5 6.75z"/>
                                        <circle cx="12" cy="13" r="2.5"/>
                                        <path stroke-linecap="round" d="M8.25 6.75 9.5 4.75h5l1.25 2"/>
                                    </svg>

                                </div>

                                <div>

                                    <h2 class="text-sm font-black text-slate-950">
                                        Media Accounts
                                    </h2>

                                    <p class="mt-0.5 text-[10px] text-slate-400">
                                        {{ $connectedCount }} of {{ $totalPlatforms }} connected
                                    </p>

                                </div>

                            </div>

                        </div>


                        @if(auth()->user()->isAdmin())

                            <a
                                href="{{ route('social-accounts.index') }}"
                                class="rounded-lg px-2.5 py-1.5 text-[10px] font-extrabold text-blue-700 transition hover:bg-emerald-50"
                            >
                                Manage
                            </a>

                        @endif

                    </div>


                    {{-- Connection progress --}}
                    @php
                        $connectionPercent = $totalPlatforms > 0
                            ? round(($connectedCount / $totalPlatforms) * 100)
                            : 0;
                    @endphp

                    <div class="mt-4">

                        <div class="mb-1.5 flex items-center justify-between">

                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400">
                                Connection status
                            </span>

                            <span class="text-[9px] font-extrabold text-blue-700">
                                {{ $connectionPercent }}%
                            </span>

                        </div>

                        <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-500 transition-all"
                                style="width: {{ min(100, max(0, $connectionPercent)) }}%"
                            ></div>

                        </div>

                    </div>

                </div>


                <div class="p-3">

                    @foreach($platforms as $key => $platform)

                        @php
                            $account = $connectedPlatforms->get($key);

                            $platformColors = [
                                'facebook' => 'bg-blue-50 text-blue-700 ring-blue-100',
                                'instagram' => 'bg-pink-50 text-pink-700 ring-pink-100',
                                'youtube' => 'bg-red-50 text-red-700 ring-red-100',
                                'tiktok' => 'bg-slate-100 text-slate-800 ring-slate-200',
                                'linkedin' => 'bg-sky-50 text-sky-700 ring-sky-100',
                                'twitter' => 'bg-slate-100 text-slate-800 ring-slate-200',
                                'website' => 'bg-blue-50 text-blue-700 ring-blue-100',
                            ];

                            $platformClass = $platformColors[$key] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
                        @endphp


                        <div class="group flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-slate-50">

                            {{-- Platform Icon --}}
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl ring-1 {{ $platformClass }}">

                                @switch($key)

                                    @case('facebook')
                                        <span class="text-sm font-black">f</span>
                                        @break

                                    @case('instagram')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <rect x="3.75" y="3.75" width="16.5" height="16.5" rx="4"/>
                                            <circle cx="12" cy="12" r="3.5"/>
                                            <circle cx="17.4" cy="6.8" r=".75" fill="currentColor" stroke="none"/>
                                        </svg>
                                        @break

                                    @case('youtube')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21.4 7.1a2.85 2.85 0 0 0-2-2C17.7 4.6 12 4.6 12 4.6s-5.7 0-7.4.5a2.85 2.85 0 0 0-2 2A29.4 29.4 0 0 0 2.1 12c0 1.6.2 3.3.5 4.9a2.85 2.85 0 0 0 2 2c1.7.5 7.4.5 7.4.5s5.7 0 7.4-.5a2.85 2.85 0 0 0 2-2c.3-1.6.5-3.3.5-4.9s-.2-3.3-.5-4.9z"/>
                                            <path d="m10.2 15.7 5-3.7-5-3.7v7.4z" fill="white"/>
                                        </svg>
                                        @break

                                    @case('tiktok')
                                        <span class="text-xs font-black">♪</span>
                                        @break

                                    @case('linkedin')
                                        <span class="text-xs font-black">in</span>
                                        @break

                                    @case('twitter')
                                        <span class="text-xs font-black">𝕏</span>
                                        @break

                                    @case('website')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <circle cx="12" cy="12" r="8.25"/>
                                            <path stroke-linecap="round" d="M3.9 9.5h16.2M3.9 14.5h16.2"/>
                                            <path stroke-linecap="round" d="M12 3.75c2 2.2 3 4.95 3 8.25s-1 6.05-3 8.25c-2-2.2-3-4.95-3-8.25s1-6.05 3-8.25z"/>
                                        </svg>
                                        @break

                                    @default
                                        <span class="text-xs font-black">
                                            {{ strtoupper(substr($platform['name'], 0, 1)) }}
                                        </span>

                                @endswitch

                            </div>


                            {{-- Account info --}}
                            <div class="min-w-0 flex-1">

                                <p class="text-xs font-extrabold text-slate-800">
                                    {{ $platform['name'] }}
                                </p>

                                <p class="mt-0.5 truncate text-[10px] text-slate-400">

                                    @if($account)
                                        {{ $account->display_name ?: 'Connected' }}
                                    @else
                                        {{ $platform['type'] }} account
                                    @endif

                                </p>

                            </div>


                            {{-- Status --}}
                            <div class="flex shrink-0 items-center gap-2">

                                @if($account)

                                    <span class="text-[9px] font-extrabold text-blue-600">
                                        Connected
                                    </span>

                                    <span class="relative flex h-2.5 w-2.5">

                                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-40"></span>

                                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-blue-500"></span>

                                    </span>

                                @else

                                    <span class="text-[9px] font-bold text-slate-400">
                                        Not connected
                                    </span>

                                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </aside>

        </div>


        {{-- =====================================================
            PERFORMANCE
        ====================================================== --}}
        <section class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="px-5 py-5">

                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                    <div class="flex items-center gap-4">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 17.5 9 13l3 2.75 7.5-8.25"/>
                                <path stroke-linecap="round" d="M17 7.5h2.5V10"/>
                            </svg>

                        </div>


                        <div>

                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                                Publishing Performance
                            </p>

                            <div class="mt-1 flex items-end gap-2">

                                <p class="text-2xl font-black tracking-tight text-slate-950">
                                    {{ $successRate }}%
                                </p>

                                <span class="mb-1 text-[10px] font-semibold text-slate-400">
                                    publishing success
                                </span>

                            </div>

                            <p class="mt-1 text-[10px] text-slate-400">
                                Published posts compared with total posts.
                            </p>

                        </div>

                    </div>


                    {{-- Progress --}}
                    <div class="w-full md:w-80">

                        <div class="mb-2 flex items-center justify-between">

                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400">
                                Progress
                            </span>

                            <span class="text-[10px] font-black text-blue-700">
                                {{ $successRate }}%
                            </span>

                        </div>

                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-gradient-to-r from-blue-600 via-blue-500 to-blue-400 transition-all duration-500"
                                style="width: {{ min(100, max(0, $successRate)) }}%"
                            ></div>

                        </div>

                        <div class="mt-2 flex justify-between text-[9px] text-slate-400">
                            <span>0%</span>
                            <span>50%</span>
                            <span>100%</span>
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

@endsection