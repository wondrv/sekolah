<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Services\AdvancedTemplateImporterService;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompleteProjectImportController extends Controller
{
    protected AdvancedTemplateImporterService $importer;

    public function __construct(AdvancedTemplateImporterService $importer)
    {
        $this->importer = $importer;
    }

    /**
     * Show complete project import interface
     */
    public function index()
    {
        $recentProjects = UserTemplate::where('user_id', Auth::id())
            ->where('settings->template_type', 'complete_project')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.templates.complete-import.index', [
            'recent_projects' => $recentProjects
        ]);
    }

    /**
     * Import complete project from GitHub
     */
    public function importFromGitHub(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'github_url' => 'required|url',
            'branch' => 'nullable|string|max:50',
            'project_name' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $githubUrl = $request->get('github_url');
            $options = [
                'branch' => $request->get('branch', 'main'),
                'name' => $request->get('project_name')
            ];

            Log::info('Complete project import from GitHub started', [
                'url' => $githubUrl,
                'user_id' => Auth::id(),
                'options' => $options
            ]);

            $result = $this->importer->importCompleteProject($githubUrl, Auth::id(), $options);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'template_id' => $result['user_template']->id,
                    'files_imported' => $result['files_imported'],
                    'project_analysis' => $result['project_analysis'],
                    'redirect' => route('admin.templates.complete-import.preview', $result['user_template']->id)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('GitHub project import failed', [
                'url' => $request->get('github_url'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'GitHub import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import complete project from ZIP upload
     */
    public function importFromZip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_zip' => 'required|file|mimes:zip|max:50240', // Max 50MB
            'project_name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $zipFile = $request->file('project_zip');
            $projectName = $request->get('project_name');

            // Store uploaded file temporarily
            $tempPath = $zipFile->store('temp');
            $fullPath = storage_path('app/' . $tempPath);

            Log::info('Complete project import from ZIP started', [
                'filename' => $zipFile->getClientOriginalName(),
                'size' => $zipFile->getSize(),
                'user_id' => Auth::id()
            ]);

            $result = $this->importer->importCompleteProject($fullPath, Auth::id(), [
                'name' => $projectName
            ]);

            // Clean up temp file
            Storage::delete($tempPath);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'template_id' => $result['user_template']->id,
                    'files_imported' => $result['files_imported'],
                    'project_analysis' => $result['project_analysis'],
                    'redirect' => route('admin.templates.complete-import.preview', $result['user_template']->id)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('ZIP project import failed', [
                'filename' => $request->file('project_zip')?->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'ZIP import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview imported project
     */
    public function preview(UserTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        if ($template->settings['template_type'] !== 'complete_project') {
            abort(404, 'Not a complete project template');
        }

        return view('admin.templates.complete-import.preview', [
            'template' => $template,
            'project_info' => $template->settings['project_structure'] ?? [],
            'main_file' => $template->settings['main_file'] ?? 'index.html',
            'assets_path' => $template->settings['assets_path'] ?? ''
        ]);
    }

    /**
     * Activate complete project template
     */
    public function activate(UserTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            // Deactivate other templates
            UserTemplate::where('user_id', Auth::id())
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Activate this template
            $template->update(['is_active' => true]);

            // Update homepage assignment if needed
            \App\Models\Setting::updateOrCreate(
                ['key' => 'active_template_id'],
                ['value' => $template->id]
            );

            Log::info('Complete project template activated', [
                'template_id' => $template->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Complete project template activated successfully for homepage'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to activate complete project template', [
                'template_id' => $template->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to activate template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete complete project template
     */
    public function delete(UserTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            // Delete all project files
            if (isset($template->settings['assets_path'])) {
                Storage::deleteDirectory($template->settings['assets_path']);
            }

            // Delete template record
            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'Complete project template deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all complete project templates
     */
    public function list()
    {
        $templates = UserTemplate::where('user_id', Auth::id())
            ->where('settings->template_type', 'complete_project')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.templates.complete-import.list', [
            'templates' => $templates
        ]);
    }
}
