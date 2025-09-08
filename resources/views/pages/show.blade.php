@extends('layouts.app')
@section('title', $page->title)
@section('meta_description', Str::limit(strip_tags($page->body), 160))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span>/</span>
            <span class="text-gray-900">{{ $page->title }}</span>
        </div>
    </nav>

    <!-- Page Header -->
    <header class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
    </header>    <!-- Page Content -->
    <div class="max-w-4xl mx-auto">
        @if($page->featured_image)
        <div class="mb-8">
            <div class="aspect-video rounded-lg overflow-hidden">
                <img src="{{ asset('storage/' . $page->featured_image) }}"
                     alt="{{ $page->title }}"
                     class="w-full h-full object-cover">
            </div>
        </div>
        @endif

        <div class="prose prose-lg max-w-none">
            {!! $page->body !!}
        </div>

        <!-- Page Footer -->
        @if($page->updated_at > $page->created_at)
        <footer class="mt-12 pt-8 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Terakhir diperbarui: {{ $page->updated_at->format('d F Y') }}
            </div>
        </footer>
        @endif
    </div>
</div>
@endsection
