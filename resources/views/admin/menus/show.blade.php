@extends('layouts.admin')

@section('title', 'Menu Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $menu->name }}</h1>
            <p class="text-gray-600 mt-2">Location: {{ ucfirst($menu->location) }} |
                Status: <span class="px-2 py-1 text-xs rounded-full {{ $menu->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $menu->is_active ? 'Active' : 'Inactive' }}
                </span>
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.menus.edit', $menu) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Edit Menu
            </a>
            <a href="{{ route('admin.menus.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Menus
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Menu Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Information</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="text-gray-900">{{ $menu->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <p class="text-gray-900">{{ ucfirst($menu->location) }}</p>
                    </div>

                    @if($menu->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="text-gray-900">{{ $menu->description }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Menu Items</label>
                        <p class="text-gray-900">{{ $menu->items->count() }} items</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created</label>
                        <p class="text-gray-900">{{ $menu->created_at->format('M d, Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                        <p class="text-gray-900">{{ $menu->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>

                @if($menu->is_active)
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span class="text-green-800 font-medium">This menu is currently active</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Menu Preview -->
            <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Preview</h3>

                @if($menu->items->count() > 0)
                <nav class="bg-gray-50 rounded-lg p-4">
                    <ul class="space-y-2">
                        @foreach($menu->items->sortBy('order') as $item)
                        <li>
                            <a href="{{ $item->url }}"
                               target="{{ $item->target ?? '_self' }}"
                               class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition duration-200 {{ $item->css_class ?? '' }} {{ !$item->is_active ? 'opacity-50' : '' }}">
                                <span class="mr-2 text-xs text-gray-500">#{{ $item->order }}</span>
                                {{ $item->title }}
                                @if($item->target === '_blank')
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                @endif
                                @if(!$item->is_active)
                                <span class="ml-auto text-xs text-red-500">(Inactive)</span>
                                @endif
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </nav>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-bars text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-600">No menu items configured</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Menu Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Menu Items</h3>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ $menu->items->count() }} items</span>
                        <button onclick="reorderMenuItems()" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm transition duration-200">
                            <i class="fas fa-sort mr-1"></i>Reorder
                        </button>
                    </div>
                </div>

                @if($menu->items->count() > 0)
                <div class="space-y-4" id="menu-items-list">
                    @foreach($menu->items->sortBy('order') as $item)
                    <div class="border border-gray-200 rounded-lg p-4 {{ $item->is_active ? 'bg-white' : 'bg-gray-50' }}" data-item-id="{{ $item->id }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">
                                    #{{ $item->order }}
                                </span>
                                <h4 class="text-md font-semibold text-gray-900">{{ $item->title }}</h4>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($item->target === '_blank')
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                                    External
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">URL</label>
                                <p class="text-gray-900 bg-gray-50 px-2 py-1 rounded">{{ $item->url }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Target</label>
                                <p class="text-gray-900">{{ $item->target ?? '_self' }}</p>
                            </div>

                            @if($item->css_class)
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">CSS Classes</label>
                                <p class="text-gray-900 bg-gray-50 px-2 py-1 rounded font-mono text-xs">{{ $item->css_class }}</p>
                            </div>
                            @endif
                        </div>

                        <div class="flex justify-end mt-3 space-x-2">
                            <form action="{{ route('admin.menus.edit', $menu) }}" method="GET" class="inline">
                                <input type="hidden" name="edit_item" value="{{ $item->id }}">
                                <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                            </form>

                            <form action="#" method="POST" class="inline" onsubmit="return confirm('Delete this menu item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-bars text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">No menu items configured for this menu.</p>
                    <a href="{{ route('admin.menus.edit', $menu) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Add Menu Items
                    </a>
                </div>
                @endif
            </div>

            <!-- Menu Actions -->
            <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Actions</h3>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.menus.edit', $menu) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit Menu
                    </a>

                    @if(!$menu->is_active)
                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition duration-200">
                            <i class="fas fa-check mr-2"></i>Activate Menu
                        </button>
                    </form>
                    @endif

                    <button type="button" onclick="duplicateMenu()" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition duration-200">
                        <i class="fas fa-copy mr-2"></i>Duplicate Menu
                    </button>

                    <button type="button" onclick="exportMenu()" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition duration-200">
                        <i class="fas fa-download mr-2"></i>Export Menu
                    </button>

                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline" data-confirm="menu: {{ $menu->name }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition duration-200">
                            <i class="fas fa-trash mr-2"></i>Delete Menu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function duplicateMenu() {
    if (confirm('Create a copy of this menu?')) {
        // Implementation for menu duplication
        window.location.href = '{{ route("admin.menus.create") }}?duplicate={{ $menu->id }}';
    }
}

function exportMenu() {
    // Create menu export data
    const menuData = {
        name: '{{ $menu->name }}',
        location: '{{ $menu->location }}',
        description: '{{ $menu->description }}',
        items: @json($menu->items->toArray())
    };

    const dataStr = JSON.stringify(menuData, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});

    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = 'menu_{{ $menu->name }}_export.json';
    link.click();
}

function reorderMenuItems() {
    // Simple reorder implementation - in real app you'd use drag & drop
    const items = document.getElementById('menu-items-list');
    if (items) {
        alert('Drag and drop reordering would be implemented here. For now, use the Edit menu to change order numbers.');
    }
}
</script>
@endsection
