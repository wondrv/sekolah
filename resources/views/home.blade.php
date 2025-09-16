@extends('layouts.app')
@section('title','Selamat Datang di Nama Sekolah')
@section('meta_description','Website resmi Nama Sekolah: tentang kita, berita, agenda.')
@section('content')
<section class="relative">
  <div class="container mx-auto px-4 py-16 grid lg:grid-cols-2 gap-10 items-center">
    <div>
      <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">Sekolah Unggul, Berkarakter, Berprestasi</h1>
      <p class="mt-4 text-slate-600">Portal informasi sekolah dengan berita terbaru dan agenda kegiatan.</p>
      <div class="mt-6 flex gap-3">
        <a href="/berita" class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Berita Terbaru</a>
        <a href="/agenda" class="px-5 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">Agenda Kegiatan</a>
      </div>
    </div>
    <div class="aspect-video bg-slate-100 rounded-lg flex items-center justify-center overflow-hidden">
      <img src="{{ asset('images/sekolah.png') }}" alt="Foto Sekolah" class="object-cover w-full h-full">
    </div>
  </div>
</section>

<section class="bg-gray-50 py-16">
  <div class="container mx-auto px-4">
    <div class="grid md:grid-cols-3 gap-8">
      <div class="text-center">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-2">Fasilitas Lengkap</h3>
        <p class="text-gray-600">Gedung modern, laboratorium lengkap, dan sarana pendukung pembelajaran terbaik.</p>
      </div>
      <div class="text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-2">Akreditasi A</h3>
        <p class="text-gray-600">Terakreditasi A dari Badan Akreditasi Nasional Sekolah/Madrasah.</p>
      </div>
      <div class="text-center">
        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-2">Guru Berpengalaman</h3>
        <p class="text-gray-600">Tenaga pendidik profesional dan berpengalaman di bidangnya.</p>
      </div>
    </div>
  </div>
</section>

<section class="container mx-auto px-4 py-16">
  <h2 class="text-3xl font-bold text-center mb-12">Berita Terbaru</h2>
  <div class="grid md:grid-cols-3 gap-8">
    {{-- Sample news cards - will be dynamic from database --}}
    @for ($i = 1; $i <= 3; $i++)
    <article class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="aspect-video bg-gray-200 flex items-center justify-center">
        <span class="text-gray-500">News Image {{ $i }}</span>
      </div>
      <div class="p-6">
        <h3 class="text-xl font-semibold mb-2">Judul Berita {{ $i }}</h3>
        <p class="text-gray-600 mb-4">Ringkasan berita singkat yang menjelaskan inti dari artikel berita ini...</p>
        <a href="/berita/sample-{{ $i }}" class="text-blue-600 hover:text-blue-800 font-medium">Baca selengkapnya →</a>
      </div>
    </article>
    @endfor
  </div>
  <div class="text-center mt-8">
    <a href="/berita" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
      Lihat Semua Berita
    </a>
  </div>
</section>

<section class="bg-gray-50 py-16">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12">Agenda Terdekat</h2>
    <div class="max-w-4xl mx-auto">
      {{-- Sample agenda timeline - will be dynamic from database --}}
      @for ($i = 1; $i <= 4; $i++)
      <div class="flex gap-4 pb-8 border-l-2 border-blue-200 ml-4 last:border-l-0">
        <div class="w-8 h-8 bg-blue-600 rounded-full flex-shrink-0 -ml-5 mt-1"></div>
        <div>
          <div class="text-sm text-gray-500 mb-1">{{ now()->addDays($i)->format('d M Y') }}</div>
          <h3 class="text-lg font-semibold mb-1">Agenda Kegiatan {{ $i }}</h3>
          <p class="text-gray-600">Deskripsi singkat tentang kegiatan yang akan dilaksanakan.</p>
        </div>
      </div>
      @endfor
    </div>
    <div class="text-center mt-8">
      <a href="/agenda" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
        Lihat Semua Agenda
      </a>
    </div>
  </div>
</section>
@endsection
