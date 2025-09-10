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
});
