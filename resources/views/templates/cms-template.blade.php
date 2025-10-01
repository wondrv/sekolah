<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamic Meta Tags -->
    <title>{{ $context['page_title'] ?? $context['site']['title'] }}</title>
    <meta name="description" content="{{ $context['meta_description'] ?? $context['site']['description'] }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $context['page_title'] ?? $context['site']['title'] }}">
    <meta property="og:description" content="{{ $context['meta_description'] ?? $context['site']['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    @if(isset($context['site']['logo']))
        <meta property="og:image" content="{{ asset($context['site']['logo']) }}">
    @endif

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $context['page_title'] ?? $context['site']['title'] }}">
    <meta name="twitter:description" content="{{ $context['meta_description'] ?? $context['site']['description'] }}">

    <!-- Favicon -->
    @if(isset($context['site']['favicon']))
        <link rel="icon" type="image/x-icon" href="{{ asset($context['site']['favicon']) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    <!-- Inject custom CSS from active user template customizations (if available) -->
    @php
        // Attempt to resolve active user template (passed via context or relationship)
        $activeUserTemplate = $template->userTemplate ?? null;
        $customCss = $activeUserTemplate->customizations['css'] ?? null;
        // Backwards compatibility: some imports place css under layout_settings
        if(!$customCss && ($template->layout_settings['custom_css'] ?? false)) {
            $customCss = $template->layout_settings['custom_css'];
        }
    @endphp
    @if(!empty($customCss))
        <style id="user-template-custom-css">
            {!! $customCss !!}
        </style>
    @endif

    <!-- Custom CSS from template settings -->
    <style>
        :root {
            --primary-color: {{ setting('primary_color', '#3B82F6') }};
            --secondary-color: {{ setting('secondary_color', '#10B981') }};
            --accent-color: {{ setting('accent_color', '#F59E0B') }};
            --text-color: {{ setting('text_color', '#1F2937') }};
            --background-color: {{ setting('background_color', '#FFFFFF') }};
        }

        /* Template-specific styles */
        @if($template->layout_settings && isset($template->layout_settings['custom_css']))
            {!! $template->layout_settings['custom_css'] !!}
        @endif
    </style>

    <!-- Additional head content -->
    @stack('head')
</head>

<body class="font-sans antialiased bg-gray-50" style="background-color: var(--background-color); color: var(--text-color);">
    <div class="min-h-screen">
        <!-- Template Sections -->
        @foreach($sections as $sectionData)
            @php
                $section = $sectionData['section'];
                $blocks = $sectionData['blocks'];
                $sectionSettings = $section->settings ?? [];
            @endphp

            <section
                class="template-section section-{{ $section->id }} {{ $sectionSettings['css_class'] ?? '' }}"
                @if(isset($sectionSettings['background_color']))
                    style="background-color: {{ $sectionSettings['background_color'] }}"
                @elseif(isset($sectionSettings['background']))
                    @if($sectionSettings['background'] === 'primary')
                        style="background-color: var(--primary-color)"
                    @elseif($sectionSettings['background'] === 'secondary')
                        style="background-color: var(--secondary-color)"
                    @elseif($sectionSettings['background'] === 'light')
                        style="background-color: #F9FAFB"
                    @elseif($sectionSettings['background'] === 'dark')
                        style="background-color: #1F2937; color: white"
                    @endif
                @endif
            >
                @if(isset($sectionSettings['container']) && $sectionSettings['container'] === false)
                    <!-- Full width section -->
                    @foreach($blocks as $blockHtml)
                        {!! $blockHtml !!}
                    @endforeach
                @else
                    <!-- Contained section -->
                    <div class="container mx-auto px-4 py-8 lg:py-12">
                        @foreach($blocks as $blockHtml)
                            {!! $blockHtml !!}
                        @endforeach
                    </div>
                @endif
            </section>
        @endforeach

        <!-- Footer sections are now handled by template system -->
        {{-- All sections including footer are now template-driven --}}
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/app.js') }}" defer></script>

    <!-- Custom JS (template layout settings) -->
    @if($template->layout_settings && isset($template->layout_settings['custom_js']))
        <script id="template-layout-custom-js">
            {!! $template->layout_settings['custom_js'] !!}
        </script>
    @endif

    <!-- Inject custom JS from active user template customizations (if available) -->
    @php
        $customJs = $activeUserTemplate->customizations['javascript'] ?? null;
    @endphp
    @if(!empty($customJs))
        <script id="user-template-custom-js">
            {!! $customJs !!}
        </script>
    @endif

    @stack('scripts')

    <!-- Analytics -->
    @if(setting('google_analytics_id'))
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ setting('google_analytics_id') }}');
        </script>
    @endif
</body>

 </html>
