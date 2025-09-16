<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified page
     */
    public function show($slug = 'tentang-kita')
    {
        $page = Page::where('slug', $slug)->firstOrFail();
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
