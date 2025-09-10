<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Quick login route for testing
Route::get('/quick-login', function() {
    $user = \App\Models\User::where('email', 'admin@school.local')->first();
    if ($user) {
        Auth::login($user);
        return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully!');
    }
    return 'Admin user not found';
})->name('admin.quick-login');

// Debug route to test authentication
Route::get('/debug-auth', function() {
    $user = Auth::user();
    if (!$user) {
        return 'No user logged in. Please <a href="/admin/login">login first</a>';
    }

    return [
        'user' => $user->toArray(),
        'isAdmin' => method_exists($user, 'isAdmin') ? $user->isAdmin() : 'method not found',
        'role' => $user->role ?? 'no role',
        'is_admin' => $user->is_admin ?? 'no is_admin field',
    ];
})->name('debug.auth');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // Settings Management
    Route::get('settings', [Admin\SettingsController::class, 'index'])->name('admin.settings.index');
    Route::put('settings', [Admin\SettingsController::class, 'update'])->name('admin.settings.update');

    // Posts Management
    Route::resource('posts', Admin\PostController::class)->names([
        'index' => 'admin.posts.index',
        'create' => 'admin.posts.create',
        'store' => 'admin.posts.store',
        'show' => 'admin.posts.show',
        'edit' => 'admin.posts.edit',
        'update' => 'admin.posts.update',
        'destroy' => 'admin.posts.destroy',
    ]);

    // Pages Management
    Route::resource('pages', Admin\PageController::class)->names([
        'index' => 'admin.pages.index',
        'create' => 'admin.pages.create',
        'store' => 'admin.pages.store',
        'show' => 'admin.pages.show',
        'edit' => 'admin.pages.edit',
        'update' => 'admin.pages.update',
        'destroy' => 'admin.pages.destroy',
    ]);

    // Events Management
    Route::resource('events', Admin\EventController::class)->names([
        'index' => 'admin.events.index',
        'create' => 'admin.events.create',
        'store' => 'admin.events.store',
        'show' => 'admin.events.show',
        'edit' => 'admin.events.edit',
        'update' => 'admin.events.update',
        'destroy' => 'admin.events.destroy',
    ]);

    // Galleries Management
    Route::resource('galleries', Admin\GalleryController::class)->names([
        'index' => 'admin.galleries.index',
        'create' => 'admin.galleries.create',
        'store' => 'admin.galleries.store',
        'show' => 'admin.galleries.show',
        'edit' => 'admin.galleries.edit',
        'update' => 'admin.galleries.update',
        'destroy' => 'admin.galleries.destroy',
    ]);

    // Facilities Management
    Route::resource('facilities', Admin\FacilityController::class)->names([
        'index' => 'admin.facilities.index',
        'create' => 'admin.facilities.create',
        'store' => 'admin.facilities.store',
        'show' => 'admin.facilities.show',
        'edit' => 'admin.facilities.edit',
        'update' => 'admin.facilities.update',
        'destroy' => 'admin.facilities.destroy',
    ]);

    // Programs Management
    Route::resource('programs', Admin\ProgramController::class)->names([
        'index' => 'admin.programs.index',
        'create' => 'admin.programs.create',
        'store' => 'admin.programs.store',
        'show' => 'admin.programs.show',
        'edit' => 'admin.programs.edit',
        'update' => 'admin.programs.update',
        'destroy' => 'admin.programs.destroy',
    ]);

    // Achievements Management
    Route::resource('achievements', Admin\AchievementController::class)->names([
        'index' => 'admin.achievements.index',
        'create' => 'admin.achievements.create',
        'store' => 'admin.achievements.store',
        'show' => 'admin.achievements.show',
        'edit' => 'admin.achievements.edit',
        'update' => 'admin.achievements.update',
        'destroy' => 'admin.achievements.destroy',
    ]);

    // Testimonials Management
    Route::resource('testimonials', Admin\TestimonialController::class)->names([
        'index' => 'admin.testimonials.index',
        'create' => 'admin.testimonials.create',
        'store' => 'admin.testimonials.store',
        'show' => 'admin.testimonials.show',
        'edit' => 'admin.testimonials.edit',
        'update' => 'admin.testimonials.update',
        'destroy' => 'admin.testimonials.destroy',
    ]);

    // Template Management
    Route::resource('templates', Admin\TemplateController::class)->names([
        'index' => 'admin.templates.index',
        'create' => 'admin.templates.create',
        'store' => 'admin.templates.store',
        'show' => 'admin.templates.show',
        'edit' => 'admin.templates.edit',
        'update' => 'admin.templates.update',
        'destroy' => 'admin.templates.destroy',
    ]);

    // Menu Management
    Route::resource('menus', Admin\MenuController::class)->names([
        'index' => 'admin.menus.index',
        'create' => 'admin.menus.create',
        'store' => 'admin.menus.store',
        'show' => 'admin.menus.show',
        'edit' => 'admin.menus.edit',
        'update' => 'admin.menus.update',
        'destroy' => 'admin.menus.destroy',
    ]);

    // NEW: Inbox Messages Management
    Route::resource('messages', Admin\MessageController::class)->names([
        'index' => 'admin.messages.index',
        'show' => 'admin.messages.show',
        'update' => 'admin.messages.update',
        'destroy' => 'admin.messages.destroy',
    ])->except(['create', 'store', 'edit']);

    Route::patch('messages/{message}/reply', [Admin\MessageController::class, 'reply'])->name('admin.messages.reply');

    // NEW: Enrollment Information Management
    Route::resource('enrollments', Admin\EnrollmentController::class)->names([
        'index' => 'admin.enrollments.index',
        'show' => 'admin.enrollments.show',
        'update' => 'admin.enrollments.update',
        'destroy' => 'admin.enrollments.destroy',
    ])->except(['create', 'store', 'edit']);

    Route::patch('enrollments/{enrollment}/approve', [Admin\EnrollmentController::class, 'approve'])->name('admin.enrollments.approve');
    Route::patch('enrollments/{enrollment}/reject', [Admin\EnrollmentController::class, 'reject'])->name('admin.enrollments.reject');
});
