@php
    $gallery = null;
    if (isset($settings['gallery_id']) && $settings['gallery_id']) {
        $gallery = \App\Models\Gallery::with('photos')->find($settings['gallery_id']);
    }
    $columns = $settings['columns'] ?? 3;
    $showCaptions = $settings['show_captions'] ?? false;
@endphp

<div class="py-12 bg-gray-50" id="{{ $blockId }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($settings['title']) && $settings['title'])
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">{{ $settings['title'] }}</h2>
            </div>
        @endif

        @if($gallery && $gallery->photos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-{{ $columns }} gap-6">
                @foreach($gallery->photos as $photo)
                    <div class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <img src="{{ $photo->file_path }}" 
                             alt="{{ $photo->alt_text ?? $photo->title }}"
                             class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                        
                        @if($showCaptions && ($photo->title || $photo->description))
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-4 left-4 right-4 text-white">
                                    @if($photo->title)
                                        <h3 class="font-semibold text-lg">{{ $photo->title }}</h3>
                                    @endif
                                    @if($photo->description)
                                        <p class="text-sm text-gray-200 mt-1">{{ $photo->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($gallery->photos->count() > 12)
                <div class="text-center mt-8">
                    <a href="{{ route('galleries.show', $gallery) }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        View Full Gallery
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-images"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Gallery Selected</h3>
                <p class="text-gray-500">Please select a gallery to display images.</p>
            </div>
        @endif
    </div>
</div>