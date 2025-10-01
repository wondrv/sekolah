@extends('layouts.admin')

@section('title', 'My Templates')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Templates</h1>
        <p class="text-gray-600">Kelola template yang telah Anda install atau buat</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('admin.templates.gallery.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <span class="mr-2">🎨</span>
            Browse Gallery
        </a>
        <a href="{{ route('admin.templates.builder.create') }}"
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
            <span class="mr-2">➕</span>
            Create New Template
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">📱</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Templates</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">✅</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['active'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">🎨</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">From Gallery</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['gallery'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">🔧</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Custom</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['custom'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <label for="search" class="sr-only">Search</label>
                <input type="text"
                       name="search"
                       id="search"
                       value="{{ $request->search }}"
                       placeholder="Search templates..."
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Source Filter -->
            <div>
                <label for="source" class="sr-only">Source</label>
                <select name="source"
                        id="source"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Sources</option>
                    <option value="gallery" {{ $request->source === 'gallery' ? 'selected' : '' }}>Gallery</option>
                    <option value="custom" {{ $request->source === 'custom' ? 'selected' : '' }}>Custom</option>
                    <option value="imported" {{ $request->source === 'imported' ? 'selected' : '' }}>Imported</option>
                </select>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <span class="mr-2">🔍</span>
                Filter
            </button>

            @if($request->search || $request->source)
            <a href="{{ route('admin.templates.my-templates.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Templates Grid -->
    <div class="bg-white shadow rounded-lg">
        @if($templates->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            @foreach($templates as $template)
            <div class="relative border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                <!-- Active Badge -->
                @if($template->is_active)
                <div class="absolute top-3 right-3 z-10">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        ✅ Active
                    </span>
                </div>
                @endif

                <!-- Template Preview -->
                <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-t-lg overflow-hidden">
                    @if($template->preview_image)
                    <img src="{{ $template->preview_image_url }}"
                         alt="{{ $template->name }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <span class="text-4xl">🎨</span>
                    </div>
                    @endif
                </div>

                <!-- Template Info -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-medium text-gray-900">{{ $template->name }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $template->source === 'gallery' ? 'bg-purple-100 text-purple-800' :
                               ($template->source === 'custom' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($template->source) }}
                        </span>
                    </div>
                                @if($template->draft_template_data)
                                <div class="mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        📝 Draft Changes
                                    </span>
                                </div>
                                @endif

                    @if($template->description)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($template->description, 100) }}</p>
                    @endif

                    <div class="text-xs text-gray-500 mb-4">
                        Updated {{ $template->updated_at->diffForHumans() }}
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.templates.my-templates.show', $template) }}"
                               class="text-blue-600 hover:text-blue-900 text-sm">
                                View Details
                            </a>
                                        @if(!$template->is_active)
                                        <form method="POST" action="{{ route('admin.templates.my-templates.preview-start', $template) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="path" value="/">
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-sm">Preview</button>
                                        </form>
                                        @if($template->draft_template_data)
                                        <form method="POST" action="{{ route('admin.templates.my-templates.draft.preview', $template) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="path" value="/">
                                            <button type="submit" class="text-amber-600 hover:text-amber-800 text-sm">Preview Draft</button>
                                        </form>
                                        @endif
                                        @endif
                            @if(!$template->is_active)
                            <form method="POST" action="{{ route('admin.templates.my-templates.activate', $template) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-green-600 hover:text-green-900 text-sm"
                                        onclick="return confirm('Activate this template?')">
                                    Activate
                                </button>
                            </form>
                            @endif
                        </div>

                        <!-- Dropdown Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="text-gray-400 hover:text-gray-600">
                                <span class="sr-only">Open options</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>

                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                <div class="py-1">
                                    <a href="{{ route('admin.templates.builder.edit', $template) }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Edit Template
                                    </a>
                                    <form method="POST" action="{{ route('admin.templates.my-templates.duplicate', $template) }}" class="inline w-full">
                                        @csrf
                                        <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Duplicate
                                        </button>
                                    </form>
                                    @if($template->is_active)
                                    <form method="POST" action="{{ route('admin.templates.my-templates.deactivate', $template) }}" class="inline w-full">
                                        @csrf
                                        <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                onclick="return confirm('Deactivate this template?')">
                                            Deactivate
                                        </button>
                                    </form>
                                    @endif
                                    @if(!$template->is_active)
                                    <form method="POST" action="{{ route('admin.templates.my-templates.destroy', $template) }}" class="inline w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50"
                                                onclick="return confirm('Delete this template permanently?')">
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($templates->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $templates->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <span class="text-4xl">📱</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No templates found</h3>
            <p class="text-gray-600 mb-6">
                @if($request->search || $request->source)
                    No templates match your current filters. Try adjusting your search criteria.
                @else
                    You haven't installed or created any templates yet.
                @endif
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('admin.templates.gallery.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <span class="mr-2">🎨</span>
                    Browse Template Gallery
                </a>
                <a href="{{ route('admin.templates.builder.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <span class="mr-2">➕</span>
                    Create Custom Template
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
