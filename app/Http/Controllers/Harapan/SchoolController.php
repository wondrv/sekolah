<?php

namespace App\Http\Controllers\Harapan;

use App\Http\Controllers\Controller;

class SchoolController extends Controller
{
    public function home()   { return view('home'); }
    public function tentang(){ return view('tentang'); }
    public function program(){ return view('program'); }
    public function berita() { return view('berita'); }
    public function galeri() { return view('galeri'); }
    public function kontak() { return view('kontak'); }
    public function ppdb()   { return view('ppdb'); }
}
