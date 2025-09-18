<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes (no login required)
Route::get('/', [HomeController::class, 'index'])->name('home');

// CMS Test route (for development/testing)
Route::get('/test-cms', function () {
    return view('test-cms');
})->name('test-cms');

// Dynamic content routes
// Backward compatibility route with tentang-kami prefix - redirects to clean URLs
Route::get('/tentang-kami/{slug?}', function ($slug = null) {
    if ($slug) {
        return redirect('/' . $slug, 301);
    }
    // For /tentang-kami without slug, use PageController to show the tentang-kami page
    return app(\App\Http\Controllers\PageController::class)->showSingle('tentang-kami');
})->name('pages.show');
// Backward compatibility for old URLs
Route::get('/profil/{slug?}', function ($slug = null) {
    $target = '/tentang-kami' . ($slug ? '/' . $slug : '');
    return redirect($target, 301);
});
Route::get('/tentang-kita/{slug?}', function ($slug = null) {
    $target = '/tentang-kami' . ($slug ? '/' . $slug : '');
    return redirect($target, 301);
});
// Removed specific PPDB route - now handled by universal /{slug} route
// Backward-friendly endpoints redirecting to anchors
Route::get('/ppdb/brosur', function () { return redirect()->to('/ppdb#brosur'); })->name('ppdb.brosur');
Route::get('/ppdb/biaya', function () { return redirect()->to('/ppdb#biaya'); })->name('ppdb.biaya');
Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/agenda', [EventController::class, 'index'])->name('events.index');
Route::get('/agenda/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/galeri/{gallery:slug}', [GalleryController::class, 'show'])->name('galleries.show');
Route::get('/fasilitas', [FacilityController::class, 'index'])->name('facilities.index');
Route::get('/fasilitas/{facility:slug}', [FacilityController::class, 'show'])->name('facilities.show');
Route::get('/program', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/program/{program:slug}', [ProgramController::class, 'show'])->name('programs.show');

// Contact routes
Route::get('/kontak', [ContactController::class, 'show'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');


// Dashboard route - redirects to admin
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin routes - hidden from public navigation
Route::prefix('admin')->group(function () {
    require __DIR__.'/admin.php';
});

// Profile routes (if needed for admin users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes
require __DIR__.'/auth.php';

// Specific named routes for important pages (before universal route)
Route::get('/ppdb', function() {
    return app(\App\Http\Controllers\PageController::class)->showSingle('ppdb');
})->name('ppdb');

// Universal single page route - MUST be last to avoid conflicts with other routes
// This allows ANY page to be accessed directly by slug without prefix
Route::get('/{slug}', [PageController::class, 'showSingle'])
    ->where('slug', '[a-zA-Z0-9\-_]+')
    ->name('pages.single');

require __DIR__.'/auth.php';
