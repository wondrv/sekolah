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
        $menu->load('items');

        return view('admin.menus.edit', compact('menu'));
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

        // Clear theme cache when menu is updated
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
            'order' => 'required|integer|min:1',
            'parent_id' => 'nullable|exists:menu_items,id',
            'is_active' => 'boolean',
        ]);

        $menu->items()->create([
            'title' => $request->title,
            'url' => $request->url,
            'order' => $request->order,
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
            'items.*.order' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $itemData) {
            MenuItem::where('id', $itemData['id'])
                   ->update(['order' => $itemData['order']]);
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
