<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TemplateRenderService;
use App\Support\Theme;

class HomeController extends Controller
{
    protected $templateRenderer;

    public function __construct(TemplateRenderService $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function index(Request $request)
    {
        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('home', [
            'pageTitle' => 'Home',
            'metaDescription' => 'Welcome to our school website'
        ]);

        if ($templateView) {
            return $templateView;
        }

        // Fallback to original home template system
        $template = Theme::getHomeTemplate();
        return view('home-template', compact('template'));
    }
}
