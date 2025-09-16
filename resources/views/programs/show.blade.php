@extends('layouts.app')

@section('title', $program->name . ' - Program Keahlian - ' . \App\Support\Theme::getSiteInfo()['name'])

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
                        <a href="{{ route('programs.index') }}" class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2">Program Keahlian</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-700 md:ml-2">{{ $program->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Program Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $program->name }}</h1>

            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                @if($program->duration)
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Durasi: {{ $program->duration }}
                    </span>
                @endif

                @if($program->level)
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Level: {{ $program->level }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Program Image -->
        @if($program->image)
            <div class="mb-8">
                <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $program->image) }}"
                         alt="{{ $program->name }}"
                         class="w-full h-full object-cover">
                </div>
            </div>
        @endif

        <!-- Program Description -->
        @if($program->description)
            <div class="prose prose-lg max-w-none mb-8">
                {!! nl2br(e($program->description)) !!}
            </div>
        @endif

        <!-- Curriculum Highlights -->
        @if($program->curriculum_highlights)
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Kurikulum & Materi</h3>
                <div class="grid md:grid-cols-2 gap-3">
                    @foreach(json_decode($program->curriculum_highlights, true) as $highlight)
                        <div class="flex items-center p-3 bg-green-50 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-800">{{ $highlight }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Career Prospects -->
        @if($program->career_prospects)
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Prospek Karir</h3>
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
                    <div class="prose prose-lg max-w-none">
                        {!! nl2br(e($program->career_prospects)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Requirements -->
        @if($program->requirements)
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Persyaratan</h3>
                <div class="bg-yellow-50 rounded-lg p-6">
                    <div class="prose prose-lg max-w-none">
                        {!! nl2br(e($program->requirements)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Certificate -->
        @if($program->certificate_info)
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Sertifikasi</h3>
                <p class="text-gray-600">{{ $program->certificate_info }}</p>
            </div>
        @endif

        <!-- Back Button -->
        <div class="flex justify-between items-center">
            <a href="{{ route('programs.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Program
            </a>

            <div class="flex items-center space-x-4">
                <a href="{{ route('enrollment') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    📝 Daftar Sekarang
                </a>

                <div class="text-sm text-gray-500">
                    @if($program->updated_at)
                        Diperbarui: {{ $program->updated_at->format('d M Y') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
