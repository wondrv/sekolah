<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Support\Theme;

class MenuController extends Controller
{
    /**
     * Display a listing of menus
     */
    public function index(): View
    {
        $menus = Menu::with('items')->get();

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu
     */
    public function create(): View
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created menu
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Generate slug from name
        $slug = Str::slug($request->name);

        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Menu::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        Menu::create([
            'name' => $request->name,
            'slug' => $slug,
            'location' => $request->location,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Clear theme cache when menu is created
        Theme::clearCache();

        return redirect()->route('admin.menus.index')
                        ->with('success', 'Menu berhasil dibuat.');
    }

    /**
     * Display the specified menu
     */
    public function show(Menu $menu): View
    {
        $menu->load('items');

        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu
     */
    public function edit(Menu $menu): View
    {
        // For admin editing, include all items (including inactive), top-level only
        $items = $menu->allItems()->whereNull('parent_id')->orderBy('sort_order')->get();

        return view('admin.menus.edit', compact('menu', 'items'));
    }

    /**
     * Update the specified menu
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:100',
            'is_active' => 'boolean',
            'menu_items' => 'array',
            'menu_items.*.id' => 'nullable|integer|exists:menu_items,id',
            'menu_items.*.title' => 'nullable|string|max:255',
            'menu_items.*.url' => 'nullable|string|max:500',
            'menu_items.*.target' => 'nullable|in:_self,_blank',
            'menu_items.*.css_class' => 'nullable|string|max:255',
            'menu_items.*.is_active' => 'nullable|boolean',
            'menu_items.*.sort_order' => 'nullable|integer|min:1',
        ]);

        // Generate slug from name if name changed
        $slug = $menu->slug;
        if ($request->name !== $menu->name) {
            $slug = Str::slug($request->name);

            // Ensure slug is unique (exclude current menu)
            $originalSlug = $slug;
            $counter = 1;
            while (Menu::where('slug', $slug)->where('id', '!=', $menu->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $menu->update([
            'name' => $request->name,
            'slug' => $slug,
            'location' => $request->location,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Upsert top-level menu items (keep existing if not present)
        $items = $request->input('menu_items', []);
        foreach ($items as $itemData) {
            // Skip empty items
            $title = trim($itemData['title'] ?? '');
            $url = trim($itemData['url'] ?? '');
            $sort = isset($itemData['sort_order']) ? (int)$itemData['sort_order'] : null;
            $target = $itemData['target'] ?? null;
            $css = $itemData['css_class'] ?? null;
            $active = isset($itemData['is_active']) && (bool)$itemData['is_active'];

            if (!($itemData['id'] ?? null) && $title === '' && $url === '') {
                continue; // nothing to create
            }

            if (!empty($itemData['id'])) {
                $menuItem = MenuItem::where('id', $itemData['id'])
                    ->where('menu_id', $menu->id)
                    ->first();
                if ($menuItem) {
                    $menuItem->update([
                        'title' => $title !== '' ? $title : $menuItem->title,
                        'url' => $url !== '' ? $url : $menuItem->url,
                        'target' => $target ?? $menuItem->target,
                        'css_class' => $css ?? $menuItem->css_class,
                        'is_active' => $active,
                        'sort_order' => $sort ?? ($menuItem->sort_order ?? 1),
                        'parent_id' => null, // keep top-level in this editor
                    ]);
                }
            } else {
                MenuItem::create([
                    'menu_id' => $menu->id,
                    'parent_id' => null,
                    'title' => $title,
                    'url' => $url,
                    'target' => $target,
                    'css_class' => $css,
                    'is_active' => $active,
                    'sort_order' => $sort ?? ((MenuItem::where('menu_id', $menu->id)->max('sort_order') ?? 0) + 1),
                ]);
            }
        }

        // Clear theme cache when menu and items are updated
        Theme::clearCache();

        return redirect()->route('admin.menus.index')
                        ->with('success', 'Menu berhasil diupdate.');
    }

    /**
     * Remove the specified menu
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        // Clear theme cache when menu is deleted
        Theme::clearCache();

        return redirect()->route('admin.menus.index')
                        ->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Add menu item
     */
    public function addItem(Request $request, Menu $menu): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'sort_order' => 'required|integer|min:1',
            'parent_id' => 'nullable|exists:menu_items,id',
            'is_active' => 'boolean',
        ]);

        $menu->items()->create([
            'title' => $request->title,
            'url' => $request->url,
            'sort_order' => $request->sort_order,
            'parent_id' => $request->parent_id,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.menus.edit', $menu)
                        ->with('success', 'Menu item berhasil ditambahkan.');
    }

    /**
     * Update menu item order
     */
    public function updateOrder(Request $request, Menu $menu): RedirectResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.sort_order' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $itemData) {
            MenuItem::where('id', $itemData['id'])
                   ->update(['sort_order' => $itemData['sort_order']]);
        }

        return redirect()->route('admin.menus.edit', $menu)
                        ->with('success', 'Urutan menu berhasil diupdate.');
    }

    /**
     * Delete menu item
     */
    public function deleteItem(Menu $menu, MenuItem $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('admin.menus.edit', $menu)
                        ->with('success', 'Menu item berhasil dihapus.');
    }
}
