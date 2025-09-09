@extends('layouts.admin')

@section('title', 'Detail Halaman')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">Detail Halaman</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.pages.edit', $page) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('admin.pages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $page->title }}</h2>
                    <div class="flex items-center text-sm text-gray-500 space-x-4 mb-4">
                        <span>Status:
                            <span class="px-2 py-1 text-xs rounded-full {{ $page->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $page->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </span>
                        <span>Urutan: {{ $page->sort_order ?? 'Tidak ada' }}</span>
                    </div>

                    @if($page->excerpt)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Ringkasan</h3>
                            <p class="text-gray-700">{{ $page->excerpt }}</p>
                        </div>
                    @endif

                    <div class="prose max-w-none">
                        <h3 class="text-lg font-semibold mb-2">Konten</h3>
                        {!! nl2br(e($page->content)) !!}
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4">Informasi Halaman</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Slug:</span>
                            <p class="text-sm text-gray-900">{{ $page->slug }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tipe:</span>
                            <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $page->page_type)) }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Dibuat:</span>
                            <p class="text-sm text-gray-900">{{ $page->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Diupdate:</span>
                            <p class="text-sm text-gray-900">{{ $page->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
