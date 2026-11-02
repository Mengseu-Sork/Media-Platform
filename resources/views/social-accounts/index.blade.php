@extends('layouts.app')

@section('content')

    <div class="min-h-full bg-[#f7f9f8]">

        {{-- Header --}}
        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-full px-5 py-6 sm:px-6 lg:px-8">

                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                    <div>

                        <div
                            class="mb-2 flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-blue-600">
                            Social Media
                        </div>

                        <h1 class="text-2xl font-black tracking-tight text-blue-800">
                            Media Accounts
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Manage the social media accounts used for publishing.
                        </p>

                    </div>


                    <a href="{{ route('social-accounts.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-700/20 transition hover:-translate-y-0.5 hover:bg-blue-800 hover:shadow-xl">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />

                        </svg>

                        Add Media Account

                    </a>

                </div>

            </div>

        </div>


        <div class="mx-auto max-w-full px-5 py-6 sm:px-6">

            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

                {{-- Total Accounts --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-gray-400">
                                Total Accounts
                            </p>

                            <p class="mt-2 text-3xl font-black tracking-tight text-gray-950">
                                {{ $socialAccounts->total() }}
                            </p>

                            <p class="mt-1 text-[10px] font-medium text-gray-400">
                                All connected accounts
                            </p>
                        </div>


                        {{-- Icon --}}
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-600 transition-all duration-200 group-hover:bg-gray-900 group-hover:text-white">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288-3M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />

                            </svg>

                        </div>

                    </div>

                </div>


                {{-- Active --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-gray-400">
                                Active
                            </p>

                            <p class="mt-2 text-3xl font-black tracking-tight text-blue-700">
                                {{ $socialAccounts->getCollection()->where('is_active', true)->count() }}
                            </p>

                            <p class="mt-1 text-[10px] font-medium text-gray-400">
                                Ready for publishing
                            </p>
                        </div>


                        {{-- Icon --}}
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-all duration-200 group-hover:bg-blue-600 group-hover:text-white">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M5 13l4 4L19 7" />

                            </svg>

                        </div>

                    </div>


                    {{-- Active indicator --}}
                    <div class="mt-4 flex items-center gap-1.5">

                        <span class="relative flex h-2 w-2">

                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-50">
                            </span>

                            <span class="relative h-2 w-2 rounded-full bg-blue-500"></span>

                        </span>

                        <span class="text-[10px] font-semibold text-blue-600">
                            Connected
                        </span>

                    </div>

                </div>


                {{-- Expired --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-red-200 hover:shadow-md">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-gray-400">
                                Expired
                            </p>

                            <p class="mt-2 text-3xl font-black tracking-tight text-red-600">
                                {{ $socialAccounts->getCollection()->filter(fn($account) => $account->connection_status === 'expired')->count() }}
                            </p>

                            <p class="mt-1 text-[10px] font-medium text-gray-400">
                                Accounts requiring attention
                            </p>
                        </div>


                        {{-- Icon --}}
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600 transition-all duration-200 group-hover:bg-red-600 group-hover:text-white">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                            </svg>

                        </div>

                    </div>


                    {{-- Expired indicator --}}
                    <div class="mt-4 flex items-center gap-1.5">

                        @if ($socialAccounts->getCollection()->filter(fn($account) => $account->connection_status === 'expired')->count() > 0)
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>

                            <span class="text-[10px] font-semibold text-red-600">
                                Action required
                            </span>
                        @else
                            <span class="h-2 w-2 rounded-full bg-gray-300"></span>

                            <span class="text-[10px] font-semibold text-gray-400">
                                No issues
                            </span>
                        @endif

                    </div>

                </div>

            </div>


            @if (session('success'))
                <div id="success-message" data-message="{{ session('success') }}"></div>
            @endif

            @if (session('error'))
                <div id="error-message" data-message="{{ session('error') }}"></div>
            @endif


            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                {{-- Table Header --}}
                <div
                    class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 md:flex-row md:items-center md:justify-between">

                    <div>

                        <div class="flex items-center gap-2">

                            <h2 class="text-base font-extrabold text-gray-950">
                                Connected Media Accounts
                            </h2>

                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[9px] font-extrabold text-blue-700">
                                {{ $socialAccounts->total() }}
                            </span>

                        </div>

                        <p class="mt-1 text-xs text-gray-400">
                            Manage the social media accounts available for publishing.
                        </p>

                    </div>


                    <a href="{{ route('social-accounts.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-800 hover:shadow-md">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />

                        </svg>

                        Add Account

                    </a>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        {{-- Head --}}
                        <thead class="border-b border-gray-100 bg-gray-50/80">

                            <tr>

                                <th
                                    class="whitespace-nowrap px-6 py-3.5 text-left text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                                    Platform
                                </th>

                                <th
                                    class="whitespace-nowrap px-6 py-3.5 text-left text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                                    Account
                                </th>

                                <th
                                    class="whitespace-nowrap px-6 py-3.5 text-left text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                                    Type
                                </th>

                                <th
                                    class="whitespace-nowrap px-6 py-3.5 text-left text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                                    Status
                                </th>

                                <th
                                    class="whitespace-nowrap px-6 py-3.5 text-left text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                                    Token
                                </th>

                                <th
                                    class="whitespace-nowrap px-6 py-3.5 text-right text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        {{-- Body --}}
                        <tbody class="divide-y divide-gray-100">

                            @forelse ($socialAccounts as $account)
                                @php

                                    $platform = strtolower(trim($account->platform));

                                    $config = match ($platform) {
                                        'facebook' => [
                                            'name' => 'Facebook',
                                            'bg' => 'bg-blue-50',
                                            'text' => 'text-blue-600',
                                        ],

                                        'instagram' => [
                                            'name' => 'Instagram',
                                            'bg' => 'bg-pink-50',
                                            'text' => 'text-pink-600',
                                        ],

                                        'youtube' => [
                                            'name' => 'YouTube',
                                            'bg' => 'bg-red-50',
                                            'text' => 'text-red-600',
                                        ],

                                        'tiktok' => [
                                            'name' => 'TikTok',
                                            'bg' => 'bg-gray-100',
                                            'text' => 'text-gray-800',
                                        ],

                                        'linkedin' => [
                                            'name' => 'LinkedIn',
                                            'bg' => 'bg-blue-50',
                                            'text' => 'text-blue-700',
                                        ],

                                        'twitter' => [
                                            'name' => 'X / Twitter',
                                            'bg' => 'bg-gray-100',
                                            'text' => 'text-gray-800',
                                        ],

                                        'website' => [
                                            'name' => 'Website',
                                            'bg' => 'bg-blue-50',
                                            'text' => 'text-blue-700',
                                        ],

                                        default => [
                                            'name' => ucfirst($platform),
                                            'bg' => 'bg-gray-100',
                                            'text' => 'text-gray-600',
                                        ],
                                    };

                                    $isExpired = $account->connection_status === 'expired';

                                @endphp


                                <tr class="group transition-colors duration-150 hover:bg-gray-50/70">

                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            {{-- Icon --}}
                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $config['bg'] }} {{ $config['text'] }}">

                                                @if ($platform === 'facebook')
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">

                                                        <path
                                                            d="M13.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.5 1.6-1.5h1.7V4c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.5-4 4.1V10H8v3h2.4v8h3.1z" />

                                                    </svg>
                                                @elseif ($platform === 'instagram')
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">

                                                        <rect x="3" y="3" width="18" height="18" rx="5"
                                                            stroke-width="1.8" />

                                                        <circle cx="12" cy="12" r="4" stroke-width="1.8" />

                                                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor"
                                                            stroke="none" />

                                                    </svg>
                                                @elseif ($platform === 'youtube')
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">

                                                        <path
                                                            d="M23.5 6.2a3 3 0 00-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 00.5 6.2 31 31 0 000 12a31 31 0 00.5 5.8 3 3 0 002.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 002.1-2.1A31 31 0 0024 12a31 31 0 00-.5-5.8zM9.6 15.6V8.4l6.2 3.6-6.2 3.6z" />

                                                    </svg>
                                                @elseif ($platform === 'tiktok')
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">

                                                        <path
                                                            d="M19.589 6.686a4.793 4.793 0 01-3.77-3.78V2h-3.605v13.137a2.994 2.994 0 11-2.996-2.993c.31 0 .61.032.9.122v-3.68a6.59 6.59 0 00-.9-.062A6.612 6.612 0 1015.82 15.1V8.85a8.37 8.37 0 004.894 1.575V6.82a4.824 4.824 0 01-1.125-.134z" />

                                                    </svg>
                                                @elseif ($platform === 'linkedin')
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">

                                                        <path
                                                            d="M6.5 8A2.5 2.5 0 116.5 3a2.5 2.5 0 010 5zM4 9h5v11H4V9zm7 0h4.8v1.5h.1c.7-1.1 2.3-1.8 4.1-1.8 4.4 0 5 2.9 5 6.7V20h-5v-4.1c0-1 0-2.3-1.4-2.3-1.5 0-1.7 1.1-1.7 2.2V20h-5V9z" />

                                                    </svg>
                                                @elseif ($platform === 'twitter')
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">

                                                        <path
                                                            d="M18.244 2.25h3.308l-7.227 8.26L22.827 21.75h-6.59l-5.158-6.736-5.9 6.736H1.87l7.73-8.835L1.254 2.25H8.01l4.663 6.167L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z" />

                                                    </svg>
                                                @elseif ($platform === 'website')
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
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


                                            <div>

                                                <p class="text-sm font-extrabold text-gray-900">
                                                    {{ $config['name'] }}
                                                </p>

                                                <p class="mt-0.5 text-[10px] text-gray-400">
                                                    {{ ucfirst($platform) }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    <td class="px-6 py-4">

                                        <div class="max-w-[250px]">

                                            <p class="truncate text-sm font-bold text-gray-800">
                                                {{ $account->display_name ?: 'Unnamed Account' }}
                                            </p>

                                            @if ($account->username)
                                                <p class="mt-0.5 truncate text-[11px] text-gray-400">
                                                    {{ '@' . ltrim($account->username, '@') }}
                                                </p>
                                            @else
                                                <p class="mt-0.5 text-[11px] text-gray-300">
                                                    No username
                                                </p>
                                            @endif

                                        </div>

                                    </td>


                                    <td class="px-6 py-4">

                                        <span class="text-xs font-medium text-gray-600">
                                            {{ $account->account_type ?: 'Account' }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4">

                                        @if (!$account->is_active)
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-[9px] font-bold text-gray-500">

                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                                                Inactive

                                            </span>
                                        @elseif ($isExpired)
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-[9px] font-bold text-red-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                                Expired

                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-[9px] font-bold text-blue-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                                                Connected

                                            </span>
                                        @endif

                                    </td>


                                    <td class="px-6 py-4">

                                        @if ($account->token_expires_at)
                                            @if ($isExpired)
                                                <div>

                                                    <p class="text-xs font-bold text-red-600">
                                                        Expired
                                                    </p>

                                                    <p class="mt-0.5 text-[10px] text-gray-400">
                                                        {{ $account->token_expires_at->format('d M Y') }}
                                                    </p>

                                                </div>
                                            @else
                                                <div>

                                                    <p class="text-xs font-semibold text-gray-600">
                                                        Valid
                                                    </p>

                                                    <p class="mt-0.5 text-[10px] text-gray-400">
                                                        Expires {{ $account->token_expires_at->format('d M Y') }}
                                                    </p>

                                                </div>
                                            @endif
                                        @else
                                            <span class="text-[10px] text-gray-400">
                                                No expiry
                                            </span>
                                        @endif

                                    </td>


                                    <td class="px-6 py-4">

                                        <div class="flex items-center justify-end gap-1">


                                            {{-- Edit --}}
                                            <a href="{{ route('social-accounts.edit', $account) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-400 transition hover:bg-blue-50 hover:text-blue-700"
                                                title="Edit Account">

                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1-1-4 10.5-9.5z" />

                                                </svg>

                                            </a>


                                            {{-- Delete --}}
                                            <form method="POST"
                                                action="{{ route('social-accounts.destroy', $account) }}"
                                                class="delete-account-form" data-account="{{ $account->display_name }}">

                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                    class="delete-account-button inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-400 transition hover:bg-red-50 hover:text-red-600"
                                                    title="Disconnect Account">

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-1 0V4h-4v3m-5 0h14" />

                                                    </svg>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="6" class="px-6 py-16 text-center">

                                        <div
                                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">

                                            <svg class="h-7 w-7" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288-3M15 7a3 3 0 11-6 0 3 3 0 016 0z" />

                                            </svg>

                                        </div>


                                        <h3 class="mt-4 text-sm font-extrabold text-gray-800">
                                            No media accounts connected
                                        </h3>


                                        <p class="mt-1 text-xs text-gray-400">
                                            Connect a social media account to start publishing.
                                        </p>


                                        <a href="{{ route('social-accounts.create') }}"
                                            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-bold text-white hover:bg-blue-800">

                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />

                                            </svg>

                                            Add Media Account

                                        </a>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if ($socialAccounts->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">

                        {{ $socialAccounts->links() }}

                    </div>
                @endif

            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const success = document.getElementById('success-message');

            if (success) {

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: success.dataset.message,
                    confirmButtonColor: '#15803d',
                    timer: 2500,
                    timerProgressBar: true
                });

            }


            const error = document.getElementById('error-message');

            if (error) {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.dataset.message,
                    confirmButtonColor: '#dc2626'
                });

            }


            document.querySelectorAll('.delete-account-button')
                .forEach(button => {

                    button.addEventListener('click', function() {

                        const form = this.closest('.delete-account-form');

                        const account =
                            form.dataset.account;


                        Swal.fire({

                            title: 'Disconnect Account?',

                            html: `
                        <p class="text-sm text-gray-500">
                            Are you sure you want to disconnect
                            <strong>${account}</strong>?
                        </p>
                    `,

                            icon: 'warning',

                            showCancelButton: true,

                            confirmButtonText: 'Yes, Disconnect',

                            cancelButtonText: 'Cancel',

                            confirmButtonColor: '#dc2626',

                            cancelButtonColor: '#6b7280',

                            reverseButtons: true,

                        }).then((result) => {

                            if (result.isConfirmed) {

                                Swal.fire({
                                    title: 'Disconnecting...',
                                    text: 'Please wait.',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                form.submit();
                            }

                        });

                    });

                });

        });
    </script>

@endsection
