@extends('layouts.admin')

@section('title', 'Detail Galeri')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">Detail Galeri</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.galleries.edit', $gallery) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('admin.galleries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $gallery->name }}</h2>
                    <div class="flex items-center text-sm text-gray-500 space-x-4 mb-4">
                        <span>Status:
                            <span class="px-2 py-1 text-xs rounded-full {{ $gallery->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $gallery->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </span>
                        <span>Foto: {{ $gallery->photos->count() }} item</span>
                    </div>

                    @if($gallery->cover_image)
                        <div class="mb-6">
                            <img src="{{ Storage::url($gallery->cover_image) }}" alt="{{ $gallery->name }}" class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @endif

                    @if($gallery->description)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Deskripsi</h3>
                            <p class="text-gray-700">{{ $gallery->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Photos Grid -->
                @if($gallery->photos->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Foto dalam Galeri</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($gallery->photos as $photo)
                            <div class="bg-gray-100 rounded-lg overflow-hidden">
                                <img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->caption }}" class="w-full h-24 object-cover">
                                @if($photo->caption)
                                    <div class="p-2">
                                        <p class="text-xs text-gray-600 truncate">{{ $photo->caption }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4">Informasi Galeri</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Slug:</span>
                            <p class="text-sm text-gray-900">{{ $gallery->slug }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Urutan:</span>
                            <p class="text-sm text-gray-900">{{ $gallery->sort_order ?? 'Tidak ada' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Dibuat:</span>
                            <p class="text-sm text-gray-900">{{ $gallery->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Diupdate:</span>
                            <p class="text-sm text-gray-900">{{ $gallery->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
