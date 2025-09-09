@extends('layouts.admin')

@section('title', 'Detail Berita')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">Detail Berita</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.posts.edit', $post) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('admin.posts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $post->title }}</h2>
                    <div class="flex items-center text-sm text-gray-500 space-x-4 mb-4">
                        <span>Kategori: {{ $post->category->name ?? 'Tidak ada kategori' }}</span>
                        <span>Status:
                            <span class="px-2 py-1 text-xs rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </span>
                        <span>Dipublish: {{ $post->published_at ? $post->published_at->format('d M Y') : 'Belum dipublish' }}</span>
                    </div>

                    @if($post->cover_image)
                        <div class="mb-6">
                            <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @endif

                    @if($post->excerpt)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Ringkasan</h3>
                            <p class="text-gray-700">{{ $post->excerpt }}</p>
                        </div>
                    @endif

                    <div class="prose max-w-none">
                        <h3 class="text-lg font-semibold mb-2">Konten</h3>
                        {!! nl2br(e($post->body)) !!}
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4">Informasi Post</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Slug:</span>
                            <p class="text-sm text-gray-900">{{ $post->slug }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Penulis:</span>
                            <p class="text-sm text-gray-900">{{ $post->user->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Dibuat:</span>
                            <p class="text-sm text-gray-900">{{ $post->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Diupdate:</span>
                            <p class="text-sm text-gray-900">{{ $post->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
