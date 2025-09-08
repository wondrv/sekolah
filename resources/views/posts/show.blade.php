@extends('layouts.app')
@section('title', $post->title)
@section('meta_description', $post->excerpt ?? Str::limit(strip_tags($post->body), 160))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('posts.index') }}" class="hover:text-blue-600">Berita</a>
            <span>/</span>
            <span class="text-gray-900">{{ $post->title }}</span>
        </div>
    </nav>

    <article class="max-w-4xl mx-auto">
        <!-- Header -->
        <header class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                @if($post->category)
                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full">
                    {{ $post->category->name }}
                </span>
                @endif
                <span>{{ $post->published_at->format('d F Y') }}</span>
                @if($post->user)
                <span>•</span>
                <span>Oleh {{ $post->user->name }}</span>
                @endif
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
            <p class="text-xl text-gray-600 mt-4 leading-relaxed">
                {{ $post->excerpt }}
            </p>
            @endif
        </header>

        <!-- Featured Image -->
        @if($post->featured_image)
        <div class="mb-8">
            <div class="aspect-video rounded-lg overflow-hidden">
                <img src="{{ asset('storage/' . $post->featured_image) }}"
                     alt="{{ $post->title }}"
                     class="w-full h-full object-cover">
            </div>
        </div>
        @endif

        <!-- Content -->
        <div class="prose prose-lg max-w-none">
            {!! $post->body !!}
        </div>

        <!-- Footer -->
        <footer class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div class="text-sm text-gray-500">
                    Dipublish {{ $post->published_at->format('d F Y') }}
                    @if($post->updated_at > $post->created_at)
                    • Diperbarui {{ $post->updated_at->format('d F Y') }}
                    @endif
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('posts.index') }}"
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        ← Kembali ke Berita
                    </a>
                </div>
            </div>
        </footer>
    </article>

    <!-- Related Posts -->
    @if($relatedPosts && $relatedPosts->count() > 0)
    <aside class="max-w-4xl mx-auto mt-16">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Berita Lainnya</h3>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedPosts as $relatedPost)
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                @if($relatedPost->featured_image)
                <div class="aspect-video bg-gray-200">
                    <img src="{{ asset('storage/' . $relatedPost->featured_image) }}"
                         alt="{{ $relatedPost->title }}"
                         class="w-full h-full object-cover">
                </div>
                @else
                <div class="aspect-video bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">Gambar tidak tersedia</span>
                </div>
                @endif

                <div class="p-4">
                    <div class="text-sm text-gray-500 mb-2">
                        {{ $relatedPost->published_at->format('d M Y') }}
                    </div>

                    <h4 class="font-semibold text-gray-900 mb-2">
                        <a href="{{ route('posts.show', $relatedPost->slug) }}" class="hover:text-blue-600">
                            {{ Str::limit($relatedPost->title, 60) }}
                        </a>
                    </h4>

                    @if($relatedPost->excerpt)
                    <p class="text-gray-600 text-sm">{{ Str::limit($relatedPost->excerpt, 100) }}</p>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
    </aside>
    @endif
</div>
@endsection
