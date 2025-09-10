@extends('layouts.admin')

@section('title', 'Template Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $template->name }}</h1>
            <p class="text-gray-600 mt-2">Template Type: {{ ucfirst($template->type) }} |
                Status: <span class="px-2 py-1 text-xs rounded-full {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                </span>
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.templates.edit', $template) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Edit Template
            </a>
            <a href="{{ route('admin.templates.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Templates
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Template Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Template Information</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="text-gray-900">{{ $template->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <p class="text-gray-900">{{ ucfirst($template->type) }}</p>
                    </div>

                    @if($template->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="text-gray-900">{{ $template->description }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sections Count</label>
                        <p class="text-gray-900">{{ $template->sections->count() }} sections</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created</label>
                        <p class="text-gray-900">{{ $template->created_at->format('M d, Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                        <p class="text-gray-900">{{ $template->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>

                @if($template->is_active)
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span class="text-green-800 font-medium">This template is currently active</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Template Sections -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Template Sections</h3>
                    <span class="text-sm text-gray-600">{{ $template->sections->count() }} sections</span>
                </div>

                @if($template->sections->count() > 0)
                <div class="space-y-4">
                    @foreach($template->sections->sortBy('order') as $section)
                    <div class="border border-gray-200 rounded-lg p-4 {{ $section->is_active ? 'bg-white' : 'bg-gray-50' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">
                                    #{{ $section->order }}
                                </span>
                                <h4 class="text-md font-semibold text-gray-900">{{ $section->name }}</h4>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                                    {{ ucfirst($section->type) }}
                                </span>
                            </div>
                        </div>

                        @if($section->blocks->count() > 0)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Blocks ({{ $section->blocks->count() }}):</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($section->blocks->sortBy('order') as $block)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $block->is_active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $block->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($section->settings && $section->settings !== '{}')
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 mb-1">Settings:</p>
                            <div class="bg-gray-100 rounded p-2">
                                <pre class="text-xs text-gray-800 overflow-x-auto">{{ is_array($section->settings) ? json_encode($section->settings, JSON_PRETTY_PRINT) : $section->settings }}</pre>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-layer-group text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">No sections configured for this template.</p>
                    <a href="{{ route('admin.templates.edit', $template) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Add Sections
                    </a>
                </div>
                @endif
            </div>

            <!-- Template Actions -->
            <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Template Actions</h3>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.templates.edit', $template) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit Template
                    </a>

                    @if(!$template->is_active)
                    <form action="{{ route('admin.templates.update', $template) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition duration-200">
                            <i class="fas fa-check mr-2"></i>Activate Template
                        </button>
                    </form>
                    @endif

                    <button type="button" onclick="duplicateTemplate()" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition duration-200">
                        <i class="fas fa-copy mr-2"></i>Duplicate Template
                    </button>

                    <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this template? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition duration-200">
                            <i class="fas fa-trash mr-2"></i>Delete Template
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function duplicateTemplate() {
    if (confirm('Create a copy of this template?')) {
        // Create a form to duplicate the template
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.templates.store") }}';

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add template data
        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.name = 'name';
        nameInput.value = '{{ $template->name }} (Copy)';
        form.appendChild(nameInput);

        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = '{{ $template->type }}';
        form.appendChild(typeInput);

        const descInput = document.createElement('input');
        descInput.type = 'hidden';
        descInput.name = 'description';
        descInput.value = '{{ $template->description }} (Duplicated from original)';
        form.appendChild(descInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
