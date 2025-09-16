@php
$siteInfo = App\Support\Theme::getSiteInfo();
$menuItems = App\Support\Theme::getMenu('header') ?? App\Support\Theme::getMenu('primary');
$headerSettings = App\Support\Theme::getSettings(['header_logo_position', 'header_sticky', 'header_transparent', 'header_bg_color', 'header_text_color', 'social_show_in_header']);
@endphp

@php
$headerClasses = 'border-b';
if ($headerSettings['header_sticky'] ?? false) $headerClasses .= ' sticky top-0 z-50';
if ($headerSettings['header_transparent'] ?? false) $headerClasses .= ' bg-white/90 backdrop-blur-md';
else $headerClasses .= ' bg-white';
@endphp

<header class="{{ $headerClasses }}" style="background-color: {{ $headerSettings['header_bg_color'] ?? '#ffffff' }}; color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">
  <nav class="container mx-auto px-4 py-3" x-data="{ mobileMenuOpen: false }">
    <div class="flex items-center {{ ($headerSettings['header_logo_position'] ?? 'left') === 'center' ? 'justify-center' : 'justify-between' }}">

      <!-- Logo Section -->
      <div class="flex items-center gap-3 {{ ($headerSettings['header_logo_position'] ?? 'left') === 'center' ? 'flex-col text-center' : '' }}">
        <a href="/" class="flex items-center gap-2">
          @if($siteInfo['logo'])
            <img src="{{ asset($siteInfo['logo']) }}" alt="Logo {{ $siteInfo['name'] }}" class="h-10 w-auto object-contain">
          @endif
          <div class="flex flex-col">
            <span class="font-bold text-lg leading-tight" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">
              {{ $siteInfo['name'] ?? 'Nama Sekolah' }}
            </span>
            @if(isset($siteInfo['tagline']) && $siteInfo['tagline'])
              <span class="text-xs opacity-75" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">
                {{ $siteInfo['tagline'] }}
              </span>
            @endif
          </div>
        </a>
      </div>

      @if(($headerSettings['header_logo_position'] ?? 'left') !== 'center')
        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-8">
          @if($menuItems && $menuItems->count() > 0)
            @foreach($menuItems as $item)
              <div class="group relative">
                <a href="{{ $item->url ?? '#' }}"
                   target="{{ $item->target ?? '_self' }}"
                   class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-black/5"
                   style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">
                  {{ $item->title ?? 'Menu' }}
                  @if($item->children->count() > 0)
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  @endif
                </a>

                @if($item->children->count() > 0)
                  <div class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="py-1">
                      @foreach($item->children as $child)
                        <a href="{{ $child->url ?? '#' }}"
                           target="{{ $child->target ?? '_self' }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                          {{ $child->title ?? 'Submenu' }}
                        </a>
                      @endforeach
                    </div>
                  </div>
                @endif
              </div>
            @endforeach
          @else
            <!-- Fallback navigation -->
            <a href="/profil" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Profil</a>
            <a href="/berita" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Berita</a>
            <a href="/agenda" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Agenda</a>
            <a href="/galeri" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Galeri</a>
            <a href="/kontak" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Kontak</a>
          @endif

          <!-- Social Media Links (if enabled) -->
          @if($headerSettings['social_show_in_header'] ?? false)
            <div class="flex items-center space-x-3 ml-6 border-l border-gray-300 pl-6">
              @if(isset($siteInfo['social']['facebook']) && $siteInfo['social']['facebook'])
                <a href="{{ $siteInfo['social']['facebook'] }}" target="_blank" class="text-blue-600 hover:text-blue-700 transition-colors">
                  <span class="sr-only">Facebook</span>
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                  </svg>
                </a>
              @endif
              @if(isset($siteInfo['social']['instagram']) && $siteInfo['social']['instagram'])
                <a href="{{ $siteInfo['social']['instagram'] }}" target="_blank" class="text-pink-600 hover:text-pink-700 transition-colors">
                  <span class="sr-only">Instagram</span>
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.297-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.807.875 1.297 2.026 1.297 3.323s-.49 2.448-1.297 3.323c-.875.807-2.026 1.297-3.323 1.297z"/>
                  </svg>
                </a>
              @endif
              @if(isset($siteInfo['social']['youtube']) && $siteInfo['social']['youtube'])
                <a href="{{ $siteInfo['social']['youtube'] }}" target="_blank" class="text-red-600 hover:text-red-700 transition-colors">
                  <span class="sr-only">YouTube</span>
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                  </svg>
                </a>
              @endif
            </div>
          @endif
        </div>

        <!-- Mobile menu button -->
        <div class="lg:hidden">
          <button @click="mobileMenuOpen = !mobileMenuOpen"
                  class="p-2 rounded-md hover:bg-black/5 transition-colors"
                  style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>
      @endif
    </div>

    <!-- Mobile Navigation Menu -->
    @if(($headerSettings['header_logo_position'] ?? 'left') !== 'center')
      <div x-show="mobileMenuOpen"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95"
           class="lg:hidden mt-4 pb-4 border-t border-gray-200">
        <div class="space-y-2 pt-4">
          @if($menuItems && $menuItems->count() > 0)
            @foreach($menuItems as $item)
              <div>
                <a href="{{ $item->url ?? '#' }}"
                   target="{{ $item->target ?? '_self' }}"
                   class="block px-3 py-2 rounded-md text-base font-medium hover:bg-black/5 transition-colors"
                   style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">
                  {{ $item->title ?? 'Menu' }}
                </a>
                @if($item->children->count() > 0)
                  <div class="ml-4 space-y-1">
                    @foreach($item->children as $child)
                      <a href="{{ $child->url ?? '#' }}"
                         target="{{ $child->target ?? '_self' }}"
                         class="block px-3 py-2 text-sm hover:bg-black/5 transition-colors rounded-md"
                         style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">
                        {{ $child->title ?? 'Submenu' }}
                      </a>
                    @endforeach
                  </div>
                @endif
              </div>
            @endforeach
          @else
            <a href="/profil" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Profil</a>
            <a href="/berita" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Berita</a>
            <a href="/agenda" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Agenda</a>
            <a href="/galeri" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Galeri</a>
            <a href="/kontak" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-black/5 transition-colors" style="color: {{ $headerSettings['header_text_color'] ?? '#000000' }};">Kontak</a>
          @endif
        </div>
      </div>
    @endif
  </nav>
</header>

<!-- Centered Navigation (when logo is centered) -->
@if(($headerSettings['header_logo_position'] ?? 'left') === 'center')
  <nav class="bg-gray-50 border-b">
    <div class="container mx-auto px-4 py-2">
      <div class="flex justify-center">
        <div class="hidden lg:flex items-center space-x-8">
          @if($menuItems && $menuItems->count() > 0)
            @foreach($menuItems as $item)
              <div class="group relative">
                <a href="{{ $item->url }}"
                   target="{{ $item->is_external ? '_blank' : '_self' }}"
                   class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200">
                  {{ $item->label }}
                  @if($item->children->count() > 0)
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  @endif
                </a>

                @if($item->children->count() > 0)
                  <div class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="py-1">
                      @foreach($item->children as $child)
                        <a href="{{ $child->url }}"
                           target="{{ $child->is_external ? '_blank' : '_self' }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                          {{ $child->label }}
                        </a>
                      @endforeach
                    </div>
                  </div>
                @endif
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
  </nav>
@endif

