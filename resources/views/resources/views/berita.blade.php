@extends('layouts.main')
@section('title','Berita Sekolah')
@section('content')
<h2 class="text-3xl font-bold text-blue-900 mb-4">Berita & Artikel</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-white rounded shadow-md overflow-hidden">
    <img src="{{ asset('images/news1.jpg') }}" alt="Berita 1" class="w-full h-48 object-cover">
    <div class="p-4">
      <h3 class="text-lg font-semibold text-blue-900 mb-2">SMA Harapan Nusantara Juara Olimpiade</h3>
      <p class="text-gray-600 text-sm">Dipublikasikan: 12 Desember 2024</p>
    </div>
  </div>
</div>
@endsection
