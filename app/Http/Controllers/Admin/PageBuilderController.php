<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\PageBuilderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PageBuilderController extends Controller
{
    protected PageBuilderService $pageBuilder;

    public function __construct(PageBuilderService $pageBuilder)
    {
        $this->pageBuilder = $pageBuilder;
    }

    /**
     * Show the page builder interface
     */
    public function show(Page $page)
    {
        $availableBlocks = $this->pageBuilder->getRegisteredBlocks();

        return view('admin.pages.builder', compact('page', 'availableBlocks'));
    }

    /**
     * Save page builder content
     */
    public function save(Request $request, Page $page): JsonResponse
    {
        $request->validate([
            'blocks' => 'required|array',
            'blocks.*.id' => 'required|string',
            'blocks.*.type' => 'required|string',
            'blocks.*.settings' => 'array',
        ]);

        $blocks = $request->input('blocks', []);
        
        // Validate each block
        $errors = [];
        foreach ($blocks as $index => $blockData) {
            $blockErrors = $this->pageBuilder->validateBlock($blockData);
            if (!empty($blockErrors)) {
                $errors["blocks.{$index}"] = $blockErrors;
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        }

        try {
            $page->update([
                'content_json' => $blocks,
                'use_page_builder' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Page saved successfully',
                'preview_url' => route('pages.show', $page)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available blocks
     */
    public function blocks(): JsonResponse
    {
        return response()->json([
            'blocks' => $this->pageBuilder->getRegisteredBlocks(),
            'categories' => $this->pageBuilder->getBlocksByCategory()
        ]);
    }

    /**
     * Get block configuration
     */
    public function blockConfig(string $type): JsonResponse
    {
        $config = $this->pageBuilder->getBlockConfig($type);
        
        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'Block type not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'config' => $config,
            'sample_data' => $this->pageBuilder->getSampleBlockData($type)
        ]);
    }

    /**
     * Preview block
     */
    public function preview(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string',
            'settings' => 'array',
        ]);

        $blockData = [
            'id' => 'preview',
            'type' => $request->input('type'),
            'settings' => $request->input('settings', []),
        ];

        $errors = $this->pageBuilder->validateBlock($blockData);
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ], 422);
        }

        try {
            $html = $this->pageBuilder->renderBlock($blockData);
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to render block: ' . $e->getMessage()
            ], 500);
        }
    }
}
