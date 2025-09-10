<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Page;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic KPIs for the dashboard
        $stats = [
            'posts' => Post::count(),
            'pages' => Page::count(),
            'events' => Event::count(),
            'galleries' => Gallery::count(),
            'users' => User::count(),
            'recent_posts' => Post::latest()->limit(5)->get(),
            'upcoming_events' => Event::where('date', '>=', now())->orderBy('date')->limit(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
