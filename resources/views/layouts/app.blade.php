<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tailwind CSS -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    <!-- Alpine.js (required for hero pop animation) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-dvh flex flex-col font-[var(--font-family)]">
<a href="#main" class="sr-only focus:not-sr-only">Lewati ke konten</a>

{{-- Dynamic Navigation --}}
@include('components.nav')

<main id="main" class="flex-1">
    @yield('content')
</main>

{{-- Dynamic Footer --}}
@include('components.footer')

@stack('scripts')
</body>
</html>
