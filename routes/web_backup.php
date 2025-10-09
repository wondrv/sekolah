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

// Public routes (no login required) - DYNAMIC TEMPLATE SYSTEM
Route::get('/', function() {
    try {
        $template = App\Models\UserTemplate::where('is_active', true)->where('template_type', 'blade_views')->first();

        if (!$template) {
            // Fallback to default template
            return response()->view('templates.fallback-home', [
                'site_title' => 'School CMS',
                'site_description' => 'Modern School Content Management System',
            ]);
        }

        $templateFiles = $template->template_files;
        $homeViewFile = $templateFiles['resources/views/home.blade.php'] ?? null;
        $layoutViewFile = $templateFiles['resources/views/layouts/main.blade.php'] ?? null;

        if (!$homeViewFile || !$layoutViewFile) {
            return response('Template files not found');
        }

        $homeContent = base64_decode($homeViewFile['content']);
        $layoutContent = base64_decode($layoutViewFile['content']);

        // Extract content section
        $contentPattern = "/@section\\s*\\(\\s*['\"]content['\"]\\s*\\)(.*?)@endsection/s";
        preg_match($contentPattern, $homeContent, $contentMatches);
        $contentSection = $contentMatches[1] ?? '';

        // Extract title section
        $titlePattern = "/@section\\s*\\(\\s*['\"]title['\"]\\s*,\\s*['\"]([^'\"]*)['\"]\\s*\\)/";
        preg_match($titlePattern, $homeContent, $titleMatches);
        $title = $titleMatches[1] ?? 'Home';

        // Replace in layout
        $finalContent = str_replace('@yield(\'content\')', trim($contentSection), $layoutContent);
        $finalContent = str_replace('@yield(\'title\', \'MA Cendekia Nusantara\')', $title, $finalContent);
        $finalContent = str_replace('@yield(\'title\')', $title, $finalContent);

        // Fix URLs and template variables
        $baseUrl = request()->getSchemeAndHttpHost();
        $finalContent = str_replace('href="/', 'href="' . $baseUrl . '/', $finalContent);
        $finalContent = str_replace('{{ route(\'home\') }}', $baseUrl, $finalContent);
        $finalContent = str_replace('{{ route(\'ppdb\') }}', $baseUrl . '/ppdb', $finalContent);
        $finalContent = str_replace('{{ date(\'Y\') }}', date('Y'), $finalContent);

        return response($finalContent)->header('Content-Type', 'text/html');

    } catch (\Exception $e) {
        return response('Template Error: ' . $e->getMessage());
    }
})->name('home');// Test route for dynamic template
Route::get('/test-template', function() {
    $template = App\Models\UserTemplate::where('is_active', true)->where('template_type', 'blade_views')->first();

    if (!$template) {
        return response('No active template found');
    }

    $templateFiles = $template->template_files;
    $homeViewFile = $templateFiles['resources/views/home.blade.php'] ?? null;
    $layoutViewFile = $templateFiles['resources/views/layouts/main.blade.php'] ?? null;

    if (!$homeViewFile || !$layoutViewFile) {
        return response('Template files not found');
    }

    $homeContent = base64_decode($homeViewFile['content']);
    $layoutContent = base64_decode($layoutViewFile['content']);

    // Extract content section
    $contentPattern = "/@section\\s*\\(\\s*['\"]content['\"]\\s*\\)(.*?)@endsection/s";
    preg_match($contentPattern, $homeContent, $contentMatches);
    $contentSection = $contentMatches[1] ?? '';

    // Extract title section
    $titlePattern = "/@section\\s*\\(\\s*['\"]title['\"]\\s*,\\s*['\"]([^'\"]*)['\"]\\s*\\)/";
    preg_match($titlePattern, $homeContent, $titleMatches);
    $title = $titleMatches[1] ?? 'Home';

    // Replace in layout
    $finalContent = str_replace('@yield(\'content\')', trim($contentSection), $layoutContent);
    $finalContent = str_replace('@yield(\'title\', \'MA Cendekia Nusantara\')', $title, $finalContent);
    $finalContent = str_replace('@yield(\'title\')', $title, $finalContent);

    // Fix URLs
    $baseUrl = request()->getSchemeAndHttpHost();
    $finalContent = str_replace('href="/', 'href="' . $baseUrl . '/', $finalContent);

    return response($finalContent)->header('Content-Type', 'text/html');
});

// Signed public template preview (no auth). This sets session flags then redirects to desired path with preview query.
Route::get('/preview/template/{userTemplate}', [PublicTemplatePreviewController::class, 'show'])
    ->middleware('signed')
    ->name('public.template-preview');

// Dynamic content routes
// Backward compatibility route with tentang-kami prefix - redirects to clean URLs
Route::get('/tentang-kami/{slug?}', function ($slug = null) {
    if ($slug) {
        return redirect('/' . $slug, 301);
    }
    // For /tentang-kami without slug, redirect to the clean URL
    return redirect('/tentang-kami', 301);
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

// Redirect old contact URL to Indonesian version
Route::get('/contact', function () {
    return redirect('/kontak', 301);
});


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
Route::get('/tentang-kami', [PageController::class, 'showSingle'])->defaults('slug', 'tentang-kami')->name('tentang-kami');
Route::get('/ppdb', [PageController::class, 'showSingle'])->defaults('slug', 'ppdb')->name('ppdb');

// Backward compatibility route with tentang-kami prefix - redirects to clean URLs
Route::get('/tentang-kami/{slug}', function ($slug) {
    return redirect('/' . $slug, 301);
});

require __DIR__.'/auth.php';

// Include template routes BEFORE the catch-all route
if (file_exists(__DIR__ . '/template.php')) {
    require __DIR__ . '/template.php';
}

// Pure CMS Routes - Handle all dynamic content
// This should be the LAST route to catch all unmatched paths
Route::get('/{path?}', [App\Http\Controllers\PureCMSController::class, 'handleRequest'])
    ->where('path', '.*')
    ->name('cms.dynamic');
