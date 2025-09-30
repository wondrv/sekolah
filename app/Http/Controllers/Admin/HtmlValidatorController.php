<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HtmlValidatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class HtmlValidatorController extends Controller
{
    protected HtmlValidatorService $validator;

    public function __construct(HtmlValidatorService $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Show HTML validator dashboard
     */
    public function index(): View
    {
        return view('admin.html-validator.index');
    }

    /**
     * Validate HTML content
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'html' => 'required|string',
            'validate_accessibility' => 'boolean',
            'validate_performance' => 'boolean',
            'validate_seo' => 'boolean',
        ]);

        $options = [
            'duplicate_ids' => true,
            'missing_alt_attributes' => $request->boolean('validate_accessibility', true),
            'heading_structure' => $request->boolean('validate_seo', true),
            'form_labels' => $request->boolean('validate_accessibility', true),
            'semantic_structure' => $request->boolean('validate_accessibility', true),
            'link_validation' => true,
            'meta_tags' => $request->boolean('validate_seo', true),
            'html5_validation' => true,
        ];

        $results = $this->validator->validateHtml($request->html, $options);

        return response()->json($results);
    }

    /**
     * Validate current page
     */
    public function validatePage(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $results = $this->validator->validatePage($request->url);

        return response()->json($results);
    }

    /**
     * Validate template
     */
    public function validateTemplate(Request $request): JsonResponse
    {
        $request->validate([
            'template_id' => 'required|integer',
        ]);

        try {
            // Get template content (simplified)
            $templateUrl = url('/') . '?preview_template=' . $request->template_id;
            $results = $this->validator->validatePage($templateUrl);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to validate template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get validation report
     */
    public function report(Request $request): View
    {
        $request->validate([
            'html' => 'required|string',
        ]);

        $results = $this->validator->validateHtml($request->html);

        return view('admin.html-validator.report', compact('results'));
    }

    /**
     * Batch validate multiple URLs
     */
    public function batchValidate(Request $request): JsonResponse
    {
        $request->validate([
            'urls' => 'required|array|max:10',
            'urls.*' => 'required|url',
        ]);

        $results = [];

        foreach ($request->urls as $url) {
            $results[] = [
                'url' => $url,
                'validation' => $this->validator->validatePage($url)
            ];
        }

        return response()->json([
            'batch_results' => $results,
            'summary' => $this->getBatchSummary($results)
        ]);
    }

    /**
     * Get batch validation summary
     */
    protected function getBatchSummary(array $results): array
    {
        $totalUrls = count($results);
        $validUrls = 0;
        $totalErrors = 0;
        $totalWarnings = 0;
        $averageScore = 0;

        foreach ($results as $result) {
            $validation = $result['validation'];
            if ($validation['valid']) {
                $validUrls++;
            }
            $totalErrors += $validation['summary']['errors'];
            $totalWarnings += $validation['summary']['warnings'];
            $averageScore += $validation['score'];
        }

        return [
            'total_urls' => $totalUrls,
            'valid_urls' => $validUrls,
            'invalid_urls' => $totalUrls - $validUrls,
            'total_errors' => $totalErrors,
            'total_warnings' => $totalWarnings,
            'average_score' => $totalUrls > 0 ? round($averageScore / $totalUrls, 1) : 0,
            'success_rate' => $totalUrls > 0 ? round(($validUrls / $totalUrls) * 100, 1) : 0,
        ];
    }
}
