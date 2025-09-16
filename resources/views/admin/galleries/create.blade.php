@extends('layouts.admin')

@section('title', 'Tambah Galeri')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Tambah Galeri Baru</h2>
            <a href="{{ route('admin.galleries.index') }}"
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Galeri *
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mb-6">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                    Slug (Optional)
                </label>
                <input type="text"
                       name="slug"
                       id="slug"
                       value="{{ old('slug') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Biarkan kosong untuk generate otomatis dari judul</p>
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Upload Gambar
                </label>
                <div id="image-upload-container">
                    <div class="image-upload-item mb-4 p-4 border border-gray-200 rounded-lg">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:space-x-4 space-y-4 lg:space-y-0">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Pilih Gambar</label>
                                <input type="file"
                                       name="images[]"
                                       accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF. Max: 2MB</p>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Alt Text (Optional)</label>
                                <input type="text"
                                       name="alt_texts[]"
                                       placeholder="Deskripsi gambar..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="flex items-end">
                                <button type="button"
                                        class="remove-image-btn px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm"
                                        style="display: none;">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button"
                        id="add-image-btn"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm">
                    + Tambah Gambar Lain
                </button>

                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.galleries.index') }}"
                   class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Simpan Galeri
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

    // Image upload functionality
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('image-upload-container');
        const addBtn = document.getElementById('add-image-btn');
        let imageCount = 1;

        // Function to update remove button visibility
        function updateRemoveButtons() {
            const items = container.querySelectorAll('.image-upload-item');
            items.forEach((item, index) => {
                const removeBtn = item.querySelector('.remove-image-btn');
                if (items.length > 1) {
                    removeBtn.style.display = 'block';
                } else {
                    removeBtn.style.display = 'none';
                }
            });
        }

        // Add new image upload field
        addBtn.addEventListener('click', function() {
            imageCount++;
            const newItem = document.createElement('div');
            newItem.className = 'image-upload-item mb-4 p-4 border border-gray-200 rounded-lg';
            newItem.innerHTML = `
                <div class="flex flex-col lg:flex-row lg:items-start lg:space-x-4 space-y-4 lg:space-y-0">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Pilih Gambar</label>
                        <input type="file"
                               name="images[]"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF. Max: 2MB</p>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Alt Text (Optional)</label>
                        <input type="text"
                               name="alt_texts[]"
                               placeholder="Deskripsi gambar..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="button"
                                class="remove-image-btn px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm">
                            Hapus
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            updateRemoveButtons();
        });

        // Remove image upload field
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-image-btn')) {
                e.target.closest('.image-upload-item').remove();
                updateRemoveButtons();
            }
        });

        // Initial setup
        updateRemoveButtons();
    });
</script>
@endsection
