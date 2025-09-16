@extends('layouts.admin')

@section('title', 'Kelola Event & Kegiatan')

@section('content')
@include('components.admin.alerts')

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-900">Event & Kegiatan</h2>
    <a href="{{ route('admin.events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Event
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow mb-6 p-4">
    <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-64">
            <input type="text" placeholder="Cari event..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Jenis</option>
            <option value="general">General</option>
            <option value="academic">Academic</option>
            <option value="extracurricular">Extracurricular</option>
        </select>
        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="upcoming">Akan Datang</option>
            <option value="ongoing">Berlangsung</option>
            <option value="past">Sudah Lewat</option>
            <option value="featured">Featured</option>
        </select>
    </div>
</div>

<!-- Events Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($events as $event)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                @if($event->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($event->description, 60) }}</div>
                                @endif
                                @if($event->is_featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $typeColors = [
                                'general' => 'bg-blue-100 text-blue-800',
                                'academic' => 'bg-green-100 text-green-800',
                                'extracurricular' => 'bg-purple-100 text-purple-800'
                            ];
                            $typeLabels = [
                                'general' => 'General',
                                'academic' => 'Academic',
                                'extracurricular' => 'Ekstrakurikuler'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$event->type] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $typeLabels[$event->type] ?? ucfirst($event->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div>{{ $event->starts_at->format('d/m/Y') }}</div>
                        <div class="text-gray-500">{{ $event->starts_at->format('H:i') }}</div>
                        @if($event->ends_at)
                            <div class="text-xs text-gray-400">
                                sampai {{ $event->ends_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $event->location ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($event->isUpcoming())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Akan Datang
                            </span>
                        @elseif($event->isOngoing())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Berlangsung
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Sudah Lewat
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                                     <a href="{{ route('admin.events.edit', $event) }}"
                                         class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                     <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" data-confirm="event: {{ $event->title }}">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                     </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Belum ada event. <a href="{{ route('admin.events.create') }}" class="text-blue-600 hover:text-blue-900">Tambah event pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($events->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $events->links() }}
        </div>
    @endif
</div>

<!-- Uses global delete modal in layouts.admin -->
@endsection
