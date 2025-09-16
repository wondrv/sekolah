@extends('layouts.admin')

@section('title', 'Create Template')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Template</h1>
            <p class="text-gray-600 mt-2">Design a new template for your website</p>
        </div>
        <a href="{{ route('admin.templates.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Templates
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.templates.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Template Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter template name" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Template Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           placeholder="template-slug" required>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">URL-friendly identifier for this template</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Template Type</label>
                    <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Type</option>
                        <option value="homepage" {{ old('type') == 'homepage' ? 'selected' : '' }}>Homepage</option>
                        <option value="about" {{ old('type') == 'about' ? 'selected' : '' }}>About Page</option>
                        <option value="contact" {{ old('type') == 'contact' ? 'selected' : '' }}>Contact Page</option>
                        <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>News Page</option>
                        <option value="events" {{ old('type') == 'events' ? 'selected' : '' }}>Events Page</option>
                        <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Custom Page</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Describe the purpose of this template">{{ old('description') }}</textarea>
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
                    <!-- Dynamic sections will be added here -->
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Active Template</label>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('admin.templates.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>Create Template
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let sectionCount = 0;
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

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Section Type</label>
                    <select name="sections[${sectionCount}][type]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Type</option>
                        <option value="hero">Hero Section</option>
                        <option value="content">Content Section</option>
                        <option value="gallery">Gallery Section</option>
                        <option value="testimonials">Testimonials</option>
                        <option value="stats">Statistics</option>
                        <option value="cta">Call to Action</option>
                        <option value="news">News & Updates</option>
                        <option value="events">Events</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Section Settings (JSON)</label>
                    <textarea name="sections[${sectionCount}][settings]" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                              placeholder='{"background": "white", "text_color": "dark", "padding": "large"}'>{}</textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="sections[${sectionCount}][is_active]" value="1" checked
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label class="ml-2 text-sm font-medium text-gray-700">Active Section</label>
                </div>
            </div>
        `;

        sectionsContainer.insertAdjacentHTML('beforeend', sectionHtml);
    });

    // Remove section functionality
    sectionsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-section')) {
            e.target.closest('.section-item').remove();
        }
    });

    // Add initial section
    addSectionBtn.click();
});
</script>
@endsection
