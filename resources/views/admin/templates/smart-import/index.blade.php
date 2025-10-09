@extends('layouts.admin')

@section('title', 'Template Import')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Template Import</h1>
        <p class="text-gray-600 mt-2">Upload ZIP template untuk Laravel Blade views (.blade.php) dan PHP files</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Main Upload Section -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-8">
                <div class="text-center mb-8">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-upload text-blue-600 text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Upload Template ZIP</h2>
                    <p class="text-gray-600">Upload file ZIP berisi Laravel Blade views (.blade.php) atau PHP template files</p>
                </div>

                <div class="max-w-2xl mx-auto">
                    <form id="templateImportForm" class="space-y-6" method="POST" action="{{ route('admin.templates.smart-import.import-file') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- File Upload -->
                        <div>
                            <label for="template_file" class="block text-sm font-medium text-gray-700 mb-3">Select ZIP File</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                                <input type="file"
                                       id="template_file"
                                       name="file"
                                       accept=".zip"
                                       class="hidden"
                                       required>
                                <label for="template_file" class="cursor-pointer">
                                    <div class="space-y-3">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                        <div>
                                            <p class="text-lg font-medium text-gray-700">Click to browse atau drag & drop</p>
                                            <p class="text-sm text-gray-500">ZIP files only, max 50MB</p>
                                        </div>
                                    </div>
                                </label>
                                <div id="file-info" class="hidden mt-4 p-3 bg-blue-50 rounded-lg text-left">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-archive text-blue-600 mr-3"></i>
                                        <div>
                                            <p id="file-name" class="font-medium text-gray-900"></p>
                                            <p id="file-size" class="text-sm text-gray-600"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Template Details -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="template_name" class="block text-sm font-medium text-gray-700 mb-2">Template Name (Optional)</label>
                                <input type="text"
                                       id="template_name"
                                       name="template_name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Auto-detected from files">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center">
                                    <input type="checkbox" id="auto_activate" name="auto_activate" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Aktifkan langsung setelah import</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" id="importBtn" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-upload mr-2"></i>
                                <span id="importBtnText">Import Template</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Template Structure Guide -->
        <div class="bg-white rounded-lg shadow-sm border mt-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📁 Struktur ZIP yang Didukung</h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Laravel Blade Structure with Full Path -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-900 mb-3">🔧 Laravel Blade Views (Recommended)</h4>
                        <pre class="text-xs bg-white p-3 rounded border overflow-x-auto">template.zip
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── main.blade.php     ← Layout utama
│       ├── home.blade.php         ← Homepage
│       ├── tentang.blade.php      ← About page
│       ├── program.blade.php      ← Programs page
│       ├── berita.blade.php       ← News page
│       ├── galeri.blade.php       ← Gallery page
│       ├── kontak.blade.php       ← Contact page
│       └── ppdb.blade.php         ← Admission page
├── app/Http/Controllers/
│   └── [Name]/
│       └── SchoolController.php   ← Optional controller
├── routes/
│   └── web.php                    ← Optional routes
└── public/
    ├── css/
    ├── js/
    └── images/</pre>
                        <p class="text-xs text-blue-800 mt-2">✅ <strong>Database-Driven:</strong> Template langsung dari database, real-time switching</p>
                    </div>

                    <!-- Simplified Blade Structure -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-green-900 mb-3">📝 Simplified Blade Structure</h4>
                        <pre class="text-xs bg-white p-3 rounded border overflow-x-auto">template.zip
├── views/                         ← Alternate structure
│   ├── layouts/
│   │   └── main.blade.php
│   ├── home.blade.php
│   ├── tentang.blade.php
│   ├── program.blade.php
│   ├── berita.blade.php
│   ├── galeri.blade.php
│   ├── kontak.blade.php
│   └── ppdb.blade.php
├── assets/                        ← Optional assets
│   ├── css/
│   ├── js/
│   └── images/
└── readme.txt                     ← Optional documentation</pre>
                        <p class="text-xs text-green-800 mt-2">✅ <strong>Auto-detected:</strong> Sistem otomatis detect path dan convert ke format database</p>
                    </div>
                </div>

                <!-- Advanced Features -->
                <div class="mt-6 grid md:grid-cols-2 gap-6">
                    <!-- Full Laravel Template -->
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-purple-900 mb-3">🚀 Full Laravel Template</h4>
                        <pre class="text-xs bg-white p-3 rounded border overflow-x-auto">template.zip
├── resources/views/               ← Blade views
├── app/Http/Controllers/          ← Controllers
├── routes/web.php                 ← Routes definition
├── public/                        ← Assets (CSS, JS, images)
├── database/migrations/           ← Optional migrations
└── composer.json                  ← Optional dependencies</pre>
                        <p class="text-xs text-purple-800 mt-2">⚡ <strong>Complete System:</strong> Views + Controllers + Routes, auto-installed</p>
                    </div>

                    <!-- Theme with Multiple Layouts -->
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-orange-900 mb-3">🎨 Multi-Layout Theme</h4>
                        <pre class="text-xs bg-white p-3 rounded border overflow-x-auto">template.zip
