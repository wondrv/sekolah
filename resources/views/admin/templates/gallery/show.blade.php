@extends('layouts.admin')

@section('title', $template->name . ' - Template Gallery')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('admin.templates.gallery.index') }}" class="text-gray-400 hover:text-gray-500">
                        Template Gallery
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-4 text-gray-500">{{ $template->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $template->name }}</h1>
        @if($template->description)
        <p class="text-gray-600">{{ $template->description }}</p>
        @endif
    </div>

    <div class="flex items-center space-x-3">
        @if($isInstalled)
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
            ✅ Installed
        </span>
        @endif

        <div class="flex space-x-2">
            @if($isInstalled && $userTemplate)
            <div class="flex space-x-2">
                @if(!$userTemplate->is_active)
                <form method="POST" action="{{ route('admin.templates.my-templates.activate', $userTemplate) }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700"
                            onclick="return confirm('Apply this template to your website?')">
                        <span class="mr-2">✨</span>
                        Apply Template
                    </button>
                </form>
                @else
                <span class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-green-50">
                    <span class="mr-2">✅</span>
                    Currently Active
                </span>
                @endif

                <a href="{{ route('admin.templates.my-templates.show', $userTemplate) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <span class="mr-2">👁️</span>
                    Manage Template
                </a>
            </div>
            @endif            @if(!$isInstalled)
            <form method="POST" action="{{ route('admin.templates.gallery.install', $template) }}" class="inline">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                        onclick="return confirm('Install this template?')">
                    <span class="mr-2">📥</span>
                    Install
                </button>
            </form>
            <form method="POST" action="{{ route('admin.templates.gallery.install', $template) }}" class="inline">
                @csrf
                <input type="hidden" name="activate" value="1" />
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700"
                        onclick="return confirm('Install dan langsung aktifkan template ini?')">
                    <span class="mr-2">⚡</span>
                    Install & Aktifkan
                </button>
            </form>
            @endif

            <button onclick="openPreview()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="mr-2">👁️</span>
                Preview
            </button>
            <a href="{{ route('admin.templates.gallery.live-preview', $template) }}"
               class="inline-flex items-center px-4 py-2 border border-indigo-300 rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                <span class="mr-2">⚡</span>
                Live Preview
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Template Overview -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
            <!-- Template Preview -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Template Preview</h3>
                <div class="aspect-w-4 aspect-h-3 bg-gray-100 rounded-lg overflow-hidden">
                    @if($template->preview_image)
                    <img src="{{ $template->preview_image_url }}"
                         alt="{{ $template->name }}"
                         class="w-full h-full object-cover cursor-pointer"
                         onclick="openPreview()">
                    @else
                    @php
                        $placeholderImages = [
                            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1497486751825-1233686d5d80?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop&crop=entropy&auto=format&q=80'
                        ];
                        $imageIndex = $template->id % count($placeholderImages);
                        $placeholderUrl = $placeholderImages[$imageIndex];
                    @endphp
                    <div class="relative w-full h-full cursor-pointer" onclick="openPreview()">
                        <img src="{{ $placeholderUrl }}"
                             alt="{{ $template->name }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white/90 backdrop-blur-sm rounded-lg px-4 py-2 text-center">
                                <span class="text-2xl mb-1 block">🎨</span>
                                <p class="text-sm text-gray-700 font-medium">Click to preview</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @if($template->preview_images && count($template->preview_images) > 1)
                <div class="mt-4 grid grid-cols-4 gap-2">
                    @foreach(array_slice($template->preview_images, 1, 4) as $image)
                    <div class="aspect-w-1 aspect-h-1 bg-gray-100 rounded overflow-hidden cursor-pointer"
                         onclick="openPreview()">
                        <img src="{{ $image }}" alt="Preview" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Template Details -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Template Information</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $template->category->name ?? 'Uncategorized' }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Version</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $template->version }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Author</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $template->author }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Downloads</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($template->downloads) }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Rating</dt>
                        <dd class="mt-1 flex items-center">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $template->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">({{ number_format($template->rating, 1) }}/5)</span>
                            </div>
                        </dd>
                    </div>

                    @if($template->tags && count($template->tags) > 0)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tags</dt>
                        <dd class="mt-1">
                            <div class="flex flex-wrap gap-1">
                                @foreach($template->tags as $tag)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $tag }}
                                </span>
                                @endforeach
                            </div>
                        </dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $template->updated_at->format('M j, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Template Features -->
    @if($template->features && count($template->features) > 0)
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Features</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($template->features as $feature)
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="ml-3 text-sm text-gray-700">{{ $feature }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Related Templates -->
    @if($relatedTemplates && $relatedTemplates->count() > 0)
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">Related Templates</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedTemplates as $related)
            <div class="border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-t-lg overflow-hidden">
                    @if($related->preview_image)
                    <img src="{{ $related->preview_image_url }}"
                         alt="{{ $related->name }}"
                         class="w-full h-full object-cover">
                    @else
                    @php
                        $relatedPlaceholders = [
                            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&h=250&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?w=400&h=250&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=400&h=250&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=400&h=250&fit=crop&crop=entropy&auto=format&q=80',
                            'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&h=250&fit=crop&crop=entropy&auto=format&q=80'
                        ];
                        $relatedIndex = $related->id % count($relatedPlaceholders);
                    @endphp
                    <img src="{{ $relatedPlaceholders[$relatedIndex] }}"
                         alt="{{ $related->name }}"
                         class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="p-4">
                    <h4 class="text-md font-medium text-gray-900 mb-1">{{ $related->name }}</h4>
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($related->description, 80) }}</p>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3 h-3 {{ $i <= $related->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            @endfor
                            <span class="ml-1 text-xs text-gray-600">{{ number_format($related->rating, 1) }}</span>
                        </div>

                        <a href="{{ route('admin.templates.gallery.show', $related) }}"
                           class="text-blue-600 hover:text-blue-900 text-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">{{ $template->name }} - Preview</h3>
            <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div id="previewContent" class="bg-gray-100 rounded-lg p-4">
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-2 text-gray-600">Loading preview...</p>
            </div>
        </div>
    </div>
</div>

<script>
function openPreview() {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');

    modal.classList.remove('hidden');

    // Fetch preview data
    fetch(`{{ route('admin.templates.gallery.preview', $template) }}`)
        .then(response => response.json())
        .then(data => {
            let previewHtml = '';

            if (data.preview_images && data.preview_images.length > 0) {
                previewHtml = `
                    <div class="grid grid-cols-1 gap-4">
                        ${data.preview_images.map(image => `
                            <div class="aspect-w-16 aspect-h-9 bg-white rounded overflow-hidden">
                                <img src="${image}" alt="Preview" class="w-full h-full object-contain">
                            </div>
                        `).join('')}
                    </div>
                `;
            } else {
                previewHtml = `
                    <div class="text-center py-12 text-gray-500">
                        <span class="text-6xl mb-4 block">🎨</span>
                        <p>No preview images available</p>
                    </div>
                `;
            }

            content.innerHTML = previewHtml;
        })
        .catch(error => {
            content.innerHTML = `
                <div class="text-center py-12 text-red-500">
                    <span class="text-6xl mb-4 block">❌</span>
                    <p>Failed to load preview</p>
                </div>
            `;
        });
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});
</script>
@endsection
