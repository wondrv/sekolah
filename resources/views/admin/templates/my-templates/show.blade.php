@extends('layouts.admin')

@section('title', $userTemplate->name)

@section('header')
<div class="flex justify-between items-center">
    <div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('templates.my-templates') }}" class="text-gray-400 hover:text-gray-500">
                        My Templates
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-4 text-gray-500">{{ $userTemplate->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $userTemplate->name }}</h1>
        @if($userTemplate->description)
        <p class="text-gray-600">{{ $userTemplate->description }}</p>
        @endif
    </div>

    <div class="flex items-center space-x-3">
        @if($userTemplate->is_active)
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
            ✅ Active Template
        </span>
        @endif

        <div class="flex space-x-2">
            @if(!$userTemplate->is_active)
            <form method="POST" action="{{ route('templates.my-templates.activate', $userTemplate) }}" class="inline">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700"
                        onclick="return confirm('Activate this template?')">
                    <span class="mr-2">✅</span>
                    Activate
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('templates.my-templates.deactivate', $userTemplate) }}" class="inline">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                        onclick="return confirm('Deactivate this template?')">
                    <span class="mr-2">⏸️</span>
                    Deactivate
                </button>
            </form>
            @endif

            <a href="{{ route('templates.builder.edit', $userTemplate) }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="mr-2">✏️</span>
                Edit
            </a>

            <form method="POST" action="{{ route('templates.my-templates.duplicate', $userTemplate) }}" class="inline">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <span class="mr-2">📋</span>
                    Duplicate
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Template Info -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Preview -->
            <div class="lg:col-span-1">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
                <div class="aspect-w-4 aspect-h-3 bg-gray-100 rounded-lg overflow-hidden">
                    @if($userTemplate->preview_image)
                    <img src="{{ $userTemplate->preview_image_url }}"
                         alt="{{ $userTemplate->name }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <span class="text-6xl">🎨</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Details -->
            <div class="lg:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Template Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Source</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium
                                {{ $userTemplate->source === 'gallery' ? 'bg-purple-100 text-purple-800' :
                                   ($userTemplate->source === 'custom' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($userTemplate->source) }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium
                                {{ $userTemplate->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $userTemplate->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $userTemplate->created_at->format('M j, Y \a\t g:i A') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $userTemplate->updated_at->format('M j, Y \a\t g:i A') }}</dd>
                    </div>

                    @if($userTemplate->galleryTemplate)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Gallery Template</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $userTemplate->galleryTemplate->name }}</dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $userTemplate->slug }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Template Structure -->
    @if($userTemplate->templates && $userTemplate->templates->count() > 0)
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Template Structure</h3>
        <div class="space-y-4">
            @foreach($userTemplate->templates as $template)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-md font-medium text-gray-900">{{ $template->name }}</h4>
                        @if($template->description)
                        <p class="text-sm text-gray-600 mt-1">{{ $template->description }}</p>
                        @endif

                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                            <span>Type: {{ ucfirst($template->type) }}</span>
                            @if($template->sections)
                            <span>{{ $template->sections->count() }} sections</span>
                            @endif
                            @if($template->active)
                            <span class="text-green-600">Active</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($template->sections && $template->sections->count() > 0)
                <div class="mt-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Sections</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($template->sections->sortBy('order') as $section)
                        <div class="bg-gray-50 rounded p-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">{{ $section->name }}</span>
                                <span class="text-xs text-gray-500">{{ $section->blocks->count() }} blocks</span>
                            </div>
                            @if($section->blocks->count() > 0)
                            <div class="mt-2">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($section->blocks->sortBy('order') as $block)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $block->type }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Export Options -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Export Template</h3>
        <form method="POST" action="{{ route('templates.my-templates.export', $userTemplate) }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="format" class="block text-sm font-medium text-gray-700">Export Format</label>
                    <select name="format" id="format" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="json">JSON File</option>
                        <option value="zip">ZIP Archive</option>
                    </select>
                </div>

                <div id="expires-hours-container" style="display: none;">
                    <label for="expires_hours" class="block text-sm font-medium text-gray-700">Download Expires In</label>
                    <select name="expires_hours" id="expires_hours" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="1">1 Hour</option>
                        <option value="6">6 Hours</option>
                        <option value="24" selected>24 Hours</option>
                        <option value="72">3 Days</option>
                        <option value="168">1 Week</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center">
                    <input type="checkbox" name="include_content" id="include_content" value="1" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="include_content" class="ml-2 block text-sm text-gray-900">Include demo content</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="include_images" id="include_images" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="include_images" class="ml-2 block text-sm text-gray-900">Include images (ZIP only)</label>
                </div>
            </div>

            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <span class="mr-2">📥</span>
                Export Template
            </button>
        </form>
    </div>

    @if(!$userTemplate->is_active)
    <!-- Danger Zone -->
    <div class="bg-white shadow rounded-lg p-6 border-l-4 border-red-400">
        <h3 class="text-lg font-medium text-red-900 mb-4">Danger Zone</h3>
        <p class="text-sm text-red-700 mb-4">
            Once you delete a template, there is no going back. Please be certain.
        </p>
        <form method="POST" action="{{ route('templates.my-templates.destroy', $userTemplate) }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700"
                    onclick="return confirm('Are you sure you want to delete this template? This action cannot be undone.')">
                <span class="mr-2">🗑️</span>
                Delete Template
            </button>
        </form>
    </div>
    @endif
</div>

<script>
document.getElementById('format').addEventListener('change', function() {
    const expiresContainer = document.getElementById('expires-hours-container');
    if (this.value === 'zip') {
        expiresContainer.style.display = 'block';
    } else {
        expiresContainer.style.display = 'none';
    }
});
</script>
@endsection
