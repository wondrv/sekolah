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
        'user' => (array)$user,
        'isAdmin' => $user->is_admin ?? 'no is_admin field',
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
    Route::post('settings/ensure-announcements-category', [Admin\SettingsController::class, 'ensureAnnouncementsCategory'])
        ->name('admin.settings.ensure_announcements_category');
    Route::post('settings/ensure-ppdb-pages', [Admin\SettingsController::class, 'ensurePPDBPages'])
        ->name('admin.settings.ensure_ppdb_pages');
    Route::post('settings/ensure-ppdb-menu', [Admin\SettingsController::class, 'ensurePPDBMenu'])
        ->name('admin.settings.ensure_ppdb_menu');
    Route::post('settings/ensure-tentang-kami-dropdown', [Admin\SettingsController::class, 'ensureProfileMenuDropdown'])
        ->name('admin.settings.ensure_tentang_kita_dropdown');
    Route::post('settings/add-agenda-under-tentang-kami', [Admin\SettingsController::class, 'addAgendaUnderProfile'])
        ->name('admin.settings.add_agenda_under_tentang_kita');

    // PPDB Management
    Route::prefix('ppdb')->name('ppdb.')->group(function () {
        Route::get('/', [Admin\PpdbController::class, 'index'])->name('index');
        Route::put('/settings', [Admin\PpdbController::class, 'updateSettings'])->name('settings.update');
        Route::get('/costs', [Admin\PpdbController::class, 'costs'])->name('costs');
        Route::post('/costs', [Admin\PpdbController::class, 'storeCost'])->name('costs.store');
        Route::put('/costs/{cost}', [Admin\PpdbController::class, 'updateCost'])->name('costs.update');
        Route::delete('/costs/{cost}', [Admin\PpdbController::class, 'destroyCost'])->name('costs.destroy');
    });

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

    // Page Builder Routes
    Route::get('pages/{page}/builder', [Admin\PageBuilderController::class, 'show'])->name('admin.pages.builder');
    Route::post('pages/{page}/builder/save', [Admin\PageBuilderController::class, 'save'])->name('admin.pages.builder.save');
    Route::get('page-builder/blocks', [Admin\PageBuilderController::class, 'blocks'])->name('admin.page-builder.blocks');
    Route::get('page-builder/blocks/{type}/config', [Admin\PageBuilderController::class, 'blockConfig'])->name('admin.page-builder.block-config');
    Route::post('page-builder/preview', [Admin\PageBuilderController::class, 'preview'])->name('admin.page-builder.preview');

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

    // Per-photo deletion
    Route::delete('photos/{photo}', [Admin\PhotoController::class, 'destroy'])->name('admin.photos.destroy');

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
    // Template Import/Export (JSON)
    Route::post('templates/import', [Admin\TemplateController::class, 'import'])->name('admin.templates.import');
    Route::post('templates/{template}/import', [Admin\TemplateController::class, 'importInto'])->name('admin.templates.import_into');
    Route::get('templates/{template}/export', [Admin\TemplateController::class, 'export'])->name('admin.templates.export');
    // Per-item deletes within templates
    Route::delete('templates/{template}/sections/{section}', [Admin\TemplateController::class, 'deleteSection'])
        ->name('admin.templates.sections.destroy');
    Route::delete('templates/{template}/blocks/{block}', [Admin\TemplateController::class, 'deleteBlock'])
        ->name('admin.templates.blocks.destroy');
    Route::post('templates/bootstrap-homepage', [Admin\TemplateController::class, 'bootstrapHomepage'])
        ->name('admin.templates.bootstrap_homepage');

    // Template Assignments
    Route::resource('template-assignments', Admin\TemplateAssignmentController::class)->names([
        'index' => 'admin.template-assignments.index',
        'store' => 'admin.template-assignments.store',
        'update' => 'admin.template-assignments.update',
        'destroy' => 'admin.template-assignments.destroy',
    ])->except(['show', 'create', 'edit']);

    // Theme Management
    Route::get('theme', [Admin\ThemeController::class, 'index'])->name('admin.theme.index');
    Route::put('theme', [Admin\ThemeController::class, 'update'])->name('admin.theme.update');
    Route::post('theme/reset', [Admin\ThemeController::class, 'reset'])->name('admin.theme.reset');
    Route::get('theme/export', [Admin\ThemeController::class, 'export'])->name('admin.theme.export');
    Route::post('theme/import', [Admin\ThemeController::class, 'import'])->name('admin.theme.import');

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
    // Per-item delete for menu items
    Route::delete('menus/{menu}/items/{item}', [Admin\MenuController::class, 'deleteItem'])
        ->name('admin.menus.items.destroy');

    // NEW: Inbox Messages Management
    Route::resource('messages', Admin\MessageController::class)->names([
        'index' => 'admin.messages.index',
        'show' => 'admin.messages.show',
        'update' => 'admin.messages.update',
        'destroy' => 'admin.messages.destroy',
    ])->except(['create', 'store', 'edit']);

    Route::patch('messages/{message}/reply', [Admin\MessageController::class, 'reply'])->name('admin.messages.reply');

    // Cache Management
    Route::post('cache/clear', function() {
        try {
            \App\Support\Theme::clearCache();
            \Illuminate\Support\Facades\Cache::flush();
            return redirect()->back()->with('success', 'Cache berhasil dibersihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    })->name('admin.cache.clear');
});
