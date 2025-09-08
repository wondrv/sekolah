@extends('layouts.app')
@section('title','Galeri Foto')
@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-12">Galeri Foto</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @for ($i = 1; $i <= 9; $i++)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="aspect-video bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500">Album {{ $i }}</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold mb-2">Album Kegiatan {{ $i }}</h3>
                <p class="text-gray-600 text-sm mb-3">Dokumentasi kegiatan sekolah dan prestasi siswa.</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">{{ rand(10, 50) }} foto</span>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat →</a>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>
@endsection
