@props(['block', 'data' => [], 'settings' => [], 'cssClass' => ''])

@php
    // Get content from block - try multiple paths
    $content = $block->content ?? [];
    $html = '';
    $title = '';

    // Try to get HTML from different sources
    if (isset($content['html'])) {
        $html = $content['html'];
    } elseif (isset($block->data['content']['html'])) {
        $html = $block->data['content']['html'];
    } elseif (isset($data['html'])) {
        $html = $data['html'];
    }

    // Try to get title
    if (isset($content['title'])) {
        $title = $content['title'];
    } elseif (isset($block->data['content']['title'])) {
        $title = $block->data['content']['title'];
    } elseif (isset($data['title'])) {
        $title = $data['title'];
    }

    // Check if HTML contains full section markup (like our SMAMITA template)
    $isFullSection = strpos($html, '<div class="container') !== false ||
                    strpos($html, '<nav ') !== false ||
                    strpos($html, '<footer ') !== false ||
                    strpos($html, 'id="about"') !== false ||
                    strpos($html, 'id="news"') !== false ||
                    strpos($html, 'id="program"') !== false;
@endphp

@if($html)
    @if($isFullSection)
        {{-- Render full HTML sections without wrapper --}}
        <div class="rich-text-full-section">
            {!! $html !!}
        </div>
    @else
        {{-- Traditional rich text with wrapper --}}
        <section class="rich-text-block {{ $cssClass }}">
            @if($title)
                <div class="container mx-auto px-4 mb-4">
                    <h2 class="text-3xl font-bold">{{ $title }}</h2>
                </div>
            @endif

            <div class="rich-text-content">
                {!! $html !!}
            </div>
        </section>
    @endif
@else
    <!-- Debug: No HTML content found -->
    <div class="p-4 text-gray-500 text-sm">
        Block: {{ $block->name ?? 'Unnamed' }} ({{ $block->type }}) - No HTML content found
        <br>Content keys: {{ is_array($content) ? implode(', ', array_keys($content)) : 'not array' }}
        <br>Data keys: {{ $block->data && is_array($block->data) ? implode(', ', array_keys($block->data)) : 'none' }}
    </div>
@endif
