@extends('layouts.admin')

@section('title', 'Edit Berita/Artikel')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Edit Berita/Artikel</h2>
        <div class="flex space-x-2">
            <a href="{{ route('posts.show', $post->slug) }}" target="_blank"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                Lihat Post
            </a>
            <a href="{{ route('admin.posts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                           placeholder="Masukkan judul berita/artikel" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug URL
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $post->slug) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                           placeholder="otomatis-dari-judul">
                    <p class="mt-1 text-sm text-gray-500">URL: {{ url('/posts/' . $post->slug) }}</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                        Ringkasan/Excerpt
                    </label>
                    <textarea id="excerpt" name="excerpt" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('excerpt') border-red-500 @enderror"
                              placeholder="Ringkasan singkat untuk preview">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body Content -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                        Konten <span class="text-red-500">*</span>
                    </label>
                    <textarea id="body" name="body" rows="12"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('body') border-red-500 @enderror"
                              placeholder="Tulis konten berita/artikel di sini..." required>{{ old('body', $post->body) }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status & Publishing -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publikasi</h3>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    <!-- Published Date -->
                    <div class="mb-4">
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publish</label>
                        <input type="datetime-local" id="published_at" name="published_at"
                               value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <button type="submit" name="action" value="save"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            Update
                        </button>
                        <button type="submit" name="action" value="save_and_continue"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            Update & Lanjut
                        </button>
                    </div>

                    <!-- Post Info -->
                    <div class="mt-4 pt-4 border-t border-gray-200 text-sm text-gray-500">
                        <p>Dibuat: {{ $post->created_at->format('d/m/Y H:i') }}</p>
                        <p>Diupdate: {{ $post->updated_at->format('d/m/Y H:i') }}</p>
                        @if($post->user)
                            <p>Penulis: {{ $post->user->name }}</p>
                        @endif
                    </div>
                </div>

                <!-- Category -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Kategori</h3>
                    <select id="category_id" name="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cover Image -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Cover</h3>

                    @if($post->cover_path)
                        <div class="mb-4">
                            <img src="{{ Storage::url($post->cover_path) }}" alt="Current cover" class="w-full h-32 object-cover rounded-lg">
                            <div class="mt-2 flex items-center">
                                <input type="checkbox" id="remove_cover" name="remove_cover" value="1" class="mr-2">
                                <label for="remove_cover" class="text-sm text-red-600">Hapus gambar saat ini</label>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <input type="file" id="cover_image" name="cover_image" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cover_image') border-red-500 @enderror">
                        @error('cover_image')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">
                            Format: JPG, PNG, GIF. Maksimal 2MB.
                            @if(!$post->cover_path)
                                Belum ada gambar cover.
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <h3 class="text-lg font-medium text-red-900 mb-4">Zona Berbahaya</h3>
                    <button type="button" onclick="confirmDelete()"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                        Hapus Post
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-bold text-gray-900">Konfirmasi Hapus</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus post ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex justify-center space-x-3 px-4 py-3">
                <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    Batal
                </button>
                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" data-confirm="berita: {{ $post->title }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Auto-generate slug from title (only if slug is empty or matches old title)
    const originalSlug = '{{ $post->slug }}';
    const originalTitle = '{{ $post->title }}';

    document.getElementById('title').addEventListener('input', function(e) {
        const title = e.target.value;
        const currentSlug = document.getElementById('slug').value;

        // Only auto-update slug if it's empty or matches the original title pattern
        if (!currentSlug || currentSlug === originalSlug) {
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            document.getElementById('slug').value = slug;
        }
    });
</script>
@endsection
