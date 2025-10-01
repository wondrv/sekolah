@extends('layouts.admin')

@section('title', 'Smart Template Import')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Smart Template Import</h1>
                        <p class="mt-2 text-gray-600">Import templates from anywhere with automatic language detection and translation</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.templates.my-templates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Templates
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Import Methods -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Import from URL -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-link text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Import from URL</h3>
                            <p class="text-sm text-gray-600">Import any school template from a website URL</p>
                        </div>
                    </div>

                    <form id="urlImportForm" class="space-y-4">
                        @csrf
                        <div>
                            <label for="template_url" class="block text-sm font-medium text-gray-700 mb-2">Template URL</label>
                            <input type="url"
                                   id="template_url"
                                   name="url"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://example.com/school-template"
                                   required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="custom_name" class="block text-sm font-medium text-gray-700 mb-2">Custom Name (Optional)</label>
                                <input type="text"
                                       id="custom_name"
                                       name="custom_name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="My Awesome Template">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center">
                                    <input type="checkbox" id="auto_activate" name="auto_activate" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Activate immediately</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="custom_description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea id="custom_description"
                                      name="custom_description"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Description for this template..."></textarea>
                        </div>

                        <div class="flex space-x-3">
                            <button type="button" id="analyzeBtn" class="btn btn-secondary flex-1">
                                <i class="fas fa-search mr-2"></i>Analyze First
                            </button>
                            <button type="submit" id="importBtn" class="btn btn-primary flex-1">
                                <i class="fas fa-download mr-2"></i>Import Now
                            </button>
                        </div>
                    </form>

                    <!-- Analysis Results -->
                    <div id="analysisResults" class="hidden mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3">Template Analysis</h4>
                        <div id="analysisContent"></div>
                    </div>
                </div>
            </div>

            <!-- Import from File -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-upload text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Import from File</h3>
                            <p class="text-sm text-gray-600">Upload JSON, ZIP, or HTML template files</p>
                        </div>
                    </div>

                    <form id="fileImportForm" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="template_file" class="block text-sm font-medium text-gray-700 mb-2">Template File</label>
                            <div class="relative">
                                <input type="file"
                                       id="template_file"
                                       name="file"
                                       accept=".json,.zip,.html,.htm"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                       required>
                                <div class="mt-1 text-xs text-gray-500">
                                    Supports JSON, ZIP, and HTML files (max 10MB)
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="file_template_name" class="block text-sm font-medium text-gray-700 mb-2">Template Name</label>
                            <input type="text"
                                   id="file_template_name"
                                   name="template_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Auto-detected from file">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="file_auto_activate" name="auto_activate" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <label for="file_auto_activate" class="ml-2 text-sm text-gray-700">Activate template after import</label>
                        </div>

                        <button type="submit" id="fileImportBtn" class="btn btn-primary w-full bg-purple-600 hover:bg-purple-700">
                            <i class="fas fa-upload mr-2"></i>Import Template
                        </button>
                    </form>

                    <!-- File Analysis Results -->
                    <div id="fileAnalysisResults" class="hidden mt-6 p-4 bg-purple-50 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3">File Analysis</h4>
                        <div id="fileAnalysisContent"></div>
                    </div>
                </div>
            </div>

            <!-- Discover Templates -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fab fa-github text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Discover Templates</h3>
                            <p class="text-sm text-gray-600">Browse curated templates from GitHub and other sources</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="source_select" class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                            <select id="source_select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all">All Sources</option>
                                <option value="github_school_templates">GitHub School Templates</option>
                                <option value="github_education_themes">Education Themes</option>
                                <option value="free_css_school">Free CSS Templates</option>
                            </select>
                        </div>

                        <button type="button" id="discoverBtn" class="btn btn-primary w-full">
                            <i class="fas fa-search mr-2"></i>Discover Templates
                        </button>
                    </div>

                    <!-- Discovery Results -->
                    <div id="discoveryResults" class="hidden mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Found Templates</h4>
                        <div id="discoveryContent" class="space-y-3 max-h-96 overflow-y-auto"></div>
                    </div>
                </div>
            </div>
        </div>        <!-- Import Progress -->
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

        <!-- Recent Imports -->
        @if(count($recent_imports) > 0)
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Imports</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recent_imports as $template)
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="aspect-w-16 aspect-h-9 mb-3">
                            <img src="{{ $template->preview_image_url }}"
                                 alt="{{ $template->name }}"
                                 class="w-full h-32 object-cover rounded">
                        </div>
                        <h4 class="font-medium text-gray-900 mb-1">{{ $template->name }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $template->created_at->format('M d, Y') }}</p>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $template->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <a href="{{ route('admin.templates.my-templates.edit', $template->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Edit
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Import Statistics -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-download text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Imports</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $import_stats['total_imports'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Active Templates</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $import_stats['active_imports'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-magic text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Auto-Translated</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $import_stats['successful_imports'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="bg-orange-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Import</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $import_stats['last_import'] ? $import_stats['last_import']->diffForHumans() : 'Never' }}
                        </p>
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
            <h3 class="text-lg font-medium text-gray-900" id="successTitle">Template Imported Successfully!</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="successMessage">Your template has been imported and is ready to use.</p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeSuccessModal" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlImportForm = document.getElementById('urlImportForm');
    const analyzeBtn = document.getElementById('analyzeBtn');
    const importBtn = document.getElementById('importBtn');
    const discoverBtn = document.getElementById('discoverBtn');
    const sourceSelect = document.getElementById('source_select');
    const analysisResults = document.getElementById('analysisResults');
    const discoveryResults = document.getElementById('discoveryResults');
    const importProgress = document.getElementById('importProgress');
    const successModal = document.getElementById('successModal');

    // File import elements
    const fileImportForm = document.getElementById('fileImportForm');
    const fileImportBtn = document.getElementById('fileImportBtn');
    const fileAnalysisResults = document.getElementById('fileAnalysisResults');

    // Analyze URL
    analyzeBtn.addEventListener('click', async function() {
        const url = document.getElementById('template_url').value;
        if (!url) {
            alert('Please enter a URL to analyze');
            return;
        }

        try {
            analyzeBtn.disabled = true;
            analyzeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Analyzing...';

            const response = await fetch('{{ route("admin.templates.smart-import.analyze") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ url: url })
            });

            const result = await response.json();

            if (result.success) {
                showAnalysisResults(result.analysis);
            } else {
                alert('Analysis failed: ' + result.error);
            }
        } catch (error) {
            alert('Analysis failed: ' + error.message);
        } finally {
            analyzeBtn.disabled = false;
            analyzeBtn.innerHTML = '<i class="fas fa-search mr-2"></i>Analyze First';
        }
    });

    // Import from URL
    urlImportForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(urlImportForm);
        const data = Object.fromEntries(formData.entries());
        data.auto_activate = document.getElementById('auto_activate').checked;

        try {
            showImportProgress();

            const response = await fetch('{{ route("admin.templates.smart-import.import-url") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            hideImportProgress();

            if (result.success) {
                showSuccessModal(result.message, result.template.name);
                // Optional: redirect to edit page
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 2000);
                }
            } else {
                alert('Import failed: ' + result.error);
            }
        } catch (error) {
            hideImportProgress();
            alert('Import failed: ' + error.message);
        }
    });

    // File Import
    fileImportForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('File import form submitted');

        const formData = new FormData(fileImportForm);
        console.log('FormData created:', formData);

        try {
            fileImportBtn.disabled = true;
            fileImportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Importing...';
            fileAnalysisResults.classList.add('hidden');

            const response = await fetch('{{ route("admin.templates.smart-import.import-file") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            console.log('Response received:', response.status);
            const result = await response.json();
            console.log('Response data:', result);

            if (result.success) {
                showFileAnalysisResults(result);
                if (result.template.is_active) {
                    showSuccessModal('Template imported and activated!', result.template.name);
                    setTimeout(() => {
                        if (confirm('Template is now active! View homepage?')) {
                            window.open('/', '_blank');
                        }
                    }, 1000);
                } else {
                    showSuccessModal('Template imported successfully!', result.template.name);
                }
            } else {
                showFileError(result.error);
            }
        } catch (error) {
            showFileError('Import failed: ' + error.message);
        } finally {
            fileImportBtn.disabled = false;
            fileImportBtn.innerHTML = '<i class="fas fa-upload mr-2"></i>Import Template';
        }
    });

    // File change handler for auto-detection
    document.getElementById('template_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const nameField = document.getElementById('file_template_name');
            if (!nameField.value) {
                // Auto-generate name from filename
                const name = file.name.replace(/\.(json|zip|html|htm)$/i, '').replace(/[-_]/g, ' ');
                nameField.value = name.charAt(0).toUpperCase() + name.slice(1);
            }
        }
    });

    // Discover templates
    discoverBtn.addEventListener('click', async function() {
        const source = sourceSelect.value;

        try {
            discoverBtn.disabled = true;
            discoverBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Discovering...';

            const response = await fetch('{{ route("admin.templates.smart-import.discover") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ source: source, limit: 10 })
            });

            const result = await response.json();

            if (result.success) {
                showDiscoveryResults(result.templates);
            } else {
                alert('Discovery failed: ' + result.error);
            }
        } catch (error) {
            alert('Discovery failed: ' + error.message);
        } finally {
            discoverBtn.disabled = false;
            discoverBtn.innerHTML = '<i class="fas fa-search mr-2"></i>Discover Templates';
        }
    });

    // Helper functions
    function showAnalysisResults(analysis) {
        const content = document.getElementById('analysisContent');
        content.innerHTML = `
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <h5 class="font-medium text-gray-900 mb-2">Template Info</h5>
                    <p class="text-sm text-gray-600"><strong>Title:</strong> ${analysis.title || 'Unknown'}</p>
                    <p class="text-sm text-gray-600"><strong>Framework:</strong> ${analysis.structure.framework || 'Unknown'}</p>
                </div>
                <div>
                    <h5 class="font-medium text-gray-900 mb-2">Language Detection</h5>
                    <p class="text-sm text-gray-600"><strong>Detected:</strong> ${analysis.language.detected}</p>
                    <p class="text-sm text-gray-600"><strong>Confidence:</strong> ${Math.round(analysis.language.confidence * 100)}%</p>
                    ${analysis.language.needs_translation ? '<p class="text-sm text-amber-600"><strong>Translation:</strong> Will be auto-translated to Indonesian</p>' : '<p class="text-sm text-green-600"><strong>Translation:</strong> No translation needed</p>'}
                </div>
            </div>
            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-700">✓ Template is ready for import with automatic conversion and translation</p>
            </div>
        `;
        analysisResults.classList.remove('hidden');
    }

    function showDiscoveryResults(templates) {
        const content = document.getElementById('discoveryContent');
        content.innerHTML = templates.map(template => `
            <div class="border rounded-lg p-3 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h5 class="font-medium text-gray-900">${template.name}</h5>
                        <p class="text-sm text-gray-600 mt-1">${template.description}</p>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">${template.author}</span>
                            ${template.features && template.features.slice(0, 2).map(feature =>
                                `<span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">${feature}</span>`
                            ).join('')}
                        </div>
                    </div>
                    <button onclick="installTemplate('${template.external_id}', ${JSON.stringify(template).replace(/"/g, '&quot;')})"
                            class="ml-3 px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                        Install
                    </button>
                </div>
            </div>
        `).join('');
        discoveryResults.classList.remove('hidden');
    }

    function showImportProgress() {
        importProgress.classList.remove('hidden');
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
            }
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressPercentage').textContent = Math.round(progress) + '%';

            // Update progress text based on percentage
            if (progress < 25) {
                document.getElementById('progressText').textContent = 'Analyzing template structure...';
            } else if (progress < 50) {
                document.getElementById('progressText').textContent = 'Detecting language and extracting content...';
            } else if (progress < 75) {
                document.getElementById('progressText').textContent = 'Converting to CMS format...';
            } else if (progress < 95) {
                document.getElementById('progressText').textContent = 'Translating content to Indonesian...';
            } else {
                document.getElementById('progressText').textContent = 'Finalizing template...';
            }
        }, 500);
    }

    function hideImportProgress() {
        importProgress.classList.add('hidden');
    }

    function showSuccessModal(message, templateName) {
        document.getElementById('successTitle').textContent = 'Template "' + templateName + '" Imported!';
        document.getElementById('successMessage').textContent = message;
        successModal.classList.remove('hidden');
    }

    function showFileAnalysisResults(result) {
        const content = document.getElementById('fileAnalysisContent');
        content.innerHTML = `
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-900">Template Name:</span>
                    <span class="text-gray-600">${result.template.name}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-900">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${result.template.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${result.template.is_active ? 'Active' : 'Imported'}
                    </span>
                </div>
                ${result.stats ? `
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-900">Sections:</span>
                    <span class="text-gray-600">${result.stats.sections_created || 0}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-900">Blocks:</span>
                    <span class="text-gray-600">${result.stats.blocks_created || 0}</span>
                </div>
                ` : ''}
                <div class="pt-3 border-t border-purple-200">
                    <p class="text-sm text-green-600">✓ Template successfully imported and ready to use</p>
                </div>
            </div>
        `;
        fileAnalysisResults.classList.remove('hidden');
    }

    function showFileError(errorMessage) {
        const content = document.getElementById('fileAnalysisContent');
        content.innerHTML = `
            <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start">
                    <span class="text-red-600 mr-2">❌</span>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800">Import Failed</p>
                        <p class="text-sm text-red-600 mt-1">${errorMessage}</p>
                        <div class="mt-2 text-xs text-red-600">
                            <p>• Pastikan file JSON, ZIP, atau HTML valid</p>
                            <p>• Ukuran file maksimal 10MB</p>
                            <p>• File JSON harus berisi struktur template yang benar</p>
                            <p>• File HTML akan dikonversi otomatis ke format CMS</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        fileAnalysisResults.classList.remove('hidden');
    }

    // Close success modal
    document.getElementById('closeSuccessModal').addEventListener('click', function() {
        successModal.classList.add('hidden');
    });

    // Global function for template installation
    window.installTemplate = async function(externalId, templateData) {
        try {
            const response = await fetch('{{ route("admin.templates.smart-import.install-external") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    external_id: externalId,
                    template_data: templateData,
                    auto_activate: false
                })
            });

            const result = await response.json();

            if (result.success) {
                showSuccessModal(result.message, result.template.name);
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 2000);
                }
            } else {
                alert('Installation failed: ' + result.error);
            }
        } catch (error) {
            alert('Installation failed: ' + error.message);
        }
    };
});
</script>
@endpush
