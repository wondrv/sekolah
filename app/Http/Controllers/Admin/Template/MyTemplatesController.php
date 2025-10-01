<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Models\UserTemplate;
use App\Models\TemplateGallery;
use App\Models\TemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ZipArchive;

class MyTemplatesController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = UserTemplate::byUser()->with(['galleryTemplate', 'templates']);

        // Filter by source
        if ($request->has('source') && $request->source) {
            if ($request->source === 'gallery') {
                $query->fromGallery();
            } elseif ($request->source === 'custom') {
                $query->custom();
            } elseif ($request->source === 'imported') {
                $query->imported();
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $templates = $query->orderBy('is_active', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(12)
            ->appends($request->all());

        $activeTemplate = UserTemplate::byUser()->active()->first();

        $stats = [
            'total' => UserTemplate::byUser()->count(),
            'active' => $activeTemplate ? 1 : 0,
            'gallery' => UserTemplate::byUser()->fromGallery()->count(),
            'custom' => UserTemplate::byUser()->custom()->count(),
        ];

        return view('admin.templates.my-templates.index', compact(
            'templates',
            'activeTemplate',
            'stats',
            'request'
        ));
    }

    public function show(UserTemplate $userTemplate)
    {
        $this->authorize('view', $userTemplate);

        $userTemplate->load(['galleryTemplate', 'templates.sections.blocks', 'revisions' => function($q){
            $q->limit(20); // show last 20
        }]);

        return view('admin.templates.my-templates.show', compact('userTemplate'));
    }

    public function activate(UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        try {
            $userTemplate->activate();

            return redirect()->back()->with('success', 'Template berhasil diaktifkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengaktifkan template: ' . $e->getMessage());
        }
    }

    public function deactivate(UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        try {
            $userTemplate->update(['is_active' => false]);

            return redirect()->back()->with('success', 'Template berhasil dinonaktifkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menonaktifkan template: ' . $e->getMessage());
        }
    }

    public function duplicate(UserTemplate $userTemplate)
    {
        $this->authorize('view', $userTemplate);

        try {
            $duplicate = $userTemplate->duplicate();

            return redirect()->route('admin.templates.my-templates.show', $duplicate)
                ->with('success', 'Template berhasil diduplikasi!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menduplikasi template: ' . $e->getMessage());
        }
    }

    public function destroy(UserTemplate $userTemplate)
    {
        $this->authorize('delete', $userTemplate);

        try {
            // Don't delete if it's the active template
            if ($userTemplate->is_active) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus template yang sedang aktif.');
            }

            $userTemplate->delete();

            return redirect()->route('admin.templates.my-templates.index')
                ->with('success', 'Template berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus template: ' . $e->getMessage());
        }
    }

    /**
     * Generate a signed public preview link for external reviewers.
     */
    public function generateSignedPreview(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('view', $userTemplate);

        $validated = $request->validate([
            'expires_minutes' => 'nullable|integer|min:5|max:4320', // up to 3 days
            'path' => 'nullable|string|max:255',
            'include_draft' => 'sometimes|boolean',
        ]);

        $minutes = $validated['expires_minutes'] ?? 120; // default 2 hours
        $path = $validated['path'] ?? '/';
        $includeDraft = $request->boolean('include_draft');

        // Build base params for signed route
        $params = ['userTemplate' => $userTemplate->id];
        if ($includeDraft) {
            $params['draft'] = 1;
        }
        if ($path && $path !== '/') {
            $params['path'] = ltrim($path, '/');
        }

    $url = URL::temporarySignedRoute(
            'public.template-preview',
            now()->addMinutes($minutes),
            $params
        );

        return redirect()->back()->with('signed_preview_link', $url)->with('success', 'Signed preview link dibuat.');
    }

    /**
     * Start live preview for a user template (session + query based)
     */
    public function startPreview(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('view', $userTemplate);

        // Store preview template id in session
        session(['preview_user_template_id' => $userTemplate->id]);

        // Optional: allow specific target path
        $path = $request->get('path', '/');
        $separator = str_contains($path, '?') ? '&' : '?';

        return redirect($path . $separator . 'preview=1')
            ->with('success', 'Mode preview dimulai untuk template: ' . $userTemplate->name);
    }

    /**
     * Stop live preview session
     */
    public function stopPreview(Request $request)
    {
        session()->forget(['preview_user_template_id', 'preview_use_draft']);
        return redirect('/')->with('success', 'Mode preview dihentikan.');
    }

    /**
     * Start draft preview (uses draft_template_data if available)
     */
    public function previewDraft(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('view', $userTemplate);

        if (!$userTemplate->hasDraft()) {
            return redirect()->back()->with('error', 'Tidak ada draft untuk template ini.');
        }

        session(['preview_user_template_id' => $userTemplate->id]);
        session(['preview_use_draft' => true]);

        $path = $request->get('path', '/');
        $separator = str_contains($path, '?') ? '&' : '?';

        return redirect($path . $separator . 'preview=1')
            ->with('success', 'Preview draft dimulai.');
    }

    /**
     * Publish draft changes into live template
     */
    public function publishDraft(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        if (!$userTemplate->hasDraft()) {
            return redirect()->back()->with('error', 'Tidak ada draft untuk dipublish.');
        }

        try {
            $userTemplate->publishDraft();
            session()->forget(['preview_use_draft']);
            return redirect()->back()->with('success', 'Draft berhasil dipublish!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mempublish draft: ' . $e->getMessage());
        }
    }

    /**
     * Discard draft changes
     */
    public function discardDraft(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('update', $userTemplate);

        if (!$userTemplate->hasDraft()) {
            return redirect()->back()->with('error', 'Tidak ada draft untuk dibatalkan.');
        }

        try {
            $userTemplate->discardDraft();
            session()->forget(['preview_use_draft']);
            return redirect()->back()->with('success', 'Draft berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan draft: ' . $e->getMessage());
        }
    }

    /**
     * Restore a template revision
     */
    public function restoreRevision(Request $request, UserTemplate $userTemplate, \App\Models\TemplateRevision $revision)
    {
        $this->authorize('update', $userTemplate);
        if ($revision->user_template_id !== $userTemplate->id) {
            abort(403);
        }

        try {
            $userTemplate->restoreRevision($revision, $request->boolean('keep_draft', true));
            return redirect()->back()->with('success', 'Revision berhasil direstore.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal restore revision: '.$e->getMessage());
        }
    }

    /**
     * Delete a template revision
     */
    public function deleteRevision(Request $request, UserTemplate $userTemplate, \App\Models\TemplateRevision $revision)
    {
        $this->authorize('update', $userTemplate);
        if ($revision->user_template_id !== $userTemplate->id) {
            abort(403);
        }

        try {
            $revision->delete();
            return redirect()->back()->with('success', 'Revision dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus revision: '.$e->getMessage());
        }
    }

    public function export(Request $request, UserTemplate $userTemplate)
    {
        $this->authorize('view', $userTemplate);

        $request->validate([
            'format' => 'required|in:json,zip',
            'include_content' => 'boolean',
            'include_images' => 'boolean',
            'expires_hours' => 'nullable|integer|min:1|max:168', // Max 1 week
        ]);

        try {
            $format = $request->get('format', 'json');
            $options = [
                'include_content' => $request->boolean('include_content', true),
                'include_images' => $request->boolean('include_images', false),
            ];

            if ($format === 'json') {
                return $this->exportAsJson($userTemplate, $options);
            } else {
                return $this->exportAsZip($userTemplate, $options, $request->get('expires_hours', 24));
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengexport template: ' . $e->getMessage());
        }
    }

    protected function exportAsJson(UserTemplate $userTemplate, array $options)
    {
        $data = $userTemplate->exportToArray();

        if (!$options['include_content']) {
            // Remove demo content if not included
            unset($data['template_data']['demo_content']);
        }

        $filename = Str::slug($userTemplate->name) . '-template.json';

        return Response::json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Type' => 'application/json',
        ]);
    }

    protected function exportAsZip(UserTemplate $userTemplate, array $options, int $expiresHours)
    {
        $exportId = Str::uuid();
        $filename = Str::slug($userTemplate->name) . '-template-' . $exportId . '.zip';
        $zipPath = 'exports/' . $filename;
        $fullPath = Storage::path($zipPath);

        // Ensure exports directory exists
        Storage::makeDirectory('exports');

        $zip = new ZipArchive();
        if ($zip->open($fullPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create zip file');
        }

        // Add template JSON
        $templateData = $userTemplate->exportToArray();
        if (!$options['include_content']) {
            unset($templateData['template_data']['demo_content']);
        }

        $zip->addFromString('template.json', json_encode($templateData, JSON_PRETTY_PRINT));

        // Add README
        $readme = $this->generateReadme($userTemplate);
        $zip->addFromString('README.md', $readme);

        // Add images if requested
        if ($options['include_images']) {
            $this->addImagesToZip($zip, $userTemplate);
        }

        $zip->close();

        // Create export record
        $export = TemplateExport::create([
            'user_id' => Auth::id(),
            'user_template_id' => $userTemplate->id,
            'filename' => $filename,
            'format' => 'zip',
            'export_options' => $options,
            'file_path' => $zipPath,
            'file_size' => Storage::size($zipPath),
            'expires_at' => now()->addHours($expiresHours),
        ]);

        return redirect()->route('admin.templates.exports')
            ->with('success', 'Template berhasil diexport! Download akan tersedia selama ' . $expiresHours . ' jam.');
    }

    protected function addImagesToZip(ZipArchive $zip, UserTemplate $userTemplate)
    {
        // Add preview image
        if ($userTemplate->preview_image && Storage::exists($userTemplate->preview_image)) {
            $zip->addFile(
                Storage::path($userTemplate->preview_image),
                'images/' . basename($userTemplate->preview_image)
            );
        }

        // Add template content images
        // This would require parsing template data for image references
        // Implementation depends on how images are stored in template_data
    }

    protected function generateReadme(UserTemplate $userTemplate): string
    {
        return "# {$userTemplate->name}\n\n" .
               "{$userTemplate->description}\n\n" .
               "## Template Information\n\n" .
               "- **Source**: " . ucfirst($userTemplate->source) . "\n" .
               "- **Created**: {$userTemplate->created_at->format('Y-m-d H:i:s')}\n" .
               "- **Updated**: {$userTemplate->updated_at->format('Y-m-d H:i:s')}\n" .
               "- **Export Date**: " . now()->format('Y-m-d H:i:s') . "\n\n" .
               "## Installation\n\n" .
               "1. Upload the template.json file through the Template Import feature\n" .
               "2. Configure any required settings\n" .
               "3. Activate the template\n\n" .
               "## Support\n\n" .
               "For support, please contact your system administrator.\n";
    }

    public function import(Request $request)
    {
        $request->validate([
            'template_file' => 'required|file|mimes:json,zip|max:10240', // Max 10MB
            'template_name' => 'required|string|max:255',
            'activate_after_import' => 'boolean',
        ]);

        try {
            $file = $request->file('template_file');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            if ($extension === 'json') {
                return $this->importFromJson($file, $request);
            } else {
                return $this->importFromZip($file, $request);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport template: ' . $e->getMessage());
        }
    }

    protected function importFromJson($file, Request $request)
    {
        $content = file_get_contents($file->getRealPath());
        $data = json_decode($content, true);

        if (!$data || !isset($data['template_data'])) {
            throw new \Exception('Invalid template file format');
        }

        $userTemplate = UserTemplate::create([
            'user_id' => Auth::id(),
            'name' => $request->get('template_name'),
            'slug' => Str::slug($request->get('template_name')) . '-' . time(),
            'description' => $data['description'] ?? 'Imported template',
            'template_data' => $data['template_data'],
            'source' => 'imported',
            'customizations' => $data['customizations'] ?? [],
        ]);

        if ($request->boolean('activate_after_import')) {
            $userTemplate->activate();
            $message = 'Template berhasil diimport dan diaktifkan!';
        } else {
            $message = 'Template berhasil diimport!';
        }

        return redirect()->route('admin.templates.my-templates.show', $userTemplate)
            ->with('success', $message);
    }

    protected function importFromZip($file, Request $request)
    {
        $tempPath = $file->store('temp');
        $zip = new ZipArchive();

        if ($zip->open(Storage::path($tempPath)) !== TRUE) {
            throw new \Exception('Cannot open zip file');
        }

        // Extract template.json
        $templateJson = $zip->getFromName('template.json');
        if (!$templateJson) {
            throw new \Exception('template.json not found in zip file');
        }

        $data = json_decode($templateJson, true);
        if (!$data || !isset($data['template_data'])) {
            throw new \Exception('Invalid template.json format');
        }

        // Create user template
        $userTemplate = UserTemplate::create([
            'user_id' => Auth::id(),
            'name' => $request->get('template_name'),
            'slug' => Str::slug($request->get('template_name')) . '-' . time(),
            'description' => $data['description'] ?? 'Imported template',
            'template_data' => $data['template_data'],
            'source' => 'imported',
            'customizations' => $data['customizations'] ?? [],
        ]);

        // Extract images if present
        $this->extractImagesFromZip($zip, $userTemplate);

        $zip->close();
        Storage::delete($tempPath);

        if ($request->boolean('activate_after_import')) {
            $userTemplate->activate();
            $message = 'Template berhasil diimport dan diaktifkan!';
        } else {
            $message = 'Template berhasil diimport!';
        }

        return redirect()->route('admin.templates.my-templates.show', $userTemplate)
            ->with('success', $message);
    }

    protected function extractImagesFromZip(ZipArchive $zip, UserTemplate $userTemplate)
    {
        $userDir = 'templates/user-' . $userTemplate->user_id . '/' . $userTemplate->slug;
        Storage::makeDirectory($userDir);

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            if (str_starts_with($filename, 'images/')) {
                $content = $zip->getFromIndex($i);
                $relativePath = $userDir . '/' . $filename;
                Storage::put($relativePath, $content);
            }
        }
    }
}
