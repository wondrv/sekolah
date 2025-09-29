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
    <div class="w-80 bg-white border-r border-gray-200 flex flex-col">
        <div class="flex-shrink-0 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Block Library</h3>
            <p class="text-sm text-gray-500">Drag blocks to build your template</p>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div class="px-6 py-4 space-y-6">
                @foreach($blockTypes as $category => $blocks)
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">{{ ucfirst($category) }} Blocks</h4>
                    <div class="space-y-2">
                        @foreach($blocks as $type => $config)
                        <div class="block-item border border-gray-200 rounded-lg p-3 cursor-move hover:border-blue-300 hover:shadow-sm transition-all"
                             data-block-type="{{ $type }}"
                             data-block-config="{{ json_encode($config) }}"
                             draggable="true">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center mr-3">
                                    <span class="text-blue-600 text-sm">
                                        @switch($config['category'])
                                            @case('header') 🎯 @break
                                            @case('content') 📝 @break
                                            @case('info') 📊 @break
                                            @case('marketing') 📢 @break
                                            @case('media') 🖼️ @break
                                            @default 🔧
                                        @endswitch
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h5 class="text-sm font-medium text-gray-900">{{ $config['name'] }}</h5>
                                    <p class="text-xs text-gray-500">{{ $config['description'] }}</p>
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
    <div class="flex-1 flex flex-col">
        <!-- Builder Toolbar -->
        <div class="flex-shrink-0 bg-gray-50 border-b border-gray-200 px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Device:</label>
                        <select id="devicePreview" class="border-gray-300 rounded-md text-sm">
                            <option value="desktop">Desktop</option>
                            <option value="tablet">Tablet</option>
                            <option value="mobile">Mobile</option>
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
        <div class="flex-1 overflow-y-auto bg-gray-100">
            <div class="py-8">
                <div id="templateCanvas" class="mx-auto bg-white shadow-lg" style="width: 1200px; min-height: 800px;">
                    <!-- Template sections will be rendered here -->
                    <div id="templateSections" class="divide-y divide-gray-200">
                        @if(isset($templateStructure['templates'][0]['sections']))
                            @foreach($templateStructure['templates'][0]['sections'] as $sectionIndex => $section)
                            <div class="section-container" data-section-index="{{ $sectionIndex }}">
                                <div class="section-header bg-gray-50 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $section['name'] }}</h4>
                                    <div class="flex items-center space-x-2">
                                        <button class="section-settings text-gray-400 hover:text-gray-600">
                                            ⚙️
                                        </button>
                                        <button class="delete-section text-red-400 hover:text-red-600">
                                            🗑️
                                        </button>
                                    </div>
                                </div>

                                <div class="section-content min-h-24 p-4 bg-white drop-zone"
                                     data-section-index="{{ $sectionIndex }}">
                                    @if(isset($section['blocks']) && count($section['blocks']) > 0)
                                        @foreach($section['blocks'] as $blockIndex => $block)
                                        <div class="block-element border border-dashed border-gray-300 rounded p-4 mb-4 relative group"
                                             data-block-index="{{ $blockIndex }}"
                                             data-block-type="{{ $block['type'] }}">
                                            <div class="block-content">
                                                <!-- Block content will be rendered based on type -->
                                                <div class="text-center text-gray-500">
                                                    <span class="text-2xl">
                                                        @switch($block['type'])
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
                                                    <h5 class="text-sm font-medium mt-2">{{ ucfirst(str_replace('-', ' ', $block['type'])) }} Block</h5>
                                                    <p class="text-xs text-gray-400">Click to edit content</p>
                                                </div>
                                            </div>

                                            <div class="block-controls absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <div class="flex space-x-1">
                                                    <button class="edit-block w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                                                        ✏️
                                                    </button>
                                                    <button class="delete-block w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600">
                                                        🗑️
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="empty-section text-center py-12 text-gray-400 border-2 border-dashed border-gray-300 rounded-lg">
                                            <span class="text-4xl block mb-2">📦</span>
                                            <p class="text-sm">Drop blocks here to build your section</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="section-container" data-section-index="0">
                                <div class="section-header bg-gray-50 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-gray-900">Header Section</h4>
                                    <div class="flex items-center space-x-2">
                                        <button class="section-settings text-gray-400 hover:text-gray-600">
                                            ⚙️
                                        </button>
                                        <button class="delete-section text-red-400 hover:text-red-600">
                                            🗑️
                                        </button>
                                    </div>
                                </div>

                                <div class="section-content min-h-24 p-4 bg-white drop-zone"
                                     data-section-index="0">
                                    <div class="empty-section text-center py-12 text-gray-400 border-2 border-dashed border-gray-300 rounded-lg">
                                        <span class="text-4xl block mb-2">📦</span>
                                        <p class="text-sm">Drop blocks here to build your section</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Add Section Button -->
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        <button id="addSection" class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-gray-500 hover:border-blue-300 hover:text-blue-600 transition-colors">
                            <span class="text-2xl block mb-1">➕</span>
                            Add New Section
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
                <button id="closeBlockModal" class="text-gray-400 hover:text-gray-600">
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
                <button id="cancelBlockEdit" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button id="saveBlockEdit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

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
            });
        });

        // Enable drop for sections
        document.querySelectorAll('.drop-zone').forEach(zone => {
            zone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('bg-blue-50', 'border-blue-300');
            });

            zone.addEventListener('dragleave', function(e) {
                this.classList.remove('bg-blue-50', 'border-blue-300');
            });

            zone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('bg-blue-50', 'border-blue-300');

                const blockType = e.dataTransfer.getData('text/plain');
                const blockConfig = JSON.parse(e.dataTransfer.getData('application/json'));
                const sectionIndex = parseInt(this.dataset.sectionIndex);

                addBlockToSection(sectionIndex, blockType, blockConfig);
            });
        });
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

    function renderSection(sectionIndex) {
        // Implementation to re-render a section
        // This would update the DOM with the current template data
        console.log('Rendering section', sectionIndex);
    }

    function loadBlockEditForm(block) {
        // Implementation to load the appropriate form for the block type
        // This would generate form fields based on the block configuration
        console.log('Loading edit form for block', block);
    }

    // Event listeners
    document.getElementById('saveTemplate').addEventListener('click', saveTemplate);

    document.getElementById('closeBlockModal').addEventListener('click', function() {
        document.getElementById('blockEditModal').classList.add('hidden');
    });

    document.getElementById('cancelBlockEdit').addEventListener('click', function() {
        document.getElementById('blockEditModal').classList.add('hidden');
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
