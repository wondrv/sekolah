<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AutoTranslationService
{
    protected array $translationServices = [
        'google' => [
            'url' => 'https://translate.googleapis.com/translate_a/single',
            'free' => true,
            'rate_limit' => 100 // requests per hour
        ],
        'libretranslate' => [
            'url' => 'https://libretranslate.de/translate',
            'free' => true,
            'rate_limit' => 60
        ]
    ];

    protected array $educationDictionary = [
        'en' => [
            'school' => 'sekolah',
            'education' => 'pendidikan',
            'university' => 'universitas',
            'college' => 'perguruan tinggi',
            'student' => 'siswa',
            'teacher' => 'guru',
            'learning' => 'pembelajaran',
            'curriculum' => 'kurikulum',
            'course' => 'kursus',
            'class' => 'kelas',
            'lesson' => 'pelajaran',
            'homework' => 'pekerjaan rumah',
            'exam' => 'ujian',
            'grade' => 'nilai',
            'diploma' => 'diploma',
            'certificate' => 'sertifikat',
            'graduation' => 'kelulusan',
            'alumni' => 'alumni',
            'faculty' => 'fakultas',
            'department' => 'jurusan',
            'library' => 'perpustakaan',
            'laboratory' => 'laboratorium',
            'classroom' => 'ruang kelas',
            'campus' => 'kampus',
            'dormitory' => 'asrama',
            'scholarship' => 'beasiswa',
            'tuition' => 'biaya kuliah',
            'semester' => 'semester',
            'academic' => 'akademik',
            'research' => 'penelitian',
            'thesis' => 'skripsi',
            'degree' => 'gelar',
            'bachelor' => 'sarjana',
            'master' => 'magister',
            'doctor' => 'doktor',
            'professor' => 'profesor',
            'principal' => 'kepala sekolah',
            'dean' => 'dekan',
            'rector' => 'rektor',
            'about us' => 'tentang kami',
            'contact us' => 'hubungi kami',
            'home' => 'beranda',
            'news' => 'berita',
            'events' => 'acara',
            'gallery' => 'galeri',
            'facilities' => 'fasilitas',
            'programs' => 'program',
            'admissions' => 'penerimaan',
            'activities' => 'kegiatan',
            'achievements' => 'prestasi',
            'vision' => 'visi',
            'mission' => 'misi',
            'goals' => 'tujuan',
            'history' => 'sejarah',
            'leadership' => 'kepemimpinan',
            'staff' => 'staf',
            'enrollment' => 'pendaftaran',
            'registration' => 'registrasi',
            'application' => 'aplikasi',
            'requirements' => 'persyaratan',
            'fees' => 'biaya',
            'schedule' => 'jadwal',
            'timetable' => 'jadwal waktu',
            'calendar' => 'kalender',
            'announcements' => 'pengumuman',
            'notice' => 'pemberitahuan',
            'download' => 'unduh',
            'documents' => 'dokumen',
            'forms' => 'formulir'
        ]
    ];

    /**
     * Translate text from source language to Indonesian
     */
    public function translateToIndonesian(string $text, string $sourceLanguage = 'auto'): array
    {
        if (empty(trim($text))) {
            return [
                'original' => $text,
                'translated' => $text,
                'source_language' => $sourceLanguage,
                'target_language' => 'id',
                'success' => true,
                'method' => 'no_translation_needed'
            ];
        }

        // If already Indonesian, return as is
        if ($sourceLanguage === 'id') {
            return [
                'original' => $text,
                'translated' => $text,
                'source_language' => 'id',
                'target_language' => 'id',
                'success' => true,
                'method' => 'no_translation_needed'
            ];
        }

        $cacheKey = 'translation_' . md5($text . '_' . $sourceLanguage . '_id');

        return Cache::remember($cacheKey, 86400, function () use ($text, $sourceLanguage) {
            // Try dictionary translation first for common education terms
            $dictionaryResult = $this->translateWithDictionary($text, $sourceLanguage);
            if ($dictionaryResult['success'] && $dictionaryResult['confidence'] > 0.8) {
                return $dictionaryResult;
            }

            // Try online translation services
            $onlineResult = $this->translateOnline($text, $sourceLanguage, 'id');
            if ($onlineResult['success']) {
                return $onlineResult;
            }

            // Fallback to dictionary even with low confidence
            if ($dictionaryResult['success']) {
                return $dictionaryResult;
            }

            // Last resort: return original with manual translation flag
            return [
                'original' => $text,
                'translated' => $text,
                'source_language' => $sourceLanguage,
                'target_language' => 'id',
                'success' => false,
                'method' => 'manual_required',
                'error' => 'Translation services unavailable'
            ];
        });
    }

    /**
     * Translate using education dictionary
     */
    protected function translateWithDictionary(string $text, string $sourceLanguage): array
    {
        if (!isset($this->educationDictionary[$sourceLanguage])) {
            return [
                'original' => $text,
                'translated' => $text,
                'source_language' => $sourceLanguage,
                'target_language' => 'id',
                'success' => false,
                'method' => 'dictionary',
                'confidence' => 0.0,
                'error' => 'Language not supported in dictionary'
            ];
        }

        $dictionary = $this->educationDictionary[$sourceLanguage];
        $originalText = $text;
        $translatedText = $text;
        $translatedWords = 0;
        $totalWords = 0;

        // Clean text for processing
        $cleanText = strtolower(strip_tags($text));

        // Split into sentences to preserve structure
        $sentences = preg_split('/[.!?]+/', $cleanText);
        $translatedSentences = [];

        foreach ($sentences as $sentence) {
            if (empty(trim($sentence))) continue;

            $words = preg_split('/\s+/', trim($sentence));
            $translatedWords_sentence = [];

            foreach ($words as $word) {
                $cleanWord = preg_replace('/[^\p{L}]/u', '', $word);
                $totalWords++;

                if (isset($dictionary[$cleanWord])) {
                    $translatedWords_sentence[] = $dictionary[$cleanWord];
                    $translatedWords++;
                } else {
                    // Check for partial matches or phrases
                    $found = false;
                    foreach ($dictionary as $englishPhrase => $indonesianPhrase) {
                        if (str_contains($sentence, $englishPhrase)) {
                            $sentence = str_replace($englishPhrase, $indonesianPhrase, $sentence);
                            $translatedWords += str_word_count($englishPhrase);
                            $found = true;
                        }
                    }

                    if (!$found) {
                        $translatedWords_sentence[] = $word;
                    }
                }
            }

            if (!empty($translatedWords_sentence)) {
                $translatedSentences[] = implode(' ', $translatedWords_sentence);
            }
        }

        if (!empty($translatedSentences)) {
            $translatedText = implode('. ', $translatedSentences);
            $translatedText = ucfirst(trim($translatedText));
        }

        $confidence = $totalWords > 0 ? $translatedWords / $totalWords : 0;

        return [
            'original' => $originalText,
            'translated' => $translatedText,
            'source_language' => $sourceLanguage,
            'target_language' => 'id',
            'success' => $confidence > 0,
            'method' => 'dictionary',
            'confidence' => $confidence,
            'stats' => [
                'total_words' => $totalWords,
                'translated_words' => $translatedWords
            ]
        ];
    }

    /**
     * Translate using online services
     */
    protected function translateOnline(string $text, string $sourceLanguage, string $targetLanguage): array
    {
        // Try Google Translate (free tier)
        $googleResult = $this->translateWithGoogle($text, $sourceLanguage, $targetLanguage);
        if ($googleResult['success']) {
            return $googleResult;
        }

        // Try LibreTranslate
        $libreResult = $this->translateWithLibreTranslate($text, $sourceLanguage, $targetLanguage);
        if ($libreResult['success']) {
            return $libreResult;
        }

        return [
            'original' => $text,
            'translated' => $text,
            'source_language' => $sourceLanguage,
            'target_language' => $targetLanguage,
            'success' => false,
            'method' => 'online',
            'error' => 'All online services failed'
        ];
    }

    /**
     * Translate with Google Translate (free tier)
     */
    protected function translateWithGoogle(string $text, string $sourceLanguage, string $targetLanguage): array
    {
        try {
            $response = Http::timeout(10)->get($this->translationServices['google']['url'], [
                'client' => 'gtx',
                'sl' => $sourceLanguage === 'auto' ? 'auto' : $sourceLanguage,
                'tl' => $targetLanguage,
                'dt' => 't',
                'q' => $text
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data[0][0][0])) {
                    $translatedText = '';
                    foreach ($data[0] as $translation) {
                        if (isset($translation[0])) {
                            $translatedText .= $translation[0];
                        }
                    }

                    $detectedLanguage = $data[2] ?? $sourceLanguage;

                    return [
                        'original' => $text,
                        'translated' => $translatedText,
                        'source_language' => $detectedLanguage,
                        'target_language' => $targetLanguage,
                        'success' => true,
                        'method' => 'google',
                        'confidence' => 0.9
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Google Translate failed: ' . $e->getMessage());
        }

        return [
            'original' => $text,
            'translated' => $text,
            'source_language' => $sourceLanguage,
            'target_language' => $targetLanguage,
            'success' => false,
            'method' => 'google',
            'error' => 'Google Translate API failed'
        ];
    }

    /**
     * Translate with LibreTranslate
     */
    protected function translateWithLibreTranslate(string $text, string $sourceLanguage, string $targetLanguage): array
    {
        try {
            $response = Http::timeout(10)->post($this->translationServices['libretranslate']['url'], [
                'q' => $text,
                'source' => $sourceLanguage === 'auto' ? 'auto' : $sourceLanguage,
                'target' => $targetLanguage,
                'format' => 'text'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['translatedText'])) {
                    return [
                        'original' => $text,
                        'translated' => $data['translatedText'],
                        'source_language' => $data['detectedLanguage']['language'] ?? $sourceLanguage,
                        'target_language' => $targetLanguage,
                        'success' => true,
                        'method' => 'libretranslate',
                        'confidence' => $data['detectedLanguage']['confidence'] ?? 0.8
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('LibreTranslate failed: ' . $e->getMessage());
        }

        return [
            'original' => $text,
            'translated' => $text,
            'source_language' => $sourceLanguage,
            'target_language' => $targetLanguage,
            'success' => false,
            'method' => 'libretranslate',
            'error' => 'LibreTranslate API failed'
        ];
    }

    /**
     * Batch translate multiple texts
     */
    public function batchTranslate(array $texts, string $sourceLanguage = 'auto'): array
    {
        $results = [];
        $batchSize = 10; // Process in batches to avoid rate limits

        $chunks = array_chunk($texts, $batchSize, true);

        foreach ($chunks as $chunk) {
            foreach ($chunk as $key => $text) {
                $results[$key] = $this->translateToIndonesian($text, $sourceLanguage);

                // Small delay to respect rate limits
                usleep(100000); // 0.1 seconds
            }
        }

        return $results;
    }

    /**
     * Translate template content with education context awareness
     */
    public function translateTemplateContent(array $translatableContent, string $sourceLanguage = 'auto'): array
    {
        $translatedContent = [];
        $stats = [
            'total' => count($translatableContent),
            'successful' => 0,
            'failed' => 0,
            'methods' => []
        ];

        foreach ($translatableContent as $path => $text) {
            $result = $this->translateToIndonesian($text, $sourceLanguage);

            $translatedContent[$path] = $result['translated'];

            if ($result['success']) {
                $stats['successful']++;
            } else {
                $stats['failed']++;
            }

            $method = $result['method'];
            $stats['methods'][$method] = ($stats['methods'][$method] ?? 0) + 1;

            // Add metadata for debugging
            $translatedContent[$path . '_meta'] = [
                'original' => $result['original'],
                'method' => $result['method'],
                'confidence' => $result['confidence'] ?? null,
                'source_language' => $result['source_language']
            ];
        }

        return [
            'translated_content' => $translatedContent,
            'stats' => $stats
        ];
    }

    /**
     * Post-process translated text for better Indonesian
     */
    protected function postProcessIndonesian(string $text): string
    {
        // Common corrections for machine-translated Indonesian
        $corrections = [
            'universitas' => 'universitas',
            'sekolah tinggi' => 'sekolah tinggi',
            'siswa siswa' => 'siswa-siswa',
            'mahasiswa mahasiswa' => 'mahasiswa-mahasiswa',
            'guru guru' => 'guru-guru',
            'kami adalah' => 'kami merupakan',
            'yang sangat' => 'yang sangat',
            'untuk anda' => 'untuk Anda',
            'kepada anda' => 'kepada Anda'
        ];

        foreach ($corrections as $wrong => $correct) {
            $text = str_ireplace($wrong, $correct, $text);
        }

        // Capitalize first letter
        $text = ucfirst(trim($text));

        return $text;
    }

    /**
     * Get translation statistics
     */
    public function getTranslationStats(): array
    {
        $cacheKey = 'translation_stats';

        return Cache::get($cacheKey, [
            'total_translations' => 0,
            'successful_translations' => 0,
            'failed_translations' => 0,
            'methods_used' => [],
            'languages_translated' => []
        ]);
    }

    /**
     * Update translation statistics
     */
    protected function updateStats(array $result): void
    {
        $stats = $this->getTranslationStats();

        $stats['total_translations']++;

        if ($result['success']) {
            $stats['successful_translations']++;
        } else {
            $stats['failed_translations']++;
        }

        $method = $result['method'];
        $stats['methods_used'][$method] = ($stats['methods_used'][$method] ?? 0) + 1;

        $language = $result['source_language'];
        $stats['languages_translated'][$language] = ($stats['languages_translated'][$language] ?? 0) + 1;

        Cache::put('translation_stats', $stats, 86400);
    }
}
