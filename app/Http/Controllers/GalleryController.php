<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Services\TemplateRenderService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    protected $templateRenderer;

    public function __construct(TemplateRenderService $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * Display a listing of galleries
     */
    public function index(Request $request)
    {
        $galleries = Gallery::withCount('photos')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('galleries.index', compact('galleries'));

        if ($templateView) {
            return $templateView;
        }

        return view('galleries.index', compact('galleries'));
    }

    /**
     * Display the specified gallery
     */
    public function show(Request $request, Gallery $gallery)
    {
        $gallery->load(['photos' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);

        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('galleries.show', compact('gallery'));

        if ($templateView) {
            return $templateView;
        }

        return view('galleries.show', compact('gallery'));
    }
}
