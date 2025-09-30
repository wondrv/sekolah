<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', App\Support\Theme::getSiteInfo()['name'])</title>
  <meta name="description" content="@yield('meta_description', App\Support\Theme::getSiteInfo()['description'])">
  <meta name="keywords" content="{{ App\Support\Theme::getSiteInfo()['keywords'] }}">
  @if(!empty($isPreviewMode))
  <meta name="robots" content="noindex,nofollow">
  @endif

  @if(isset($ogImage))
  <meta property="og:image" content="{{ $ogImage }}">
  @endif
  <meta property="og:title" content="@yield('title', App\Support\Theme::getSiteInfo()['name'])">
  <meta property="og:description" content="@yield('meta_description', App\Support\Theme::getSiteInfo()['description'])">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ request()->url() }}">

  <link rel="icon" href="{{ asset(App\Support\Theme::getSiteInfo()['favicon']) }}">
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

  <!-- Alpine.js for small interactivity (navbar, dropdowns) -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  @php
    $colors = App\Support\Theme::getThemeColors();
    $typography = App\Support\Theme::getTypography();
  @endphp

  <style>
    [x-cloak] { display: none !important; }
    :root {
      /* Color System */
      --color-primary: {{ $colors['primary'] }};
      --color-secondary: {{ $colors['secondary'] }};
      --color-accent: {{ $colors['accent'] }};
      --color-success: {{ $colors['success'] }};
      --color-warning: {{ $colors['warning'] }};
      --color-error: {{ $colors['error'] }};

      /* Typography System */
      --font-family: '{{ $typography['font_family'] }}', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      --font-size-base: {{ $typography['font_size_base'] }};

      /* SMAMITA Template Colors */
      --smamita-primary: #005A9C;
      --smamita-secondary: #FDB813;
      --smamita-dark: #333;
    }

    /* SMAMITA Template Specific Styles */
    .rich-text-full-section .text-primary {
      color: var(--smamita-primary) !important;
    }

    .rich-text-full-section .bg-primary {
      background-color: var(--smamita-primary) !important;
    }

    .rich-text-full-section .btn-primary {
      background-color: var(--smamita-primary) !important;
      border-color: var(--smamita-primary) !important;
    }

    .rich-text-full-section .btn-primary:hover {
      background-color: #004080 !important;
      border-color: #004080 !important;
    }

    .rich-text-full-section .text-warning {
      color: var(--smamita-secondary) !important;
    }

    /* Ensure proper body padding for fixed navbar */
    body.has-fixed-nav {
      padding-top: 76px;
    }

    /* Font Awesome for icons */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
      --line-height-base: {{ $typography['line_height_base'] }};
      --font-weight-normal: {{ $typography['font_weight_normal'] }};
      --font-weight-semibold: {{ $typography['font_weight_semibold'] }};
      --font-weight-bold: {{ $typography['font_weight_bold'] }};

      /* Design System */
      --border-radius: {{ $typography['border_radius'] }};
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);

      /* Spacing System */
      --spacing-xs: 0.25rem;
      --spacing-sm: 0.5rem;
      --spacing-md: 1rem;
      --spacing-lg: 1.5rem;
      --spacing-xl: 2rem;
      --spacing-2xl: 3rem;
    }

    /* Base Typography */
    body {
      font-family: var(--font-family);
      font-size: var(--font-size-base);
      line-height: var(--line-height-base);
      font-weight: var(--font-weight-normal);
    }

    /* Custom Component Styles */
    .btn-primary {
      background-color: var(--color-primary);
      border-color: var(--color-primary);
      border-radius: var(--border-radius);
      color: white;
      padding: 0.5rem 1rem;
      font-weight: var(--font-weight-semibold);
      transition: all 0.2s ease;
    }

    .btn-primary:hover {
      filter: brightness(0.9);
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
    }

    .btn-secondary {
      background-color: var(--color-secondary);
      border-color: var(--color-secondary);
      border-radius: var(--border-radius);
      color: white;
      padding: 0.5rem 1rem;
      font-weight: var(--font-weight-semibold);
      transition: all 0.2s ease;
    }

    .btn-accent {
      background-color: var(--color-accent);
      border-color: var(--color-accent);
      border-radius: var(--border-radius);
      color: white;
      padding: 0.5rem 1rem;
      font-weight: var(--font-weight-semibold);
      transition: all 0.2s ease;
    }

    /* Card Styles */
    .card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow-sm);
      transition: all 0.2s ease;
    }

    .card:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }

    /* Link Styles */
    .link-primary {
      color: var(--color-primary);
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .link-primary:hover {
      color: var(--color-primary);
      filter: brightness(0.8);
      text-decoration: underline;
    }

    /* Text Colors */
    .text-primary { color: var(--color-primary); }
    .text-secondary { color: var(--color-secondary); }
    .text-accent { color: var(--color-accent); }
    .text-success { color: var(--color-success); }
    .text-warning { color: var(--color-warning); }
    .text-error { color: var(--color-error); }

    /* Background Colors */
    .bg-primary { background-color: var(--color-primary); }
    .bg-secondary { background-color: var(--color-secondary); }
    .bg-accent { background-color: var(--color-accent); }
    .bg-success { background-color: var(--color-success); }
    .bg-warning { background-color: var(--color-warning); }
    .bg-error { background-color: var(--color-error); }

    /* Border Colors */
    .border-primary { border-color: var(--color-primary); }
    .border-secondary { border-color: var(--color-secondary); }
    .border-accent { border-color: var(--color-accent); }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: var(--border-radius);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--color-primary);
      border-radius: var(--border-radius);
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--color-secondary);
    }

    /* Loading Skeleton */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
      border-radius: var(--border-radius);
    }

    @keyframes loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }

    /* Responsive Font Scaling */
    @media (max-width: 768px) {
      :root {
        --font-size-base: 14px;
      }
    }

    /* High Contrast Mode Support */
    @media (prefers-contrast: high) {
      :root {
        --color-primary: #000080;
        --color-secondary: #333333;
        --color-accent: #cc6600;
      }
    }

    /* Dark Mode Support (for future implementation) */
    @media (prefers-color-scheme: dark) {
      .card {
        background: #1f2937;
        color: #f9fafb;
      }
    }
  </style>

  @stack('head')
