@extends('layouts.app')


@section('content')

    <div class="min-h-full bg-[#f7f9f8]">

        <div class="mx-auto max-w-full px-5 py-6 sm:px-6 lg:px-8">

            <div>

                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:border-blue-200 hover:shadow-md">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />

                    </svg>

                    Back to Users

                </a>

            </div>

        </div>

        <div class="mx-auto max-w-full px-5 lg:px-8">


            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4">

                    <div class="flex gap-3">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-red-600 shadow-sm">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                            </svg>

                        </div>

                        <div>

                            <p class="text-sm font-bold text-red-800">
                                Please correct the following:
                            </p>

                            <ul class="mt-2 space-y-1 text-xs text-red-700">

                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>
            @endif


            <form method="POST" action="{{ route('users.store') }}" id="create-user-form">

                @csrf


                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                    {{-- Card Header --}}
                    <div class="border-b border-gray-100 px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7-7h14a7 7 0 00-7 7z" />

                                </svg>

                            </div>


                            <div>

                                <h2 class="text-base font-extrabold text-blue-700">
                                    Account Information
                                </h2>

                                <p class="mt-0.5 text-xs text-gray-500">
                                    Enter the new user's account details.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Inputs --}}
                    <div class="p-6">

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                            {{-- Name --}}
                            <div>

                                <label for="name" class="mb-2 block text-xs font-bold text-gray-700">

                                    Full Name
                                    <span class="text-red-500">*</span>

                                </label>

                                <input id="name" name="name" type="text" value="{{ old('name') }}"
                                    placeholder="Enter full name" required autofocus
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                                @error('name')
                                    <p class="mt-1.5 text-xs font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Email --}}
                            <div>

                                <label for="email" class="mb-2 block text-xs font-bold text-gray-700">

                                    Email Address
                                    <span class="text-red-500">*</span>

                                </label>

                                <input id="email" name="email" type="email" value="{{ old('email') }}"
                                    placeholder="name@example.com" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                                @error('email')
                                    <p class="mt-1.5 text-xs font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Role --}}
                            <div>

                                <label for="role" class="mb-2 block text-xs font-bold text-gray-700">

                                    User Role
                                    <span class="text-red-500">*</span>

                                </label>

                                <select id="role" name="role" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                                    <option value="">
                                        Select user role
                                    </option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>

                                            {{ ucfirst($role) }}

                                        </option>
                                    @endforeach

                                </select>

                                @error('role')
                                    <p class="mt-1.5 text-xs font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Password --}}
                            <div>

                                <label for="password" class="mb-2 block text-xs font-bold text-gray-700">

                                    Password
                                    <span class="text-red-500">*</span>

                                </label>

                                <input id="password" name="password" type="password" placeholder="Enter password" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                                @error('password')
                                    <p class="mt-1.5 text-xs font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Confirm Password --}}
                            <div>

                                <label for="password_confirmation" class="mb-2 block text-xs font-bold text-gray-700">

                                    Confirm Password
                                    <span class="text-red-500">*</span>

                                </label>

                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    placeholder="Confirm password" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Actions --}}
                <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-amber-300 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-amber-500">

                        Cancel

                    </a>


                    <button type="submit" id="create-user-submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />

                        </svg>

                        Create User

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================
     SWEETALERT2
================================================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('create-user-form');
            const submitButton = document.getElementById('create-user-submit');

            if (!form || !submitButton) {
                return;
            }


            form.addEventListener('submit', function() {

                submitButton.disabled = true;

                submitButton.innerHTML = `
                <svg class="h-4 w-4 animate-spin"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4">
                    </circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>

                </svg>

                Creating...
            `;

            });

        });
    </script>

@endsection
