@extends('layouts.admin')

@section('title', 'Edit Menu')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Menu</h1>
            <p class="text-gray-600 mt-2">Modify menu: {{ $menu->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.menus.show', $menu) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-eye mr-2"></i>Preview
            </a>
            <a href="{{ route('admin.menus.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Menus
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Menu Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $menu->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter menu name" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Menu Location</label>
                    <select id="location" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Location</option>
                        <option value="header" {{ old('location', $menu->location) == 'header' ? 'selected' : '' }}>Header Navigation</option>
                        <option value="footer" {{ old('location', $menu->location) == 'footer' ? 'selected' : '' }}>Footer Navigation</option>
                        <option value="sidebar" {{ old('location', $menu->location) == 'sidebar' ? 'selected' : '' }}>Sidebar Navigation</option>
                        <option value="mobile" {{ old('location', $menu->location) == 'mobile' ? 'selected' : '' }}>Mobile Navigation</option>
                        <option value="custom" {{ old('location', $menu->location) == 'custom' ? 'selected' : '' }}>Custom Location</option>
                    </select>
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Describe the purpose of this menu">{{ old('description', $menu->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Menu Items</h3>
                    <button type="button" id="add-menu-item" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Add Menu Item
                    </button>
                </div>

                <div id="menu-items-container" class="space-y-4">
                    @foreach($menu->items as $index => $item)
                    <div class="menu-item border border-gray-200 rounded-lg p-4" data-item="{{ $index + 1 }}">
                        <input type="hidden" name="menu_items[{{ $index + 1 }}][id]" value="{{ $item->id }}">

                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-md font-semibold text-gray-800">{{ $item->title }}</h4>
                            <div class="flex items-center gap-3">
                                <form action="{{ route('admin.menus.items.destroy', [$menu, $item]) }}" method="POST" class="inline" data-confirm="hapus menu item: {{ $item->title }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 cursor-pointer">Hapus Item</button>
                                </form>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" name="menu_items[{{ $index + 1 }}][title]" value="{{ $item->title }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter menu title" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Link Type</label>
                                <select name="menu_items[{{ $index + 1 }}][link_type]" class="link-type-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Select Type</option>
                                    <option value="page" {{ ($item->link_type ?? 'page') == 'page' ? 'selected' : '' }}>Internal Page</option>
                                    <option value="url" {{ ($item->link_type ?? 'page') == 'url' ? 'selected' : '' }}>Custom URL</option>
                                    <option value="external" {{ ($item->link_type ?? 'page') == 'external' ? 'selected' : '' }}>External Link</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                                <input type="number" name="menu_items[{{ $index + 1 }}][order]" value="{{ $item->order }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       min="1" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="link-input-container">
                                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                                <input type="text" name="menu_items[{{ $index + 1 }}][url]" value="{{ $item->url }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter URL">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                                <select name="menu_items[{{ $index + 1 }}][target]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="_self" {{ ($item->target ?? '_self') == '_self' ? 'selected' : '' }}>Same Window</option>
                                    <option value="_blank" {{ ($item->target ?? '_self') == '_blank' ? 'selected' : '' }}>New Window</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CSS Classes</label>
                                <input type="text" name="menu_items[{{ $index + 1 }}][css_class]" value="{{ $item->css_class }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Optional CSS classes">
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="menu_items[{{ $index + 1 }}][is_active]" value="1" {{ $item->is_active ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label class="ml-2 text-sm font-medium text-gray-700">Active Menu Item</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center mb-6">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Active Menu</label>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.menus.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>Update Menu
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = {{ $menu->items->count() }};
    const menuItemsContainer = document.getElementById('menu-items-container');
    const addMenuItemBtn = document.getElementById('add-menu-item');

    // Available pages for linking
    const availablePages = [
        { value: '/', text: 'Homepage' },
        { value: '/about', text: 'About Us' },
        { value: '/programs', text: 'Programs' },
        { value: '/facilities', text: 'Facilities' },
        { value: '/events', text: 'Events' },
        { value: '/news', text: 'News' },
        { value: '/contact', text: 'Contact' },
        { value: '/admissions', text: 'Admissions' },
        { value: '/gallery', text: 'Gallery' }
    ];

    addMenuItemBtn.addEventListener('click', function() {
        itemCount++;

        let pageOptions = '';
        availablePages.forEach(page => {
            pageOptions += `<option value="${page.value}">${page.text}</option>`;
        });

        const menuItemHtml = `
            <div class="menu-item border border-gray-200 rounded-lg p-4" data-item="${itemCount}">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-md font-semibold text-gray-800">Menu Item ${itemCount}</h4>
                    <button type="button" class="remove-menu-item text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="menu_items[${itemCount}][title]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter menu title" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link Type</label>
                        <select name="menu_items[${itemCount}][link_type]" class="link-type-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Type</option>
                            <option value="page">Internal Page</option>
                            <option value="url">Custom URL</option>
                            <option value="external">External Link</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <input type="number" name="menu_items[${itemCount}][order]" value="${itemCount}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               min="1" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="link-input-container">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link</label>
                        <input type="text" name="menu_items[${itemCount}][url]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter URL or select page">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                        <select name="menu_items[${itemCount}][target]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="_self">Same Window</option>
                            <option value="_blank">New Window</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CSS Classes</label>
                        <input type="text" name="menu_items[${itemCount}][css_class]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Optional CSS classes">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="menu_items[${itemCount}][is_active]" value="1" checked
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label class="ml-2 text-sm font-medium text-gray-700">Active Menu Item</label>
                </div>
            </div>
        `;

        menuItemsContainer.insertAdjacentHTML('beforeend', menuItemHtml);
    });

    // Handle link type changes
    menuItemsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('link-type-select')) {
            const linkType = e.target.value;
            const menuItem = e.target.closest('.menu-item');
            const linkContainer = menuItem.querySelector('.link-input-container');
            const itemNumber = menuItem.dataset.item;

            let pageOptions = '';
            availablePages.forEach(page => {
                pageOptions += `<option value="${page.value}">${page.text}</option>`;
            });

            if (linkType === 'page') {
                linkContainer.innerHTML = `
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Page</label>
                    <select name="menu_items[${itemNumber}][url]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select a page</option>
                        ${pageOptions}
                    </select>
                `;
            } else {
                linkContainer.innerHTML = `
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                    <input type="url" name="menu_items[${itemNumber}][url]"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           placeholder="${linkType === 'external' ? 'https://example.com' : 'Enter URL'}" required>
                `;
            }
        }
    });

    // Remove menu item functionality (client-side only for newly added ones)
    menuItemsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-menu-item')) {
            e.target.closest('.menu-item').remove();
        }
    });
});
</script>
@endsection
