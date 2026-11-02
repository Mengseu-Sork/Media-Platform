<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> Media Platform </title> 
    <link rel="shortcut icon" href="image/logo.png" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center min-h-screen px-4">
    <div
        class="bg-white shadow-2xl rounded-3xl p-8 sm:p-10 w-full max-w-md transform transition duration-500 hover:scale-105">
        <img src="{{ 'image/logo.png' }}" alt="NGO Forum Logo" class="mx-auto w-32 sm:w-48 h-auto">

        <h2 class="text-xl font-bold mt-6 text-blue-700 mb-6 text-center">Welcome to Media Platform</h2>

        <p class="mt-1.5 text-sm leading-6 text-center text-gray-500"> Please sign in to access your account. </p>


        @if (session('status'))
            <div class="mb-5 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
                <div class="flex items-start gap-3"> <svg xmlns="http://www.w3.org/2000/svg"
                        class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-sm font-medium text-blue-700"> {{ session('status') }} </p>
                </div>
            </div>
            @endif {{-- ============================================= VALIDATION ERRORS ============================================== --}} @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                    <div class="flex items-start gap-3"> <svg xmlns="http://www.w3.org/2000/svg"
                            class="mt-0.5 h-5 w-5 shrink-0 text-red-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01 M12 5.5a6.5 6.5 0 110 13 6.5 6.5 0 010-13z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-700"> Unable to sign in </p>
                            <ul class="mt-1 space-y-1 text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li> {{ $error }} </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-gray-700"> Email Address </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M3 8l9 6 9-6 M5 5h14 a2 2 0 012 2v10 a2 2 0 01-2 2H5 a2 2 0 01-2-2V7 a2 2 0 012-2z" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            autofocus autocomplete="username" placeholder="Enter your email address"
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 py-3.5 pl-11 pr-4 text-sm text-gray-900 placeholder:text-gray-400 transition hover:border-gray-400 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100 @error('email') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror" />
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600"> {{ $message }} </p>
                    @enderror
                </div>
                <div>
                    <div class="mb-2 flex items-center justify-between"> <label for="password"
                            class="block text-sm font-semibold text-gray-700"> Password </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-medium text-blue-700 transition hover:text-blue-800 hover:underline">
                                Forgot password? </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M16.5 10.5V8 a4.5 4.5 0 00-9 0v2.5 M6 10.5h12 a2 2 0 012 2v6 a2 2 0 01-2 2H6 a2 2 0 01-2-2v-6 a2 2 0 012-2z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            placeholder="Enter your password"
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 py-3.5 pl-11 pr-12 text-sm text-gray-900 placeholder:text-gray-400 transition hover:border-gray-400 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100 @error('password') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror" />

                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 transition hover:text-blue-700 focus:outline-none"
                            aria-label="Show password">
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5 c4.477 0 8.268 2.943 9.542 7 -1.274 4.057 -5.065 7 -9.542 7 -4.477 0 -8.268-2.943 -9.542-7z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600"> {{ $message }} </p>
                    @enderror
                </div>
                <div>
                    <label for="remember_me" class="inline-flex cursor-pointer items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-blue-700 focus:ring-2 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600"> Remember me </span>
                    </label>
                </div>
                <button type="submit"
                    class="group flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-all duration-200 hover:-translate-y-0.5 hover:from-blue-700 hover:to-blue-800 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-200 active:translate-y-0">
                    <span> Sign In </span>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 transition-transform duration-200 group-hover:translate-x-1" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5 m0 0l-5 5 m5-5H6" />
                    </svg>
                </button>
            </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            this.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    </script>
</body>

</html>
