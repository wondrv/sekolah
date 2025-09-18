@extends('layouts.admin')

@section('title', 'Create Menu')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Menu</h1>
            <p class="text-gray-600 mt-2">Create a new navigation menu for your website</p>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Menus
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.menus.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Menu Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
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
                        <option value="header" {{ old('location') == 'header' ? 'selected' : '' }}>Header Navigation</option>
                        <option value="footer" {{ old('location') == 'footer' ? 'selected' : '' }}>Footer Navigation</option>
                        <option value="sidebar" {{ old('location') == 'sidebar' ? 'selected' : '' }}>Sidebar Navigation</option>
                        <option value="mobile" {{ old('location') == 'mobile' ? 'selected' : '' }}>Mobile Navigation</option>
                        <option value="custom" {{ old('location') == 'custom' ? 'selected' : '' }}>Custom Location</option>
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
                          placeholder="Describe the purpose of this menu">{{ old('description') }}</textarea>
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
                    <!-- Dynamic menu items will be added here -->
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Active Menu</label>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('admin.menus.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>Create Menu
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 0;
    const menuItemsContainer = document.getElementById('menu-items-container');
    const addMenuItemBtn = document.getElementById('add-menu-item');


    addMenuItemBtn.addEventListener('click', function() {
        itemCount++;

        const menuItemHtml = `
            <div class="menu-item border border-gray-200 rounded-lg p-4" data-item="${itemCount}">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-md font-semibold text-gray-800">Menu Item ${itemCount}</h4>
                    <button type="button" class="remove-menu-item text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="menu_items[${itemCount}][title]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter menu title" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <input type="number" name="menu_items[${itemCount}][sort_order]" value="${itemCount}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               min="1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent (optional)</label>
                        <select name="menu_items[${itemCount}][parent_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 parent-selector">
                            <option value="">— Top Level —</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Select existing item to make this a submenu</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                    <input type="text" name="menu_items[${itemCount}][url]"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: /ppdb atau https://example.com" required>
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

    // No link-type handling: URLs are entered directly for fully CMS-driven control

    // Dynamic parent selector population
    function updateParentSelectors() {
        const allItems = document.querySelectorAll('.menu-item');
        const parentSelectors = document.querySelectorAll('.parent-selector');

        parentSelectors.forEach(selector => {
            const currentItem = selector.closest('.menu-item');
            const currentItemIndex = currentItem.getAttribute('data-item');

            // Clear existing options except "Top Level"
            selector.innerHTML = '<option value="">— Top Level —</option>';

            // Add options for all other items (except self)
            allItems.forEach(item => {
                const itemIndex = item.getAttribute('data-item');
                const titleInput = item.querySelector('input[name*="[title]"]');
                if (itemIndex !== currentItemIndex && titleInput && titleInput.value.trim()) {
                    const option = document.createElement('option');
                    option.value = itemIndex;
                    option.textContent = titleInput.value.trim() || `Item ${itemIndex}`;
                    selector.appendChild(option);
                }
            });
        });
    }

    // Update parent selectors when titles change
    menuItemsContainer.addEventListener('input', function(e) {
        if (e.target.matches('input[name*="[title]"]')) {
            updateParentSelectors();
        }
    });

    // Remove menu item functionality
    menuItemsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-menu-item')) {
            e.target.closest('.menu-item').remove();
            updateParentSelectors();
        }
    });

    // Add initial menu item
    addMenuItemBtn.click();

    // Update parent selectors after adding initial item
    setTimeout(updateParentSelectors, 10);
});
</script>
@endsection
