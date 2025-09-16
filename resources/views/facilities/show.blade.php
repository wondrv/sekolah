@extends('layouts.app')

@section('title', $facility->name . ' - Fasilitas - ' . \App\Support\Theme::getSiteInfo()['name'])

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('facilities.index') }}" class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2">Fasilitas</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-700 md:ml-2">{{ $facility->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Facility Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $facility->name }}</h1>

            @if($facility->capacity || $facility->location)
                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                    @if($facility->capacity)
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Kapasitas: {{ $facility->capacity }}
                        </span>
                    @endif

                    @if($facility->location)
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $facility->location }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Facility Image -->
        @if($facility->image)
            <div class="mb-8">
                <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $facility->image) }}"
                         alt="{{ $facility->name }}"
                         class="w-full h-full object-cover">
                </div>
            </div>
        @endif

        <!-- Facility Description -->
        @if($facility->description)
            <div class="prose prose-lg max-w-none mb-8">
                {!! nl2br(e($facility->description)) !!}
            </div>
        @endif

        <!-- Features -->
        @if($facility->features)
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Fitur & Fasilitas</h3>
                <div class="grid md:grid-cols-2 gap-3">
                    @foreach(json_decode($facility->features, true) as $feature)
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-800">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Additional Information -->
        @if($facility->operating_hours || $facility->contact_person)
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Informasi Tambahan</h3>

                @if($facility->operating_hours)
                    <div class="mb-3">
                        <h4 class="font-medium text-gray-900 mb-1">Jam Operasional</h4>
                        <p class="text-gray-600">{{ $facility->operating_hours }}</p>
                    </div>
                @endif

                @if($facility->contact_person)
                    <div>
                        <h4 class="font-medium text-gray-900 mb-1">Penanggung Jawab</h4>
                        <p class="text-gray-600">{{ $facility->contact_person }}</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Back Button -->
        <div class="flex justify-between items-center">
            <a href="{{ route('facilities.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Fasilitas
            </a>

            <div class="flex items-center space-x-4 text-sm text-gray-500">
                @if($facility->updated_at)
                    <span>Diperbarui: {{ $facility->updated_at->format('d M Y') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
