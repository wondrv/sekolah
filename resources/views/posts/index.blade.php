@extends('layouts.app')
@section('title', 'Berita & Artikel')
@section('meta_description', 'Berita terbaru dan artikel dari sekolah')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-400 to-orange-500 pt-24 pb-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-2xl font-bold mb-2 text-red-600">BERITA</h1>
            <h2 class="text-3xl font-normal text-gray-800">Berita & Artikel</h2>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Category Filters -->
        @if($categories->count() > 0)
        <div class="mb-8 px-4">
            <div class="flex flex-wrap gap-2 justify-center max-w-5xl mx-auto">
                <button onclick="filterPosts('all')"
                   class="category-filter px-3 py-2 rounded-full text-xs font-medium transition-all duration-200 bg-orange-500 text-black hover:bg-orange-600 whitespace-nowrap"
                   data-category="all">
                    All Posts
                </button>
                @foreach($categories as $category)
                <button onclick="filterPosts('{{ $category->slug }}')"
                   class="category-filter px-3 py-2 rounded-full text-xs font-medium transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-orange-100 hover:text-orange-600 whitespace-nowrap"
                   data-category="{{ $category->slug }}">
                    {{ Str::limit($category->name, 20) }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Posts List -->
        @if($posts->count() > 0)
        <div class="max-w-4xl mx-auto" id="posts-container">
            @foreach($posts as $post)
            <article class="post-item mb-8 pb-8 border-b border-gray-200 last:border-b-0"
                     data-category="{{ $post->category ? $post->category->slug : 'uncategorized' }}">

                <!-- Post Title -->
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 hover:text-orange-600 transition-colors mb-3">
                    <a href="{{ route('posts.show', $post->slug) }}">
                        {{ $post->title }}
                    </a>
                </h2>

                <!-- Post Meta -->
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                    <span>{{ $post->published_at->format('F j, Y') }}</span>
                    <span>/</span>
                    <a href="{{ route('posts.show', $post->slug) }}#respond" class="hover:text-orange-600">
                        No Comments
                    </a>
                </div>

                <!-- Post Excerpt -->
                <div class="text-gray-700 mb-4 leading-relaxed">
                    @if($post->excerpt)
                        {{ Str::limit($post->excerpt, 200) }}
                    @else
                        {{ Str::limit(strip_tags($post->body), 200) }}
                    @endif
                </div>

                <!-- Read More Link -->
                <a href="{{ route('posts.show', $post->slug) }}"
                   class="inline-flex items-center text-orange-600 hover:text-orange-700 font-medium">
                    Read More
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            {{ $posts->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Belum Ada Berita</h3>
                <p class="text-gray-600 mb-8">Berita dan artikel akan segera ditampilkan di sini.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-orange-500 text-black font-medium rounded-full hover:bg-orange-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            btn.className = 'category-filter px-3 py-2 rounded-full text-xs font-medium transition-all duration-200 bg-orange-500 text-black hover:bg-orange-600 whitespace-nowrap';
        } else {
            btn.className = 'category-filter px-3 py-2 rounded-full text-xs font-medium transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-orange-100 hover:text-orange-600 whitespace-nowrap';
        }
    });

    // Filter posts with smooth animation
    posts.forEach((post, index) => {
        if (category === 'all' || post.dataset.category === category) {
            post.style.display = 'block';
            post.style.opacity = '0';

            // Staggered animation
            setTimeout(() => {
                post.style.transition = 'opacity 0.3s ease-out';
                post.style.opacity = '1';
            }, index * 50);
        } else {
            post.style.transition = 'opacity 0.2s ease-out';
            post.style.opacity = '0';
            setTimeout(() => {
                post.style.display = 'none';
            }, 200);
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
