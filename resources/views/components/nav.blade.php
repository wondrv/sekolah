@php
$siteInfo = App\Support\Theme::getSiteInfo();
$menuItems = App\Support\Theme::getMenu('primary');
@endphp

<header class="border-b bg-white/80 backdrop-blur">
  <nav class="container mx-auto px-4 py-3 flex items-center justify-between">
    <a href="/" class="flex items-center gap-2">
      <img src="{{ asset($siteInfo['logo']) }}" alt="Logo" class="h-10">
      <span class="font-semibold">{{ $siteInfo['name'] }}</span>
    </a>

    @if($menuItems && $menuItems->count() > 0)
    <ul class="hidden md:flex items-center gap-6">
      @foreach($menuItems as $item)
        <li class="group relative">
          <a href="{{ $item->url }}"
             target="{{ $item->is_external ? '_blank' : '_self' }}"
             class="hover:underline">
            {{ $item->label }}
          </a>

          @if($item->children->count() > 0)
            <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-1 py-2 min-w-48 z-50">
              @foreach($item->children as $child)
                <li>
                  <a href="{{ $child->url }}"
                     target="{{ $child->is_external ? '_blank' : '_self' }}"
                     class="block px-4 py-2 hover:bg-gray-100">
                    {{ $child->label }}
                  </a>
                </li>
              @endforeach
            </ul>
          @endif
        </li>
      @endforeach
    </ul>
    @else
    {{-- Fallback navigation --}}
    <ul class="hidden md:flex items-center gap-6">
      <li><a href="/profil" class="hover:underline">Profil</a></li>
      <li><a href="/berita" class="hover:underline">Berita</a></li>
      <li><a href="/agenda" class="hover:underline">Agenda</a></li>
      <li><a href="/galeri" class="hover:underline">Galeri</a></li>
      <li><a href="/kontak" class="hover:underline">Kontak</a></li>
    </ul>
    @endif

    {{-- Mobile menu button --}}
    <button class="md:hidden" onclick="toggleMobileMenu()">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>
  </nav>

  {{-- Mobile menu --}}
  <div id="mobile-menu" class="md:hidden hidden border-t bg-white">
    @if($menuItems && $menuItems->count() > 0)
      @foreach($menuItems as $item)
        <a href="{{ $item->url }}"
           target="{{ $item->is_external ? '_blank' : '_self' }}"
           class="block px-4 py-3 border-b hover:bg-gray-50">
          {{ $item->label }}
        </a>
      @endforeach
    @endif
  </div>
</header>

<script>
function toggleMobileMenu() {
  const menu = document.getElementById('mobile-menu');
  menu.classList.toggle('hidden');
}
</script>