├── resources/views/
│   ├── layouts/
│   │   ├── main.blade.php         ← Primary layout
│   │   ├── admin.blade.php        ← Admin layout
│   │   └── guest.blade.php        ← Guest layout
│   ├── components/                ← Blade components
│   │   ├── header.blade.php
│   │   └── footer.blade.php
│   └── pages/
│       ├── home.blade.php
│       └── ...</pre>
                        <p class="text-xs text-orange-800 mt-2">🎯 <strong>Advanced:</strong> Multiple layouts, components, organized structure</p>
                    </div>
                </div>

                <!-- System Features -->
                <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3">🔥 Fitur Sistem Terbaru:</h4>
                    <div class="grid md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <h5 class="font-medium text-blue-900 mb-2">📊 Database-Driven Rendering:</h5>
                            <ul class="text-blue-800 space-y-1 text-xs">
                                <li>• Template disimpan di database, bukan file sistem</li>
                                <li>• Real-time template switching tanpa restart</li>
                                <li>• Laravel syntax parsing (route helpers, @@yield, dll)</li>
                                <li>• Auto-generated absolute URLs</li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-medium text-green-900 mb-2">⚡ Universal Dynamic Routing:</h5>
                            <ul class="text-green-800 space-y-1 text-xs">
                                <li>• Semua route (/, /tentang, /program, dll) dinamis</li>
                                <li>• Template view auto-detected berdasarkan path</li>
                                <li>• Fallback system untuk missing views</li>
                                <li>• Support multiple template types</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h4 class="font-semibold text-yellow-900 mb-2">💡 Tips Upload & Switching:</h4>
                    <ul class="text-sm text-yellow-800 space-y-1">
                        <li>• <strong>Auto-Detection:</strong> Sistem otomatis deteksi struktur dan convert ke database format</li>
                        <li>• <strong>Real-time Switch:</strong> Set template sebagai "Active" → langsung applied ke semua halaman</li>
                        <li>• <strong>Laravel Syntax:</strong> Route helpers, @@yield('title'), dll otomatis di-parse</li>
                        <li>• <strong>Clean URLs:</strong> /tentang, /program, /ppdb → URL normal tanpa encoding</li>
                        <li>• <strong>Template Fallback:</strong> Jika view tidak ditemukan, otomatis gunakan home.blade.php</li>
                        <li>• <strong>Asset Support:</strong> CSS, JS, images otomatis tersedia di /template-assets/</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Import Progress -->
        <div id="importProgress" class="hidden bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-cog fa-spin text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Importing Template</h3>
                    <p class="text-sm text-gray-600">Please wait while we process your template...</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <div class="flex justify-between items-center">
                    <span id="progressText" class="text-sm text-gray-600">Starting import...</span>
                    <span id="progressPercentage" class="text-sm font-medium text-gray-900">0%</span>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div id="alertContainer"></div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const templateFileInput = document.getElementById('template_file');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const templateImportForm = document.getElementById('templateImportForm');
    const importBtn = document.getElementById('importBtn');
    const importBtnText = document.getElementById('importBtnText');
    const importProgress = document.getElementById('importProgress');
    const alertContainer = document.getElementById('alertContainer');

    // File input change handler
    templateFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('hidden');

            // Auto-fill template name if empty
            const templateNameInput = document.getElementById('template_name');
            if (!templateNameInput.value) {
                const nameWithoutExt = file.name.replace(/\.[^/.]+$/, "");
                templateNameInput.value = nameWithoutExt.replace(/[-_]/g, ' ')
                    .replace(/\b\w/g, l => l.toUpperCase());
            }
        } else {
            fileInfo.classList.add('hidden');
        }
    });

    // Form submit handler
    templateImportForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(templateImportForm);

        // Show progress
        showProgress();
        updateProgress(10, 'Starting upload...');

        try {
            const response = await fetch('{{ route("admin.templates.smart-import.import-file") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            updateProgress(50, 'Processing template...');

            const result = await response.json();

            if (result.success) {
                updateProgress(100, 'Import completed successfully!');

                setTimeout(() => {
                    showAlert('success', 'Template imported successfully! ' + result.message);
                    hideProgress();

                    // Redirect to My Templates or template detail
                    if (result.redirect) {
                        window.location.href = result.redirect;
                    } else {
                        window.location.href = '{{ route("admin.templates.my-templates.index") }}';
                    }
                }, 1000);
            } else {
                hideProgress();
                showAlert('error', 'Import failed: ' + result.error);
            }

        } catch (error) {
            hideProgress();
            showAlert('error', 'Import failed: ' + error.message);
        }
    });

    // Helper functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showProgress() {
        importProgress.classList.remove('hidden');
        importBtn.disabled = true;
        importBtnText.textContent = 'Importing...';
    }

    function hideProgress() {
        importProgress.classList.add('hidden');
        importBtn.disabled = false;
        importBtnText.textContent = 'Import Template';
    }

    function updateProgress(percentage, text) {
        document.getElementById('progressBar').style.width = percentage + '%';
        document.getElementById('progressPercentage').textContent = percentage + '%';
        document.getElementById('progressText').textContent = text;
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
        const iconClass = type === 'success' ? 'fas fa-check-circle text-green-600' : 'fas fa-exclamation-circle text-red-600';

        alertContainer.innerHTML = `
            <div class="mb-6 p-4 border rounded-lg ${alertClass}">
                <div class="flex items-center">
                    <i class="${iconClass} mr-3"></i>
                    <span>${message}</span>
                </div>
            </div>
        `;
    }

    // Drag and drop functionality
    const dropZone = templateFileInput.parentElement;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            templateFileInput.files = files;
            templateFileInput.dispatchEvent(new Event('change'));
        }
    }
});
</script>

@endsection
