@props(['block', 'data' => [], 'settings' => [], 'cssClass' => ''])

@php
    // Get content from block - try multiple paths
    $content = $block->content ?? [];

    // Extract hero data
    $title = $content['title'] ?? $block->data['content']['title'] ?? $data['title'] ?? '';
    $subtitle = $content['subtitle'] ?? $block->data['content']['subtitle'] ?? $data['subtitle'] ?? '';
    $background_image = $content['background_image'] ?? $block->data['content']['background_image'] ?? $data['background_image'] ?? '';
    $text_align = $content['text_align'] ?? $block->data['content']['text_align'] ?? $data['text_align'] ?? 'text-center';
    $background_color = $content['background_color'] ?? $block->data['content']['background_color'] ?? $data['background_color'] ?? 'bg-gradient-to-r from-blue-600 to-blue-800';
@endphp

<section class="hero-block relative overflow-hidden {{ $background_color }} text-white">
  @if($background_image)
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $background_image }}')"></div>
    <div class="absolute inset-0 bg-black/50"></div>
  @endif

  <div class="relative container mx-auto px-4 py-24">
    <div class="max-w-4xl {{ $text_align }} {{ $text_align === 'text-center' ? 'mx-auto' : '' }}">
      @if($title)
        <h1 class="text-4xl md:text-6xl font-bold mb-6">
          {{ $title }}
        </h1>
      @endif

      @if($subtitle)
        <p class="text-xl md:text-2xl mb-8 opacity-90">
          {{ $subtitle }}
        </p>
      @endif

      @if(isset($content['buttons']) && is_array($content['buttons']) || isset($block->data['content']['buttons']) && is_array($block->data['content']['buttons']))
        @php
            $buttons = $content['buttons'] ?? $block->data['content']['buttons'] ?? [];
        @endphp
        <div class="flex flex-wrap gap-4 {{ $text_align === 'text-center' ? 'justify-center' : '' }}">
          @foreach($buttons as $button)
            <a href="{{ $button['url'] ?? '#' }}"
               class="inline-block px-8 py-3 rounded-lg font-semibold transition-colors {{ ($button['style'] ?? '') === 'secondary' ? 'bg-white text-gray-900 hover:bg-gray-100' : 'bg-accent hover:bg-accent/90' }}">
              {{ $button['text'] ?? 'Learn More' }}
            </a>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</section>
