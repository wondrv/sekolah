@php
    $limit = $settings['limit'] ?? 6;
    $showImages = $settings['show_images'] ?? true;
    $showDates = $settings['show_dates'] ?? true;
    $layout = $settings['layout'] ?? 'grid'; // grid or list
    
    $events = \App\Models\Event::where('date', '>=', now())
        ->orderBy('date', 'asc')
        ->take($limit)
        ->get();
    
    $title = $settings['title'] ?? 'Upcoming Events';
@endphp

<div class="py-12 bg-gray-50" id="{{ $blockId }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
            @if(isset($settings['description']) && $settings['description'])
                <p class="mt-4 text-lg text-gray-600">{{ $settings['description'] }}</p>
            @endif
        </div>

        @if($events->count() > 0)
            @if($layout === 'grid')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            @if($showImages && $event->image)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ $event->image }}" 
                                         alt="{{ $event->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            @endif

                            <div class="p-6">
                                @if($showDates)
                                    <div class="flex items-center text-sm text-blue-600 mb-3">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        <span>{{ $event->date->format('d M Y') }}</span>
                                        @if($event->time)
                                            <span class="ml-2">{{ $event->time->format('H:i') }}</span>
                                        @endif
                                    </div>
                                @endif

                                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                    <a href="{{ route('events.show', $event) }}" class="hover:text-blue-600 transition-colors">
                                        {{ $event->title }}
                                    </a>
                                </h3>

                                @if($event->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ Str::limit(strip_tags($event->description), 100) }}
                                    </p>
                                @endif

                                @if($event->location)
                                    <div class="flex items-center text-sm text-gray-500 mb-4">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                @endif

                                <a href="{{ route('events.show', $event) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Read More
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- List Layout -->
                <div class="space-y-6">
                    @foreach($events as $event)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <div class="flex flex-col md:flex-row">
                                @if($showImages && $event->image)
                                    <div class="md:w-1/3 h-48 md:h-auto overflow-hidden">
                                        <img src="{{ $event->image }}" 
                                             alt="{{ $event->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endif

                                <div class="flex-1 p-6">
                                    @if($showDates)
                                        <div class="flex items-center text-sm text-blue-600 mb-3">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            <span>{{ $event->date->format('d M Y') }}</span>
                                            @if($event->time)
                                                <span class="ml-2">{{ $event->time->format('H:i') }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                        <a href="{{ route('events.show', $event) }}" class="hover:text-blue-600 transition-colors">
                                            {{ $event->title }}
                                        </a>
                                    </h3>

                                    @if($event->description)
                                        <p class="text-gray-600 mb-4">
                                            {{ Str::limit(strip_tags($event->description), 200) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        @if($event->location)
                                            <div class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-2"></i>
                                                <span>{{ $event->location }}</span>
                                            </div>
                                        @endif

                                        <a href="{{ route('events.show', $event) }}" 
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                            Read More
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(isset($settings['show_view_all']) && $settings['show_view_all'])
                <div class="text-center mt-12">
                    <a href="{{ route('events.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        View All Events
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Upcoming Events</h3>
                <p class="text-gray-500">Check back later for upcoming events and activities.</p>
            </div>
        @endif
    </div>
</div>