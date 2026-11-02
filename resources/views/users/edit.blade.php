@extends('layouts.app')

@section('content')

    <div class="min-h-full bg-[#f7f9f8]">

        <div class="mx-auto max-w-full px-5 py-6 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

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


            {{-- Validation Errors --}}
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


            <form method="POST" action="{{ route('users.update', $user) }}" id="edit-user-form">

                @csrf
                @method('PUT')


                {{-- ====================================================
                    ACCOUNT INFORMATION
                ===================================================== --}}
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-6 py-5">

                        <div class="flex items-center justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7-7h14a7 7 0 00-7 7z" />

                                    </svg>

                                </div>


                                <div>

                                    <h2 class="text-base font-extrabold text-gray-950">
                                        Account Information
                                    </h2>

                                    <p class="mt-0.5 text-xs text-gray-400">
                                        Update the user's account details.
                                    </p>

                                </div>

                            </div>


                            @if (auth()->id() === $user->id)
                                <span
                                    class="rounded-full bg-blue-50 px-3 py-1 text-[9px] font-extrabold uppercase tracking-wider text-blue-700">
                                    Current account
                                </span>
                            @endif

                        </div>

                    </div>


                    <div class="p-6">

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                            {{-- Name --}}
                            <div>

                                <label for="name" class="mb-2 block text-xs font-bold text-gray-700">

                                    Full Name
                                    <span class="text-red-500">*</span>

                                </label>

                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                                @error('name')
                                    <p class="mt-1.5 text-xs text-red-600">
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

                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                                @error('email')
                                    <p class="mt-1.5 text-xs text-red-600">
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

                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}"
                                            {{ old('role', $user->role) === $role ? 'selected' : '' }}>

                                            {{ ucfirst($role) }}

                                        </option>
                                    @endforeach

                                </select>

                                @error('role')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Password --}}
                            <div>

                                <label for="password" class="mb-2 block text-xs font-bold text-gray-700">
                                    New Password
                                </label>

                                <input id="password" name="password" type="password"
                                    placeholder="Leave blank to keep current password"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                                @error('password')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <p class="mt-1.5 text-[10px] text-gray-400">
                                    Leave blank if the password should remain unchanged.
                                </p>

                            </div>


                            {{-- Confirm Password --}}
                            <div>

                                <label for="password_confirmation" class="mb-2 block text-xs font-bold text-gray-700">
                                    Confirm New Password
                                </label>

                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    placeholder="Confirm new password"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ====================================================
                    ACTIONS
                ===================================================== --}}
                <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-amber-300 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-amber-500">

                        Cancel

                    </a>


                    <button type="submit" id="update-user-submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />

                        </svg>

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('edit-user-form');
            const submitButton = document.getElementById('update-user-submit');

            if (!form || !submitButton) {
                return;
            }


            form.addEventListener('submit', function() {

                submitButton.disabled = true;

                submitButton.innerHTML = `
                <svg class="h-4 w-4 animate-spin"
                     fill="none"
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

                Saving...
            `;

            });

        });
    </script>

@endsection
