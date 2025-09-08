<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Event;
use App\Models\Staff;
use App\Models\PpdbApplicant;

class AdminController extends Controller
{
    public function dashboard()
    {
        $postCount = Post::count();
        $eventCount = Event::count();
        $staffCount = Staff::count();
        $ppdbCount = PpdbApplicant::count();

        $recentPosts = Post::with('category')
            ->latest()
            ->limit(5)
            ->get();

        $upcomingEvents = Event::where('starts_at', '>', now())
            ->orderBy('starts_at', 'asc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'postCount',
            'eventCount',
            'staffCount',
            'ppdbCount',
            'recentPosts',
            'upcomingEvents'
        ));
    }
}
