@extends('layouts.app')
@section('title', 'Agenda Kegiatan')
@section('meta_description', 'Agenda kegiatan dan acara sekolah terbaru')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Agenda Kegiatan</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Jadwal kegiatan dan acara penting di sekolah kami</p>
    </div>

    <!-- Filters -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-4 justify-center">
            <form method="GET" class="flex flex-wrap gap-2">
                <!-- Type Filter -->
                <select name="type" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="akademik" {{ request('type') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="ekstrakurikuler" {{ request('type') == 'ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                    <option value="umum" {{ request('type') == 'umum' ? 'selected' : '' }}>Umum</option>
                </select>

                <!-- Month Filter -->
                <select name="month" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                    @endfor
                </select>

                <!-- Year Filter -->
                <select name="year" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year <= date('Y') + 2; $year++)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>

                @if(request()->hasAny(['type', 'month', 'year']))
                <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Reset Filter
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Events List -->
    @if($events->count() > 0)
    <div class="space-y-6 mb-8">
        @foreach($events as $event)
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Date -->
                <div class="flex-shrink-0">
                    <div class="bg-blue-600 text-white rounded-lg p-4 text-center min-w-[100px]">
                        <div class="text-2xl font-bold">{{ $event->starts_at->format('d') }}</div>
                        <div class="text-sm">{{ $event->starts_at->format('M Y') }}</div>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        @if($event->type)
                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm">
                            {{ ucfirst($event->type) }}
                        </span>
                        @endif
                        <span class="text-gray-500 text-sm">
                            {{ $event->starts_at->format('H:i') }}
                            @if($event->ends_at && $event->ends_at->format('Y-m-d') == $event->starts_at->format('Y-m-d'))
                            - {{ $event->ends_at->format('H:i') }}
                            @elseif($event->ends_at)
                            - {{ $event->ends_at->format('d M Y H:i') }}
                            @endif
                        </span>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <a href="{{ route('events.show', $event) }}" class="hover:text-blue-600">
                            {{ $event->title }}
                        </a>
                    </h3>

                    @if($event->description)
                    <p class="text-gray-600 mb-4">{{ Str::limit(strip_tags($event->description), 200) }}</p>
                    @endif

                    @if($event->location)
                    <div class="flex items-center text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $event->location }}
                    </div>
                    @endif

                    <a href="{{ route('events.show', $event) }}"
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                        Lihat Detail
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $events->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <div class="text-gray-500 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Agenda</h3>
        <p class="text-gray-600">Agenda kegiatan akan segera ditampilkan di sini.</p>
    </div>
    @endif
</div>
@endsection
