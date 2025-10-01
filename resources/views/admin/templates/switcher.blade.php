@extends('layouts.admin')

@section('title', 'Template Switcher')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Template Switcher</h1>
                        <p class="mt-2 text-gray-600">Beralih antar template yang telah diimpor</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.templates.smart-import.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>Import Template Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Current Active Template -->
        @if($activeTemplate)
        <div class="bg-white rounded-lg shadow-sm border mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Template Aktif Saat Ini</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check mr-1"></i>Aktif
                    </span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center space-x-4">
                        @if($activeTemplate->preview_image)
                            <img src="{{ $activeTemplate->preview_image_url }}" alt="{{ $activeTemplate->name }}" class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-code text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $activeTemplate->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $activeTemplate->description }}</p>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Diaktifkan: {{ $activeTemplate->updated_at->format('d M Y H:i') }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-user mr-1"></i>
                                    Source: {{ ucfirst($activeTemplate->source) }}
                                </span>
                                @if($activeTemplate->template_files)
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-files mr-1"></i>
                                    {{ count($activeTemplate->template_files) }} files
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.templates.my-templates.edit', $activeTemplate->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <a href="/" target="_blank" class="btn btn-outline btn-sm">
                                <i class="fas fa-external-link-alt mr-1"></i>Lihat Homepage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Available Templates -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Template Tersedia</h2>

                @if($templates->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($templates as $template)
                        <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow {{ $template->is_active ? 'ring-2 ring-green-500' : '' }}">
                            <!-- Template Preview -->
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                @if($template->preview_image)
                                    <img src="{{ $template->preview_image_url }}" alt="{{ $template->name }}" class="w-full h-32 object-cover">
                                @else
                                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-file-code text-gray-400 text-3xl"></i>
                                    </div>
                                @endif

                                @if($template->is_active)
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Aktif
                                    </span>
                                </div>
                                @endif
                            </div>

                            <!-- Template Info -->
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-1">{{ $template->name }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($template->description, 80) }}</p>

                                <!-- Template Details -->
                                <div class="space-y-1 mb-4">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-calendar-alt mr-2 w-3"></i>
                                        {{ $template->created_at->format('d M Y') }}
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-tag mr-2 w-3"></i>
                                        {{ ucfirst($template->source) }}
                                    </div>
                                    @if($template->template_files)
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-files mr-2 w-3"></i>
                                        {{ count($template->template_files) }} files
                                    </div>
                                    @endif
                                    @if(isset($template->template_data['templates']))
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-cubes mr-2 w-3"></i>
                                        {{ count($template->template_data['templates']) }} templates
                                    </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    @if(!$template->is_active)
                                    <form action="{{ route('admin.templates.my-templates.activate', $template->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full btn btn-primary btn-sm" onclick="return confirm('Aktifkan template {{ $template->name }}?')">
                                            <i class="fas fa-power-off mr-1"></i>Aktifkan
                                        </button>
                                    </form>
                                    @else
                                    <button class="w-full btn btn-secondary btn-sm cursor-not-allowed" disabled>
                                        <i class="fas fa-check mr-1"></i>Template Aktif
                                    </button>
                                    @endif

                                    <a href="{{ route('admin.templates.my-templates.edit', $template->id) }}" class="btn btn-outline btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Template</h3>
                            <p class="text-gray-600 mb-6">Anda belum memiliki template. Import template pertama Anda untuk memulai.</p>
                            <a href="{{ route('admin.templates.smart-import.index') }}" class="btn btn-primary">
                                <i class="fas fa-download mr-2"></i>Import Template Sekarang
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- File Viewer Modal (for file-based templates) -->
        <div id="fileViewerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-medium">File Template</h3>
                        <button onclick="closeFileViewer()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="fileViewerContent" class="p-4 max-h-96 overflow-y-auto">
                        <!-- File content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showFileViewer(templateId) {
    // Show file content for file-based templates
    document.getElementById('fileViewerModal').classList.remove('hidden');
    // Load file content via AJAX
}

function closeFileViewer() {
    document.getElementById('fileViewerModal').classList.add('hidden');
}

// Auto-refresh preview when template is activated
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[action*="activate"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Template berhasil diaktifkan!');
                    // Reload page to show updated state
                    window.location.reload();
                } else {
                    alert('Gagal mengaktifkan template: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengaktifkan template');
            });
        });
    });
});
</script>
@endsection
