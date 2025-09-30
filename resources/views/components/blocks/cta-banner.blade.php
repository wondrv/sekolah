@props(['block', 'content', 'settings', 'style_settings'])

@php
    // Merge content and settings for backward compatibility
    $data = array_merge($content ?? [], $settings ?? [], $style_settings ?? []);
@endphp

@php $anchor = $data['anchor'] ?? null; @endphp
<section @if($anchor) id="{{ $anchor }}" @endif class="cta-banner-block py-16 {{ $data['background_color'] ?? 'bg-gradient-to-r from-blue-600 to-blue-800' }} text-white">
  <div class="container mx-auto px-4">
    <div class="text-center max-w-4xl mx-auto">
      @if(isset($data['title']))
        <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ $data['title'] }}</h2>
      @endif

      @if(isset($data['subtitle']))
        <p class="text-xl mb-8 opacity-90">{{ $data['subtitle'] }}</p>
      @endif

      @if(isset($data['button']))
        <a href="{{ $data['button']['url'] ?? '#' }}"
           class="inline-block px-8 py-4 bg-white text-blue-900 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors">
          {{ $data['button']['text'] ?? 'Get Started' }}
        </a>
      @endif
    </div>
  </div>
</section>
