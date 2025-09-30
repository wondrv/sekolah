@props(['block', 'data' => [], 'settings' => [], 'cssClass' => ''])

@php
    // Get content from block data
    $content = $block->content ?? [];

    $title = $content['title'] ?? $block->data['content']['title'] ?? $data['title'] ?? '';
    $stats = $content['stats'] ?? $block->data['content']['stats'] ?? $data['stats'] ?? [];
    $background_color = $content['background_color'] ?? $block->data['content']['background_color'] ?? $data['background_color'] ?? 'bg-blue-900';
@endphp

<section class="stats-block py-16 {{ $background_color }} text-white">
  <div class="container mx-auto px-4">
    @if($title)
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">{{ $title }}</h2>
    @endif

    @if(is_array($stats) && count($stats) > 0)
      <div class="grid grid-cols-2 md:grid-cols-{{ count($stats) <= 4 ? count($stats) : 4 }} gap-8">
        @foreach($stats as $stat)
          <div class="text-center">
            @if(isset($stat['number']))
              <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stat['number'] }}</div>
            @endif
            @if(isset($stat['label']))
              <div class="text-lg opacity-90">{{ $stat['label'] }}</div>
            @endif
            @if(isset($stat['description']))
              <div class="text-sm opacity-75 mt-1">{{ $stat['description'] }}</div>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
