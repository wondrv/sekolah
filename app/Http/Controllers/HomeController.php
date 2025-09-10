<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Theme;

class HomeController extends Controller
{
    public function index()
    {
        $template = Theme::getHomeTemplate();

        if (!$template) {
            // Fallback to traditional home view if no template is set
            return view('home');
        }

        return view('home-template', compact('template'));
    }
}
