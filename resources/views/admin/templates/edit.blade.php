@extends('layouts.admin')

@section('title', 'Edit Template')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Template</h1>
            <p class="text-gray-600 mt-2">Modify template: {{ $template->name }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <form action="{{ route('admin.templates.import_into', $template) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" accept="application/json" class="block text-sm" required>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition duration-200">Import JSON</button>
            </form>
            <a href="{{ route('admin.templates.export', $template) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-download mr-2"></i>Export
            </a>
            <a href="{{ route('admin.templates.show', $template) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-eye mr-2"></i>Preview
            </a>
            <a href="{{ route('admin.templates.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Templates
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.templates.update', $template) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Template Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $template->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter template name" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Template Slug (optional)</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $template->slug) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g. homepage">
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Describe the purpose of this template">{{ old('description', $template->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Template Sections</h3>
                    <button type="button" id="add-section" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Add Section
                    </button>
                </div>

                <div id="sections-container" class="space-y-4">
                    @foreach($template->sections as $index => $section)
                    <div class="section-item border border-gray-200 rounded-lg p-4" data-section="{{ $index + 1 }}">
                        <input type="hidden" name="sections[{{ $index + 1 }}][id]" value="{{ $section->id }}">

                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-md font-semibold text-gray-800">{{ $section->name }}</h4>
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        class="text-red-600 hover:text-red-800 cursor-pointer"
                                        data-delete-url="{{ route('admin.templates.sections.destroy', [$template, $section]) }}"
                                        data-confirm="hapus section: {{ $section->name }}">
                                    Hapus Section
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Section Name</label>
                                <input type="text" name="sections[{{ $index + 1 }}][name]" value="{{ $section->name }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter section name" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                                <input type="number" name="sections[{{ $index + 1 }}][order]" value="{{ $section->order }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       min="1" required>
                            </div>
                        </div>

                        <!-- Removed Section Type (not supported by Section model) -->

                        {{-- Settings JSON removed: Section model has no settings column. Keep UI minimal and valid. --}}

                        <div class="flex items-center mb-4">
                            <input type="checkbox" name="sections[{{ $index + 1 }}][is_active]" value="1" {{ ($section->is_active ?? $section->active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label class="ml-2 text-sm font-medium text-gray-700">Active Section</label>
                        </div>

                        @if($section->relationLoaded('blocks') && $section->blocks && count($section->blocks))
                        <div class="mt-4 border-t pt-4">
                            <h5 class="font-semibold text-gray-800 mb-2">Blocks</h5>
                            <ul class="space-y-2">
                                @foreach($section->blocks as $block)
                                    <li class="flex items-center justify-between bg-gray-50 rounded px-3 py-2">
                                        <span>
                                            <span class="inline-block text-xs px-2 py-0.5 rounded bg-gray-200 mr-2">#{{ $block->order }}</span>
                                            <span class="font-medium">{{ $block->type }}</span>
                                        </span>
                                        <button type="button" class="text-red-600 hover:text-red-800 cursor-pointer"
                                                data-delete-url="{{ route('admin.templates.blocks.destroy', [$template, $block]) }}"
                                                data-confirm="hapus block: {{ $block->type }} (#{{ $block->order }})">
                                            Hapus Block
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center mb-6">
                <input type="checkbox" id="active" name="active" value="1" {{ old('active', $template->active) ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="active" class="ml-2 text-sm font-medium text-gray-700">Active Template</label>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.templates.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>Update Template
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let sectionCount = {{ $template->sections->count() }};
    const sectionsContainer = document.getElementById('sections-container');
    const addSectionBtn = document.getElementById('add-section');

    addSectionBtn.addEventListener('click', function() {
        sectionCount++;
        const sectionHtml = `
            <div class="section-item border border-gray-200 rounded-lg p-4" data-section="${sectionCount}">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-md font-semibold text-gray-800">Section ${sectionCount}</h4>
                    <button type="button" class="remove-section text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Section Name</label>
                        <input type="text" name="sections[${sectionCount}][name]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter section name" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <input type="number" name="sections[${sectionCount}][order]" value="${sectionCount}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               min="1" required>
                    </div>
                </div>

                <!-- Removed Section Type (not supported by Section model) -->

                <!-- Settings JSON removed: not supported by schema -->

                <div class="flex items-center">
                    <input type="checkbox" name="sections[${sectionCount}][is_active]" value="1" checked
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label class="ml-2 text-sm font-medium text-gray-700">Active Section</label>
                </div>
            </div>
        `;

        sectionsContainer.insertAdjacentHTML('beforeend', sectionHtml);
    });

    // Remove section functionality (client-side only for newly added ones)
    sectionsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-section')) {
            e.target.closest('.section-item').remove();
        }
    });
});
 </script>
<script>
// Handle delete buttons without nesting forms
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-delete-url]');
    if (!btn) return;
    e.preventDefault();
    const url = btn.getAttribute('data-delete-url');
    const confirmMsg = btn.getAttribute('data-confirm') || 'Yakin ingin menghapus item ini?';
    if (!url) return;
    if (!confirm(confirmMsg)) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
});
</script>
@endsection


