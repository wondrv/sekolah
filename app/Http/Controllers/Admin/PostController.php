<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of posts
     */
    public function index()
    {
        $posts = Post::with(['category', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = Category::all();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new post
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('cover_image');
        $data['user_id'] = Auth::check() ? Auth::user()->id : null;

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('posts', 'public');
            $data['cover_path'] = $path;
        }

        // Set published_at if status is published and no date is set
        if ($data['status'] === 'published' && !$data['published_at']) {
            $data['published_at'] = now();
        }

        Post::create($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified post
     */
    public function show(Post $post)
    {
        $post->load(['category', 'user']);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('cover_image');

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($post->cover_path) {
                Storage::disk('public')->delete($post->cover_path);
            }

            $path = $request->file('cover_image')->store('posts', 'public');
            $data['cover_path'] = $path;
        }

        // Set published_at if status is published and no date is set
        if ($data['status'] === 'published' && !$data['published_at']) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        // Delete associated image
        if ($post->cover_path) {
            Storage::disk('public')->delete($post->cover_path);
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
