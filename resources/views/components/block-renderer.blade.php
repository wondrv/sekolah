@props(['block'])

@php
    // Normalize type: prefer snake_case; builder may save kebab-case or snake_case
    $rawType = $block->type ?? 'unknown';
    $snake = str_replace('-', '_', $rawType);
    $kebab = str_replace('_', '-', $snake);

    // Map canonical types for existing component filenames
    $componentMap = [
        'hero' => 'hero',
        'card_grid' => 'card-grid',
        'rich_text' => 'rich-text',
        'stats' => 'stats',
        'cta_banner' => 'cta-banner',
        'gallery_teaser' => 'gallery-teaser',
        'events_teaser' => 'events-teaser',
        'posts_teaser' => 'posts-teaser',
        'announcements_teaser' => 'announcements-teaser',
    ];

    $componentKey = $componentMap[$snake] ?? null;
@endphp

@if($componentKey && view()->exists('components.blocks.' . $componentKey))
    @php
        // rich_text has two variants: rich-text & rich_text. Ensure we pass expected props.
        $viewToUse = 'components.blocks.' . $componentKey;
        $params = ['block' => $block, 'data' => $block->data];
        if ($snake === 'rich_text') {
            // Both variants exist; prefer the underscore variant for HTML heavy content
            if (view()->exists('components.blocks.rich_text')) {
                $viewToUse = 'components.blocks.rich_text';
            }
        }
    @endphp
    @include($viewToUse, $params)
@else
    @php
        // Try dynamic include if not explicitly mapped
        $tryPaths = [
            'components.blocks.' . $kebab,
            'components.blocks.' . $snake,
        ];
        $found = null;
        foreach($tryPaths as $p) {
            if(view()->exists($p)) { $found = $p; break; }
        }
    @endphp
    @if($found)
        @php
            $params = ['block' => $block, 'data' => $block->data];
        @endphp
        @include($found, $params)
    @else
        <div class="py-8 bg-gray-100 text-center border border-dashed border-gray-300 rounded">
            <p class="text-gray-500 text-sm">Unknown block type: <code>{{ $rawType }}</code></p>
        </div>
    @endif
@endif
