<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-xl border-b border-gray-200">

    <div class="max-w-full mx-auto px-4 md:px-6 lg:px-8">

        <div class="h-24 flex items-center justify-between gap-4">

            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 flex-shrink-0 group">

                {{-- Logo --}}
                <div class="p-2">

                    <img src="{{ asset('image/logo.png') }}" alt="NGO Forum on Cambodia"
                        class="max-w-full max-h-16 object-contain">

                </div>

            </a>

            <div class="flex items-center justify-center flex-1">

                <div
                    class="flex items-center
                        gap-1
                        bg-gray-50/80
                        border border-gray-100
                        rounded-2xl
                        p-1">

                    {{-- =====================================================
                        DASHBOARD
                    ====================================================== --}}

                    <a href="{{ route('dashboard') }}"
                        class="group relative
                            flex items-center gap-2
                            px-3 md:px-3.5 lg:px-4
                            py-2
                            rounded-xl
                            text-xs lg:text-sm
                            font-semibold
                            whitespace-nowrap
                            transition-all duration-200
                            {{ request()->routeIs('dashboard')
                                ? 'bg-white text-blue-700 shadow-sm ring-1 ring-gray-100'
                                : 'text-gray-600 hover:bg-white hover:text-blue-700 hover:shadow-sm' }}">

                        <span
                            class="flex items-center justify-center
                                w-7 h-7
                                rounded-lg
                                transition
                                {{ request()->routeIs('dashboard')
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'bg-white text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600' }}">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
                            </svg>

                        </span>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    {{-- =====================================================
                        POSTS
                    ====================================================== --}}

                    <a href="{{ route('posts.index') }}"
                        class="group relative
                            flex items-center gap-2
                            px-3 md:px-3.5 lg:px-4
                            py-2
                            rounded-xl
                            text-xs lg:text-sm
                            font-semibold
                            whitespace-nowrap
                            transition-all duration-200
                            {{ request()->routeIs('posts.*')
                                ? 'bg-white text-blue-700 shadow-sm ring-1 ring-gray-100'
                                : 'text-gray-600 hover:bg-white hover:text-blue-700 hover:shadow-sm' }}">

                        <span
                            class="flex items-center justify-center
                                w-7 h-7
                                rounded-lg
                                transition
                                {{ request()->routeIs('posts.*')
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'bg-white text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600' }}">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586A2 2 0 0114 3.586L19.414 9A2 2 0 0120 10.414V19a2 2 0 01-2 2z" />
                            </svg>

                        </span>

                        <span>
                            Posts
                        </span>

                    </a>


                    {{-- =====================================================
                        SOCIAL ACCOUNTS
                    ====================================================== --}}

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('social-accounts.index') }}"
                            class="group relative
                                flex items-center gap-2
                                px-3 md:px-3.5 lg:px-4
                                py-2
                                rounded-xl
                                text-xs lg:text-sm
                                font-semibold
                                whitespace-nowrap
                                transition-all duration-200
                                {{ request()->routeIs('social-accounts.*')
                                    ? 'bg-white text-blue-700 shadow-sm ring-1 ring-gray-100'
                                    : 'text-gray-600 hover:bg-white hover:text-blue-700 hover:shadow-sm' }}">

                            <span
                                class="flex items-center justify-center
                                    w-7 h-7
                                    rounded-lg
                                    transition
                                    {{ request()->routeIs('social-accounts.*')
                                        ? 'bg-blue-100 text-blue-700'
                                        : 'bg-white text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600' }}">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>

                            </span>

                            <span>
                                Social Accounts
                            </span>

                        </a>


                        {{-- =====================================================
                            ADMIN
                        ====================================================== --}}


                        <a href="{{ route('users.index') }}"
                            class="group relative
                                flex items-center gap-2
                                px-3 md:px-3.5 lg:px-4
                                py-2
                                rounded-xl
                                text-xs lg:text-sm
                                font-semibold
                                whitespace-nowrap
                                transition-all duration-200
                                {{ request()->routeIs('users.*')
                                    ? 'bg-white text-blue-700 shadow-sm ring-1 ring-gray-100'
                                    : 'text-gray-600 hover:bg-white hover:text-blue-700 hover:shadow-sm' }}">

                            <span
                                class="flex items-center justify-center
                                    w-7 h-7
                                    rounded-lg
                                    transition
                                    {{ request()->routeIs('users.*')
                                        ? 'bg-blue-100 text-blue-700'
                                        : 'bg-white text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600' }}">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.052-.382-3.016z" />
                                </svg>

                            </span>

                            <span>
                                Admin
                            </span>

                        </a>
                    @endif

                </div>

            </div>

            <div class="flex items-center gap-1.5 lg:gap-2 flex-shrink-0">

                <div
                    class="flex items-center
                        bg-gray-50
                        border border-gray-100
                        rounded-2xl
                        p-1
                        shadow-sm">

                    {{-- User Profile --}}
                    <div class="flex items-center gap-2.5
                            px-2 md:px-2.5 lg:px-3
                            py-1.5">

                        {{-- Avatar --}}
                        <div
                            class="relative
                                w-7 h-7 lg:w-9 lg:h-9
                                rounded-xl
                                bg-gradient-to-br
                                from-blue-600
                                via-blue-700
                                to-blue-800
                                text-white
                                flex items-center justify-center
                                font-bold
                                text-xs
                                shadow-sm
                                ring-2 ring-white">

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                            {{-- Online indicator --}}
                            <span
                                class="absolute
                                    bottom-0 right-0
                                    w-2.5 h-2.5
                                    rounded-full
                                    bg-emerald-400
                                    border-2 border-white">
                            </span>

                        </div>


                        {{-- User Information --}}
                        <div class="hidden md:block leading-tight">

                            <p
                                class="text-xs
                                    font-bold
                                    text-gray-800
                                    max-w-[110px] lg:max-w-[140px]
                                    truncate">
                                {{ auth()->user()->name }}
                            </p>

                            <div class="flex items-center gap-1.5 mt-1">

                                <span
                                    class="text-[9px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-blue-700">
                                    {{ auth()->user()->role ?? 'Staff' }}
                                </span>

                                <span class="text-gray-300">•</span>

                                <span class="text-[9px] lg:text-[10px] text-gray-400">
                                    Online
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Divider --}}
                    <div class="hidden md:block
                        h-7 w-px
                        bg-gray-200"></div>


                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">

                        @csrf

                        <button type="submit"
                            class="group
                                w-9 h-9 lg:w-10 lg:h-10
                                rounded-xl
                                flex items-center justify-center
                                text-red-400
                                hover:text-red-600
                                hover:bg-red-50
                                transition-all duration-200"
                                title="Logout">

                            <svg class="w-[18px] h-[18px]
                                group-hover:translate-x-0.5
                                transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</nav>
