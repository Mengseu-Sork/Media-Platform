
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Media Platform</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100">

    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">

        <div class="absolute -top-32 -right-32 w-80 h-80
                    bg-blue-200/30 rounded-full blur-3xl">
        </div>

        <div class="absolute -bottom-32 -left-32 w-80 h-80
                    bg-emerald-200/30 rounded-full blur-3xl">
        </div>

    </div>


    <!-- Main -->
    <main class="relative min-h-screen flex items-center justify-center px-4 py-10">

        <div class="w-full max-w-md">

            <!-- Card -->
            <div
                class="bg-white rounded-[2rem]
                        shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)]
                        border border-gray-100
                        overflow-hidden">

                <!-- Green Header -->
                <div
                    class="h-2 bg-gradient-to-r from-blue-600
                            via-blue-500 to-blue-700">
                </div>


                <div class="p-7 sm:p-10">

                    <!-- Logo -->
                    <div class="flex justify-center mb-7">

                        <div
                            class="flex items-center justify-center h-32 p-3">

                            <img src="{{ asset('image/logo.png') }}" alt="NGO Forum on Cambodia Logo"
                                class="max-w-full max-h-full object-contain">

                        </div>

                    </div>


                    <!-- Title -->
                    <div class="text-center mb-8">

                        <h1
                            class="text-2xl
                                   font-extrabold
                                   text-blue-700">
                            Forgot Password?
                        </h1>

                        <p
                            class="mt-3 text-sm
                                  text-gray-500
                                  leading-6 max-w-sm mx-auto">
                            Enter the email address associated with your
                            account and we will send you a secure link
                            to reset your password.
                        </p>

                    </div>


                    <!-- Success Message -->
                    @if (session('status'))
                        <div
                            class="mb-6 rounded-2xl
                                    border border-blue-200
                                    bg-blue-50
                                    px-4 py-4">

                            <div class="flex items-start gap-3">

                                <div
                                    class="flex-shrink-0
                                            w-8 h-8 rounded-full
                                            bg-blue-100
                                            flex items-center justify-center">

                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-blue-800">
                                        Reset link sent
                                    </p>

                                    <p class="mt-1 text-sm text-blue-700">
                                        {{ session('status') }}
                                    </p>

                                </div>

                            </div>

                        </div>
                    @endif


                    <!-- Validation Errors -->
                    @if ($errors->any())

                        <div
                            class="mb-6 rounded-2xl
                                    border border-red-200
                                    bg-red-50
                                    px-4 py-4">

                            <div class="flex items-start gap-3">

                                <div
                                    class="flex-shrink-0
                                            w-8 h-8 rounded-full
                                            bg-red-100
                                            flex items-center justify-center">

                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-red-800">
                                        Unable to send reset link
                                    </p>

                                    <ul class="mt-1 text-sm text-red-700 space-y-1">

                                        @foreach ($errors->all() as $error)
                                            <li>
                                                {{ $error }}
                                            </li>
                                        @endforeach

                                    </ul>

                                </div>

                            </div>

                        </div>

                    @endif


                    <!-- Form -->
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6"
                        id="forgotPasswordForm">

                        @csrf


                        <!-- Email -->
                        <div>

                            <label for="email"
                                class="block text-sm font-semibold
                                       text-gray-700 mb-2">
                                Email Address
                            </label>


                            <div class="relative group">

                                <!-- Email Icon -->
                                <div
                                    class="absolute inset-y-0 left-0
                                            flex items-center pl-4
                                            pointer-events-none">

                                    <svg class="w-5 h-5 text-gray-400
                                               group-focus-within:text-blue-600
                                               transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>

                                </div>


                                <input id="email" name="email" type="email" value="{{ old('email') }}"
                                    placeholder="you@example.com" required autofocus autocomplete="email"
                                    class="w-full
                                           rounded-xl
                                           border border-gray-300
                                           bg-gray-50
                                           pl-12 pr-4 py-3.5
                                           text-gray-900
                                           placeholder-gray-400
                                           shadow-sm
                                           outline-none
                                           transition-all
                                           duration-200
                                           hover:bg-white
                                           focus:bg-white
                                           focus:border-blue-500
                                           focus:ring-4
                                           focus:ring-blue-100">

                            </div>


                            @error('email')
                                <p
                                    class="mt-2 flex items-center gap-1.5
                                          text-sm text-red-600">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01" />
                                    </svg>

                                    {{ $message }}

                                </p>
                            @enderror

                        </div>


                        <!-- Submit -->
                        <button type="submit" id="submitButton"
                            class="w-full
                                   flex items-center justify-center
                                   gap-2
                                   rounded-xl
                                   bg-gradient-to-r
                                   from-blue-600
                                   to-blue-600
                                   hover:from-blue-700
                                   hover:to-blue-700
                                   active:scale-[0.99]
                                   text-white
                                   font-bold
                                   py-3.5
                                   px-5
                                   shadow-lg
                                   shadow-blue-600/20
                                   hover:shadow-xl
                                   hover:shadow-blue-600/25
                                   transition-all
                                   duration-200
                                   focus:outline-none
                                   focus:ring-4
                                   focus:ring-blue-200">

                            <!-- Mail Icon -->
                            <svg id="buttonIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>

                            <span id="buttonText">
                                Send Password Reset Link
                            </span>

                        </button>

                    </form>


                    <!-- Divider -->
                    <div class="flex items-center gap-4 my-7">

                        <div class="flex-1 h-px bg-gray-200"></div>

                        <span
                            class="text-xs text-gray-400 uppercase
                                     tracking-wider">
                            or
                        </span>

                        <div class="flex-1 h-px bg-gray-200"></div>

                    </div>


                    <!-- Back Login -->
                    <a href="{{ route('login') }}"
                        class="w-full
                               flex items-center justify-center
                               gap-2
                               rounded-xl
                               border border-gray-200
                               bg-white
                               hover:bg-gray-50
                               hover:border-blue-200
                               text-gray-700
                               hover:text-blue-700
                               font-semibold
                               py-3.5
                               transition-all
                               duration-200
                               focus:outline-none
                               focus:ring-4
                               focus:ring-blue-100">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m7 7H3" />
                        </svg>

                        Back to Login

                    </a>

                </div>

            </div>

        </div>

    </main>


    <!-- Submit Loading State -->
    <script>
        document.getElementById('forgotPasswordForm')
            .addEventListener('submit', function() {

                const button = document.getElementById('submitButton');
                const buttonText = document.getElementById('buttonText');
                const buttonIcon = document.getElementById('buttonIcon');

                button.disabled = true;

                button.classList.add(
                    'opacity-75',
                    'cursor-not-allowed'
                );

                buttonIcon.outerHTML = `
                    <svg
                        class="w-5 h-5 animate-spin"
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
                `;

                buttonText.textContent = 'Sending Reset Link...';

            });
    </script>

</body>

</html>

