<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Services\TemplateRenderService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $templateRenderer;

    public function __construct(TemplateRenderService $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * Display a listing of posts
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'user'])->published();

        // Filter by category slug if provided (e.g., ?kategori=pengumuman)
        if ($request->filled('kategori')) {
            $category = Category::where('slug', $request->kategori)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                  ->orWhere('body', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(12);
        $categories = Category::withCount('posts')->get();

        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('posts.index', compact('posts', 'categories'));

        if ($templateView) {
            return $templateView;
        }

        return view('posts.index', compact('posts', 'categories'));
    }

    /**
     * Display the specified post
     */
    public function show(Request $request, Post $post)
    {
        // Check if post is published
        if ($post->status !== 'published' || $post->published_at > now()) {
            abort(404);
        }

        $post->load(['category', 'user']);

        // Get related posts
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->limit(3)
            ->get();

        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('posts.show', compact('post', 'relatedPosts'));

        if ($templateView) {
            return $templateView;
        }

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}
