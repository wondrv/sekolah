@extends('layouts.admin')

@section('title', 'Edit Halaman')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Edit Halaman</h2>
        <div class="flex space-x-2">
            <a href="{{ route('pages.show', $page->slug) }}" target="_blank"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                Lihat Halaman
            </a>
            <a href="{{ route('admin.pages.builder', $page) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                Page Builder
            </a>
            <a href="{{ route('admin.pages.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="p-6" data-form="update">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Halaman <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                           placeholder="Masukkan judul halaman" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug URL
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                           placeholder="otomatis-dari-judul">
                    <p class="mt-1 text-sm text-gray-500">URL: {{ url('/pages/' . $page->slug) }}</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body Content -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                        Konten Halaman <span class="text-red-500">*</span>
                    </label>
                    <textarea id="body" name="body" rows="15"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('body') border-red-500 @enderror"
                              placeholder="Tulis konten halaman di sini..." required>{{ old('body', $page->body) }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- SEO Settings -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO & Meta</h3>

                    <!-- Meta Title -->
                    <div class="mb-4">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Title
                        </label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_title') border-red-500 @enderror"
                               placeholder="Judul untuk mesin pencari">
                        <p class="mt-1 text-xs text-gray-500">Maks. 60 karakter untuk hasil pencarian optimal</p>
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-4">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Description
                        </label>
                        <textarea id="meta_description" name="meta_description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_description') border-red-500 @enderror"
                                  placeholder="Deskripsi untuk mesin pencari">{{ old('meta_description', $page->meta_description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Maks. 160 karakter untuk snippet pencarian optimal</p>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- OG Image -->
                    <div class="mb-4">
                        <label for="og_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Featured Image URL
                        </label>
                        <input type="url" id="og_image" name="og_image" value="{{ old('og_image', $page->og_image) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('og_image') border-red-500 @enderror"
                               placeholder="https://example.com/image.jpg">
                        <p class="mt-1 text-xs text-gray-500">Gambar untuk media sosial dan preview</p>
                        @error('og_image')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Publishing Actions -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publikasi</h3>

                    <!-- Pin Status -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned', $page->is_pinned) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Pin halaman ini</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Halaman yang di-pin akan ditampilkan di menu utama</p>
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

                    <!-- Page Info -->
                    <div class="mt-4 pt-4 border-t border-gray-200 text-sm text-gray-500">
                        <p>Dibuat: {{ $page->created_at->format('d/m/Y H:i') }}</p>
                        <p>Diupdate: {{ $page->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Page Stats -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-blue-900 mb-3">Statistik Halaman</h3>
                    <div class="space-y-2 text-sm text-blue-800">
                        <div class="flex justify-between">
                            <span>Jumlah Karakter:</span>
                            <span id="charCount">{{ strlen($page->body) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Estimasi Waktu Baca:</span>
                            <span id="readTime">{{ ceil(str_word_count($page->body) / 200) }} menit</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span>
                                @if($page->is_pinned)
                                    <span class="text-green-600 font-medium">Pinned</span>
                                @else
                                    <span class="text-gray-600">Regular</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <h3 class="text-lg font-medium text-red-900 mb-4">Zona Berbahaya</h3>
                    <button type="button"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm"
                            data-delete-url="{{ route('admin.pages.destroy', $page) }}"
                            data-confirm="halaman: {{ $page->title }}">
                        Hapus Halaman
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Auto-update stats when body content changes
    const bodyTextarea = document.getElementById('body');
    const charCount = document.getElementById('charCount');
    const readTime = document.getElementById('readTime');

    bodyTextarea.addEventListener('input', function() {
        const text = this.value;
        const charLength = text.length;
        const wordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
        const estimatedReadTime = Math.max(1, Math.ceil(wordCount / 200));

        charCount.textContent = charLength;
        readTime.textContent = estimatedReadTime + ' menit';
    });

    // Auto-generate slug from title (only if slug is empty or matches old title)
    const originalSlug = '{{ $page->slug }}';
    const originalTitle = '{{ $page->title }}';

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
