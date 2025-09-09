@extends('layouts.app')
@section('title', 'Berita & Artikel')
@section('meta_description', 'Berita terbaru dan artikel dari sekolah')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-black py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">BERITA</h1>
            <div class="w-24 h-1 bg-white mx-auto mb-6"></div>
            <h2 class="text-2xl md:text-3xl font-semibold mb-4">Berita & Artikel</h2>
            <p class="text-blue-100 max-w-2xl mx-auto text-lg">Ikuti perkembangan terbaru dan kegiatan menarik di sekolah kami</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Category Filters -->
        @if($categories->count() > 0)
        <div class="mb-8">
            <div class="flex flex-wrap gap-2 justify-center lg:justify-start">
                <button onclick="filterPosts('all')"
                   class="category-filter px-6 py-3 rounded-lg font-medium transition-colors bg-blue-600 text-white"
                   data-category="all">
                    All Posts
                </button>
                @foreach($categories as $category)
                <button onclick="filterPosts('{{ $category->slug }}')"
                   class="category-filter px-6 py-3 rounded-lg font-medium transition-colors bg-white text-gray-700 hover:bg-gray-100 border border-gray-300"
                   data-category="{{ $category->slug }}">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Search Bar -->
        <div class="mb-8">
            <form method="GET" class="max-w-md mx-auto lg:mx-0">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari berita atau artikel..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <span class="bg-blue-600 text-white px-4 py-1 rounded-md text-sm hover:bg-blue-700 transition-colors">
                            Cari
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Posts List -->
        @if($posts->count() > 0)
        <div class="space-y-8" id="posts-container">
            @foreach($posts as $post)
            <article class="post-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300"
                     data-category="{{ $post->category ? $post->category->slug : 'uncategorized' }}">
                <div class="md:flex">
                    @if($post->cover_path)
                    <div class="md:w-1/3">
                        <img src="{{ asset('storage/' . $post->cover_path) }}"
                             alt="{{ $post->title }}"
                             class="w-full h-48 md:h-full object-cover">
                    </div>
                    <div class="md:w-2/3 p-6">
                    @else
                    <div class="p-6">
                    @endif
                        <!-- Article Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                                <a href="{{ route('posts.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>
                        </div>

                        <!-- Meta Information -->
                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $post->published_at->format('F j, Y') }}
                            </span>
                            @if($post->category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $post->category->name }}
                            </span>
                            @endif
                            <span class="text-blue-600 hover:text-blue-800">
                                <a href="{{ route('posts.show', $post->slug) }}#respond">No Comments</a>
                            </span>
                        </div>

                        <!-- Excerpt -->
                        @if($post->excerpt)
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            {{ Str::limit($post->excerpt, 200) }}
                        </p>
                        @else
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            {{ Str::limit(strip_tags($post->body), 200) }}
                        </p>
                        @endif

                        <!-- Read More Button -->
                        <a href="{{ route('posts.show', $post->slug) }}"
                           class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors">
                            Read More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            {{ $posts->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <div class="bg-white rounded-lg shadow-md p-12 max-w-md mx-auto">
                <div class="text-gray-400 mb-6">
                    <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-3">Belum Ada Berita</h3>
                <p class="text-gray-600 mb-6">Berita dan artikel akan segera ditampilkan di sini.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function filterPosts(category) {
    // Get all post items
    const posts = document.querySelectorAll('.post-item');
    const buttons = document.querySelectorAll('.category-filter');

    // Update button styles
    buttons.forEach(btn => {
        if (btn.dataset.category === category) {
            btn.className = 'category-filter px-6 py-3 rounded-lg font-medium transition-colors bg-blue-600 text-white';
        } else {
            btn.className = 'category-filter px-6 py-3 rounded-lg font-medium transition-colors bg-white text-gray-700 hover:bg-gray-100 border border-gray-300';
        }
    });

    // Filter posts
    posts.forEach(post => {
        if (category === 'all' || post.dataset.category === category) {
            post.style.display = 'block';
            // Add fade-in animation
            post.style.opacity = '0';
            setTimeout(() => {
                post.style.transition = 'opacity 0.3s ease-in-out';
                post.style.opacity = '1';
            }, 100);
        } else {
            post.style.display = 'none';
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial active state for "All Posts" button
    filterPosts('all');
});
</script>
@endsection
