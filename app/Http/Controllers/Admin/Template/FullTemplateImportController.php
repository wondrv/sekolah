<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Services\FullTemplateImporterService;
use App\Models\UserTemplate;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FullTemplateImportController extends Controller
{
    protected FullTemplateImporterService $importer;

    public function __construct(FullTemplateImporterService $importer)
    {
        $this->importer = $importer;
    }

    /**
     * Show full template import page
     */
    public function index()
    {
        return view('admin.templates.full-import.index');
    }

    /**
     * Import template from various sources
     */
    public function import(Request $request)
    {
        $request->validate([
            'source' => 'required|string',
            'type' => 'required|in:github,url,zip',
            'name' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:50'
        ]);

        try {
            $options = [];
            if ($request->name) {
                $options['name'] = $request->name;
            }
            if ($request->branch) {
                $options['branch'] = $request->branch;
            }

            $result = $this->importer->importFullTemplate(
                $request->source,
                \Illuminate\Support\Facades\Auth::id(),
                $options
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'template_id' => $result['user_template']->id,
                    'files_imported' => $result['files_imported'],
                    'redirect' => route('admin.templates.full-import.preview', $result['user_template']->id)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload and import ZIP file
     */
    public function uploadZip(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:50000', // 50MB max
            'name' => 'nullable|string|max:255'
        ]);

        try {
            $zipFile = $request->file('zip_file');
            $tempPath = $zipFile->store('temp');
            $fullPath = Storage::path($tempPath);

            $options = [];
            if ($request->name) {
                $options['name'] = $request->name;
            }

            $result = $this->importer->importFullTemplate(
                $fullPath,
                \Illuminate\Support\Facades\Auth::id(),
                $options
            );

            // Clean up temp file
            Storage::delete($tempPath);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'template_id' => $result['user_template']->id,
                    'files_imported' => $result['files_imported'],
                    'redirect' => route('admin.templates.full-import.preview', $result['user_template']->id)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview imported template
     */
    public function preview(UserTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        $templateData = $template->template_data;
        if ($templateData['type'] !== 'full_template') {
            abort(404, 'Not a full template');
        }

        return view('admin.templates.full-import.preview', compact('template', 'templateData'));
    }

    /**
     * Activate template for homepage
     */
    public function activate(UserTemplate $template)
    {
        if ($template->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        try {
            // Deactivate other full templates
            UserTemplate::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('settings->template_type', 'full_template')
                ->update(['status' => 'inactive']);

            // Activate this template
            $template->update(['status' => 'active']);

            // Set as homepage template
            \App\Models\Setting::set('homepage_template_type', 'full_template');
            \App\Models\Setting::set('active_full_template_id', $template->id);

            return response()->json([
                'success' => true,
                'message' => 'Template activated successfully for homepage'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all imported full templates
     */
    public function list()
    {
        $templates = UserTemplate::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('settings->template_type', 'full_template')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.templates.full-import.list', compact('templates'));
    }

    /**
     * Delete imported template
     */
    public function delete(UserTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $templateData = $template->template_data;

            // Delete template files
            if (isset($templateData['assets_path'])) {
                Storage::deleteDirectory($templateData['assets_path']);
            }

            // Delete template record
            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template: ' . $e->getMessage()
            ], 500);
        }
    }
}
