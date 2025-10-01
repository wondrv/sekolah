@extends('layouts.admin')

@section('title', 'Complete Project Import')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Complete Project Import</h1>
                        <p class="mt-2 text-gray-600">Import lengkap project GitHub dengan semua file dan struktur</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.templates.smart-import.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Smart Import
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Import Methods -->
        <div class="grid lg:grid-cols-2 gap-8 mb-8">

            <!-- GitHub Project Import -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fab fa-github text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Import dari GitHub Project</h3>
                            <p class="text-sm text-gray-600">Download dan import seluruh project dari GitHub repository</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-blue-900 mb-2">✨ Fitur Complete Import:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Deteksi otomatis semua file HTML, CSS, JS, images</li>
                            <li>• Analisis struktur project (static, nodejs, built)</li>
                            <li>• Auto-generate index.html jika diperlukan</li>
                            <li>• Sinkronisasi semua assets dan dependencies</li>
                            <li>• Siap diaktifkan langsung ke homepage</li>
                        </ul>
                    </div>

                    <form id="githubProjectForm" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">GitHub Repository URL</label>
                            <input type="url" name="github_url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="https://github.com/username/repository" required>
                            <p class="text-xs text-gray-500 mt-1">Contoh: https://github.com/startbootstrap/startbootstrap-creative</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                                <input type="text" name="branch" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                       placeholder="main" value="main">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Project Name</label>
                                <input type="text" name="project_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                       placeholder="My Awesome Template">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-full" id="githubImportBtn">
                            <i class="fab fa-github mr-2"></i>Import Complete Project
                        </button>
                    </form>
                </div>
            </div>

            <!-- ZIP Upload Import -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-file-archive text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Upload Project ZIP</h3>
                            <p class="text-sm text-gray-600">Upload file ZIP berisi complete project</p>
                        </div>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-green-900 mb-2">📦 Yang Akan Diproses:</h4>
                        <ul class="text-sm text-green-800 space-y-1">
                            <li>• Semua file HTML, CSS, JavaScript</li>
                            <li>• Images, fonts, dan assets lainnya</li>
                            <li>• Struktur direktori dipertahankan</li>
                            <li>• Auto-detect main HTML file</li>
                            <li>• Generate template data untuk CMS</li>
                        </ul>
                    </div>

                    <form id="zipProjectForm" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project ZIP File</label>
                            <div class="relative">
                                <input type="file" name="project_zip" accept=".zip"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                       required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Maximum 50MB. ZIP harus berisi complete website project.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project Name</label>
                            <input type="text" name="project_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="My Project Template" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-full" id="zipImportBtn">
                            <i class="fas fa-upload mr-2"></i>Upload & Import Project
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Projects -->
        @if(count($recent_projects) > 0)
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Complete Projects</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recent_projects as $project)
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-medium text-gray-900">{{ $project->name }}</h4>
                            <span class="px-2 py-1 text-xs rounded-full {{ $project->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $project->is_active ? 'Active' : 'Imported' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $project->description }}</p>

                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                            <span>{{ $project->created_at->format('M d, Y') }}</span>
                            <span>{{ $project->settings['project_structure']['total_files'] ?? 0 }} files</span>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('admin.templates.complete-import.preview', $project->id) }}"
                               class="flex-1 text-center px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                Preview
                            </a>
                            @if(!$project->is_active)
                            <button onclick="activateProject({{ $project->id }})"
                                    class="flex-1 text-center px-3 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200">
                                Activate
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Help Section -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">📚 Panduan Complete Project Import</h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">GitHub Repository Requirements:</h3>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Repository harus public</li>
                            <li>• Berisi file HTML, CSS, JS yang ready-to-use</li>
                            <li>• Tidak perlu build process (atau sudah di-build)</li>
                            <li>• Struktur project yang jelas</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">ZIP File Requirements:</h3>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Maksimal 50MB</li>
                            <li>• Berisi complete website project</li>
                            <li>• Minimal ada 1 file HTML</li>
                            <li>• Assets (CSS, JS, images) dalam struktur yang benar</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-white rounded border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-2">✨ Apa yang Dilakukan Sistem:</h4>
                    <div class="text-sm text-gray-700">
                        <p><strong>1. Analisis Project:</strong> Deteksi file HTML, CSS, JS, images, dan struktur project</p>
                        <p><strong>2. File Processing:</strong> Copy semua file ke storage dengan struktur yang dipertahankan</p>
                        <p><strong>3. Template Generation:</strong> Buat template data untuk CMS integration</p>
                        <p><strong>4. Ready to Use:</strong> Project siap diaktifkan dan ditampilkan di homepage</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900" id="successTitle">Project Imported Successfully!</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="successMessage">Your complete project has been imported and is ready to use.</p>
                <div id="projectStats" class="mt-3 text-xs text-gray-600"></div>
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeSuccessModal" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Importing Complete Project...</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="loadingMessage">Please wait while we process your project files...</p>
                <div class="mt-3">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const githubForm = document.getElementById('githubProjectForm');
    const zipForm = document.getElementById('zipProjectForm');
    const githubBtn = document.getElementById('githubImportBtn');
    const zipBtn = document.getElementById('zipImportBtn');

    // GitHub Import
    githubForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(githubForm);
        const data = Object.fromEntries(formData.entries());

        try {
            showLoading('Downloading and processing GitHub repository...');
            githubBtn.disabled = true;
            githubBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Importing...';

            const response = await fetch('{{ route("admin.templates.complete-import.github") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            hideLoading();

            if (result.success) {
                showSuccess(
                    'GitHub Project Imported!',
                    result.message,
                    `Files imported: ${result.files_imported}<br>
                     HTML files: ${result.project_analysis.html_count}<br>
                     CSS files: ${result.project_analysis.css_count}<br>
                     JS files: ${result.project_analysis.js_count}<br>
                     Images: ${result.project_analysis.image_count}`
                );
                githubForm.reset();

                // Optional redirect to preview
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 3000);
                }
            } else {
                alert('Import failed: ' + result.message);
            }

        } catch (error) {
            hideLoading();
            alert('Import failed: ' + error.message);
        } finally {
            githubBtn.disabled = false;
            githubBtn.innerHTML = '<i class="fab fa-github mr-2"></i>Import Complete Project';
        }
    });

    // ZIP Import
    zipForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(zipForm);

        try {
            showLoading('Processing ZIP file and extracting project...');
            zipBtn.disabled = true;
            zipBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';

            const response = await fetch('{{ route("admin.templates.complete-import.zip") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            const result = await response.json();
            hideLoading();

            if (result.success) {
                showSuccess(
                    'ZIP Project Imported!',
                    result.message,
                    `Files imported: ${result.files_imported}<br>
                     HTML files: ${result.project_analysis.html_count}<br>
                     CSS files: ${result.project_analysis.css_count}<br>
                     JS files: ${result.project_analysis.js_count}<br>
                     Images: ${result.project_analysis.image_count}`
                );
                zipForm.reset();

                // Optional redirect to preview
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 3000);
                }
            } else {
                alert('Import failed: ' + result.message);
            }

        } catch (error) {
            hideLoading();
            alert('Import failed: ' + error.message);
        } finally {
            zipBtn.disabled = false;
            zipBtn.innerHTML = '<i class="fas fa-upload mr-2"></i>Upload & Import Project';
        }
    });

    // Helper functions
    function showLoading(message) {
        document.getElementById('loadingMessage').textContent = message;
        document.getElementById('loadingModal').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loadingModal').classList.add('hidden');
    }

    function showSuccess(title, message, stats = '') {
        document.getElementById('successTitle').textContent = title;
        document.getElementById('successMessage').textContent = message;
        if (stats) {
            document.getElementById('projectStats').innerHTML = stats;
        }
        document.getElementById('successModal').classList.remove('hidden');
    }

    document.getElementById('closeSuccessModal').addEventListener('click', function() {
        document.getElementById('successModal').classList.add('hidden');
    });
});

// Activate project function
async function activateProject(templateId) {
    try {
        const response = await fetch(`/admin/template-system/complete-import/${templateId}/activate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();

        if (result.success) {
            alert('Project activated successfully!');
            location.reload();
        } else {
            alert('Failed to activate: ' + result.message);
        }
    } catch (error) {
        alert('Failed to activate: ' + error.message);
    }
}
</script>
@endsection
