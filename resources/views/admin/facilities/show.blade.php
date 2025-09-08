@extends('layouts.admin')

@section('title', 'Detail Fasilitas')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.facilities.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Detail Fasilitas</h1>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.facilities.edit', $facility) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Facility Image -->
            @if($facility->image)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}"
                         class="w-full h-64 object-cover">
                </div>
            @endif

            <!-- Facility Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $facility->name }}</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $facility->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $facility->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>

                <div class="prose max-w-none">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Deskripsi</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $facility->description }}</p>
                </div>

                @if($facility->facilities)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Fasilitas yang Tersedia</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            {!! nl2br(e($facility->facilities)) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi</h3>

                <div class="space-y-4">
                    @if($facility->capacity)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kapasitas:</span>
                            <span class="font-medium">{{ number_format($facility->capacity) }} orang</span>
                        </div>
                    @endif

                    @if($facility->location)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Lokasi:</span>
                            <span class="font-medium">{{ $facility->location }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium {{ $facility->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $facility->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="font-medium">{{ $facility->created_at->format('d M Y') }}</span>
                    </div>

                    @if($facility->updated_at != $facility->created_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Diperbarui:</span>
                            <span class="font-medium">{{ $facility->updated_at->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Technical Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Info Teknis</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Slug:</span>
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $facility->slug }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID:</span>
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $facility->id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
