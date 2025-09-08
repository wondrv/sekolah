<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified page
     */
    public function show($slug = 'profil')
    {
        $page = Page::where('slug', $slug)->firstOrFail();

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
