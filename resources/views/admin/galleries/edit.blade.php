@extends('layouts.admin')

@section('title', 'Edit Galeri')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Edit Galeri: {{ $gallery->title }}</h2>
            <a href="{{ route('admin.galleries.index') }}"
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Galeri *
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title', $gallery->title) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mb-6">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                    Slug
                </label>
                <input type="text"
                       name="slug"
                       id="slug"
                       value="{{ old('slug', $gallery->slug) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $gallery->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Existing Photos Management -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-medium text-gray-900">Foto Saat Ini</h3>
                    @if($gallery->photos && $gallery->photos->count())
                        <p class="text-xs text-gray-500">Tip: Seret dan lepas kartu untuk mengubah urutan</p>
                    @endif
                </div>
                @if($gallery->photos && $gallery->photos->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 js-photos-grid">
                        @foreach($gallery->photos->sortBy('sort_order') as $photo)
                            <div class="border rounded-lg p-3 bg-white relative" draggable="true" data-photo-id="{{ $photo->id }}">
                                <div class="absolute top-2 right-2 text-gray-400 cursor-move drag-handle" title="Seret untuk mengurutkan">☰</div>
                                <img src="{{ Storage::url($photo->path) }}" alt="{{ $photo->alt }}" class="w-full h-40 object-cover rounded mb-3 pointer-events-none select-none">
                                <div class="space-y-2">
                                    <label class="block text-xs text-gray-600">Alt Text</label>
                                    <input type="text" name="photos[{{ $photo->id }}][alt]" value="{{ old("photos.{$photo->id}.alt", $photo->alt) }}" class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                    <label class="block text-xs text-gray-600 mt-2">Urutan</label>
                                    <input type="number" name="photos[{{ $photo->id }}][sort_order]" value="{{ old("photos.{$photo->id}.sort_order", $photo->sort_order) }}" class="w-full px-2 py-1 border border-gray-300 rounded text-sm js-sort-input">
                                </div>
                                <div class="mt-3 flex justify-between items-center">
                                    <a href="{{ Storage::url($photo->path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat</a>
                                    <form action="{{ route('admin.photos.destroy', $photo) }}" method="POST" data-confirm="foto: {{ $photo->alt ?: basename($photo->path) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada foto untuk galeri ini.</p>
                @endif
            </div>

            <!-- Upload New Photos -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Unggah Foto Baru</h3>
                <div class="space-y-2">
                    <input id="newImagesInput" type="file" name="new_images[]" multiple accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="text-xs text-gray-500">Anda dapat memilih beberapa file sekaligus. Format: JPG, PNG, GIF, WEBP. Maks 4MB per foto.</p>
                    <div id="newImagePreview" class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.galleries.index') }}"
                   class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Update Galeri
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        const title = this.value;
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        document.getElementById('slug').value = slug;
    });

    // Drag & Drop reordering for existing photos
    (function() {
        const grid = document.querySelector('.js-photos-grid');
        if (!grid) return;

        let dragEl = null;

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('[draggable="true"]:not(.dragging)')];
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        function renumberSortInputs() {
            [...grid.children].forEach((card, index) => {
                const input = card.querySelector('.js-sort-input');
                if (input) input.value = index; // 0-based order
            });
        }

        grid.addEventListener('dragstart', (e) => {
            const target = e.target.closest('[draggable="true"]');
            if (!target) return;
            dragEl = target;
            dragEl.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            // For Firefox
            e.dataTransfer.setData('text/plain', '');
        });

        grid.addEventListener('dragover', (e) => {
            e.preventDefault();
            const afterElement = getDragAfterElement(grid, e.clientY);
            if (afterElement == null) {
                grid.appendChild(dragEl);
            } else {
                grid.insertBefore(dragEl, afterElement);
            }
        });

        grid.addEventListener('dragend', () => {
            if (!dragEl) return;
            dragEl.classList.remove('dragging');
            renumberSortInputs();
            dragEl = null;
        });
    })();

    // Client-side previews for newly selected images
    (function() {
        const input = document.getElementById('newImagesInput');
        const preview = document.getElementById('newImagePreview');
        if (!input || !preview) return;

        input.addEventListener('change', function() {
            preview.innerHTML = '';
            const files = Array.from(this.files || []);
            files.forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const url = URL.createObjectURL(file);
                const card = document.createElement('div');
                card.className = 'border rounded-lg p-2 bg-white';
                card.innerHTML = `
                    <img src="${url}" class="w-full h-28 object-cover rounded mb-2" alt="preview">
                    <div class="text-xs text-gray-600 truncate">${file.name}</div>
                `;
                preview.appendChild(card);
                // Revoke URL after image loads to free memory
                const img = card.querySelector('img');
                img.onload = () => URL.revokeObjectURL(url);
            });
        });
    })();
</script>
@endsection
