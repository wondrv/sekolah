<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LanguageDetectionService
{
    protected array $languageIndicators = [
        'id' => ['sekolah', 'pendidikan', 'universitas', 'siswa', 'mahasiswa', 'guru', 'dosen', 'pembelajaran', 'kurikulum', 'kegiatan', 'fasilitas', 'prestasi', 'lulusan', 'alumni', 'berita', 'tentang', 'kontak', 'masuk', 'daftar', 'beranda'],
        'en' => ['school', 'education', 'university', 'college', 'student', 'teacher', 'learning', 'curriculum', 'activities', 'facilities', 'achievements', 'graduates', 'alumni', 'news', 'about', 'contact', 'login', 'register', 'home'],
        'es' => ['escuela', 'educación', 'universidad', 'estudiante', 'profesor', 'aprendizaje', 'currículo', 'actividades', 'instalaciones', 'logros', 'graduados', 'noticias', 'acerca', 'contacto', 'inicio'],
        'fr' => ['école', 'éducation', 'université', 'étudiant', 'professeur', 'apprentissage', 'curriculum', 'activités', 'installations', 'réalisations', 'diplômés', 'nouvelles', 'à propos', 'contact', 'accueil'],
        'de' => ['schule', 'bildung', 'universität', 'student', 'lehrer', 'lernen', 'lehrplan', 'aktivitäten', 'einrichtungen', 'leistungen', 'absolventen', 'nachrichten', 'über', 'kontakt', 'startseite'],
        'pt' => ['escola', 'educação', 'universidade', 'estudante', 'professor', 'aprendizagem', 'currículo', 'atividades', 'instalações', 'conquistas', 'graduados', 'notícias', 'sobre', 'contato', 'início'],
        'it' => ['scuola', 'educazione', 'università', 'studente', 'insegnante', 'apprendimento', 'curriculum', 'attività', 'strutture', 'risultati', 'laureati', 'notizie', 'informazioni', 'contatto', 'home'],
        'ru' => ['школа', 'образование', 'университет', 'студент', 'учитель', 'обучение', 'учебная программа', 'деятельность', 'объекты', 'достижения', 'выпускники', 'новости', 'о нас', 'контакт', 'главная'],
        'zh' => ['学校', '教育', '大学', '学生', '老师', '学习', '课程', '活动', '设施', '成就', '毕业生', '新闻', '关于', '联系', '首页'],
        'ja' => ['学校', '教育', '大学', '学生', '先生', '学習', 'カリキュラム', '活動', '施設', '成果', '卒業生', 'ニュース', 'について', '連絡先', 'ホーム'],
        'ko' => ['학교', '교육', '대학교', '학생', '선생님', '학습', '교육과정', '활동', '시설', '성과', '졸업생', '뉴스', '소개', '연락처', '홈'],
        'ar' => ['مدرسة', 'تعليم', 'جامعة', 'طالب', 'معلم', 'تعلم', 'منهج', 'أنشطة', 'مرافق', 'إنجازات', 'خريجون', 'أخبار', 'حول', 'اتصال', 'الرئيسية'],
        'hi' => ['स्कूल', 'शिक्षा', 'विश्वविद्यालय', 'छात्र', 'शिक्षक', 'सीखना', 'पाठ्यक्रम', 'गतिविधियां', 'सुविधाएं', 'उपलब्धियां', 'स्नातक', 'समाचार', 'के बारे में', 'संपर्क', 'मुख्य'],
        'th' => ['โรงเรียน', 'การศึกษา', 'มหาวิทยาลัย', 'นักเรียน', 'ครู', 'การเรียนรู้', 'หลักสูตร', 'กิจกรรม', 'สิ่งอำนวยความสะดวก', 'ความสำเร็จ', 'ผู้สำเร็จการศึกษา', 'ข่าว', 'เกี่ยวกับ', 'ติดต่อ', 'หน้าแรก'],
        'vi' => ['trường học', 'giáo dục', 'đại học', 'học sinh', 'giáo viên', 'học tập', 'chương trình giảng dạy', 'hoạt động', 'cơ sở vật chất', 'thành tích', 'sinh viên tốt nghiệp', 'tin tức', 'về chúng tôi', 'liên hệ', 'trang chủ'],
        'ms' => ['sekolah', 'pendidikan', 'universiti', 'pelajar', 'guru', 'pembelajaran', 'kurikulum', 'aktiviti', 'kemudahan', 'pencapaian', 'graduan', 'berita', 'mengenai', 'hubungi', 'laman utama']
    ];

    protected array $commonWords = [
        'id' => ['dan', 'atau', 'dengan', 'untuk', 'dari', 'ke', 'pada', 'di', 'yang', 'adalah', 'akan', 'telah', 'dapat', 'lebih', 'juga', 'saya', 'kami', 'mereka', 'ini', 'itu'],
        'en' => ['the', 'and', 'or', 'with', 'for', 'from', 'to', 'on', 'in', 'that', 'is', 'will', 'have', 'can', 'more', 'also', 'we', 'they', 'this', 'that'],
        'es' => ['el', 'la', 'y', 'o', 'con', 'para', 'de', 'a', 'en', 'que', 'es', 'será', 'tienen', 'puede', 'más', 'también', 'nosotros', 'ellos', 'esto', 'eso'],
        'fr' => ['le', 'la', 'et', 'ou', 'avec', 'pour', 'de', 'à', 'sur', 'que', 'est', 'sera', 'ont', 'peut', 'plus', 'aussi', 'nous', 'ils', 'ce', 'ça'],
        'de' => ['der', 'die', 'und', 'oder', 'mit', 'für', 'von', 'zu', 'auf', 'dass', 'ist', 'wird', 'haben', 'kann', 'mehr', 'auch', 'wir', 'sie', 'dies', 'das'],
        'pt' => ['o', 'a', 'e', 'ou', 'com', 'para', 'de', 'para', 'em', 'que', 'é', 'será', 'têm', 'pode', 'mais', 'também', 'nós', 'eles', 'isso', 'aquilo'],
        'it' => ['il', 'la', 'e', 'o', 'con', 'per', 'di', 'a', 'su', 'che', 'è', 'sarà', 'hanno', 'può', 'più', 'anche', 'noi', 'loro', 'questo', 'quello']
    ];

    /**
     * Detect language from text content
     */
    public function detectLanguage(string $text, bool $useOnlineService = false): array
    {
        $cacheKey = 'lang_detect_' . md5($text);

        return Cache::remember($cacheKey, 3600, function () use ($text, $useOnlineService) {
            $result = [
                'primary_language' => 'en',
                'confidence' => 0.0,
                'detected_languages' => [],
                'is_multilingual' => false,
                'detection_method' => 'local'
            ];

            // Normalize text for analysis
            $normalizedText = $this->normalizeText($text);

            if (empty($normalizedText)) {
                return $result;
            }

            // Local detection first
            $localResult = $this->detectLanguageLocal($normalizedText);

            // If confidence is low and online service is available, try online detection
            if ($localResult['confidence'] < 0.6 && $useOnlineService) {
                $onlineResult = $this->detectLanguageOnline($text);
                if ($onlineResult && $onlineResult['confidence'] > $localResult['confidence']) {
                    $localResult = array_merge($localResult, $onlineResult);
                    $localResult['detection_method'] = 'online';
                }
            }

            return $localResult;
        });
    }

    /**
     * Local language detection using keyword matching
     */
    protected function detectLanguageLocal(string $text): array
    {
        $words = $this->extractWords($text);
        $languageScores = [];
        $totalWords = count($words);

        if ($totalWords === 0) {
            return [
                'primary_language' => 'en',
                'confidence' => 0.0,
                'detected_languages' => [],
                'is_multilingual' => false
            ];
        }

        // Score each language based on keyword matches
        foreach ($this->languageIndicators as $lang => $indicators) {
            $score = 0;
            $matchedWords = 0;

            foreach ($words as $word) {
                // Education-specific keywords (higher weight)
                if (in_array($word, $indicators)) {
                    $score += 10;
                    $matchedWords++;
                }

                // Common words (lower weight)
                if (isset($this->commonWords[$lang]) && in_array($word, $this->commonWords[$lang])) {
                    $score += 2;
                    $matchedWords++;
                }
            }

            if ($matchedWords > 0) {
                $languageScores[$lang] = [
                    'score' => $score,
                    'matches' => $matchedWords,
                    'ratio' => $matchedWords / $totalWords
                ];
            }
        }

        // Special handling for character-based languages
        $languageScores = array_merge($languageScores, $this->detectCharacterBasedLanguages($text));

        // Sort by score
        uasort($languageScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $detectedLanguages = [];
        $totalScore = array_sum(array_column($languageScores, 'score'));

        foreach ($languageScores as $lang => $data) {
            if ($data['score'] > 0) {
                $detectedLanguages[$lang] = [
                    'confidence' => $totalScore > 0 ? $data['score'] / $totalScore : 0,
                    'matches' => $data['matches'],
                    'ratio' => $data['ratio']
                ];
            }
        }

        $primaryLanguage = key($detectedLanguages) ?: 'en';
        $confidence = $detectedLanguages[$primaryLanguage]['confidence'] ?? 0.0;

        return [
            'primary_language' => $primaryLanguage,
            'confidence' => $confidence,
            'detected_languages' => $detectedLanguages,
            'is_multilingual' => count($detectedLanguages) > 1
        ];
    }

    /**
     * Detect character-based languages (Chinese, Japanese, Korean, Arabic, etc.)
     */
    protected function detectCharacterBasedLanguages(string $text): array
    {
        $scores = [];

        // Chinese characters
        if (preg_match('/[\x{4e00}-\x{9fff}]+/u', $text, $matches)) {
            $chineseChars = mb_strlen(implode('', $matches));
            $scores['zh'] = [
                'score' => $chineseChars * 5,
                'matches' => $chineseChars,
                'ratio' => $chineseChars / mb_strlen($text)
            ];
        }

        // Japanese characters (Hiragana, Katakana)
        if (preg_match('/[\x{3040}-\x{309f}\x{30a0}-\x{30ff}]+/u', $text, $matches)) {
            $japaneseChars = mb_strlen(implode('', $matches));
            $scores['ja'] = [
                'score' => $japaneseChars * 5,
                'matches' => $japaneseChars,
                'ratio' => $japaneseChars / mb_strlen($text)
            ];
        }

        // Korean characters
        if (preg_match('/[\x{ac00}-\x{d7af}]+/u', $text, $matches)) {
            $koreanChars = mb_strlen(implode('', $matches));
            $scores['ko'] = [
                'score' => $koreanChars * 5,
                'matches' => $koreanChars,
                'ratio' => $koreanChars / mb_strlen($text)
            ];
        }

        // Arabic characters
        if (preg_match('/[\x{0600}-\x{06ff}]+/u', $text, $matches)) {
            $arabicChars = mb_strlen(implode('', $matches));
            $scores['ar'] = [
                'score' => $arabicChars * 5,
                'matches' => $arabicChars,
                'ratio' => $arabicChars / mb_strlen($text)
            ];
        }

        // Thai characters
        if (preg_match('/[\x{0e00}-\x{0e7f}]+/u', $text, $matches)) {
            $thaiChars = mb_strlen(implode('', $matches));
            $scores['th'] = [
                'score' => $thaiChars * 5,
                'matches' => $thaiChars,
                'ratio' => $thaiChars / mb_strlen($text)
            ];
        }

        return $scores;
    }

    /**
     * Online language detection using external API
     */
    protected function detectLanguageOnline(string $text): ?array
    {
        try {
            // Using a free language detection API
            $response = Http::timeout(5)->post('https://ws.detectlanguage.com/0.2/detect', [
                'q' => substr($text, 0, 1000), // Limit text length
                'key' => config('services.detectlanguage.key') // Optional API key
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']['detections'][0])) {
                    $detection = $data['data']['detections'][0];
                    return [
                        'primary_language' => $detection['language'],
                        'confidence' => $detection['confidence'],
                        'detected_languages' => [$detection['language'] => ['confidence' => $detection['confidence']]],
                        'is_multilingual' => false
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Online language detection failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Normalize text for analysis
     */
    protected function normalizeText(string $text): string
    {
        // Remove HTML tags
        $text = strip_tags($text);

        // Convert to lowercase
        $text = mb_strtolower($text);

        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);

        // Remove special characters but keep letters and spaces
        $text = preg_replace('/[^\p{L}\s]/u', ' ', $text);

        return trim($text);
    }

    /**
     * Extract words from text
     */
    protected function extractWords(string $text): array
    {
        $words = preg_split('/\s+/', $text);
        return array_filter(array_map('trim', $words), function ($word) {
            return strlen($word) > 1; // Filter out single characters
        });
    }

    /**
     * Get language name from code
     */
    public function getLanguageName(string $code): string
    {
        $languageNames = [
            'id' => 'Bahasa Indonesia',
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'pt' => 'Português',
            'it' => 'Italiano',
            'ru' => 'Русский',
            'zh' => '中文',
            'ja' => '日本語',
            'ko' => '한국어',
            'ar' => 'العربية',
            'hi' => 'हिन्दी',
            'th' => 'ไทย',
            'vi' => 'Tiếng Việt',
            'ms' => 'Bahasa Melayu'
        ];

        return $languageNames[$code] ?? ucfirst($code);
    }

    /**
     * Check if translation is needed (not Indonesian)
     */
    public function needsTranslation(string $languageCode): bool
    {
        return $languageCode !== 'id';
    }

    /**
     * Detect template structure and extract translatable content
     */
    public function extractTranslatableContent(array $templateData): array
    {
        $translatableContent = [];

        if (isset($templateData['templates'])) {
            foreach ($templateData['templates'] as $index => $template) {
                // Template metadata
                $translatableContent["templates.{$index}.name"] = $template['name'] ?? '';
                $translatableContent["templates.{$index}.description"] = $template['description'] ?? '';

                // Sections and blocks
                if (isset($template['sections'])) {
                    foreach ($template['sections'] as $sIndex => $section) {
                        $translatableContent["templates.{$index}.sections.{$sIndex}.name"] = $section['name'] ?? '';

                        if (isset($section['blocks'])) {
                            foreach ($section['blocks'] as $bIndex => $block) {
                                $translatableContent["templates.{$index}.sections.{$sIndex}.blocks.{$bIndex}.name"] = $block['name'] ?? '';

                                // Extract content based on block type
                                $content = $block['content'] ?? $block['data'] ?? [];
                                $translatableContent = array_merge(
                                    $translatableContent,
                                    $this->extractBlockContent($content, "templates.{$index}.sections.{$sIndex}.blocks.{$bIndex}.content")
                                );
                            }
                        }
                    }
                }
            }
        }

        return array_filter($translatableContent, function ($value) {
            return !empty(trim(strip_tags($value)));
        });
    }

    /**
     * Extract translatable content from block data
     */
    protected function extractBlockContent($content, string $prefix): array
    {
        $translatable = [];

        if (is_array($content)) {
            foreach ($content as $key => $value) {
                if (is_string($value) && !empty(trim(strip_tags($value)))) {
                    $translatable["{$prefix}.{$key}"] = $value;
                } elseif (is_array($value)) {
                    $translatable = array_merge(
                        $translatable,
                        $this->extractBlockContent($value, "{$prefix}.{$key}")
                    );
                }
            }
        } elseif (is_string($content)) {
            $translatable[$prefix] = $content;
        }

        return $translatable;
    }

    /**
     * Apply translations back to template data
     */
    public function applyTranslations(array $templateData, array $translations): array
    {
        foreach ($translations as $path => $translatedText) {
            $this->setNestedValue($templateData, $path, $translatedText);
        }

        return $templateData;
    }

    /**
     * Set nested array value using dot notation
     */
    protected function setNestedValue(array &$array, string $path, $value): void
    {
        $keys = explode('.', $path);
        $current = &$array;

        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }

        $current = $value;
    }
}
