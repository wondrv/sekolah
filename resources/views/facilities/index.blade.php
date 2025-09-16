@extends('layouts.app')

@section('title', 'Fasilitas - ' . \App\Support\Theme::getSiteInfo()['name'])

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Fasilitas Sekolah</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Fasilitas modern dan lengkap untuk mendukung kegiatan belajar mengajar yang optimal
            </p>
        </div>

        <!-- Facilities Grid -->
        @if($facilities->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($facilities as $facility)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        @if($facility->image)
                            <div class="h-48 bg-gray-200 overflow-hidden">
                                <img src="{{ asset('storage/' . $facility->image) }}"
                                     alt="{{ $facility->name }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $facility->name }}</h3>

                            @if($facility->capacity)
                                <p class="text-sm text-blue-600 mb-2">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Kapasitas: {{ $facility->capacity }}
                                    </span>
                                </p>
                            @endif

                            <p class="text-gray-600 text-sm mb-4">
                                {{ Str::limit($facility->description, 120) }}
                            </p>

                            @if($facility->features)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(json_decode($facility->features, true) as $feature)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <a href="{{ route('facilities.show', $facility->slug) }}"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                Lihat Detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Fasilitas</h3>
                    <p class="text-gray-500">Informasi fasilitas akan segera ditambahkan.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
