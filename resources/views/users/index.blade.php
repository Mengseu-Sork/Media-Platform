@extends('layouts.app')

@section('content')

    <div class="min-h-[calc(100vh-4rem)] bg-[#f7f9f8]">

        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-full px-5 py-6 lg:px-8">

                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                    <div>

                        <div class="mt-3 flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288-3M15 7a3 3 0 11-6 0 3 3 0 016 0z" />

                                </svg>

                            </div>


                            <div>

                                <h1 class="text-2xl font-black tracking-tight text-blue-700">
                                    User Management
                                </h1>

                                <p class="mt-1 text-sm text-gray-500">
                                    Manage users, roles, and system access.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Create User --}}
                    <a href="{{ route('users.create') }}"
                        class="group inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-700/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-blue-800 hover:shadow-xl">

                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/15">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />

                            </svg>

                        </span>

                        Create User

                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />

                        </svg>

                    </a>

                </div>

            </div>

        </div>



        <div class="mx-auto max-w-full px-5 py-6">

            <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

                {{-- Total --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                                Total Users
                            </p>

                            <p class="mt-2 text-3xl font-black text-gray-950">
                                {{ $users->total() }}
                            </p>

                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288-3M15 7a3 3 0 11-6 0 3 3 0 016 0z" />

                            </svg>

                        </div>

                    </div>

                </div>


                {{-- Admin --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                                Administrators
                            </p>

                            <p class="mt-2 text-3xl font-black text-gray-950">
                                {{ $users->getCollection()->where('role', 'admin')->count() }}
                            </p>

                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M12 15l3 3m0 0l3-3m-3 3V9m-4 12h8a2 2 0 002-2V7a2 2 0 00-2-2h-8a2 2 0 00-2 2v12a2 2 0 002 2z" />

                            </svg>

                        </div>

                    </div>

                </div>


                {{-- Managers --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                                Managers
                            </p>

                            <p class="mt-2 text-3xl font-black text-gray-950">
                                {{ $users->getCollection()->where('role', 'manager')->count() }}
                            </p>

                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                            </svg>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 SUCCESS / ERROR
            ========================================================= --}}
            @if (session('success'))
                <div id="session-success" data-message="{{ session('success') }}"></div>
            @endif

            @if (session('error'))
                <div id="session-error" data-message="{{ session('error') }}"></div>
            @endif


            {{-- ========================================================
                 USERS TABLE
            ========================================================= --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                {{-- Table Header --}}
                <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 md:flex-row md:items-center md:justify-between bg-blue-600">

                    <div>

                        <h2 class="text-lg font-extrabold text-white uppercase">
                            System Users
                        </h2>

                        <p class="mt-1 text-xs text-gray-200">
                            View and manage all registered users.
                        </p>

                    </div>


                    <div class="rounded-lg bg-white px-3 py-2 text-[10px] font-bold text-blue-600">
                        {{ $users->firstItem() ?? 0 }}
                        -
                        {{ $users->lastItem() ?? 0 }}
                        of
                        {{ $users->total() }}
                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="border-b border-gray-100 bg-blue-50">

                            <tr>

                                <th
                                    class="px-6 py-3.5 text-left text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                                    User
                                </th>

                                <th
                                    class="px-6 py-3.5 text-left text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                                    Email
                                </th>

                                <th
                                    class="px-6 py-3.5 text-left text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                                    Role
                                </th>

                                <th
                                    class="px-6 py-3.5 text-left text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                                    Created
                                </th>

                                <th
                                    class="px-6 py-3.5 text-right text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse ($users as $user)
                                @php

                                    $roleStyle = match (strtolower($user->role ?? 'staff')) {
                                        'admin' => [
                                            'badge' => 'bg-red-50 text-red-700',
                                            'icon' => 'bg-red-50 text-red-600',
                                        ],

                                        'manager' => [
                                            'badge' => 'bg-blue-50 text-blue-700',
                                            'icon' => 'bg-blue-50 text-blue-600',
                                        ],

                                        default => [
                                            'badge' => 'bg-blue-50 text-blue-700',
                                            'icon' => 'bg-blue-50 text-blue-700',
                                        ],
                                    };

                                @endphp


                                <tr class="group transition-colors hover:bg-gray-50/80">


                                    {{-- User --}}
                                    <td class="px-6 py-2">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 text-sm font-black text-white shadow-sm">

                                                {{ strtoupper(substr($user->name, 0, 1)) }}

                                                @if (auth()->id() === $user->id)
                                                    <span
                                                        class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-white bg-blue-500"></span>
                                                @endif

                                            </div>


                                            <div class="min-w-0">

                                                <p class="truncate text-sm font-bold text-gray-900">
                                                    {{ $user->name }}
                                                </p>

                                                @if (auth()->id() === $user->id)
                                                    <span class="text-[10px] font-semibold text-blue-600">
                                                        You
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-gray-400">
                                                        User #{{ $user->id }}
                                                    </span>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Email --}}
                                    <td class="px-6 py-2">

                                        <span class="text-sm text-gray-600">
                                            {{ $user->email }}
                                        </span>

                                    </td>


                                    {{-- Role --}}
                                    <td class="px-6 py-2">

                                        <span
                                            class="inline-flex items-center rounded-full px-3 py-1 text-[9px] font-extrabold uppercase tracking-wider {{ $roleStyle['badge'] }}">

                                            {{ ucfirst($user->role ?? 'staff') }}

                                        </span>

                                    </td>


                                    {{-- Created --}}
                                    <td class="px-6 py-2">

                                        <div>

                                            <p class="text-xs font-semibold text-gray-600">
                                                {{ $user->created_at?->format('d M Y') }}
                                            </p>

                                        </div>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="px-6 py-2">

                                        <div class="flex items-center justify-end gap-1">


                                            {{-- Edit --}}
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-400 transition hover:bg-blue-50 hover:text-blue-700"
                                                title="Edit User">

                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 10.5-9.5z" />

                                                </svg>

                                            </a>


                                            {{-- Delete --}}
                                            @if (auth()->id() !== $user->id)
                                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                    class="delete-user-form">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                        class="delete-user-btn inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-400 transition hover:bg-red-50 hover:text-red-600"
                                                        title="Delete User" data-user="{{ $user->name }}">

                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">

                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.8"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-1 0V4h-4v3m-5 0h14" />

                                                        </svg>

                                                    </button>

                                                </form>
                                            @else
                                                <span
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600"
                                                    title="You cannot delete your own account">

                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            d="M12 9v2m0 4h.01M5.5 19h13a1.5 1.5 0 001.3-2.25l-6.5-11a1.5 1.5 0 00-2.6 0l-6.5 11A1.5 1.5 0 005.5 19z" />

                                                    </svg>

                                                </span>
                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="px-6 py-16 text-center">

                                        <div
                                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 text-gray-300">

                                            <svg class="h-7 w-7" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288-3M15 7a3 3 0 11-6 0 3 3 0 016 0z" />

                                            </svg>

                                        </div>

                                        <h3 class="mt-4 text-sm font-extrabold text-gray-800">
                                            No users found
                                        </h3>

                                        <p class="mt-1 text-xs text-gray-400">
                                            Create your first system user.
                                        </p>

                                        <a href="{{ route('admin.users.create') }}"
                                            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-bold text-white hover:bg-blue-800">

                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />

                                            </svg>

                                            Create User

                                        </a>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">

                        {{ $users->links() }}

                    </div>
                @endif

            </div>

        </div>

    </div>


    {{-- ================================================================
         SWEETALERT2
    ================================================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | Success Message
            |--------------------------------------------------------------------------
            */
            const success = document.getElementById('session-success');

            if (success) {

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: success.dataset.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#15803d',
                    timer: 2500,
                    timerProgressBar: true
                });

            }


            /*
            |--------------------------------------------------------------------------
            | Error Message
            |--------------------------------------------------------------------------
            */
            const error = document.getElementById('session-error');

            if (error) {

                Swal.fire({
                    icon: 'error',
                    title: 'Unable to complete',
                    text: error.dataset.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc2626'
                });

            }


            /*
            |--------------------------------------------------------------------------
            | Delete Confirmation
            |--------------------------------------------------------------------------
            */
            document.querySelectorAll('.delete-user-btn').forEach(function(button) {

                button.addEventListener('click', function() {

                    const form = this.closest('.delete-user-form');
                    const userName = this.dataset.user;


                    Swal.fire({
                        title: 'Delete User?',
                        html: `
                            <div class="text-sm text-gray-500">
                                You are about to permanently delete
                                <strong class="text-gray-800">${userName}</strong>.
                                <br>
                                This action cannot be undone.
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true,
                        focusCancel: true
                    }).then((result) => {

                        if (result.isConfirmed) {

                            Swal.fire({
                                title: 'Deleting...',
                                text: 'Please wait.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
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
