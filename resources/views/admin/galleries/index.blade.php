@extends('layouts.admin')

@section('title', 'Manajemen Galeri')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Daftar Galeri</h2>
            <a href="{{ route('admin.galleries.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                Tambah Galeri
            </a>
        </div>
    </div>

    <div class="p-6">
        @if($galleries->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Judul</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Slug</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Foto</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Tanggal</th>
                            <th class="text-center py-3 px-4 font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($galleries as $gallery)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4">{{ $gallery->title }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $gallery->slug }}</td>
                            <td class="py-3 px-4">{{ $gallery->photos_count ?? 0 }} foto</td>
                            <td class="py-3 px-4 text-gray-600">{{ $gallery->created_at->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.galleries.edit', $gallery) }}"
                                       class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" data-confirm="galeri: {{ $gallery->title }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $galleries->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">Belum ada galeri. <a href="{{ route('admin.galleries.create') }}" class="text-blue-600 hover:underline">Tambah galeri pertama</a></p>
            </div>
        @endif
    </div>
</div>

<!-- Uses global delete modal in layouts.admin -->
@endsection
