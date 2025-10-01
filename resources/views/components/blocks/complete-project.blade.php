@props(['template', 'mainFile', 'assetsPath'])

@php
    $projectFiles = $template->template_data['templates'][0]['sections'][0]['blocks'][0]['content']['files'] ?? [];
    $storageUrl = url('storage');
    $mainFileUrl = isset($projectFiles[$mainFile]) ? $projectFiles[$mainFile]['public_url'] : '';
@endphp

<div class="complete-project-template">
    @if($mainFileUrl)
        <!-- Display the main HTML file in an iframe -->
        <div class="w-full h-screen border rounded-lg overflow-hidden">
            <iframe
                src="{{ $mainFileUrl }}"
                class="w-full h-full border-0"
                title="{{ $template->name }}"
                sandbox="allow-scripts allow-same-origin allow-forms"
            ></iframe>
        </div>
    @else
        <!-- Fallback display -->
        <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
            <div class="mb-6">
                <i class="fas fa-code text-6xl text-blue-500 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $template->name }}</h2>
                <p class="text-gray-600">Complete project template imported successfully</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $template->settings['project_structure']['html_count'] ?? 0 }}
                    </div>
                    <div class="text-sm text-blue-800">HTML Files</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $template->settings['project_structure']['css_count'] ?? 0 }}
                    </div>
                    <div class="text-sm text-green-800">CSS Files</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ $template->settings['project_structure']['js_count'] ?? 0 }}
                    </div>
                    <div class="text-sm text-yellow-800">JS Files</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">
                        {{ $template->settings['project_structure']['image_count'] ?? 0 }}
                    </div>
                    <div class="text-sm text-purple-800">Images</div>
                </div>
            </div>

            @if($mainFile && isset($projectFiles[$mainFile]))
                <div class="mb-4">
                    <a href="{{ $projectFiles[$mainFile]['public_url'] }}"
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        View Complete Project
                    </a>
                </div>
            @endif

            <div class="text-sm text-gray-500">
                <p>Main File: {{ $mainFile }}</p>
                <p>Total Files: {{ count($projectFiles) }}</p>
                <p>Imported: {{ $template->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    @endif
</div>

<style>
.complete-project-template iframe {
    border: none;
    width: 100%;
    height: 100%;
}
</style>
