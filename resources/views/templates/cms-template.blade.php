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

        <!-- Default Footer if no footer section -->
        @php
            $hasFooterSection = collect($sections)->contains(function($sectionData) {
                return isset($sectionData['section']->settings['is_footer']) && $sectionData['section']->settings['is_footer'];
            });
        @endphp
        @if(!$hasFooterSection)
            <footer class="bg-gray-900 text-white py-12">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- School Info -->
                        <div class="md:col-span-2">
                            <h3 class="text-xl font-bold mb-4">{{ $context['site']['title'] }}</h3>
                            <p class="text-gray-300 mb-4">{{ $context['site']['description'] }}</p>
                            @if(isset($context['site']['address']))
                                <p class="text-gray-300 text-sm">{{ $context['site']['address'] }}</p>
                            @endif
                        </div>

                        <!-- Contact Info -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                            <div class="space-y-2 text-sm text-gray-300">
                                @if(isset($context['site']['contact_email']))
                                    <p>Email: {{ $context['site']['contact_email'] }}</p>
                                @endif
                                @if(isset($context['site']['contact_phone']))
                                    <p>Telepon: {{ $context['site']['contact_phone'] }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Media Sosial</h4>
                            <div class="flex space-x-4">
                                @if(isset($context['site']['social_links']['facebook']) && $context['site']['social_links']['facebook'])
                                    <a href="{{ $context['site']['social_links']['facebook'] }}" target="_blank" class="text-gray-300 hover:text-white">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(isset($context['site']['social_links']['instagram']) && $context['site']['social_links']['instagram'])
                                    <a href="{{ $context['site']['social_links']['instagram'] }}" target="_blank" class="text-gray-300 hover:text-white">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.321-1.297C4.198 14.926 3.708 13.775 3.708 12.478s.49-2.448 1.42-3.321c.93-.873 2.024-1.297 3.321-1.297s2.448.424 3.321 1.297c.93.873 1.42 2.024 1.42 3.321s-.49 2.448-1.42 3.321c-.873.807-2.024 1.297-3.321 1.297zm7.268-1.297c-.873.807-2.024 1.297-3.321 1.297s-2.448-.49-3.321-1.297c-.93-.873-1.42-2.024-1.42-3.321s.49-2.448 1.42-3.321c.873-.873 2.024-1.297 3.321-1.297s2.448.424 3.321 1.297c.93.873 1.42 2.024 1.42 3.321s-.49 2.448-1.42 3.321z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(isset($context['site']['social_links']['youtube']) && $context['site']['social_links']['youtube'])
                                    <a href="{{ $context['site']['social_links']['youtube'] }}" target="_blank" class="text-gray-300 hover:text-white">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                        <p>&copy; {{ date('Y') }} {{ $context['site']['title'] }}. All rights reserved.</p>
                        <p class="mt-2">Powered by School CMS</p>
                    </div>
                </div>
            </footer>
        @endif
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/app.js') }}" defer></script>

    <!-- Custom JS -->
    @if($template->layout_settings && isset($template->layout_settings['custom_js']))
        <script>
            {!! $template->layout_settings['custom_js'] !!}
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

@php
function hasFooterSection($sections) {
    foreach ($sections as $sectionData) {
        $section = $sectionData['section'];
        if (str_contains(strtolower($section->name), 'footer')) {
            return true;
        }
    }
    return false;
}
@endphp
