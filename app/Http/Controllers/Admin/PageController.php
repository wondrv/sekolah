<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of pages
     */
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'body' => 'required|string',
            'is_pinned' => 'sometimes|boolean',
        ]);

        $data = $request->only(['title', 'slug', 'body']);
        $data['is_pinned'] = $request->has('is_pinned');

        Page::create($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dibuat.');
    }

    /**
     * Display the specified page
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'body' => 'required|string',
            'is_pinned' => 'sometimes|boolean',
        ]);

        $data = $request->only(['title', 'slug', 'body']);
        $data['is_pinned'] = $request->has('is_pinned');

        $page->update($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diupdate.');
    }

    /**
     * Remove the specified page
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dihapus.');
    }
}
