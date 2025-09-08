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
                                    <button onclick="confirmDelete({{ $gallery->id }}, '{{ $gallery->title }}')"
                                            class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm">
                                        Hapus
                                    </button>
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

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus galeri "<span id="galleryTitle" class="font-medium"></span>"?</p>

        <div class="flex justify-end space-x-4">
            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(galleryId, galleryTitle) {
        document.getElementById('galleryTitle').textContent = galleryTitle;
        document.getElementById('deleteForm').action = `/admin/galleries/${galleryId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>
@endsection
