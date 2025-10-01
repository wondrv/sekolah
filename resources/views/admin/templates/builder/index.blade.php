@extends('layouts.admin')

@section('title', 'Template Builder')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Template Builder</h1>
        <p class="text-gray-600">Buat template website sekolah dengan drag & drop</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.templates.builder.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Template Baru
        </a>
        <a href="{{ route('admin.templates.gallery.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Template Gallery
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Template Baru</h3>
                    <p class="text-blue-100 text-sm mt-1">Mulai dari template kosong</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.templates.builder.create') }}"
               class="mt-4 inline-flex items-center text-sm font-medium text-blue-100 hover:text-white">
                Buat Template
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Import Template</h3>
                    <p class="text-green-100 text-sm mt-1">Upload template dari file</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
            </div>
            <button onclick="openImportModal()"
                    class="mt-4 inline-flex items-center text-sm font-medium text-green-100 hover:text-white">
                Import Sekarang
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Gallery Template</h3>
                    <p class="text-purple-100 text-sm mt-1">Pilih dari template siap pakai</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.templates.gallery.index') }}"
               class="mt-4 inline-flex items-center text-sm font-medium text-purple-100 hover:text-white">
                Jelajahi Gallery
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Categories Overview -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Kategori Template</h3>
            <p class="text-sm text-gray-600 mt-1">Pilih kategori yang sesuai dengan jenis sekolah Anda</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categories as $category)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors cursor-pointer"
                         onclick="window.location.href='{{ route('admin.templates.gallery.category', $category->slug) }}'">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3"
                                 style="background-color: {{ $category->color ?? '#3B82F6' }}20; color: {{ $category->color ?? '#3B82F6' }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $category->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $category->activeTemplates ? $category->activeTemplates->count() : 0 }} template</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $category->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- My Templates -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Template Saya</h3>
                <p class="text-sm text-gray-600 mt-1">Template yang telah Anda buat atau install</p>
            </div>
            <a href="{{ route('admin.templates.my-templates.index') }}"
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua
            </a>
        </div>
        <div class="p-6">
            @if($templates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($templates->take(6) as $template)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors relative">
                            <!-- Status badge -->
                            @if($template->is_active)
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </div>
                            @endif

                            <!-- Preview -->
                            <div class="aspect-video bg-gray-100 rounded-lg mb-3 overflow-hidden">
                                @if($template->preview_image)
                                    <img src="{{ $template->preview_image_url }}"
                                         alt="{{ $template->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h6m-6 4h6m-9-4h.01M10 12h.01m-7 0h.01"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div>
                                <h4 class="font-medium text-gray-900 line-clamp-1">{{ $template->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $template->description ?: 'Template custom' }}</p>

                                <!-- Source badge -->
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                                        {{ $template->source === 'gallery' ? 'bg-blue-100 text-blue-800' :
                                           ($template->source === 'custom' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($template->source) }}
                                    </span>
                                </div>

                                <!-- Actions -->
                                <div class="mt-3 flex justify-between items-center">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.templates.builder.edit', $template) }}"
                                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.templates.my-templates.show', $template) }}"
                                           class="text-xs text-gray-600 hover:text-gray-800 font-medium">
                                            Detail
                                        </a>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $template->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada template</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat template baru atau install dari gallery</p>
                    <div class="mt-6 flex justify-center gap-3">
                        <a href="{{ route('admin.templates.builder.create') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Template Baru
                        </a>
                        <a href="{{ route('admin.templates.gallery.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Lihat Gallery
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Import Template Modal -->
<div id="importModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="importForm" action="{{ route('admin.templates.smart-import.index') }}" method="GET">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Import Template
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Gunakan Smart Import untuk mengimpor template dari berbagai sumber.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Buka Smart Import
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeImportModal()">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
                                    <input id="activate_after_import"
                                           name="activate_after_import"
                                           type="checkbox"
                                           value="1"
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <label for="activate_after_import" class="ml-2 block text-sm text-gray-900">
                                        Aktifkan template setelah import
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Import Template
                    </button>
                    <button type="button"
                            onclick="closeImportModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('importForm').reset();
}

// File drag and drop handling
const fileInput = document.getElementById('template_file');
const dropZone = fileInput.closest('.border-dashed');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-green-400', 'bg-green-50');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-green-400', 'bg-green-50');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-green-400', 'bg-green-50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        updateFileInputText(files[0]);
    }
});

fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        updateFileInputText(e.target.files[0]);
    }
});

function updateFileInputText(file) {
    const dropZone = fileInput.closest('.border-dashed');
    const textElement = dropZone.querySelector('p.pl-1');
    if (textElement) {
        textElement.textContent = `File dipilih: ${file.name}`;
    }
}
</script>
@endpush



