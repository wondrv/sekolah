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
            <a href="{{ route('admin.pages.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="p-6">
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
                    <button type="button" onclick="confirmDelete()"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                        Hapus Halaman
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
                    Apakah Anda yakin ingin menghapus halaman ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex justify-center space-x-3 px-4 py-3">
                <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    Batal
                </button>
                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline">
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
