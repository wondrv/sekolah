<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\TemplateRenderService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $templateRenderer;

    public function __construct(TemplateRenderService $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * Display the specified page
     */
    public function show(Request $request, $slug = 'tentang-kami')
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        
        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest(
            $slug === 'ppdb' ? 'pages.custom.ppdb' : 'pages.show',
            compact('page')
        );

        if ($templateView) {
            return $templateView;
        }

        // Fallback to original views
        if ($slug === 'ppdb') {
            return view('pages.custom.ppdb', compact('page'));
        }
        return view('pages.show', compact('page'));
    }

    /**
     * Display the specified page by direct slug
     */
    public function showSingle(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest(
            $slug === 'ppdb' ? 'pages.custom.ppdb' : 'pages.show',
            compact('page')
        );

        if ($templateView) {
            return $templateView;
        }

        // Fallback to original views
        // Handle special page templates
        if ($slug === 'ppdb') {
            return view('pages.custom.ppdb', compact('page'));
        }

        return view('pages.show', compact('page'));
    }

    /**
     * Get pinned pages for navigation
     */
    public function pinned()
    {
        return Page::pinned()->orderBy('title')->get();
    }
}
