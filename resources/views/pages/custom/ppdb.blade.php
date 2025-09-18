@extends('layouts.app')
@section('title', $page->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($page->body), 160))

@section('content')
@php
  $brochure = \App\Models\Setting::get('ppdb_brochure');
  $brochureUrl = \App\Models\Setting::get('ppdb_brochure_url');
  $download = $brochureUrl ?: ($brochure ? asset('storage/' . $brochure) : null);
@endphp

<div class="container mx-auto px-4 py-8">
  <header class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $page->title }}</h1>
    @if($download)
      <a href="{{ $download }}" target="_blank"
         class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        Unduh Brosur (PDF)
      </a>
    @endif
  </header>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar / Anchors -->
    <aside class="lg:col-span-1">
      <nav class="sticky top-24 space-y-2 text-sm">
        <a href="#brosur" class="block text-gray-700 hover:text-blue-600">Brosur</a>
        <a href="#biaya" class="block text-gray-700 hover:text-blue-600">Biaya Pendaftaran</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="lg:col-span-3">
      <div class="prose prose-lg max-w-none">
        {!! $page->body !!}
      </div>
    </main>
  </div>
</div>
@endsection
