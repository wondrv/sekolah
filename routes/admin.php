<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Quick login route for testing
Route::get('/quick-login', function() {
    $user = \App\Models\User::where('email', 'admin@school.local')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/admin/dashboard')->with('success', 'Logged in as admin!');
    }
    return 'Admin user not found';
})->name('admin.quick-login');

// Test layout route
Route::get('/test-layout', function() {
    return view('test-layout');
})->name('test.layout');

// Debug layout route
Route::get('/debug-layout', function() {
    return view('debug-layout');
})->name('debug.layout');

// EMERGENCY: reset admin credentials & auto login (remove in production)
Route::match(['get','post'],'/emergency-reset-admin', function(\Illuminate\Http\Request $request) {
    if (!app()->isLocal()) {
        abort(403, 'Not allowed in this environment.');
    }
    $email = 'admin@school.local';
    $password = $request->get('password', 'password');
    $user = \App\Models\User::firstOrNew(['email' => $email]);
    $user->name = 'Administrator';
    $user->role = 'admin';
    $user->is_admin = true;
    $user->email_verified_at = now();
    $user->password = bcrypt($password);
    $user->save();
    Auth::login($user);
    return redirect()->route('admin.dashboard')->with('success', 'Emergency admin reset. Email: '.$email.' Password: '.$password);
})->name('admin.emergency-reset-admin');

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
    // Template Import/Export (JSON) - Import functionality moved to Smart Import system
    // Route::post('templates/import', [Admin\TemplateController::class, 'import'])->name('admin.templates.import');
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
        Route::post('gallery/bulk-install', [Admin\Template\TemplateGalleryController::class, 'bulkInstall'])->name('gallery.bulk-install');

        // External templates (MUST be before {template} routes to avoid conflicts)
        Route::post('gallery/external/install', [Admin\Template\TemplateGalleryController::class, 'installExternal'])->name('gallery.external.install');
        Route::get('gallery/external/preview', [Admin\Template\TemplateGalleryController::class, 'previewExternal'])->name('gallery.external.preview');

        // Template-specific routes (MUST be after external routes)
        Route::get('gallery/{template}', [Admin\Template\TemplateGalleryController::class, 'show'])->name('gallery.show');
        Route::get('gallery/{template}/preview', [Admin\Template\TemplateGalleryController::class, 'preview'])->name('gallery.preview');
        Route::get('gallery/{template}/live-preview', [Admin\Template\TemplateGalleryController::class, 'livePreview'])->name('gallery.live-preview');
        Route::post('gallery/{template}/install', [Admin\Template\TemplateGalleryController::class, 'install'])->name('gallery.install');

        // Smart Template Import
        Route::get('smart-import', [Admin\Template\SmartImportController::class, 'index'])->name('smart-import.index');
        Route::post('smart-import/discover', [Admin\Template\SmartImportController::class, 'discover'])->name('smart-import.discover');
        Route::post('smart-import/analyze', [Admin\Template\SmartImportController::class, 'analyzeUrl'])->name('smart-import.analyze');
        Route::post('smart-import/import-url', [Admin\Template\SmartImportController::class, 'importFromUrl'])->name('smart-import.import-url');
        Route::post('smart-import/import-file', [Admin\Template\SmartImportController::class, 'importFromFile'])->name('smart-import.import-file');

        // Full Template Import (WordPress-like)
        Route::get('full-import', [Admin\Template\FullTemplateImportController::class, 'index'])->name('full-import.index');
        Route::post('full-import/import', [Admin\Template\FullTemplateImportController::class, 'import'])->name('full-import.import');
        Route::post('full-import/upload', [Admin\Template\FullTemplateImportController::class, 'uploadZip'])->name('full-import.upload');
        Route::get('full-import/list', [Admin\Template\FullTemplateImportController::class, 'list'])->name('full-import.list');
        Route::get('full-import/{template}/preview', [Admin\Template\FullTemplateImportController::class, 'preview'])->name('full-import.preview');
        Route::post('full-import/{template}/activate', [Admin\Template\FullTemplateImportController::class, 'activate'])->name('full-import.activate');
        Route::delete('full-import/{template}', [Admin\Template\FullTemplateImportController::class, 'delete'])->name('full-import.delete');
        Route::post('smart-import/install-external', [Admin\Template\SmartImportController::class, 'installExternal'])->name('smart-import.install-external');
        Route::get('smart-import/progress', [Admin\Template\SmartImportController::class, 'getProgress'])->name('smart-import.progress');

        // Live Import (Quick Import)
        Route::post('live-import/quick', [Admin\Template\LiveImportController::class, 'quickImport'])->name('live-import.quick');
        Route::post('live-import/batch', [Admin\Template\LiveImportController::class, 'batchImport'])->name('live-import.batch');
        Route::get('live-import/popular-urls', [Admin\Template\LiveImportController::class, 'getPopularUrls'])->name('live-import.popular-urls');
        Route::post('live-import/test-language', [Admin\Template\LiveImportController::class, 'testLanguageDetection'])->name('live-import.test-language');

        // My Templates
        Route::get('my-templates', [Admin\Template\MyTemplatesController::class, 'index'])->name('my-templates.index');
        Route::get('my-templates/{userTemplate}', [Admin\Template\MyTemplatesController::class, 'show'])->name('my-templates.show');
        Route::get('my-templates/{userTemplate}/edit', [Admin\Template\MyTemplatesController::class, 'edit'])->name('my-templates.edit');
        Route::put('my-templates/{userTemplate}', [Admin\Template\MyTemplatesController::class, 'update'])->name('my-templates.update');
        Route::post('my-templates/{userTemplate}/activate', [Admin\Template\MyTemplatesController::class, 'activate'])->name('my-templates.activate');
        Route::post('my-templates/{userTemplate}/deactivate', [Admin\Template\MyTemplatesController::class, 'deactivate'])->name('my-templates.deactivate');
        Route::post('my-templates/{userTemplate}/duplicate', [Admin\Template\MyTemplatesController::class, 'duplicate'])->name('my-templates.duplicate');
        Route::delete('my-templates/{userTemplate}', [Admin\Template\MyTemplatesController::class, 'destroy'])->name('my-templates.destroy');

        // Template Export/Import
        Route::post('my-templates/{userTemplate}/export', [Admin\Template\MyTemplatesController::class, 'export'])->name('my-templates.export');
        Route::post('import', [Admin\Template\MyTemplatesController::class, 'import'])->name('my-templates.import');

        // Template Builder
        Route::get('builder', [Admin\Template\TemplateBuilderController::class, 'index'])->name('builder.index');
        Route::get('builder/create', [Admin\Template\TemplateBuilderController::class, 'create'])->name('builder.create');
        Route::post('builder', [Admin\Template\TemplateBuilderController::class, 'store'])->name('builder.store');
        Route::get('builder/{userTemplate}/edit', [Admin\Template\TemplateBuilderController::class, 'edit'])->name('builder.edit');
        Route::put('builder/{userTemplate}', [Admin\Template\TemplateBuilderController::class, 'update'])->name('builder.update');
        Route::get('builder/{userTemplate}/preview', [Admin\Template\TemplateBuilderController::class, 'preview'])->name('builder.preview');
        // Publish & Apply (force apply current template_data to site DB tables)
        Route::post('builder/{userTemplate}/publish', [Admin\Template\TemplateBuilderController::class, 'publish'])
            ->name('builder.publish');

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

        // Live Preview Routes
        Route::post('my-templates/{userTemplate}/preview-start', [Admin\Template\MyTemplatesController::class, 'startPreview'])
            ->name('my-templates.preview-start');
        Route::post('preview-stop', [Admin\Template\MyTemplatesController::class, 'stopPreview'])
            ->name('preview-stop');

        // Draft actions
        Route::post('my-templates/{userTemplate}/draft/preview', [Admin\Template\MyTemplatesController::class, 'previewDraft'])
            ->name('my-templates.draft.preview');
        Route::post('my-templates/{userTemplate}/draft/publish', [Admin\Template\MyTemplatesController::class, 'publishDraft'])
            ->name('my-templates.draft.publish');
        Route::post('my-templates/{userTemplate}/draft/discard', [Admin\Template\MyTemplatesController::class, 'discardDraft'])
            ->name('my-templates.draft.discard');

        // Revisions
        Route::post('my-templates/{userTemplate}/revisions/{revision}/restore', [Admin\Template\MyTemplatesController::class, 'restoreRevision'])
            ->name('my-templates.revisions.restore');
        Route::delete('my-templates/{userTemplate}/revisions/{revision}', [Admin\Template\MyTemplatesController::class, 'deleteRevision'])
            ->name('my-templates.revisions.delete');

        // Signed public preview link generation
        Route::post('my-templates/{userTemplate}/signed-preview-link', [Admin\Template\MyTemplatesController::class, 'generateSignedPreview'])
            ->name('my-templates.signed-preview-link');
    });

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

        // Live Preview Routes
        Route::post('my-templates/{userTemplate}/preview-start', [Admin\Template\MyTemplatesController::class, 'startPreview'])
            ->name('my-templates.preview-start');
        Route::post('preview-stop', [Admin\Template\MyTemplatesController::class, 'stopPreview'])
            ->name('preview-stop');

        // Draft actions
        Route::post('my-templates/{userTemplate}/draft/preview', [Admin\Template\MyTemplatesController::class, 'previewDraft'])
            ->name('my-templates.draft.preview');
        Route::post('my-templates/{userTemplate}/draft/publish', [Admin\Template\MyTemplatesController::class, 'publishDraft'])
            ->name('my-templates.draft.publish');
        Route::post('my-templates/{userTemplate}/draft/discard', [Admin\Template\MyTemplatesController::class, 'discardDraft'])
            ->name('my-templates.draft.discard');

        // Revisions
        Route::post('my-templates/{userTemplate}/revisions/{revision}/restore', [Admin\Template\MyTemplatesController::class, 'restoreRevision'])
            ->name('my-templates.revisions.restore');
        Route::delete('my-templates/{userTemplate}/revisions/{revision}', [Admin\Template\MyTemplatesController::class, 'deleteRevision'])
            ->name('my-templates.revisions.delete');

        // Signed public preview link generation
        Route::post('my-templates/{userTemplate}/signed-preview-link', [Admin\Template\MyTemplatesController::class, 'generateSignedPreview'])
            ->name('my-templates.signed-preview-link');
    });

    // HTML Validator Routes
    Route::prefix('html-validator')->name('admin.html-validator.')->group(function () {
        Route::get('/', [Admin\HtmlValidatorController::class, 'index'])->name('index');
        Route::post('validate', [Admin\HtmlValidatorController::class, 'validate'])->name('validate');
        Route::post('validate-page', [Admin\HtmlValidatorController::class, 'validatePage'])->name('validate-page');
        Route::post('validate-template', [Admin\HtmlValidatorController::class, 'validateTemplate'])->name('validate-template');
        Route::post('batch-validate', [Admin\HtmlValidatorController::class, 'batchValidate'])->name('batch-validate');
        Route::get('report', [Admin\HtmlValidatorController::class, 'report'])->name('report');
    });

    // Cache Management
    Route::post('cache/clear', function() {
        try {
            \App\Support\Theme::clearCache();
            \Illuminate\Support\Facades\Cache::flush();
            return redirect()->back()->with('success', 'Cache berhasih dibersihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    })->name('admin.cache.clear');
