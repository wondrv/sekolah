<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Theme;

class HomeController extends Controller
{
    public function index()
    {
        $template = Theme::getHomeTemplate();

        // Always pass the template variable, even if null
        return view('home-template', compact('template'));
    }
}
