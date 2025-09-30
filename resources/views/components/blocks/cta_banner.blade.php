@props(['block', 'data' => [], 'settings' => [], 'cssClass' => ''])

@php
    // Get content from block data
    $content = $block->content ?? [];

    $title = $content['title'] ?? $block->data['content']['title'] ?? $data['title'] ?? '';
    $subtitle = $content['subtitle'] ?? $block->data['content']['subtitle'] ?? $data['subtitle'] ?? '';
    $button_text = $content['button_text'] ?? $block->data['content']['button_text'] ?? $data['button_text'] ?? '';
    $button_url = $content['button_url'] ?? $block->data['content']['button_url'] ?? $data['button_url'] ?? '#';
    $background_color = $content['background_color'] ?? $block->data['content']['background_color'] ?? $data['background_color'] ?? 'bg-gradient-to-r from-blue-600 to-blue-800';
@endphp

<section class="cta-banner-block py-16 {{ $background_color }} text-white">
  <div class="container mx-auto px-4">
    <div class="text-center max-w-4xl mx-auto">
      @if($title)
        <h2 class="text-3xl md:text-4xl font-bold mb-6">{{ $title }}</h2>
      @endif

      @if($subtitle)
        <p class="text-xl mb-8 opacity-90">{{ $subtitle }}</p>
      @endif

      @if($button_text)
        <a href="{{ $button_url }}" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors">
          {{ $button_text }}
        </a>
      @endif
