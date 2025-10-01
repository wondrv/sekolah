@extends('layouts.admin')

@section('title', 'Edit Template - ' . $userTemplate->name)

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Template</h1>
                    <p class="text-gray-600 mt-2">{{ $userTemplate->name }}</p>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.templates.my-templates.show', $userTemplate) }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Template
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Template Information</h2>

                        <form action="{{ route('admin.templates.my-templates.update', $userTemplate) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-4">
                                <!-- Template Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Template Name</label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           value="{{ old('name', $userTemplate->name) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea name="description"
                                              id="description"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Template description...">{{ old('description', $userTemplate->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Save Button -->
                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-save mr-2"></i>Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Template Structure -->
                <div class="bg-white rounded-lg shadow mt-6">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Template Structure</h2>

                        @if(isset($userTemplate->template_data['templates']))
                            @foreach($userTemplate->template_data['templates'] as $templateIndex => $template)
                                <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                    <h3 class="font-medium text-gray-900 mb-3">{{ $template['name'] ?? 'Template ' . ($templateIndex + 1) }}</h3>

                                    @if(isset($template['sections']) && is_array($template['sections']))
                                        <div class="space-y-3">
                                            @foreach($template['sections'] as $sectionIndex => $section)
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <h4 class="font-medium text-gray-800 mb-2">
                                                        <i class="fas fa-layer-group mr-2 text-blue-500"></i>
                                                        {{ $section['name'] ?? 'Section ' . ($sectionIndex + 1) }}
                                                    </h4>

                                                    @if(isset($section['blocks']) && is_array($section['blocks']))
                                                        <div class="ml-4 space-y-2">
                                                            @foreach($section['blocks'] as $blockIndex => $block)
                                                                <div class="flex items-center text-sm text-gray-600">
                                                                    <i class="fas fa-cube mr-2 text-green-500"></i>
                                                                    <span class="capitalize">{{ $block['type'] ?? 'unknown' }}</span>
                                                                    @if(isset($block['data']['title']))
                                                                        <span class="ml-2 text-gray-500">- {{ Str::limit($block['data']['title'], 50) }}</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 text-sm">No sections found in this template.</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                                <p class="text-gray-600">No template structure found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Template Info -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Template Details</h3>

                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Status:</span>
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $userTemplate->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $userTemplate->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-700">Source:</span>
                            <span class="ml-2 text-sm text-gray-600 capitalize">{{ $userTemplate->source }}</span>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-700">Created:</span>
                            <span class="ml-2 text-sm text-gray-600">{{ $userTemplate->created_at->format('M d, Y') }}</span>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-700">Updated:</span>
                            <span class="ml-2 text-sm text-gray-600">{{ $userTemplate->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        @if(!$userTemplate->is_active)
                            <form action="{{ route('admin.templates.my-templates.activate', $userTemplate) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                    <i class="fas fa-play mr-2"></i>Activate Template
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.templates.my-templates.deactivate', $userTemplate) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                                    <i class="fas fa-pause mr-2"></i>Deactivate Template
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.templates.my-templates.duplicate', $userTemplate) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-copy mr-2"></i>Duplicate Template
                            </button>
                        </form>

                        <a href="{{ route('admin.templates.my-templates.export', $userTemplate) }}"
                           class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-center block">
                            <i class="fas fa-download mr-2"></i>Export Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
