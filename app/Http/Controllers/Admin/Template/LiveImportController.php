<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Services\SmartTemplateImporterService;
use App\Services\LanguageDetectionService;
use App\Services\AutoTranslationService;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LiveImportController extends Controller
{
    protected SmartTemplateImporterService $importer;
    protected LanguageDetectionService $languageDetector;
    protected AutoTranslationService $translator;

    public function __construct(
        SmartTemplateImporterService $importer,
        LanguageDetectionService $languageDetector,
        AutoTranslationService $translator
    ) {
        $this->importer = $importer;
        $this->languageDetector = $languageDetector;
        $this->translator = $translator;
    }

    /**
     * Live demo import - import any template URL instantly
     */
    public function quickImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'auto_activate' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $url = $request->get('url');
            $userId = Auth::id();
            $autoActivate = $request->boolean('auto_activate', true); // Default true for quick import

            Log::info('Quick import started', ['url' => $url, 'user_id' => $userId]);

            // Use smart importer with auto-activation enabled
            $result = $this->importer->importFromUrl($url, $userId, [
                'auto_activate' => $autoActivate,
                'custom_name' => $this->generateTemplateName($url),
                'custom_description' => 'Auto-imported template dengan deteksi bahasa dan terjemahan otomatis'
            ]);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'template' => [
                        'id' => $result['template']->id,
                        'name' => $result['template']->name,
                        'slug' => $result['template']->slug,
                        'preview_url' => $result['template']->preview_image_url,
                        'is_active' => $result['template']->is_active,
                        'homepage_url' => $result['template']->is_active ? url('/') : null
                    ],
                    'stats' => $result['stats'],
                    'translation_info' => $this->getTranslationInfo($result['stats']),
                    'message' => $autoActivate ?
                        'Template berhasil diimpor dan langsung aktif di homepage!' :
                        'Template berhasil diimpor!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'],
                    'code' => $result['code'] ?? 'IMPORT_FAILED',
                    'suggestions' => $this->getErrorSuggestions($result)
                ], 422);
            }

        } catch (\Exception $e) {
            Log::error('Quick import failed', [
                'url' => $request->get('url'),
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Import gagal: ' . $e->getMessage(),
                'code' => 'UNEXPECTED_ERROR',
                'suggestions' => ['Coba lagi dengan URL yang valid', 'Pastikan website dapat diakses']
            ], 500);
        }
    }

    /**
     * Batch import multiple URLs
     */
    public function batchImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'urls' => 'required|array|min:1|max:5',
            'urls.*' => 'required|url',
            'auto_activate_last' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $urls = $request->get('urls');
        $userId = Auth::id();
        $autoActivateLast = $request->boolean('auto_activate_last', true);

        $results = [];
        $successful = 0;
        $failed = 0;

        foreach ($urls as $index => $url) {
            try {
                $isLast = ($index === count($urls) - 1);
                $shouldActivate = $autoActivateLast && $isLast;

                $result = $this->importer->importFromUrl($url, $userId, [
                    'auto_activate' => $shouldActivate,
                    'custom_name' => $this->generateTemplateName($url) . ' #' . ($index + 1),
                    'custom_description' => 'Template dari batch import'
                ]);

                if ($result['success']) {
                    $successful++;
                    $results[] = [
                        'url' => $url,
                        'success' => true,
                        'template' => [
                            'id' => $result['template']->id,
                            'name' => $result['template']->name,
                            'is_active' => $result['template']->is_active
                        ]
                    ];
                } else {
                    $failed++;
                    $results[] = [
                        'url' => $url,
                        'success' => false,
                        'error' => $result['error']
                    ];
                }

                // Small delay between imports
                usleep(500000); // 0.5 seconds

            } catch (\Exception $e) {
                $failed++;
                $results[] = [
                    'url' => $url,
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => $successful > 0,
            'summary' => [
                'total' => count($urls),
                'successful' => $successful,
                'failed' => $failed
            ],
            'results' => $results,
            'message' => "Batch import selesai: {$successful} berhasil, {$failed} gagal"
        ]);
    }

    /**
     * Get popular school template URLs for quick import
     */
    public function getPopularUrls()
    {
        $popularUrls = [
            [
                'name' => 'Bootstrap School Theme',
                'url' => 'https://bootstrapmade.com/demo/Mentor/',
                'description' => 'Template sekolah modern dengan Bootstrap',
                'category' => 'Modern School',
                'preview' => 'https://bootstrapmade.com/content/demo/Mentor.jpg'
            ],
            [
                'name' => 'Education Template',
                'url' => 'https://colorlib.com/preview/theme/courseplus/',
                'description' => 'Template pendidikan dengan fitur course',
                'category' => 'Online Learning',
                'preview' => 'https://colorlib.com/wp/wp-content/uploads/sites/2/courseplus-free-template.jpg'
            ],
            [
                'name' => 'University Theme',
                'url' => 'https://themewagon.com/themes/free-responsive-bootstrap-4-html5-university-website-template-academic/',
                'description' => 'Template universitas professional',
                'category' => 'University',
                'preview' => 'https://themewagon.com/wp-content/uploads/2019/06/academic.jpg'
            ],
            [
                'name' => 'Kids School Template',
                'url' => 'https://colorlib.com/preview/theme/kiddos/',
                'description' => 'Template sekolah anak-anak yang colorful',
                'category' => 'Elementary School',
                'preview' => 'https://colorlib.com/wp/wp-content/uploads/sites/2/kiddos-free-template.jpg'
            ],
            [
                'name' => 'Online Course Platform',
                'url' => 'https://colorlib.com/preview/theme/eduka/',
                'description' => 'Platform pembelajaran online modern',
                'category' => 'E-Learning',
                'preview' => 'https://colorlib.com/wp/wp-content/uploads/sites/2/eduka-free-template.jpg'
            ]
        ];

        return response()->json([
            'success' => true,
            'urls' => $popularUrls,
            'total' => count($popularUrls)
        ]);
    }

    /**
     * Test language detection on a URL
     */
    public function testLanguageDetection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $url = $request->get('url');

            // Quick analysis for language detection
            $analysis = $this->importer->analyzeTemplate($url);

            if ($analysis['success']) {
                $languageInfo = $analysis['language'];

                // If not Indonesian, get translation sample
                $translationSample = null;
                if ($languageInfo['primary_language'] !== 'id') {
                    $sampleText = substr($analysis['meta']['title'] ?? 'Sample text', 0, 100);
                    $translationResult = $this->translator->translateToIndonesian($sampleText, $languageInfo['primary_language']);
                    $translationSample = $translationResult;
                }

                return response()->json([
                    'success' => true,
                    'language_detection' => [
                        'detected_language' => $languageInfo['primary_language'],
                        'language_name' => $this->languageDetector->getLanguageName($languageInfo['primary_language']),
                        'confidence' => round($languageInfo['confidence'] * 100, 1) . '%',
                        'is_multilingual' => $languageInfo['is_multilingual'],
                        'all_languages' => $languageInfo['detected_languages'],
                        'needs_translation' => $languageInfo['primary_language'] !== 'id'
                    ],
                    'translation_sample' => $translationSample,
                    'site_info' => [
                        'title' => $analysis['meta']['title'],
                        'description' => $analysis['meta']['description'],
                        'framework' => $analysis['structure']['framework']
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $analysis['error']
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Language detection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate template name from URL
     */
    protected function generateTemplateName(string $url): string
    {
        $domain = parse_url($url, PHP_URL_HOST);
        $path = parse_url($url, PHP_URL_PATH);

        // Clean up domain name
        $name = str_replace(['www.', '.com', '.net', '.org', '.edu'], '', $domain ?? '');

        // Add path info if meaningful
        if ($path && $path !== '/') {
            $pathParts = array_filter(explode('/', $path));
            if (!empty($pathParts)) {
                $name .= ' - ' . ucfirst(end($pathParts));
            }
        }

        return ucwords(str_replace(['-', '_', '.'], ' ', $name)) . ' (Auto Import)';
    }

    /**
     * Get translation information from stats
     */
    protected function getTranslationInfo(array $stats): array
    {
        $localizationStats = $stats['localization'] ?? [];

        return [
            'was_translated' => $localizationStats['translation_needed'] ?? false,
            'source_language' => $localizationStats['source_language'] ?? 'unknown',
            'target_language' => $localizationStats['target_language'] ?? 'id',
            'translation_stats' => $localizationStats
        ];
    }

    /**
     * Get error suggestions based on error type
     */
    protected function getErrorSuggestions(array $result): array
    {
        $code = $result['code'] ?? 'UNKNOWN_ERROR';

        $suggestions = [
            'FETCH_ERROR' => [
                'Pastikan URL dapat diakses dan website aktif',
                'Coba dengan URL yang berbeda',
                'Periksa koneksi internet Anda'
            ],
            'ANALYSIS_ERROR' => [
                'Website mungkin menggunakan JavaScript yang kompleks',
                'Coba dengan template HTML statis',
                'Gunakan URL dari demo template'
            ],
            'CONVERSION_ERROR' => [
                'Format website tidak dapat dikonversi',
                'Coba dengan template yang lebih sederhana',
                'Gunakan template dari gallery sebagai alternatif'
            ],
            'CREATE_ERROR' => [
                'Terjadi masalah saat menyimpan template',
                'Coba import ulang',
                'Hubungi administrator jika masalah berlanjut'
            ]
        ];

        return $suggestions[$code] ?? [
            'Coba lagi dengan URL yang berbeda',
            'Pastikan website dapat diakses',
            'Gunakan template dari gallery jika import gagal'
        ];
    }
}
