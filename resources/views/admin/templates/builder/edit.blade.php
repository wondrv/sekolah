@extends('layouts.admin')

@section('title', 'Edit Template - ' . $userTemplate->name)

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
                        <span class="ml-4 text-gray-500">{{ $userTemplate->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit Template</h1>
        <p class="text-gray-600">{{ $userTemplate->name }}</p>
    </div>

    <div class="flex items-center space-x-3">
        @if($userTemplate->is_active)
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
            ✅ Active Template
        </span>
        @endif

        <div class="flex space-x-2">
            <a href="{{ route('admin.templates.builder.preview', $userTemplate) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="mr-2">👁️</span>
                Preview
            </a>

            <button id="saveTemplate"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <span class="mr-2">💾</span>
                Save Template
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<div id="templateBuilder" class="h-screen flex">
    <!-- Sidebar - Block Library -->
    <div class="w-96 bg-gradient-to-br from-gray-50 to-gray-100 border-r border-gray-200 flex flex-col shadow-lg">
        <div class="flex-shrink-0 px-6 py-6 border-b border-gray-200 bg-white">
            <div class="flex items-center mb-2">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-white text-lg font-bold">🧱</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Block Library</h3>
                    <p class="text-sm text-gray-600">Drag & drop to build your page</p>
                </div>
            </div>
            <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-xs text-blue-700 flex items-center">
                    <span class="mr-2">💡</span>
                    <strong>Tip:</strong> Click and drag any block below to your template
                </p>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-4 py-6">
            <div class="space-y-8">
                @foreach($blockTypes as $category => $blocks)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Light header bar so only text appears dark -->
                    <div class="px-4 py-3 bg-white/80 backdrop-blur-sm category-bar">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center no-select-highlight">
                            <span class="mr-2">
                                @switch($category)
                                    @case('header') 🎯 @break
                                    @case('content') 📝 @break
                                    @case('info') 📊 @break
                                    @case('marketing') 📢 @break
                                    @case('media') 🖼️ @break
                                    @default 🔧
                                @endswitch
                            </span>
                            {{ ucfirst($category) }} Blocks
                        </h4>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($blocks as $type => $config)
                        <div class="block-item bg-gradient-to-r from-white to-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-4 cursor-move hover:border-blue-400 hover:shadow-lg hover:from-blue-50 hover:to-blue-100 transition-all duration-300 transform hover:scale-105"
                             data-block-type="{{ $type }}"
                             data-block-config="{{ json_encode($config) }}"
                             draggable="true">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                    <span class="text-white text-lg">
                                        @if(is_array($config) && isset($config['category']))
                                            @switch($config['category'])
                                                @case('header') 🎯 @break
                                                @case('content') 📝 @break
                                                @case('info') 📊 @break
                                                @case('marketing') 📢 @break
                                                @case('media') 🖼️ @break
                                                @default 🔧
                                            @endswitch
                                        @else
                                            🔧
                                        @endif
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-sm font-bold text-gray-900 mb-1">
                                        {{ is_array($config) && isset($config['name']) ? $config['name'] : ucfirst($type) }}
                                    </h5>
                                    <p class="text-xs text-gray-600 leading-relaxed">
                                        {{ is_array($config) && isset($config['description']) ? $config['description'] : 'Block component' }}
                                    </p>
                                    <div class="mt-2 flex items-center">
                                        <span class="text-xs text-blue-600 font-medium">✋ Drag me to canvas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Builder Area -->
    <div class="flex-1 flex flex-col bg-gray-50">
        <!-- Builder Toolbar -->
        <div class="flex-shrink-0 bg-white border-b border-gray-200 px-6 py-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                            <span class="text-white text-sm font-bold">🎨</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Canvas</h3>
                            <p class="text-xs text-gray-600">Build your template here</p>
                        </div>
                    </div>
                    <div class="h-8 w-px bg-gray-300"></div>
                    <div class="flex items-center space-x-3">
                        <label class="text-sm font-semibold text-gray-700">Device Preview:</label>
                        <select id="devicePreview" class="border-gray-300 rounded-lg text-sm px-3 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="desktop">🖥️ Desktop</option>
                            <option value="tablet">📱 Tablet</option>
                            <option value="mobile">📱 Mobile</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button id="undoBtn" class="p-2 text-gray-400 hover:text-gray-600" disabled>
                            <span class="sr-only">Undo</span>
                            ↶
                        </button>
                        <button id="redoBtn" class="p-2 text-gray-400 hover:text-gray-600" disabled>
                            <span class="sr-only">Redo</span>
                            ↷
                        </button>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <span id="autoSaveStatus" class="text-sm text-gray-500">All changes saved</span>
                    <div id="autoSaveIndicator" class="w-2 h-2 bg-green-500 rounded-full hidden"></div>
                </div>
            </div>
        </div>

        <!-- Template Canvas -->
        <div class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-100 to-gray-200 p-8">
            <div class="flex justify-center">
                <div id="templateCanvas" class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden" style="width: 1200px; min-height: 900px;">
                    <!-- Canvas Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 font-bold">📄</span>
                                </div>
                                <div>
                                    <h4 class="text-black font-bold">{{ $userTemplate->name }}</h4>
                                    <p class="text-blue-100 text-sm">Template Preview</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-black text-sm">🔴 Live Edit Mode</span>
                            </div>
                        </div>
                    </div>

                    <!-- Template sections will be rendered here -->
                    <div id="templateSections" class="divide-y divide-gray-100">
                        @if(is_array($templateStructure) && isset($templateStructure['templates']) && is_array($templateStructure['templates']) && isset($templateStructure['templates'][0]) && isset($templateStructure['templates'][0]['sections']))
                            @foreach($templateStructure['templates'][0]['sections'] as $sectionIndex => $section)
                            <div class="section-container border border-gray-200 bg-white mb-1" data-section-index="{{ $sectionIndex }}">
                                <div class="section-header bg-gray-50 px-6 py-4 flex items-center justify-between border-b border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                                            <span class="text-gray-800 text-sm font-bold">📋</span>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-black force-black-text no-select-highlight">{{ $section['name'] }}</h4>
                                            <p class="text-gray-300 text-xs">Section {{ $sectionIndex + 1 }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button class="section-settings px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                            ⚙️ Settings
                                        </button>
                                        <button class="delete-section px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                            🗑️ Delete
                                        </button>
                                    </div>
                                </div>

                                <div class="section-content min-h-32 p-6 bg-gradient-to-br from-gray-50 to-white drop-zone border-t border-gray-200"
                                     data-section-index="{{ $sectionIndex }}">
                                    @if(isset($section['blocks']) && is_array($section['blocks']) && count($section['blocks']) > 0)
                                        @foreach($section['blocks'] as $blockIndex => $block)
                                        <div class="block-element bg-white border-2 border-gray-200 rounded-xl p-6 mb-4 relative group hover:border-blue-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                                             data-block-index="{{ $blockIndex }}"
                                             data-block-type="{{ is_array($block) && isset($block['type']) ? $block['type'] : 'unknown' }}">
                                            <div class="block-content">
                                                <!-- Block content will be rendered based on type -->
                                                <div class="text-center text-gray-600">
                                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                                        <span class="text-white text-2xl">
                                                            @php $blockType = is_array($block) && isset($block['type']) ? $block['type'] : 'unknown' @endphp
                                                            @switch($blockType)
                                                                @case('hero') 🎯 @break
                                                                @case('card-grid') 📋 @break
                                                                @case('rich-text') 📝 @break
                                                                @case('stats') 📊 @break
                                                                @case('cta-banner') 📢 @break
                                                                @case('gallery-teaser') 🖼️ @break
                                                                @case('events-teaser') 📅 @break
                                                                @default 🔧
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                    <h5 class="text-lg font-bold text-gray-800 mb-2">{{ ucfirst(str_replace('-', ' ', $blockType)) }} Block</h5>
                                                    <p class="text-sm text-gray-500 mb-4">Click to edit content and customize this block</p>
                                                    <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-full">
                                                        <span class="text-blue-600 text-sm font-medium">✏️ Click to edit</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="block-controls absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                <div class="flex space-x-2">
                                                    <button class="edit-block px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-lg transform hover:scale-105 transition-all">
                                                        ✏️ Edit
                                                    </button>
                                                    <button class="delete-block px-3 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 shadow-lg transform hover:scale-105 transition-all">
                                                        🗑️ Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="empty-section text-center py-16 text-gray-500 border-2 border-dashed border-blue-300 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 transition-all duration-300">
                                            <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                                <span class="text-white text-2xl">📦</span>
                                            </div>
                                            <h4 class="text-lg font-bold text-gray-700 mb-2">Empty Section</h4>
                                            <p class="text-sm text-gray-600 mb-4">Drag blocks from the library to build your content</p>
                                            <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-full">
                                                <span class="text-blue-600 text-sm font-medium">👈 Start by dragging a block here</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="section-container border border-gray-200 bg-white mb-1" data-section-index="0">
                                <div class="section-header bg-gray-50 px-6 py-4 flex items-center justify-between border-b border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                                            <span class="text-gray-800 text-sm font-bold">📋</span>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-black force-black-text no-select-highlight">Header Section</h4>
                                            <p class="text-gray-300 text-xs">Section 1</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button class="section-settings px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                            ⚙️ Settings
                                        </button>
                                        <button class="delete-section px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                            🗑️ Delete
                                        </button>
                                    </div>
                                </div>

                                <div class="section-content min-h-32 p-6 bg-gradient-to-br from-gray-50 to-white drop-zone border-t border-gray-200"
                                     data-section-index="0">
                                    <div class="empty-section text-center py-16 text-gray-500 border-2 border-dashed border-blue-300 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 transition-all duration-300">
                                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                            <span class="text-white text-2xl">📦</span>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-700 mb-2">Empty Section</h4>
                                        <p class="text-sm text-gray-600 mb-4">Drag blocks from the library to build your content</p>
                                        <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-full">
                                            <span class="text-blue-600 text-sm font-medium">👈 Start by dragging a block here</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Add Section Button -->
                    <div class="p-6 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <button id="addSection" class="w-full border-2 border-dashed border-green-300 rounded-xl p-6 text-gray-600 hover:border-green-500 hover:text-green-700 hover:bg-green-50 transition-all duration-300 transform hover:scale-105">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                                <span class="text-white text-xl font-bold">➕</span>
                            </div>
                            <span class="text-lg font-bold block mb-1">Add New Section</span>
                            <p class="text-sm text-gray-500">Create another section for your template</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Properties Panel -->
    <div id="propertiesPanel" class="w-80 bg-white border-l border-gray-200 hidden">
        <div class="flex-shrink-0 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Properties</h3>
                <button id="closeProperties" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div id="propertiesContent" class="px-6 py-4">
                <!-- Dynamic properties form will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Block Edit Modal -->
<div id="blockEditModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="blockModalTitle">Edit Block</h3>
                <button type="button" id="closeBlockModal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div id="blockEditForm">
                <!-- Dynamic form content will be loaded here -->
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancelBlockEdit" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all">
                    Cancel
                </button>
                <button type="button" id="saveBlockEdit" class="px-6 py-3 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 shadow-lg transform hover:scale-105 transition-all">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom CSS for Template Builder */
/* Force black text utility without altering parent background */
.force-black-text { color: #111827 !important; }
.category-bar h4 span { filter: drop-shadow(0 0 1px rgba(0,0,0,0.15)); }
.no-select-highlight { background: transparent !important; }
.no-select-highlight::selection { background: transparent; color:#111827; }

/* Remove inadvertent selection background that showed as blue in screenshots */
.force-black-text::selection { background: rgba(0,0,0,0.05); color:#111827; }
body ::selection { background: rgba(59,130,246,0.15); }
.block-item {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 2px dashed #e2e8f0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.block-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
}

.block-item:active {
    transform: scale(0.98);
}

.drop-zone {
    transition: all 0.3s ease;
}

.drop-zone.dragover {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border-color: #3b82f6 !important;
    transform: scale(1.02);
}

.section-container {
    transition: all 0.3s ease;
}

.section-container:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.block-element {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.block-element:hover {
    transform: translateY(-4px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.empty-section {
    transition: all 0.3s ease;
    cursor: pointer;
}

.empty-section:hover {
    background: linear-gradient(135deg, #dbeafe 0%, #c7d2fe 100%);
    transform: scale(1.01);
}

/* Smooth scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Loading animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Responsive adjustments */
@media (max-width: 1400px) {
    #templateCanvas {
        width: 100% !important;
        max-width: 1200px;
    }
}

@media (max-width: 1200px) {
    .w-96 {
        width: 20rem;
    }

    #templateCanvas {
        width: 100% !important;
        max-width: 900px;
    }
}

@media (max-width: 768px) {
    .w-96 {
        width: 100%;
        position: fixed;
        left: -100%;
        z-index: 50;
        transition: left 0.3s ease;
    }

    .w-96.open {
        left: 0;
    }

    #templateCanvas {
        width: 100% !important;
        max-width: none;
        margin: 1rem;
    }
}

/* Gradient borders */
.gradient-border {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2px;
    border-radius: 12px;
}

.gradient-border-content {
    background: white;
    border-radius: 10px;
}

/* Interactive elements */
button:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

.interactive-bounce:hover {
    animation: bounce 0.6s ease-in-out;
}

@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        transform: translate3d(0, 0, 0);
    }
    40%, 43% {
        transform: translate3d(0, -8px, 0);
    }
    70% {
        transform: translate3d(0, -4px, 0);
    }
    90% {
        transform: translate3d(0, -1px, 0);
    }
}
</style>

<script>
// Template Builder JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const templateData = @json($templateStructure);
    let currentTemplate = templateData;
    let selectedBlock = null;
    let selectedSection = null;

    // Drag and Drop functionality
    initializeDragAndDrop();

    // Block editing
    initializeBlockEditing();

    // Auto-save
    initializeAutoSave();

    function initializeDragAndDrop() {
        // Enable drag for block items
        document.querySelectorAll('.block-item').forEach(item => {
            item.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', this.dataset.blockType);
                e.dataTransfer.setData('application/json', this.dataset.blockConfig);

                // Add visual feedback
                this.style.opacity = '0.5';
                this.style.transform = 'scale(0.95)';

                // Create drag preview
                const dragPreview = this.cloneNode(true);
                dragPreview.style.transform = 'rotate(5deg)';
                dragPreview.style.opacity = '0.9';
                document.body.appendChild(dragPreview);
                e.dataTransfer.setDragImage(dragPreview, 50, 25);

                setTimeout(() => {
                    document.body.removeChild(dragPreview);
                }, 1);
            });

            item.addEventListener('dragend', function(e) {
                this.style.opacity = '';
                this.style.transform = '';
            });
        });

        // Enable drop for sections
        document.querySelectorAll('.drop-zone').forEach(zone => {
            zone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'copy';
                this.classList.add('dragover');

                // Add pulsing animation
                this.style.animation = 'pulse 1s infinite';
            });

            zone.addEventListener('dragleave', function(e) {
                // Only remove if we're leaving the zone entirely
                if (!this.contains(e.relatedTarget)) {
                    this.classList.remove('dragover');
                    this.style.animation = '';
                }
            });

            zone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                this.style.animation = '';

                const blockType = e.dataTransfer.getData('text/plain');
                const blockConfig = JSON.parse(e.dataTransfer.getData('application/json'));
                const sectionIndex = parseInt(this.dataset.sectionIndex);

                // Add success animation
                this.style.background = '#10b981';
                this.style.transform = 'scale(1.05)';

                setTimeout(() => {
                    this.style.background = '';
                    this.style.transform = '';
                    addBlockToSection(sectionIndex, blockType, blockConfig);
                }, 300);
            });
        });

        // Add mobile touch support
        if ('ontouchstart' in window) {
            initializeTouchDragDrop();
        }
    }

    function initializeBlockEditing() {
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('edit-block')) {
                const blockElement = e.target.closest('.block-element');
                const sectionIndex = parseInt(blockElement.closest('.section-content').dataset.sectionIndex);
                const blockIndex = parseInt(blockElement.dataset.blockIndex);

                editBlock(sectionIndex, blockIndex);
            }

            if (e.target.classList.contains('delete-block')) {
                const blockElement = e.target.closest('.block-element');
                const sectionIndex = parseInt(blockElement.closest('.section-content').dataset.sectionIndex);
                const blockIndex = parseInt(blockElement.dataset.blockIndex);

                if (confirm('Delete this block?')) {
                    deleteBlock(sectionIndex, blockIndex);
                }
            }
        });
    }

    function initializeAutoSave() {
        let saveTimeout;

        function triggerAutoSave() {
            clearTimeout(saveTimeout);
            document.getElementById('autoSaveStatus').textContent = 'Saving...';
            document.getElementById('autoSaveIndicator').classList.remove('hidden');

            saveTimeout = setTimeout(() => {
                saveTemplate();
            }, 2000);
        }

        // Monitor changes
        document.addEventListener('input', triggerAutoSave);
        document.addEventListener('change', triggerAutoSave);
    }

    function addBlockToSection(sectionIndex, blockType, blockConfig) {
        // Implementation for adding blocks
        console.log('Adding block', blockType, 'to section', sectionIndex);

        // Update template data
        if (!currentTemplate.templates[0].sections[sectionIndex].blocks) {
            currentTemplate.templates[0].sections[sectionIndex].blocks = [];
        }

        const newBlock = {
            type: blockType,
            order: currentTemplate.templates[0].sections[sectionIndex].blocks.length,
            active: true,
            data: getDefaultBlockData(blockType)
        };

        currentTemplate.templates[0].sections[sectionIndex].blocks.push(newBlock);

        // Re-render section
        renderSection(sectionIndex);
    }

    function editBlock(sectionIndex, blockIndex) {
        selectedSection = sectionIndex;
        selectedBlock = blockIndex;

        const block = currentTemplate.templates[0].sections[sectionIndex].blocks[blockIndex];

        // Show edit modal
        document.getElementById('blockEditModal').classList.remove('hidden');
        document.getElementById('blockModalTitle').textContent = `Edit ${block.type} Block`;

        // Load block form
        loadBlockEditForm(block);
    }

    function deleteBlock(sectionIndex, blockIndex) {
        currentTemplate.templates[0].sections[sectionIndex].blocks.splice(blockIndex, 1);

        // Re-index remaining blocks
        currentTemplate.templates[0].sections[sectionIndex].blocks.forEach((block, index) => {
            block.order = index;
        });

        renderSection(sectionIndex);
    }

    function saveTemplate() {
        fetch(`{{ route('admin.templates.builder.update', $userTemplate) }}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name: '{{ $userTemplate->name }}',
                description: '{{ $userTemplate->description }}',
                template_data: currentTemplate
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('autoSaveStatus').textContent = 'All changes saved';
                document.getElementById('autoSaveIndicator').classList.add('hidden');
            } else {
                document.getElementById('autoSaveStatus').textContent = 'Save failed';
                console.error('Save failed:', data.message);
            }
        })
        .catch(error => {
            document.getElementById('autoSaveStatus').textContent = 'Save failed';
            console.error('Save error:', error);
        });
    }

    function getDefaultBlockData(blockType) {
        const defaults = {
            'hero': {
                title: 'Welcome to Our School',
                subtitle: 'Building tomorrow\'s leaders today',
                button_text: 'Learn More',
                button_url: '#'
            },
            'card-grid': {
                title: 'Our Programs',
                cards: [
                    { title: 'Academic Excellence', description: 'Comprehensive curriculum designed for success' },
                    { title: 'Extracurricular Activities', description: 'Develop talents beyond the classroom' },
                    { title: 'Student Support', description: 'Dedicated guidance and counseling services' }
                ]
            },
            'rich-text': {
                content: '<p>Add your content here...</p>'
            },
            'stats': {
                title: 'Our Achievements',
                stats: [
                    { number: 1000, label: 'Students', description: 'Currently enrolled' },
                    { number: 50, label: 'Teachers', description: 'Expert educators' },
                    { number: 25, label: 'Years', description: 'Of excellence' }
                ]
            }
        };

        return defaults[blockType] || {};
    }

    function initializeTouchDragDrop() {
        let dragElement = null;
        let touchStartPos = { x: 0, y: 0 };

        document.querySelectorAll('.block-item').forEach(item => {
            item.addEventListener('touchstart', function(e) {
                dragElement = this;
                const touch = e.touches[0];
                touchStartPos = { x: touch.clientX, y: touch.clientY };

                this.style.opacity = '0.7';
                this.style.transform = 'scale(0.95) rotate(2deg)';
            });

            item.addEventListener('touchmove', function(e) {
                if (!dragElement) return;
                e.preventDefault();

                const touch = e.touches[0];
                const deltaX = touch.clientX - touchStartPos.x;
                const deltaY = touch.clientY - touchStartPos.y;

                // Move element
                this.style.position = 'fixed';
                this.style.left = touch.clientX - 50 + 'px';
                this.style.top = touch.clientY - 25 + 'px';
                this.style.zIndex = '1000';

                // Check drop zones
                const dropZone = document.elementFromPoint(touch.clientX, touch.clientY);
                document.querySelectorAll('.drop-zone').forEach(zone => {
                    zone.classList.remove('dragover');
                });

                if (dropZone && dropZone.classList.contains('drop-zone')) {
                    dropZone.classList.add('dragover');
                }
            });

            item.addEventListener('touchend', function(e) {
                if (!dragElement) return;

                const touch = e.changedTouches[0];
                const dropZone = document.elementFromPoint(touch.clientX, touch.clientY);

                // Reset styles
                this.style.opacity = '';
                this.style.transform = '';
                this.style.position = '';
                this.style.left = '';
                this.style.top = '';
                this.style.zIndex = '';

                document.querySelectorAll('.drop-zone').forEach(zone => {
                    zone.classList.remove('dragover');
                });

                if (dropZone && dropZone.classList.contains('drop-zone')) {
                    const blockType = this.dataset.blockType;
                    const blockConfig = JSON.parse(this.dataset.blockConfig);
                    const sectionIndex = parseInt(dropZone.dataset.sectionIndex);

                    addBlockToSection(sectionIndex, blockType, blockConfig);
                }

                dragElement = null;
            });
        });
    }

    function showSuccessNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <span class="text-xl">✅</span>
                <span class="font-medium">${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Animate out
        setTimeout(() => {
            notification.style.transform = 'translateX(full)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    function showErrorNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <span class="text-xl">❌</span>
                <span class="font-medium">${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        setTimeout(() => {
            notification.style.transform = 'translateX(full)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    function renderSection(sectionIndex) {
        // Implementation to re-render a section
        // This would update the DOM with the current template data
        console.log('Rendering section', sectionIndex);
        showSuccessNotification('Section updated successfully!');
    }

    function loadBlockEditForm(block) {
        // Implementation to load the appropriate form for the block type
        // This would generate form fields based on the block configuration
        console.log('Loading edit form for block', block);

        const formContent = `
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Block Title</label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="${block.name || ''}" placeholder="Enter block title">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Block Content</label>
                    <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="4" placeholder="Enter block content">${block.content || ''}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
                        <input type="color" class="w-full h-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="#ffffff">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                        <input type="color" class="w-full h-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="#000000">
                    </div>
                </div>
            </div>
        `;

        document.getElementById('blockEditForm').innerHTML = formContent;
    }

    // Modal control functions
    window.closeModal = function() {
        document.getElementById('blockEditModal').classList.add('hidden');
        selectedBlock = null;
        selectedSection = null;
    };

    window.saveBlockChanges = function() {
        if (selectedSection !== null && selectedBlock !== null) {
            // Get form values
            const formInputs = document.querySelectorAll('#blockEditForm input, #blockEditForm textarea');
            const updatedData = {};

            formInputs.forEach(input => {
                if (input.type === 'color') {
                    updatedData[input.previousElementSibling.textContent.toLowerCase().replace(' ', '_')] = input.value;
                } else {
                    updatedData[input.placeholder.toLowerCase().replace('enter ', '').replace(' ', '_')] = input.value;
                }
            });

            // Update block data
            if (currentTemplate.templates[0].sections[selectedSection] &&
                currentTemplate.templates[0].sections[selectedSection].blocks[selectedBlock]) {
                Object.assign(currentTemplate.templates[0].sections[selectedSection].blocks[selectedBlock].data, updatedData);
            }

            showSuccessNotification('Block updated successfully!');
            closeModal();
            renderSection(selectedSection);
        }
    };

    // Event listeners
    document.getElementById('saveTemplate').addEventListener('click', saveTemplate);

    document.getElementById('closeBlockModal').addEventListener('click', function(e) {
        e.preventDefault();
        closeModal();
    });

    document.getElementById('cancelBlockEdit').addEventListener('click', function(e) {
        e.preventDefault();
        closeModal();
    });

    document.getElementById('saveBlockEdit').addEventListener('click', function(e) {
        e.preventDefault();
        saveBlockChanges();
    });

    // Close modal when clicking outside
    document.getElementById('blockEditModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('blockEditModal').classList.contains('hidden')) {
            closeModal();
        }
    });

    document.getElementById('addSection').addEventListener('click', function() {
        const newSection = {
            name: 'New Section',
            order: currentTemplate.templates[0].sections.length,
            settings: { background: 'light' },
            blocks: []
        };

        currentTemplate.templates[0].sections.push(newSection);

        // Re-render template
        location.reload(); // Simple approach - in production, would update DOM
    });
});
</script>
@endsection
