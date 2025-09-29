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

    // Template System Routes
    Route::prefix('template-system')->name('admin.templates.')->group(function () {
        // Template Gallery
        Route::get('gallery', [Admin\Template\TemplateGalleryController::class, 'index'])->name('gallery.index');
        Route::get('gallery/categories', [Admin\Template\TemplateGalleryController::class, 'categories'])->name('gallery.categories');
        Route::get('gallery/category/{category:slug}', [Admin\Template\TemplateGalleryController::class, 'byCategory'])->name('gallery.category');
        Route::get('gallery/{template}', [Admin\Template\TemplateGalleryController::class, 'show'])->name('gallery.show');
        Route::get('gallery/{template}/preview', [Admin\Template\TemplateGalleryController::class, 'preview'])->name('gallery.preview');
        Route::post('gallery/{template}/install', [Admin\Template\TemplateGalleryController::class, 'install'])->name('gallery.install');

        // My Templates
        Route::get('my-templates', [Admin\Template\MyTemplatesController::class, 'index'])->name('my-templates');
        Route::get('my-templates/{userTemplate}', [Admin\Template\MyTemplatesController::class, 'show'])->name('my-templates.show');
        Route::post('my-templates/{userTemplate}/activate', [Admin\Template\MyTemplatesController::class, 'activate'])->name('my-templates.activate');
        Route::post('my-templates/{userTemplate}/deactivate', [Admin\Template\MyTemplatesController::class, 'deactivate'])->name('my-templates.deactivate');
        Route::post('my-templates/{userTemplate}/duplicate', [Admin\Template\MyTemplatesController::class, 'duplicate'])->name('my-templates.duplicate');
        Route::delete('my-templates/{userTemplate}', [Admin\Template\MyTemplatesController::class, 'destroy'])->name('my-templates.destroy');

        // Template Export/Import
        Route::post('my-templates/{userTemplate}/export', [Admin\Template\MyTemplatesController::class, 'export'])->name('my-templates.export');
        Route::post('import', [Admin\Template\MyTemplatesController::class, 'import'])->name('import');

        // Template Builder
        Route::get('builder', [Admin\Template\TemplateBuilderController::class, 'index'])->name('builder.index');
        Route::get('builder/create', [Admin\Template\TemplateBuilderController::class, 'create'])->name('builder.create');
        Route::post('builder', [Admin\Template\TemplateBuilderController::class, 'store'])->name('builder.store');
        Route::get('builder/{userTemplate}/edit', [Admin\Template\TemplateBuilderController::class, 'edit'])->name('builder.edit');
        Route::put('builder/{userTemplate}', [Admin\Template\TemplateBuilderController::class, 'update'])->name('builder.update');
        Route::get('builder/{userTemplate}/preview', [Admin\Template\TemplateBuilderController::class, 'preview'])->name('builder.preview');

        // Builder API endpoints
        Route::post('builder/{userTemplate}/sections', [Admin\Template\TemplateBuilderController::class, 'saveSection'])->name('builder.save-section');
        Route::post('builder/{userTemplate}/blocks', [Admin\Template\TemplateBuilderController::class, 'addBlock'])->name('builder.add-block');
        Route::put('builder/{userTemplate}/blocks', [Admin\Template\TemplateBuilderController::class, 'updateBlock'])->name('builder.update-block');
        Route::delete('builder/{userTemplate}/blocks', [Admin\Template\TemplateBuilderController::class, 'deleteBlock'])->name('builder.delete-block');

        // Export Management
        Route::get('exports', [Admin\Template\TemplateExportController::class, 'index'])->name('exports');
        Route::get('exports/{export}/download', [Admin\Template\TemplateExportController::class, 'download'])->name('exports.download');
        Route::delete('exports/{export}', [Admin\Template\TemplateExportController::class, 'destroy'])->name('exports.destroy');
        Route::post('exports/cleanup-expired', [Admin\Template\TemplateExportController::class, 'cleanupExpired'])->name('exports.cleanup-expired');
        Route::post('exports/bulk-download', [Admin\Template\TemplateExportController::class, 'bulkDownload'])->name('exports.bulk-download');
    });

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
