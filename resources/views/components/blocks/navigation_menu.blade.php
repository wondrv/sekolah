@php
    $menus = \App\Models\Menu::all();
    $selectedMenuId = $data['menu_id'] ?? null;
    $selectedMenu = $selectedMenuId ? \App\Models\Menu::find($selectedMenuId) : \App\Models\Menu::first();
    $style = $data['style'] ?? 'horizontal';
@endphp

@if($selectedMenu)
<nav 
    class="block-navigation navigation-{{ $style }} {{ $cssClass }}"
    data-block-id="{{ $block->id }}"
    data-block-type="{{ $block->type }}"
    @if(!empty($settings))
    style="@foreach($settings as $key => $value){{ $key }}: {{ $value }};@endforeach"
    @endif
>
    @if($style === 'horizontal')
    <ul class="flex space-x-6">
        @foreach($selectedMenu->items as $item)
        <li>
            <a 
                href="{{ $item->url }}" 
                class="text-gray-700 hover:text-blue-600 transition-colors"
                @if($item->target) target="{{ $item->target }}" @endif
            >
                {{ $item->label }}
            </a>
            
            @if($item->children && count($item->children) > 0)
            <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-lg mt-2 py-2 min-w-48">
                @foreach($item->children as $child)
                <li>
                    <a 
                        href="{{ $child->url }}" 
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                        @if($child->target) target="{{ $child->target }}" @endif
                    >
                        {{ $child->label }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach
    </ul>
    @else
    <ul class="space-y-2">
        @foreach($selectedMenu->items as $item)
        <li>
            <a 
                href="{{ $item->url }}" 
                class="block text-gray-700 hover:text-blue-600 transition-colors py-2"
                @if($item->target) target="{{ $item->target }}" @endif
            >
                {{ $item->label }}
            </a>
            
            @if($item->children && count($item->children) > 0)
            <ul class="ml-4 mt-2 space-y-1">
                @foreach($item->children as $child)
                <li>
                    <a 
                        href="{{ $child->url }}" 
                        class="block text-gray-600 hover:text-blue-600 transition-colors py-1"
                        @if($child->target) target="{{ $child->target }}" @endif
                    >
                        {{ $child->label }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach
    </ul>
    @endif
</nav>
@endif