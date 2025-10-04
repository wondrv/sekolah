@extends('layouts.main')
@section('title','Galeri Foto')
@section('content')
<h2 class="text-3xl font-bold text-blue-900 mb-4">Galeri Kegiatan</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  <img src="{{ asset('images/galeri1.jpg') }}" alt="Kegiatan 1" class="rounded-lg shadow hover:opacity-80 transition">
  <img src="{{ asset('images/galeri2.jpg') }}" alt="Kegiatan 2" class="rounded-lg shadow hover:opacity-80 transition">
  <img src="{{ asset('images/news1.jpg') }}" alt="Kegiatan 3" class="rounded-lg shadow hover:opacity-80 transition">
</div>
@endsection
