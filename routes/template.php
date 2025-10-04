

// Routes from template: routes/web.php
// Installed on: 2025-10-04 17:25:16
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Harapan\SchoolController;

Route::get('/', [SchoolController::class, 'home'])->name('home');
Route::get('/tentang', [SchoolController::class, 'tentang'])->name('tentang');
Route::get('/program', [SchoolController::class, 'program'])->name('program');
Route::get('/berita', [SchoolController::class, 'berita'])->name('berita');
Route::get('/galeri', [SchoolController::class, 'galeri'])->name('galeri');
Route::get('/kontak', [SchoolController::class, 'kontak'])->name('kontak');
Route::get('/ppdb', [SchoolController::class, 'ppdb'])->name('ppdb');

