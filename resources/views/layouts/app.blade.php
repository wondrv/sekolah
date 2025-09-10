<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', App\Support\Theme::getSiteInfo()['name'])</title>
  <meta name="description" content="@yield('meta_description', App\Support\Theme::getSiteInfo()['description'])">

  @if(isset($ogImage))
  <meta property="og:image" content="{{ $ogImage }}">
  @endif
  <meta property="og:title" content="@yield('title', App\Support\Theme::getSiteInfo()['name'])">
  <meta property="og:description" content="@yield('meta_description', App\Support\Theme::getSiteInfo()['description'])">

  <link rel="icon" href="{{ App\Support\Theme::getSiteInfo()['favicon'] }}">
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

  <style>
    :root {
      @php $colors = App\Support\Theme::getThemeColors(); @endphp
      --color-primary: {{ $colors['primary'] }};
      --color-secondary: {{ $colors['secondary'] }};
      --color-accent: {{ $colors['accent'] }};
      --color-success: {{ $colors['success'] }};
      --color-warning: {{ $colors['warning'] }};
      --color-error: {{ $colors['error'] }};

      @php $typography = App\Support\Theme::getTypography(); @endphp
      --font-family: {{ $typography['font_family'] }};
      --font-size-base: {{ $typography['font_size_base'] }};
      --line-height-base: {{ $typography['line_height_base'] }};
      --font-weight-normal: {{ $typography['font_weight_normal'] }};
      --font-weight-semibold: {{ $typography['font_weight_semibold'] }};
      --font-weight-bold: {{ $typography['font_weight_bold'] }};
    }
  </style>
  @stack('head')
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
