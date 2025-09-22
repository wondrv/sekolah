<div class="relative bg-gradient-to-r from-blue-600 to-blue-800 overflow-hidden" id="{{ $blockId }}">
    @if(isset($settings['image']) && $settings['image'])
        <div class="absolute inset-0">
            <img src="{{ $settings['image'] }}" alt="Hero Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        </div>
    @endif

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center {{ $settings['alignment'] ?? 'center' === 'left' ? 'text-left' : ($settings['alignment'] ?? 'center' === 'right' ? 'text-right' : 'text-center') }}">
            @if(isset($settings['subtitle']) && $settings['subtitle'])
                <p class="text-blue-200 text-lg font-medium mb-4">{{ $settings['subtitle'] }}</p>
            @endif

            @if(isset($settings['title']) && $settings['title'])
                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">
                    {{ $settings['title'] }}
                </h1>
            @endif

            @if(isset($settings['description']) && $settings['description'])
                <p class="text-xl text-blue-100 mb-8 max-w-3xl {{ $settings['alignment'] ?? 'center' === 'center' ? 'mx-auto' : '' }}">
                    {{ $settings['description'] }}
                </p>
            @endif

            @if(isset($settings['button_text']) && $settings['button_text'] && isset($settings['button_url']) && $settings['button_url'])
                <div class="space-x-4">
                    <a href="{{ $settings['button_url'] }}" 
                       class="inline-flex items-center px-8 py-3 border border-transparent text-lg font-medium rounded-md text-blue-900 bg-white hover:bg-blue-50 transition-colors duration-200">
                        {{ $settings['button_text'] }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Optional overlay pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent pointer-events-none"></div>
</div>