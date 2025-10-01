@props(['data' => []])

@php
$siteTitle = $data['site_title'] ?? 'School CMS';
$menuItems = $data['menu_items'] ?? [];
$logoText = $data['logo_text'] ?? $siteTitle;
$backgroundColor = $data['background_color'] ?? 'bg-white';
$textColor = $data['text_color'] ?? 'text-gray-800';
$sticky = $data['sticky'] ?? true;
@endphp

<nav class="{{ $backgroundColor }} {{ $textColor }} shadow-md {{ $sticky ? 'sticky top-0 z-50' : '' }}">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <h1 class="text-xl font-bold">{{ $logoText }}</h1>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6">
                @foreach($menuItems as $item)
                    <a href="{{ $item['url'] ?? '#' }}"
                       target="{{ $item['target'] ?? '_self' }}"
                       class="hover:text-blue-600 transition-colors duration-200">
                        {{ $item['title'] ?? 'Menu' }}
                    </a>
                @endforeach
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-btn" class="focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            @foreach($menuItems as $item)
                <a href="{{ $item['url'] ?? '#' }}"
                   target="{{ $item['target'] ?? '_self' }}"
                   class="block py-2 hover:text-blue-600 transition-colors duration-200">
                    {{ $item['title'] ?? 'Menu' }}
                </a>
            @endforeach
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});
</script>
