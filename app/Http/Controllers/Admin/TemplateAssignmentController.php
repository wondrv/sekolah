<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateAssignment;
use App\Models\Template;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TemplateAssignmentController extends Controller
{
    public function index(): View
    {
        $assignments = TemplateAssignment::with('template')
            ->orderByDesc('priority')
            ->orderBy('route_pattern')
            ->get();

        $templates = Template::where('active', true)->get();
        $pages = Page::select('slug', 'title')->get();

        return view('admin.template-assignments.index', compact('assignments', 'templates', 'pages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'route_pattern' => 'required|string|max:255',
            'page_slug' => 'nullable|string|max:255',
            'template_id' => 'required|exists:templates,id',
            'priority' => 'integer|min:0|max:100',
            'active' => 'boolean',
        ]);

        TemplateAssignment::create($validated);

        return redirect()->route('admin.template-assignments.index')
            ->with('success', 'Template assignment created successfully.');
    }

    public function update(Request $request, TemplateAssignment $templateAssignment): RedirectResponse
    {
        $validated = $request->validate([
            'route_pattern' => 'required|string|max:255',
            'page_slug' => 'nullable|string|max:255',
            'template_id' => 'required|exists:templates,id',
            'priority' => 'integer|min:0|max:100',
            'active' => 'boolean',
        ]);

        $templateAssignment->update($validated);

        return redirect()->route('admin.template-assignments.index')
            ->with('success', 'Template assignment updated successfully.');
    }

    public function destroy(TemplateAssignment $templateAssignment): RedirectResponse
    {
        $templateAssignment->delete();

        return redirect()->route('admin.template-assignments.index')
            ->with('success', 'Template assignment deleted successfully.');
    }
}