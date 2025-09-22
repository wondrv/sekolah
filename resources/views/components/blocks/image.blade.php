@php
    $src = $data['src'] ?? '';
    $alt = $data['alt'] ?? '';
    $caption = $data['caption'] ?? '';
    $size = $data['size'] ?? 'medium';
    
    $sizeClass = match($size) {
        'small' => 'max-w-sm',
        'large' => 'max-w-4xl',
        'full' => 'w-full',
        default => 'max-w-2xl',
    };
@endphp

<div 
    class="block-image {{ $sizeClass }} mx-auto {{ $cssClass }}"
    data-block-id="{{ $block->id }}"
    data-block-type="{{ $block->type }}"
    @if(!empty($settings))
    style="@foreach($settings as $key => $value){{ $key }}: {{ $value }};@endforeach"
    @endif
>
    @if($src)
    <figure>
        <img 
            src="{{ $src }}" 
            alt="{{ $alt }}"
            class="w-full h-auto rounded-lg shadow-md"
            loading="lazy"
        >
        @if($caption)
        <figcaption class="mt-3 text-sm text-gray-600 text-center">
            {{ $caption }}
        </figcaption>
        @endif
    </figure>
    @else
    <div class="bg-gray-200 rounded-lg aspect-video flex items-center justify-center">
        <div class="text-center text-gray-500">
            <i class="fas fa-image text-4xl mb-2"></i>
            <p>No image selected</p>
        </div>
    </div>
    @endif
</div>