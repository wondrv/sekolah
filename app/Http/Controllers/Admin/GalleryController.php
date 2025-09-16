<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::withCount('photos')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.galleries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:galleries,slug',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_texts' => 'nullable|array',
            'alt_texts.*' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['title', 'slug', 'description']);

        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $gallery = Gallery::create($data);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('galleries', 'public');

                $gallery->photos()->create([
                    'path' => $path,
                    'alt' => $request->input("alt_texts.{$index}") ?? '',
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        return view('admin.galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        $gallery->load('photos');
        return view('admin.galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:galleries,slug,' . $gallery->id,
            'description' => 'nullable|string',
            // New images (optional)
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            // Existing photos metadata updates
            'photos' => 'nullable|array',
            'photos.*.alt' => 'nullable|string|max:255',
            'photos.*.sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['title', 'slug', 'description']);

        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $gallery->update($data);

        // Update existing photos (alt text, sort order)
        $photosInput = $request->input('photos', []);
        if (!empty($photosInput)) {
            foreach ($photosInput as $photoId => $photoData) {
                /** @var \App\Models\Photo|null $photo */
                $photo = $gallery->photos()->where('id', $photoId)->first();
                if (!$photo) continue;
                $update = [];
                if (array_key_exists('alt', $photoData)) {
                    $update['alt'] = $photoData['alt'] ?? '';
                }
                if (array_key_exists('sort_order', $photoData)) {
                    $update['sort_order'] = (int) $photoData['sort_order'];
                }
                if (!empty($update)) {
                    $photo->update($update);
                }
            }
        }

        // Handle new image uploads
        if ($request->hasFile('new_images')) {
            $startOrder = (int) ($gallery->photos()->max('sort_order') ?? 0);
            foreach ($request->file('new_images') as $index => $image) {
                if (!$image->isValid()) continue;
                $path = $image->store('galleries', 'public');
                $gallery->photos()->create([
                    'path' => $path,
                    'alt' => '',
                    'sort_order' => $startOrder + $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil dihapus.');
    }
}
