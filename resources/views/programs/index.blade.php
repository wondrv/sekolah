@extends('layouts.app')

@section('title', 'Program Keahlian - ' . \App\Support\Theme::getSiteInfo()['name'])

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Program Keahlian</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Berbagai program keahlian yang sesuai dengan kebutuhan industri dan perkembangan teknologi
            </p>
        </div>

        <!-- Programs Grid -->
        @if($programs->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($programs as $program)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        @if($program->image)
                            <div class="h-48 bg-gray-200 overflow-hidden">
                                <img src="{{ asset('storage/' . $program->image) }}"
                                     alt="{{ $program->name }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $program->name }}</h3>

                            @if($program->duration)
                                <p class="text-sm text-green-600 mb-2">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Durasi: {{ $program->duration }}
                                    </span>
                                </p>
                            @endif

                            <p class="text-gray-600 text-sm mb-4">
                                {{ Str::limit($program->description, 120) }}
                            </p>

                            @if($program->curriculum_highlights)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(array_slice(json_decode($program->curriculum_highlights, true) ?? [], 0, 3) as $highlight)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $highlight }}
                                            </span>
                                        @endforeach
                                        @if(count(json_decode($program->curriculum_highlights, true) ?? []) > 3)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                +{{ count(json_decode($program->curriculum_highlights, true)) - 3 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <a href="{{ route('programs.show', $program->slug) }}"
                               class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Program</h3>
                    <p class="text-gray-500">Informasi program keahlian akan segera ditambahkan.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
