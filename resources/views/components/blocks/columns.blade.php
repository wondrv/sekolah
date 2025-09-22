@php
    $columns = $data['columns'] ?? '2';
    $gap = $data['gap'] ?? 'medium';
    
    $gridClass = match($columns) {
        '1' => 'grid-cols-1',
        '3' => 'grid-cols-1 md:grid-cols-3',
        '4' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 md:grid-cols-2',
    };
    
    $gapClass = match($gap) {
        'none' => 'gap-0',
        'small' => 'gap-4',
        'large' => 'gap-12',
        default => 'gap-8',
    };
@endphp

<div 
    class="block-columns grid {{ $gridClass }} {{ $gapClass }} {{ $cssClass }}"
    data-block-id="{{ $block->id }}"
    data-block-type="{{ $block->type }}"
    @if(!empty($settings))
    style="@foreach($settings as $key => $value){{ $key }}: {{ $value }};@endforeach"
    @endif
>
    @for($i = 1; $i <= (int)$columns; $i++)
        <div class="column column-{{ $i }}">
            {!! $data["column_{$i}_content"] ?? "<p>Column {$i} content</p>" !!}
        </div>
    @endfor
</div>