</head>
<body class="min-h-screen flex flex-col antialiased has-fixed-nav" style="background-color: var(--background-color); color: var(--text-color); font-family: var(--font-family);">
  <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-md z-50">
    Lewati ke konten utama
  </a>

  {{-- Dynamic Navigation --}}
  @include('components.nav')

  <main id="main" class="flex-1">
    @yield('content')
  </main>

  {{-- Dynamic Footer --}}
  @include('components.footer')

  <!-- Back to Top Button -->
  <button id="backToTop"
          class="fixed bottom-4 right-4 bg-primary text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-secondary z-40"
          style="background-color: var(--color-primary);">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
    </svg>
  </button>

  <!-- Loading Indicator -->
  <div id="pageLoader" class="fixed inset-0 bg-white bg-opacity-90 flex items-center justify-center z-50 hidden">
    <div class="flex items-center space-x-2">
      <div class="w-4 h-4 bg-primary rounded-full animate-bounce"></div>
      <div class="w-4 h-4 bg-primary rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
      <div class="w-4 h-4 bg-primary rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
    </div>
  </div>

  <script>
    // Back to Top functionality
    const backToTopButton = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        backToTopButton.classList.remove('opacity-0', 'invisible');
        backToTopButton.classList.add('opacity-100', 'visible');
      } else {
        backToTopButton.classList.add('opacity-0', 'invisible');
        backToTopButton.classList.remove('opacity-100', 'visible');
      }
    });

    backToTopButton.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Page loading indicator
    document.addEventListener('DOMContentLoaded', () => {
      const loader = document.getElementById('pageLoader');

      // Show loader on page transitions
      document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (link && link.href && !link.href.includes('#') && !link.target === '_blank') {
          loader.classList.remove('hidden');
        }
      });

      // Hide loader when page loads
      window.addEventListener('load', () => {
        loader.classList.add('hidden');
      });
    });

    // Lazy loading for images
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.remove('skeleton');
            observer.unobserve(img);
          }
        });
      });

      document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
      });
    }

    // Enhanced accessibility
    document.addEventListener('keydown', (e) => {
      // Skip to main content with Alt+M
      if (e.altKey && e.key === 'm') {
        document.getElementById('main').focus();
      }

      // Focus visible dropdown menu items with arrow keys
      if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        const focusedElement = document.activeElement;
        const dropdown = focusedElement.closest('.group');
        if (dropdown) {
          const menuItems = dropdown.querySelectorAll('a');
          const currentIndex = Array.from(menuItems).indexOf(focusedElement);
          let nextIndex = currentIndex;

          if (e.key === 'ArrowDown') {
            nextIndex = currentIndex < menuItems.length - 1 ? currentIndex + 1 : 0;
          } else {
            nextIndex = currentIndex > 0 ? currentIndex - 1 : menuItems.length - 1;
          }

          menuItems[nextIndex].focus();
          e.preventDefault();
        }
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
