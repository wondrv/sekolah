@extends('layouts.app')
@section('title', $event->title)
@section('meta_description', Str::limit(strip_tags($event->description ?? ''), 160))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('events.index') }}" class="hover:text-blue-600">Agenda</a>
            <span>/</span>
            <span class="text-gray-900">{{ $event->title }}</span>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <header class="mb-8">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if($event->type)
                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">
                    {{ ucfirst($event->type) }}
                </span>
                @endif
                <span class="text-gray-500 text-sm">
                    {{ $event->starts_at->format('d F Y, H:i') }} WIB
                    @if($event->ends_at)
                        @if($event->ends_at->format('Y-m-d') == $event->starts_at->format('Y-m-d'))
                            - {{ $event->ends_at->format('H:i') }} WIB
                        @else
                            - {{ $event->ends_at->format('d F Y, H:i') }} WIB
                        @endif
                    @endif
                </span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-6">
                {{ $event->title }}
            </h1>

            <!-- Event Info Cards -->
            <div class="grid md:grid-cols-3 gap-4 mb-8">
                <!-- Date & Time -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="font-semibold text-blue-900">Waktu</h3>
                    </div>
                    <p class="text-blue-800 text-sm">
                        <strong>Mulai:</strong> {{ $event->starts_at->format('d F Y, H:i') }}<br>
                        @if($event->ends_at)
                        <strong>Selesai:</strong> {{ $event->ends_at->format('d F Y, H:i') }}
                        @endif
                    </p>
                </div>

                <!-- Location -->
                @if($event->location)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="font-semibold text-green-900">Lokasi</h3>
                    </div>
                    <p class="text-green-800 text-sm">{{ $event->location }}</p>
                </div>
                @endif

                <!-- Status -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-900">Status</h3>
                    </div>
                    <p class="text-gray-800 text-sm">
                        @if($event->starts_at > now())
                            <span class="text-yellow-600">Akan Datang</span>
                        @elseif($event->ends_at && $event->ends_at < now())
                            <span class="text-gray-600">Selesai</span>
                        @else
                            <span class="text-green-600">Sedang Berlangsung</span>
                        @endif
                    </p>
                </div>
            </div>
        </header>

        <!-- Description -->
        @if($event->description)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi</h2>
            <div class="prose max-w-none">
                {!! $event->description !!}
            </div>
        </div>
        @endif

        <!-- Additional Information -->
        @if($event->organizer || $event->contact_person || $event->contact_info)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Kontak</h2>

            @if($event->organizer)
            <div class="mb-3">
                <strong class="text-gray-700">Penyelenggara:</strong>
                <span class="text-gray-600">{{ $event->organizer }}</span>
            </div>
            @endif

            @if($event->contact_person)
            <div class="mb-3">
                <strong class="text-gray-700">Narahubung:</strong>
                <span class="text-gray-600">{{ $event->contact_person }}</span>
            </div>
            @endif

            @if($event->contact_info)
            <div class="mb-3">
                <strong class="text-gray-700">Kontak:</strong>
                <span class="text-gray-600">{{ $event->contact_info }}</span>
            </div>
            @endif
        </div>
        @endif

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('events.index') }}"
               class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Agenda
            </a>
        </div>
    </div>
</div>
@endsection
