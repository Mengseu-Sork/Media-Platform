@extends('layouts.app')

@section('content')

    <div class="min-h-[calc(100vh-4rem)] bg-[#f5f8f6]">

        {{-- HEADER --}}
        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-full px-6 py-6">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.15em]">

                            <a href="{{ route('social-accounts.index') }}" class="text-gray-400 hover:text-blue-700">
                                Media Accounts
                            </a>

                            <span class="text-gray-300">
                                /
                            </span>

                            <span class="text-blue-700">
                                Connect
                            </span>

                        </div>

                        <h1 class="mt-3 text-2xl font-black tracking-tight text-blue-700">
                            Connect Publishing Channel
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Select where your content should be published.
                        </p>

                    </div>

                    <a href="{{ route('social-accounts.index') }}"
                        class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-700 hover:border-blue-200 hover:text-blue-700">
                        ← Back
                    </a>

                </div>

            </div>

        </div>


        <div class="mx-auto max-w-full px-6 py-6">

            @if (session('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif


            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">

                    @foreach ($errors->all() as $error)
                        <div class="text-xs text-red-700">
                            {{ $error }}
                        </div>
                    @endforeach

                </div>
            @endif


            <form method="POST" action="{{ route('social-accounts.store') }}" id="connect-form">

                @csrf


                <div class="grid grid-cols-12 gap-6">

                    {{-- PLATFORM SELECTION --}}
                    <section class="col-span-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-100 px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-xs font-black text-blue-700">
                                    01
                                </div>

                                <div>

                                    <h2 class="text-sm font-extrabold text-gray-950">
                                        Select Publishing Channel
                                    </h2>

                                    <p class="mt-0.5 text-[11px] text-gray-400">
                                        Laravel will start the correct connection or verification process.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="p-6">

                            <div class="grid grid-cols-2 gap-4 md:grid-cols-3">

                                @foreach ($platforms as $platform)
                                    @php

                                        $value = strtolower($platform->value);

                                        $config = match ($value) {
                                            'facebook' => [
                                                'name' => 'Facebook',

                                                'description' => 'Facebook Page',

                                                'method' => 'Server configuration',
                                            ],

                                            'instagram' => [
                                                'name' => 'Instagram',

                                                'description' => 'Instagram Business',

                                                'method' => 'Server configuration',
                                            ],

                                            'youtube' => [
                                                'name' => 'YouTube',

                                                'description' => 'YouTube Channel',

                                                'method' => 'Google OAuth',
                                            ],

                                            'tiktok' => [
                                                'name' => 'TikTok',

                                                'description' => 'TikTok Account',

                                                'method' => 'TikTok OAuth',
                                            ],

                                            'linkedin' => [
                                                'name' => 'LinkedIn',

                                                'description' => 'LinkedIn Organization',

                                                'method' => 'LinkedIn OAuth',
                                            ],

                                            'twitter' => [
                                                'name' => 'X / Twitter',

                                                'description' => 'X Account',

                                                'method' => 'X OAuth 2.0',
                                            ],

                                            'website' => [
                                                'name' => 'Website',

                                                'description' => 'NGO Forum Website',

                                                'method' => 'Website API',
                                            ],

                                            default => [
                                                'name' => $platform->label(),

                                                'description' => 'Publishing channel',

                                                'method' => 'Connection',
                                            ],
                                        };

                                    @endphp


                                    <label class="cursor-pointer">

                                        <input type="radio" name="platform" value="{{ $value }}"
                                            class="peer sr-only" {{ $loop->first ? 'required' : '' }}>


                                        <div
                                            class="relative rounded-2xl border-2 border-gray-100 bg-white p-5 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg peer-checked:border-blue-600 peer-checked:bg-blue-50/50">

                                            {{-- CHECK --}}
                                            <div
                                                class="absolute right-4 top-4 hidden h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-white peer-checked:flex">

                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>

                                            </div>


                                            {{-- ICON --}}
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                                                @if ($value === 'facebook')
                                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M13.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.5 1.6-1.5h1.7V4c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.5-4 4.1V10H8v3h2.4v8h3.1z" />
                                                    </svg>
                                                @elseif($value === 'instagram')
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <rect x="3" y="3" width="18" height="18" rx="5"
                                                            stroke-width="1.8" />
                                                        <circle cx="12" cy="12" r="4" stroke-width="1.8" />
                                                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor"
                                                            stroke="none" />
                                                    </svg>
                                                @elseif($value === 'youtube')
                                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M23.5 6.2a3 3 0 00-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 00.5 6.2 31 31 0 000 12a31 31 0 00.5 5.8 3 3 0 002.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 002.1-2.1A31 31 0 0024 12a31 31 0 00-.5-5.8zM9.6 15.6V8.4l6.2 3.6  -6.2 3.6z" />
                                                    </svg>
                                                @elseif($value === 'tiktok')
                                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                                                        <path
                                                            d="M19.589 6.686a4.793 4.793 0 01-3.77-3.78V2h-3.605v13.137a2.994 2.994 0 11-2.996-2.993c.31 0 .61.032.9.122v-3.68a6.59 6.59 0 00-.9-.062A6.612 6.612 0 1015.82 15.1V8.85a8.37 8.37 0 004.894 1.575V6.82a4.824 4.824 0 01-1.125-.134z" />
                                                    </svg>
                                                @elseif($value === 'linkedin')
                                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M6.5 8A2.5 2.5 0 116.5 3a2.5 2.5 0 010 5zM4 9h5v11H4V9zm7 0h4.8v1.5h.1c.7-1.1 2.3-1.8 4.1-1.8 4.4 0 5 2.9 5 6.7V20h-5v-4.1c0-1 0-2.3-1.4-2.3-1.5 0-1.7 1.1-1.7 2.2V20h-5V9z" />
                                                    </svg>
                                                @elseif($value === 'twitter')
                                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M18.244 2.25h3.308l-7.227 8.26L22.827 21.75h-6.59l-5.158-6.736-5.9 6.736H1.87l7.73-8.835L1.254 2.25H8.01l4.663 6.167L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z" />
                                                    </svg>
                                                @elseif($value === 'website')
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                                                        <path
                                                            d="M3 12h18M12 3c2.2 2.4 3.3 5.4 3.3 9S14.2 18.6 12 21M12 3c-2.2 2.4-3.3 5.4-3.3 9S9.8 18.6 12 21"
                                                            stroke-width="1.8" />
                                                    </svg>
                                                @else
                                                    <span class="text-sm font-black">
                                                        {{ strtoupper(substr($config['name'], 0, 1)) }}
                                                    </span>
                                                @endif

                                            </div>


                                            <p class="mt-5 text-sm font-extrabold text-gray-900">
                                                {{ $config['name'] }}
                                            </p>

                                            <p class="mt-1 text-[11px] leading-4 text-gray-400">
                                                {{ $config['description'] }}
                                            </p>

                                            <div
                                                class="mt-4 inline-flex rounded-full bg-gray-50 px-2.5 py-1 text-[9px] font-bold text-gray-500">
                                                {{ $config['method'] }}
                                            </div>

                                        </div>

                                    </label>
                                @endforeach

                            </div>


                            @error('platform')
                                <p class="mt-4 text-xs font-semibold text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </section>


                    {{-- SIDE --}}
                    <aside class="col-span-4 space-y-5">

                        <div class="rounded-2xl bg-gradient-to-br from-blue-700 to-blue-800 p-6 text-white shadow-lg">

                            <p class="text-[9px] font-bold uppercase tracking-[0.18em] text-blue-200">
                                Publishing
                            </p>

                            <h2 class="mt-2 text-lg font-black">
                                Connect once
                            </h2>

                            <p class="mt-2 text-xs leading-5 text-blue-100">
                                OAuth providers use secure authorization. Facebook and Instagram use your configured Meta
                                credentials. Website uses the configured NGO Forum API.
                            </p>

                        </div>


                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                            <h2 class="text-sm font-extrabold text-gray-950">
                                Connection flow
                            </h2>


                            <div class="mt-5 space-y-5">

                                <div class="flex gap-3">

                                    <span
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-700 text-[10px] font-black text-white">
                                        1
                                    </span>

                                    <div>
                                        <p class="text-xs font-bold text-gray-800">
                                            Select channel
                                        </p>

                                        <p class="mt-1 text-[10px] leading-4 text-gray-400">
                                            Choose a social platform or Website.
                                        </p>
                                    </div>

                                </div>


                                <div class="flex gap-3">

                                    <span
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-[10px] font-black text-gray-700">
                                        2
                                    </span>

                                    <div>
                                        <p class="text-xs font-bold text-gray-800">
                                            Authorize or verify
                                        </p>

                                        <p class="mt-1 text-[10px] leading-4 text-gray-400">
                                            Laravel performs the correct connection method.
                                        </p>
                                    </div>

                                </div>


                                <div class="flex gap-3">

                                    <span
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-[10px] font-black text-gray-700">
                                        3
                                    </span>

                                    <div>
                                        <p class="text-xs font-bold text-gray-800">
                                            Ready to publish
                                        </p>

                                        <p class="mt-1 text-[10px] leading-4 text-gray-400">
                                            The destination appears in the publishing system.
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </aside>

                </div>


                {{-- ACTIONS --}}
                <div class="mt-6 flex justify-end gap-2 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">

                    <a href="{{ route('social-accounts.index') }}"
                        class="rounded-xl border border-gray-200 bg-amber-300 px-5 py-2.5 text-xs font-bold text-white hover:bg-amber-500">
                        Cancel
                    </a>

                    <button type="submit" id="connect-button"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-700/20 hover:bg-blue-800">
                        Connect Account
                    </button>

                </div>

            </form>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form =
                document.getElementById('connect-form');

            const button =
                document.getElementById('connect-button');

            if (!form || !button) {
                return;
            }

            form.addEventListener('submit', function() {

                button.disabled = true;

                button.innerHTML = `
            <svg
                class="h-4 w-4 animate-spin"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>

                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                ></path>
            </svg>

            Connecting...
        `;
            });

        });
    </script>

@endsection
