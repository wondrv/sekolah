@extends('layouts.app')
@section('title','Berita Sekolah')
@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-12">Berita Sekolah</h1>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @for ($i = 1; $i <= 6; $i++)
        <article class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="aspect-video bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500">Berita {{ $i }}</span>
            </div>
            <div class="p-6">
                <div class="text-sm text-gray-500 mb-2">{{ now()->subDays(rand(1, 30))->format('d M Y') }}</div>
                <h3 class="text-xl font-semibold mb-3">Judul Berita Sekolah {{ $i }}</h3>
                <p class="text-gray-600 mb-4">Ringkasan berita yang memberikan gambaran singkat tentang isi artikel...</p>
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Baca selengkapnya →</a>
            </div>
        </article>
        @endfor
    </div>

    <div class="flex justify-center mt-12">
        <nav class="flex space-x-2">
            <a href="#" class="px-3 py-2 bg-blue-600 text-white rounded">1</a>
            <a href="#" class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">2</a>
            <a href="#" class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">3</a>
        </nav>
    </div>
</div>
@endsection
