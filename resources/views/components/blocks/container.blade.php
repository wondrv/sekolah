@php
    $containerClass = match($data['width'] ?? 'container') {
        'full' => 'w-full',
        'narrow' => 'max-w-4xl mx-auto',
        default => 'container mx-auto',
    };
    
    $paddingClass = match($data['padding'] ?? 'medium') {
        'none' => '',
        'small' => 'p-4',
        'large' => 'p-12',
        default => 'p-8',
    };
@endphp

<div 
    class="block-container {{ $containerClass }} {{ $paddingClass }} {{ $cssClass }}"
    data-block-id="{{ $block->id }}"
    data-block-type="{{ $block->type }}"
    @if(!empty($settings))
    style="@foreach($settings as $key => $value){{ $key }}: {{ $value }};@endforeach"
    @endif
>
    {!! $data['content'] ?? '<p>Container block</p>' !!}
</div>