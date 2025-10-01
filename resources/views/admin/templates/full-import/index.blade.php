@extends('layouts.admin')

@section('title', 'Full Template Import')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Full Template Import</h1>
        <p class="text-gray-600 mt-2">Import complete website templates from GitHub, ZIP files, or URLs. Works like WordPress themes!</p>
    </div>

    <!-- Import Tabs -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex">
                <button class="tab-btn active py-2 px-4 border-b-2 border-blue-500 text-blue-600 font-medium" data-tab="github">
                    GitHub Repository
                </button>
                <button class="tab-btn py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="url">
                    Website URL
                </button>
                <button class="tab-btn py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="zip">
                    ZIP Upload
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- GitHub Import -->
            <div id="github-tab" class="tab-content">
                <h3 class="text-lg font-medium mb-4">Import from GitHub Repository</h3>
                <form id="github-form" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">GitHub Repository URL</label>
                        <input type="url" name="source" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="https://github.com/username/repository" required>
                        <p class="text-sm text-gray-500 mt-1">Example: https://github.com/startbootstrap/startbootstrap-agency</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Template Name (Optional)</label>
                            <input type="text" name="name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="My Awesome Template">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Branch (Optional)</label>
                            <input type="text" name="branch" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="main" value="main">
                        </div>
                    </div>
                    <input type="hidden" name="type" value="github">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Import Template
                    </button>
                </form>
            </div>

            <!-- URL Import -->
            <div id="url-tab" class="tab-content hidden">
                <h3 class="text-lg font-medium mb-4">Import from Website URL</h3>
                <form id="url-form" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website URL</label>
                        <input type="url" name="source" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="https://example.com" required>
                        <p class="text-sm text-gray-500 mt-1">The system will crawl and download the entire website</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Template Name (Optional)</label>
                        <input type="text" name="name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Website Template">
                    </div>
                    <input type="hidden" name="type" value="url">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Import Template
                    </button>
                </form>
            </div>

            <!-- ZIP Upload -->
            <div id="zip-tab" class="tab-content hidden">
                <h3 class="text-lg font-medium mb-4">Upload ZIP Template</h3>
                <form id="zip-form" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ZIP File</label>
                        <input type="file" name="zip_file" accept=".zip" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <p class="text-sm text-gray-500 mt-1">Upload a ZIP file containing your template (max 50MB)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Template Name (Optional)</label>
                        <input type="text" name="name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Uploaded Template">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Upload Template
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <h3 class="text-lg font-medium mb-2">Importing Template...</h3>
                <p class="text-gray-600">This may take a few minutes depending on template size.</p>
            </div>
        </div>
    </div>

    <!-- Recent Imports -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Recent Full Templates</h2>
            <a href="{{ route('admin.templates.full-import.list') }}" class="text-blue-600 hover:text-blue-800">View All</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="recent-templates">
            <!-- Recent templates will be loaded here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Update button states
            tabBtns.forEach(b => b.classList.remove('active', 'border-blue-500', 'text-blue-600'));
            tabBtns.forEach(b => b.classList.add('border-transparent', 'text-gray-500'));
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Update content visibility
            tabContents.forEach(content => content.classList.add('hidden'));
            document.getElementById(tabId + '-tab').classList.remove('hidden');
        });
    });
    
    // Form submissions
    const forms = ['github-form', 'url-form'];
    forms.forEach(formId => {
        document.getElementById(formId).addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const loadingModal = document.getElementById('loading-modal');
            
            loadingModal.classList.remove('hidden');
            loadingModal.classList.add('flex');
            
            fetch('{{ route('admin.templates.full-import.import') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                loadingModal.classList.add('hidden');
                loadingModal.classList.remove('flex');
                
                if (data.success) {
                    alert(data.message + `\nFiles imported: ${data.files_imported}`);
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                loadingModal.classList.add('hidden');
                loadingModal.classList.remove('flex');
                alert('Error: ' + error.message);
            });
        });
    });
    
    // ZIP form submission
    document.getElementById('zip-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const loadingModal = document.getElementById('loading-modal');
        
        loadingModal.classList.remove('hidden');
        loadingModal.classList.add('flex');
        
        fetch('{{ route('admin.templates.full-import.upload') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingModal.classList.add('hidden');
            loadingModal.classList.remove('flex');
            
            if (data.success) {
                alert(data.message + `\nFiles imported: ${data.files_imported}`);
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            loadingModal.classList.add('hidden');
            loadingModal.classList.remove('flex');
            alert('Error: ' + error.message);
        });
    });
});
</script>
@endpush
@endsection