@extends('layouts.app')
@section('title', 'Galeri Foto')
@section('meta_description', 'Galeri foto kegiatan dan momen penting di sekolah')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Galeri Foto</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Dokumentasi kegiatan dan momen berkesan di sekolah kami</p>
    </div>

    <!-- Galleries Grid -->
    @if($galleries->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($galleries as $gallery)
        <div class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <a href="{{ route('galleries.show', $gallery->slug) }}">
                @if($gallery->photos->count() > 0)
                <div class="aspect-video bg-gray-200 relative overflow-hidden">
                    <img src="{{ asset('storage/' . $gallery->photos->first()->image_path) }}"
                         alt="{{ $gallery->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                    <!-- Photo count overlay -->
                    <div class="absolute top-3 right-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded text-sm">
                        {{ $gallery->photos_count }} foto
                    </div>
                </div>
                @else
                <div class="aspect-video bg-gray-200 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm">Belum ada foto</span>
                    </div>
                </div>
                @endif
            </a>

            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-blue-600">
                    <a href="{{ route('galleries.show', $gallery->slug) }}">
                        {{ $gallery->title }}
                    </a>
                </h3>

                @if($gallery->description)
                <p class="text-gray-600 mb-4">{{ Str::limit($gallery->description, 100) }}</p>
                @endif

                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>{{ $gallery->created_at->format('d M Y') }}</span>
                    <span>{{ $gallery->photos_count }} foto</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $galleries->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <div class="text-gray-500 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Galeri</h3>
        <p class="text-gray-600">Galeri foto akan segera ditampilkan di sini.</p>
    </div>
    @endif
</div>
@endsection
