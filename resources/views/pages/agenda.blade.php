@extends('layouts.app')
@section('title','Agenda Kegiatan')
@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-12">Agenda Kegiatan</h1>

    <div class="max-w-4xl mx-auto">
        @for ($i = 1; $i <= 8; $i++)
        <div class="flex gap-6 pb-8 border-l-2 border-blue-200 ml-8 last:border-l-0">
            <div class="flex-shrink-0 -ml-10">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex flex-col items-center justify-center text-white text-sm">
                    <span class="font-bold">{{ now()->addDays($i * 3)->format('d') }}</span>
                    <span>{{ now()->addDays($i * 3)->format('M') }}</span>
                </div>
            </div>
            <div class="flex-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-xl font-semibold">Kegiatan Sekolah {{ $i }}</h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Akademik</span>
                    </div>
                    <p class="text-gray-600 mb-3">Deskripsi lengkap tentang kegiatan yang akan dilaksanakan di sekolah.</p>
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span>📅 {{ now()->addDays($i * 3)->format('d M Y') }}</span>
                        <span>🕒 08:00 - 12:00</span>
                        <span>📍 Aula Sekolah</span>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>
@endsection
