@php
    $defaultStats = [
        ['number' => '1000+', 'label' => 'Students', 'icon' => 'fas fa-users'],
        ['number' => '50+', 'label' => 'Teachers', 'icon' => 'fas fa-chalkboard-teacher'],
        ['number' => '25+', 'label' => 'Years Experience', 'icon' => 'fas fa-calendar-alt'],
        ['number' => '95%', 'label' => 'Success Rate', 'icon' => 'fas fa-trophy']
    ];
    $stats = $settings['stats'] ?? $defaultStats;
@endphp

<div class="py-16 bg-blue-600" id="{{ $blockId }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($settings['title']) && $settings['title'])
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white">{{ $settings['title'] }}</h2>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($stats as $stat)
                <div class="text-center text-white">
                    @if(isset($stat['icon']) && $stat['icon'])
                        <div class="text-4xl mb-4 text-blue-200">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                    @endif
                    
                    <div class="text-4xl lg:text-5xl font-bold mb-2">
                        {{ $stat['number'] ?? '0' }}
                    </div>
                    
                    <div class="text-lg text-blue-100">
                        {{ $stat['label'] ?? 'Statistic' }}
                    </div>
                    
                    @if(isset($stat['description']) && $stat['description'])
                        <div class="text-sm text-blue-200 mt-2">
                            {{ $stat['description'] }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>