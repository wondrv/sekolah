@props(['block', 'content', 'settings', 'style_settings'])

@php
    // Merge content and settings for backward compatibility
    $data = array_merge($content ?? [], $settings ?? [], $style_settings ?? []);
@endphp

<section class="rich-text-block py-16 {{ $data['background_color'] ?? 'bg-white' }}">
  <div class="container mx-auto px-4">
    <div class="max-w-{{ $data['max_width'] ?? '4xl' }} mx-auto">
      @if(isset($data['title']))
        <h2 class="text-3xl md:text-4xl font-bold mb-8 {{ $data['text_align'] ?? 'text-center' }}">
          {{ $data['title'] }}
        </h2>
      @endif

      @if(isset($data['content']))
        <div class="prose prose-lg max-w-none {{ $data['text_align'] ?? 'text-left' }}">
          {!! $data['content'] !!}
        </div>
      @endif
    </div>
  </div>
</section>
