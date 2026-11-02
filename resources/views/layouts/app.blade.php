<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Media Platforms')
    </title>

    <link rel="icon" href="{{ asset('image/logo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    @stack('scripts')

</body>

</html>
