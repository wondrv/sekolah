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
use App\Http\Controllers\PublicTemplatePreviewController;

// Public routes (no login required) - CLEAN DYNAMIC SYSTEM
Route::get('/', [App\Http\Controllers\PureCMSController::class, 'handleRequest'])->defaults('path', '/')->name('home');

// Signed public template preview (no auth). This sets session flags then redirects to desired path with preview query.
Route::get('/preview/template/{userTemplate}', [PublicTemplatePreviewController::class, 'show'])
    ->middleware('signed')
    ->name('public.template-preview');

// Contact routes - POST only (GET handled by dynamic system)
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

// Pure CMS Routes - Handle all dynamic content
// This should be the LAST route to catch all unmatched paths
Route::get('/{path?}', [App\Http\Controllers\PureCMSController::class, 'handleRequest'])
    ->where('path', '.*')
    ->name('cms.dynamic');
