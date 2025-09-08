<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of galleries
     */
    public function index()
    {
        $galleries = Gallery::withCount('photos')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('galleries.index', compact('galleries'));
    }

    /**
     * Display the specified gallery
     */
    public function show(Gallery $gallery)
    {
        $gallery->load(['photos' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);

        return view('galleries.show', compact('gallery'));
    }
}
