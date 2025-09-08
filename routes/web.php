<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes (no login required)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dynamic content routes
Route::get('/profil/{slug?}', [PageController::class, 'show'])->name('pages.show');
Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/agenda', [EventController::class, 'index'])->name('events.index');
Route::get('/agenda/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/galeri/{gallery:slug}', [GalleryController::class, 'show'])->name('galleries.show');

// Contact route
Route::view('/kontak', 'pages.kontak')->name('contact');

// Dashboard route - redirects to admin
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin routes - hidden from public navigation
Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Admin CRUD routes
    Route::resource('posts', Admin\PostController::class, ['as' => 'admin']);
    Route::resource('pages', Admin\PageController::class, ['as' => 'admin']);
    Route::resource('events', Admin\EventController::class, ['as' => 'admin']);
    Route::resource('galleries', Admin\GalleryController::class, ['as' => 'admin']);
    Route::resource('facilities', Admin\FacilityController::class, ['as' => 'admin']);
    Route::resource('programs', Admin\ProgramController::class, ['as' => 'admin']);
    Route::resource('testimonials', Admin\TestimonialController::class, ['as' => 'admin']);
    Route::resource('achievements', Admin\AchievementController::class, ['as' => 'admin']);

    // Profile management within admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
