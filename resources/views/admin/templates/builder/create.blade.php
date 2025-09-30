@extends('layouts.admin')

@section('title', 'Create New Template')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('admin.templates.builder.index') }}" class="text-gray-400 hover:text-gray-500">
                        Template Builder
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-4 text-gray-500">Create Template</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Create New Template</h1>
        <p class="text-gray-600">Build a custom template from scratch</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <form method="POST" action="{{ route('admin.templates.builder.store') }}" class="space-y-6 p-6">
            @csrf

            <!-- Template Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Template Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name') }}"
                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., My Custom Homepage"
                       required>
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description"
                          id="description"
                          rows="3"
                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Brief description of this template...">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Optional: Describe what this template is for and its key features.</p>
            </div>

            <!-- Category -->
            @if($categories->count() > 0)
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Category
                </label>
                <select name="category_id"
                        id="category_id"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a category (optional)</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <!-- Template Type Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Getting Started
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Your new template will start with a basic structure that you can customize:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Empty homepage layout ready for content blocks</li>
                                <li>Drag-and-drop visual builder interface</li>
                                <li>Access to all available block types (hero, cards, text, etc.)</li>
                                <li>Real-time preview of your changes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Block Types Preview -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Available Block Types</h3>
                <div class="space-y-6">
                    @foreach($blockTypes as $category => $blocks)
                    <div>
                        <h4 class="text-md font-medium text-gray-800 mb-3 capitalize">{{ $category }} Blocks</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($blocks as $type => $config)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                                            <span class="text-blue-600 text-sm">
                                                @switch($category)
                                                    @case('header') 🎯 @break
                                                    @case('content') 📝 @break
                                                    @case('info') 📊 @break
                                                    @case('marketing') 📢 @break
                                                    @case('media') 🖼️ @break
                                                    @default 🔧
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            {{ is_array($config) && isset($config['name']) ? $config['name'] : ucfirst(str_replace('-', ' ', $type)) }}
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            {{ is_array($config) && isset($config['description']) ? $config['description'] : 'Block component' }}
                                        </p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                            {{ ucfirst($category) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.templates.builder.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <span class="mr-2">←</span>
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="mr-2">✨</span>
                    Create Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
