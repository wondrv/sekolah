<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\Section;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates
     */
    public function index(): View
    {
        $templates = Template::with('sections.blocks')->get();

        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template
     */
    public function create(): View
    {
        return view('admin.templates.create');
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        Template::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.templates.index')
                        ->with('success', 'Template berhasil dibuat.');
    }

    /**
     * Display the specified template
     */
    public function show(Template $template): View
    {
        $template->load('sections.blocks');

        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified template
     */
    public function edit(Template $template): View
    {
        $template->load('sections.blocks');
        $availableBlocks = [
            'hero' => 'Hero Section',
            'card-grid' => 'Card Grid',
            'rich-text' => 'Rich Text',
            'stats' => 'Statistics',
            'cta-banner' => 'Call to Action',
            'gallery-teaser' => 'Gallery Teaser',
            'events-teaser' => 'Events Teaser',
        ];

        return view('admin.templates.edit', compact('template', 'availableBlocks'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, Template $template): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $template->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.templates.index')
                        ->with('success', 'Template berhasil diupdate.');
    }

    /**
     * Remove the specified template
     */
    public function destroy(Template $template): RedirectResponse
    {
        $template->delete();

        return redirect()->route('admin.templates.index')
                        ->with('success', 'Template berhasil dihapus.');
    }

    /**
     * Add section to template
     */
    public function addSection(Request $request, Template $template): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer|min:1',
        ]);

        $template->sections()->create([
            'name' => $request->name,
            'order' => $request->order,
        ]);

        return redirect()->route('admin.templates.edit', $template)
                        ->with('success', 'Section berhasil ditambahkan.');
    }

    /**
     * Add block to section
     */
    public function addBlock(Request $request, Template $template, Section $section): RedirectResponse
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'content' => 'nullable|json',
            'order' => 'required|integer|min:1',
        ]);

        $section->blocks()->create([
            'type' => $request->type,
            'content' => $request->content ? json_decode($request->content, true) : [],
            'order' => $request->order,
        ]);

        return redirect()->route('admin.templates.edit', $template)
                        ->with('success', 'Block berhasil ditambahkan.');
    }
}
