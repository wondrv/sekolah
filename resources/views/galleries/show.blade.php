@extends('layouts.app')
@section('title', $gallery->title)
@section('meta_description', $gallery->description ?? 'Galeri foto ' . $gallery->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('galleries.index') }}" class="hover:text-blue-600">Galeri</a>
            <span>/</span>
            <span class="text-gray-900">{{ $gallery->title }}</span>
        </div>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $gallery->title }}</h1>

        @if($gallery->description)
        <p class="text-gray-600 max-w-3xl">{{ $gallery->description }}</p>
        @endif

        <div class="flex items-center gap-4 mt-4 text-sm text-gray-500">
            <span>{{ $gallery->created_at->format('d F Y') }}</span>
            <span>•</span>
            <span>{{ $gallery->photos->count() }} foto</span>
        </div>
    </div>

    <!-- Photos Grid -->
    @if($gallery->photos->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
        @foreach($gallery->photos as $photo)
        <div class="group relative aspect-square bg-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300">
            <img src="{{ asset('storage/' . $photo->image_path) }}"
                 alt="{{ $photo->title ?? $gallery->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer"
                 onclick="openLightbox('{{ asset('storage/' . $photo->image_path) }}', '{{ $photo->title ?? $gallery->title }}', '{{ $photo->description ?? '' }}')">

            @if($photo->title)
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <p class="text-white text-sm font-medium">{{ $photo->title }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <div class="text-gray-500 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Foto</h3>
        <p class="text-gray-600">Foto akan segera ditambahkan ke galeri ini.</p>
    </div>
    @endif

    <!-- Back Button -->
    <div class="text-center">
        <a href="{{ route('galleries.index') }}"
           class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Galeri
        </a>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <!-- Close button -->
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Image -->
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain">

        <!-- Caption -->
        <div id="lightbox-caption" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
            <h3 id="lightbox-title" class="text-white font-semibold text-lg"></h3>
            <p id="lightbox-description" class="text-gray-300 text-sm mt-1"></p>
        </div>
    </div>
</div>

<script>
function openLightbox(imageSrc, title, description) {
    const lightbox = document.getElementById('lightbox');
    const image = document.getElementById('lightbox-image');
    const titleEl = document.getElementById('lightbox-title');
    const descriptionEl = document.getElementById('lightbox-description');
    const caption = document.getElementById('lightbox-caption');

    image.src = imageSrc;
    image.alt = title;
    titleEl.textContent = title;
    descriptionEl.textContent = description;

    // Hide caption if no title or description
    if (!title && !description) {
        caption.style.display = 'none';
    } else {
        caption.style.display = 'block';
    }

    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close lightbox on click outside image
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Close lightbox on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>
@endsection
