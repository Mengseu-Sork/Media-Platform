<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reset Password | NGO Forum on Cambodia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100">

    <div class="min-h-screen flex items-center justify-center px-4 py-6">

        <div class="w-full max-w-md">

            <div class="bg-white rounded-3xl shadow-2xl p-8">

                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <div class="bg-white rounded-2xl p-3">
                        <img src="{{ asset('image/logo.png') }}" alt="NGO Forum on Cambodia Logo"
                            class="w-auto h-28">
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center mb-8">

                    <h1 class="text-2xl sm:text-3xl font-bold text-blue-700">
                        Reset Password
                    </h1>

                    <p class="mt-3 text-sm text-gray-600">
                        Create a new secure password for your account.
                    </p>

                </div>

                <!-- Errors -->
                @if ($errors->any())
                    <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3">

                        <ul class="text-sm text-red-600 space-y-1">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">

                    @csrf

                    <!-- Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    <div>

                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Address
                        </label>

                        <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}"
                            required autofocus autocomplete="email"
                            class="w-full rounded-xl border border-gray-300
                                   px-4 py-3
                                   focus:border-blue-500
                                   focus:ring-2
                                   focus:ring-blue-200
                                   focus:outline-none">

                    </div>

                    <!-- New Password -->
                    <div>

                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            New Password
                        </label>

                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            placeholder="Enter new password"
                            class="w-full rounded-xl border border-gray-300
                                   px-4 py-3
                                   focus:border-blue-500
                                   focus:ring-2
                                   focus:ring-blue-200
                                   focus:outline-none">

                    </div>

                    <!-- Confirm Password -->
                    <div>

                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirm New Password
                        </label>

                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            autocomplete="new-password" placeholder="Confirm new password"
                            class="w-full rounded-xl border border-gray-300
                                   px-4 py-3
                                   focus:border-blue-500
                                   focus:ring-2
                                   focus:ring-blue-200
                                   focus:outline-none">

                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full rounded-xl
                               bg-gradient-to-r from-blue-600 to-blue-600
                               hover:from-blue-700 hover:to-blue-700
                               text-white font-bold
                               py-3.5
                               shadow-lg
                               hover:shadow-xl
                               transition">
                        Reset Password
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>
