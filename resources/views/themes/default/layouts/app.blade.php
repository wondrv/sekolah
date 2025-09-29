<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- SEO Meta Tags -->
    @if(isset($metaDescription))
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    @if(isset($metaKeywords))
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif

    <!-- Open Graph Meta Tags -->
    @if(isset($ogTitle))
        <meta property="og:title" content="{{ $ogTitle }}">
    @endif
    @if(isset($ogDescription))
        <meta property="og:description" content="{{ $ogDescription }}">
    @endif
    @if(isset($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/app.js') }}" defer></script>

    <!-- Theme Custom Styles -->
    <style>
        :root {
            --color-primary: {{ theme_setting('colors.primary', '#3b82f6') }};
            --color-secondary: {{ theme_setting('colors.secondary', '#64748b') }};
            --color-accent: {{ theme_setting('colors.accent', '#f59e0b') }};
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        @include('themes.default.partials.navigation')

        <!-- Page Header -->
        @if(isset($showPageHeader) && $showPageHeader)
            @include('themes.default.partials.page-header')
        @endif

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        @include('themes.default.partials.footer')
    </div>

    @stack('scripts')
</body>
</html>
