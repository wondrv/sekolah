@php
    $limit = $settings['limit'] ?? 6;
    $showImages = $settings['show_images'] ?? true;
    $showDates = $settings['show_dates'] ?? true;
    $showExcerpt = $settings['show_excerpt'] ?? true;
    $layout = $settings['layout'] ?? 'grid'; // grid or list
    $category = $settings['category_id'] ?? null;
    
    $query = \App\Models\Post::published()->orderBy('created_at', 'desc');
    
    if ($category) {
        $query->where('category_id', $category);
    }
    
    $posts = $query->take($limit)->get();
    
    $title = $settings['title'] ?? 'Latest News';
@endphp

<div class="py-12 bg-white" id="{{ $blockId }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
            @if(isset($settings['description']) && $settings['description'])
                <p class="mt-4 text-lg text-gray-600">{{ $settings['description'] }}</p>
            @endif
        </div>

        @if($posts->count() > 0)
            @if($layout === 'grid')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            @if($showImages && $post->featured_image)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ $post->featured_image }}" 
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            @endif

                            <div class="p-6">
                                @if($showDates)
                                    <div class="flex items-center text-sm text-gray-500 mb-3">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        <time datetime="{{ $post->created_at->toDateString() }}">
                                            {{ $post->created_at->format('d M Y') }}
                                        </time>
                                        @if($post->category)
                                            <span class="mx-2">•</span>
                                            <span class="text-blue-600">{{ $post->category->name }}</span>
                                        @endif
                                    </div>
                                @endif

                                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                    <a href="{{ route('posts.show', $post) }}" class="hover:text-blue-600 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                @if($showExcerpt && $post->excerpt)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ Str::limit($post->excerpt, 100) }}
                                    </p>
                                @elseif($showExcerpt && $post->content)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ Str::limit(strip_tags($post->content), 100) }}
                                    </p>
                                @endif

                                <a href="{{ route('posts.show', $post) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Read More
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <!-- List Layout -->
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <div class="flex flex-col md:flex-row">
                                @if($showImages && $post->featured_image)
                                    <div class="md:w-1/3 h-48 md:h-auto overflow-hidden">
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endif

                                <div class="flex-1 p-6">
                                    @if($showDates)
                                        <div class="flex items-center text-sm text-gray-500 mb-3">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            <time datetime="{{ $post->created_at->toDateString() }}">
                                                {{ $post->created_at->format('d M Y') }}
                                            </time>
                                            @if($post->category)
                                                <span class="mx-2">•</span>
                                                <span class="text-blue-600">{{ $post->category->name }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                        <a href="{{ route('posts.show', $post) }}" class="hover:text-blue-600 transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>

                                    @if($showExcerpt && $post->excerpt)
                                        <p class="text-gray-600 mb-4">
                                            {{ Str::limit($post->excerpt, 200) }}
                                        </p>
                                    @elseif($showExcerpt && $post->content)
                                        <p class="text-gray-600 mb-4">
                                            {{ Str::limit(strip_tags($post->content), 200) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-sm text-gray-500">
                                            @if($post->author)
                                                <i class="fas fa-user mr-2"></i>
                                                <span>{{ $post->author->name }}</span>
                                            @endif
                                        </div>

                                        <a href="{{ route('posts.show', $post) }}" 
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                            Read More
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            @if(isset($settings['show_view_all']) && $settings['show_view_all'])
                <div class="text-center mt-12">
                    <a href="{{ route('posts.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        View All News
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No News Available</h3>
                <p class="text-gray-500">Check back later for the latest news and updates.</p>
            </div>
        @endif
    </div>
</div>