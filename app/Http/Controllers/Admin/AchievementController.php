<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    /**
     * Display a listing of achievements
     */
    public function index()
    {
        $achievements = Achievement::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.achievements.index', compact('achievements'));
    }

    /**
     * Show the form for creating a new achievement
     */
    public function create()
    {
        return view('admin.achievements.create');
    }

    /**
     * Store a newly created achievement
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:achievements,slug',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 10),
            'level' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('achievements', 'public');
            $data['image'] = $path;
        }

        Achievement::create($data);

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Achievement created successfully.');
    }

    /**
     * Display the specified achievement
     */
    public function show(Achievement $achievement)
    {
        return view('admin.achievements.show', compact('achievement'));
    }

    /**
     * Show the form for editing the specified achievement
     */
    public function edit(Achievement $achievement)
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    /**
     * Update the specified achievement
     */
    public function update(Request $request, Achievement $achievement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:achievements,slug,' . $achievement->id,
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 10),
            'level' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($achievement->image && Storage::disk('public')->exists($achievement->image)) {
                Storage::disk('public')->delete($achievement->image);
            }

            $path = $request->file('image')->store('achievements', 'public');
            $data['image'] = $path;
        }

        $achievement->update($data);

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Achievement updated successfully.');
    }

    /**
     * Remove the specified achievement from storage
     */
    public function destroy(Achievement $achievement)
    {
        // Delete associated image
        if ($achievement->image && Storage::disk('public')->exists($achievement->image)) {
            Storage::disk('public')->delete($achievement->image);
        }

        $achievement->delete();

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Achievement deleted successfully.');
    }
}
