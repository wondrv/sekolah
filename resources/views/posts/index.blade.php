@extends('layouts.app')
@section('title', 'Berita Sekolah')
@section('meta_description', 'Berita terbaru dan artikel dari sekolah')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Berita Sekolah</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Ikuti perkembangan terbaru dan kegiatan menarik di sekolah kami</p>
    </div>

    <!-- Filters -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-4 justify-between items-center">
            <!-- Search -->
            <form method="GET" class="flex gap-2">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari berita..."
                    value="{{ request('search') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Cari
                </button>
            </form>

            <!-- Categories -->
            @if($categories->count() > 0)
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('posts.index') }}"
                   class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                <a href="{{ route('posts.index', ['category' => $category->slug]) }}"
                   class="px-4 py-2 rounded-full {{ request('category') == $category->slug ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    {{ $category->name }} ({{ $category->posts_count }})
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Posts Grid -->
    @if($posts->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($posts as $post)
        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            @if($post->featured_image)
            <div class="aspect-video bg-gray-200">
                <img src="{{ asset('storage/' . $post->featured_image) }}"
                     alt="{{ $post->title }}"
                     class="w-full h-full object-cover">
            </div>
            @else
            <div class="aspect-video bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500">Gambar tidak tersedia</span>
            </div>
            @endif

            <div class="p-6">
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                    @if($post->category)
                    <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded">
                        {{ $post->category->name }}
                    </span>
                    @endif
                    <span>{{ $post->published_at->format('d M Y') }}</span>
                </div>

                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                    <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-blue-600">
                        {{ $post->title }}
                    </a>
                </h3>

                @if($post->excerpt)
                <p class="text-gray-600 mb-4">{{ Str::limit($post->excerpt, 120) }}</p>
                @endif

                <a href="{{ route('posts.show', $post->slug) }}"
                   class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    Baca Selengkapnya
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </article>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $posts->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <div class="text-gray-500 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Berita</h3>
        <p class="text-gray-600">Berita akan segera ditampilkan di sini.</p>
    </div>
    @endif
</div>
@endsection
