<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FormLinkValidationService
{
    public const TITLE_MATCH_THRESHOLD = 100.0;

    /**
     * @var array<int, string>
     */
    private array $exactHosts = [
        'docs.google.com',
        'forms.gle',
        'forms.office.com',
        'typeform.com',
        'jotform.com',
        'tally.so',
        'formstack.com',
    ];

    /**
     * @var array<int, string>
     */
    private array $suffixHosts = [
        '.typeform.com',
        '.jotform.com',
        '.tally.so',
        '.formstack.com',
    ];

    public function validate(?string $url, ?string $expectedTitle): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (!$this->isAllowedFormUrl($url)) {
            return 'Domain link form tidak didukung. Gunakan link Google Form atau provider form yang didukung.';
        }

        if (empty($expectedTitle)) {
            return null;
        }

        $fetchedTitle = $this->fetchFormTitle($url);

        if ($fetchedTitle === null) {
            return 'Judul form tidak dapat diambil dari link. Pastikan link form publik dan bisa diakses.';
        }

        if (!$this->titlesMatch($expectedTitle, $fetchedTitle)) {
            return 'Judul form pada link tidak sama dengan judul yang diinput.';
        }

        return null;
    }

    public function isAllowedFormUrl(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = (string) parse_url($url, PHP_URL_PATH);

        if (str_starts_with($host, 'www.')) {
            $host = substr($host, 4);
        }

        if ($host === 'docs.google.com' && !str_starts_with($path, '/forms/')) {
            return false;
        }

        if (in_array($host, $this->exactHosts, true)) {
            return true;
        }

        foreach ($this->suffixHosts as $suffix) {
            if (str_ends_with($host, $suffix)) {
                return true;
            }
        }

        return false;
    }

    public function fetchFormTitle(string $url): ?string
    {
        $metadata = $this->fetchFormMetadata($url);

        return $metadata['title'];
    }

    /**
     * @return array{title: ?string, question_count: ?int, question_titles: array<int, string>, question_items: array<int, array{title: string, type: string, type_code: ?int}>, debug: array{entry_ids: array<int, string>, entry_ids_count: int, has_load_data: bool, question_titles_count: int, type_counts: array<string, int>}}
     */
    public function fetchFormMetadata(string $url): array
    {
        try {
            $response = Http::timeout(12)
                ->withHeaders(['Accept' => 'text/html'])
                ->withOptions([
                    'allow_redirects' => ['max' => 5],
                ])
                ->get($url);

            if ($response->failed()) {
                return [
                    'title' => null,
                    'question_count' => null,
                    'question_titles' => [],
                    'question_items' => [],
                    'debug' => [
                        'entry_ids' => [],
                        'entry_ids_count' => 0,
                        'has_load_data' => false,
                        'question_titles_count' => 0,
                        'type_counts' => [],
                    ],
                ];
            }

            $html = $response->body();
            $entryIds = $this->extractEntryIdsFromHtml($html);
            $hasLoadData = preg_match('/FB_PUBLIC_LOAD_DATA_\s*=\s*(\[.*?\]);/s', $html) === 1;

            $title = $this->extractTitle($html);

            try {
                $questionCount = $this->extractQuestionCount($html);
            } catch (\Throwable) {
                $questionCount = null;
            }

            try {
                $questionItems = $this->extractDetectedQuestionItems($html);
            } catch (\Throwable) {
                $questionItems = [];
            }

            $questionTitles = array_values(array_map(
                static fn (array $item): string => $item['title'],
                $questionItems
            ));

            if (empty($entryIds) && !empty($questionTitles)) {
                $questionCount = count($questionTitles);
            } elseif ($questionCount === null && !empty($questionTitles)) {
                $questionCount = count($questionTitles);
            }

            return [
                'title' => $title,
                'question_count' => $questionCount,
                'question_titles' => $questionTitles,
                'question_items' => $questionItems,
                'debug' => [
                    'entry_ids' => $entryIds,
                    'entry_ids_count' => count($entryIds),
                    'has_load_data' => $hasLoadData,
                    'question_titles_count' => count($questionTitles),
                    'type_counts' => $this->summarizeTypeCounts($questionItems),
                ],
            ];
        } catch (\Throwable) {
            return [
                'title' => null,
                'question_count' => null,
                'question_titles' => [],
                'question_items' => [],
                'debug' => [
                    'entry_ids' => [],
                    'entry_ids_count' => 0,
                    'has_load_data' => false,
                    'question_titles_count' => 0,
                    'type_counts' => [],
                ],
            ];
        }
    }

    public function titlesMatch(string $expectedTitle, string $actualTitle): bool
    {
        return $this->normalizeTitle($expectedTitle) === $this->normalizeTitle($actualTitle);
    }

    public function titleSimilarityPercent(string $expectedTitle, string $actualTitle): float
    {
        $expected = $this->normalizeTitle($expectedTitle);
        $actual = $this->normalizeTitle($actualTitle);

        if ($expected === '' || $actual === '') {
            return 0.0;
        }

        similar_text($expected, $actual, $percent);

        return round($percent, 2);
    }

    private function extractTitle(string $html): ?string
    {
        if (preg_match('/<meta[^>]+(?:property|name)=["\']og:title["\'][^>]*>/i', $html, $metaTagMatch) === 1) {
            $metaTag = $metaTagMatch[0];
            if (preg_match('/content=["\']([^"\']+)["\']/i', $metaTag, $contentMatch) === 1) {
                $title = trim(html_entity_decode($contentMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                if ($title !== '') {
                    return $this->cleanupProviderSuffix($title);
                }
            }
        }

        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $titleTagMatch) === 1) {
            $title = trim(html_entity_decode(strip_tags($titleTagMatch[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($title !== '') {
                return $this->cleanupProviderSuffix($title);
            }
        }

        return null;
    }

    private function cleanupProviderSuffix(string $title): string
    {
        return trim((string) preg_replace('/\s*[-|]\s*(google forms?|formulir google)$/i', '', $title));
    }

    private function normalizeTitle(string $title): string
    {
        $normalized = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = function_exists('mb_strtolower') ? mb_strtolower($normalized, 'UTF-8') : strtolower($normalized);
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $normalized);
        $normalized = preg_replace('/\s+/u', ' ', (string) $normalized);

        return trim((string) $normalized);
    }

    private function extractQuestionCount(string $html): ?int
    {
        $entryIds = $this->extractEntryIdsFromHtml($html);

        if (!empty($entryIds)) {
            return count($entryIds);
        }

        return $this->extractQuestionCountFromGoogleLoadData($html);
    }

    private function extractQuestionCountFromGoogleLoadData(string $html): ?int
    {
        if (preg_match('/FB_PUBLIC_LOAD_DATA_\s*=\s*(\[.*?\]);/s', $html, $match) !== 1) {
            return null;
        }

        try {
            $decoded = json_decode($match[1], true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return null;
        }

        if (!is_array($decoded)) {
            return null;
        }

        $questionIds = [];
        $stack = [$decoded];

        while (!empty($stack)) {
            $node = array_pop($stack);

            if (!is_array($node)) {
                continue;
            }

            if (
                array_key_exists(0, $node) &&
                array_key_exists(1, $node) &&
                array_key_exists(3, $node) &&
                array_key_exists(4, $node) &&
                is_numeric($node[0]) &&
                is_string($node[1]) &&
                trim($node[1]) !== '' &&
                is_numeric($node[3]) &&
                is_array($node[4])
            ) {
                $questionIds[(string) $node[0]] = true;
            }

            foreach ($node as $child) {
                if (is_array($child)) {
                    $stack[] = $child;
                }
            }
        }

        return count($questionIds) > 0 ? count($questionIds) : null;
    }

    /**
     * @return array<int, string>
     */
    private function extractDetectedQuestionTitles(string $html): array
    {
        $items = $this->extractDetectedQuestionItems($html);

        return array_values(array_map(static fn (array $item): string => $item['title'], $items));
    }

    /**
     * @return array<int, array{title: string, type: string, type_code: ?int}>
     */
    private function extractDetectedQuestionItems(string $html): array
    {
        $entryIds = $this->extractEntryIdsFromHtml($html);

        if (preg_match('/FB_PUBLIC_LOAD_DATA_\s*=\s*(\[.*?\]);/s', $html, $match) !== 1) {
            return [];
        }

        try {
            $decoded = json_decode($match[1], true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return [];
        }

        if (!is_array($decoded)) {
            return [];
        }

        $candidates = $this->extractQuestionCandidatesFromLoadData($decoded);

        if (empty($entryIds)) {
            return $this->filterQuestionCandidatesFallback($candidates);
        }

        $entryIdLookup = array_fill_keys($entryIds, true);
        $titlesById = [];
        $this->collectQuestionTitlesFromNode($decoded, $entryIdLookup, $titlesById);

        if (empty($titlesById)) {
            return $this->filterQuestionCandidatesFallback($candidates);
        }

        ksort($titlesById, SORT_NUMERIC);

        $items = [];
        foreach ($titlesById as $questionId => $title) {
            $typeCode = $candidates[$questionId]['type_code'] ?? null;
            $items[] = [
                'title' => $title,
                'type' => $this->mapGoogleQuestionType($typeCode),
                'type_code' => $typeCode,
            ];
        }

        return $items;
    }

    /**
     * @return array<string, array{title: string, type_code: ?int}>
     */
    private function extractQuestionCandidatesFromLoadData(array $decoded): array
    {
        $candidates = [];
        $this->collectQuestionCandidatesFromNode($decoded, $candidates);

        return $candidates;
    }

    /**
     * @param array<string, array{title: string, type_code: ?int}> $candidates
     */
    private function collectQuestionCandidatesFromNode(mixed $node, array &$candidates): void
    {
        if (!is_array($node)) {
            return;
        }

        if (
            array_key_exists(0, $node) &&
            array_key_exists(1, $node) &&
            is_numeric($node[0]) &&
            is_string($node[1])
        ) {
            $id = (string) $node[0];
            $text = trim((string) preg_replace('/\s+/u', ' ', $node[1]));
            $typeCode = isset($node[3]) && is_numeric($node[3]) ? (int) $node[3] : null;

            if ($text !== '' && !isset($candidates[$id])) {
                $candidates[$id] = [
                    'title' => $text,
                    'type_code' => $typeCode,
                ];
            } elseif ($text !== '' && isset($candidates[$id]) && $candidates[$id]['type_code'] === null && $typeCode !== null) {
                $candidates[$id]['type_code'] = $typeCode;
            }
        }

        foreach ($node as $child) {
            $this->collectQuestionCandidatesFromNode($child, $candidates);
        }
    }

    /**
     * @param array<string, array{title: string, type_code: ?int}> $candidates
     * @return array<int, array{title: string, type: string, type_code: ?int}>
     */
    private function filterQuestionCandidatesFallback(array $candidates): array
    {
        if (empty($candidates)) {
            return [];
        }

        ksort($candidates, SORT_NUMERIC);

        $items = [];
        foreach ($candidates as $candidate) {
            $title = trim((string) preg_replace('/\s+/u', ' ', $candidate['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $typeCode = $candidate['type_code'] ?? null;
            $items[] = [
                'title' => $title,
                'type' => $this->mapGoogleQuestionType($typeCode),
                'type_code' => $typeCode,
            ];
        }

        if (empty($items)) {
            return [];
        }

        $numbered = array_values(array_filter($items, static function (array $item): bool {
            return preg_match('/^\d+\./u', $item['title']) === 1;
        }));

        if (count($numbered) >= 3) {
            return $this->uniqueQuestionItemsByTitle($numbered);
        }

        $filtered = array_values(array_filter($items, static function (array $item): bool {
            $title = $item['title'];
            if (preg_match('/^bagian\b/i', $title) === 1) {
                return false;
            }

            return mb_strlen($title) >= 6;
        }));

        return $this->uniqueQuestionItemsByTitle($filtered);
    }

    /**
     * @param array<int, array{title: string, type: string, type_code: ?int}> $items
     * @return array<int, array{title: string, type: string, type_code: ?int}>
     */
    private function uniqueQuestionItemsByTitle(array $items): array
    {
        $seen = [];
        $result = [];
        foreach ($items as $item) {
            $key = trim($item['title']);
            if ($key === '' || isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $result[] = $item;
        }

        return $result;
    }

    private function mapGoogleQuestionType(?int $typeCode): string
    {
        return match ($typeCode) {
            0 => 'short_text',
            1 => 'paragraph',
            2 => 'multiple_choice',
            3 => 'dropdown',
            4 => 'checkbox',
            5 => 'linear_scale',
            6 => 'multiple_choice_grid',
            7 => 'checkbox_grid',
            9 => 'date',
            10 => 'time',
            11 => 'date_time',
            default => $typeCode === null ? 'unknown' : 'unknown_' . $typeCode,
        };
    }

    /**
     * @param array<int, array{title: string, type: string, type_code: ?int}> $questionItems
     * @return array<string, int>
     */
    private function summarizeTypeCounts(array $questionItems): array
    {
        $summary = [];
        foreach ($questionItems as $item) {
            $type = $item['type'];
            $summary[$type] = ($summary[$type] ?? 0) + 1;
        }

        ksort($summary);

        return $summary;
    }

    /**
     * @return array<int, string>
     */
    private function extractEntryIdsFromHtml(string $html): array
    {
        $inputIds = [];

        if (preg_match_all('/name=["\']entry\.([0-9]+)["\']/i', $html, $nameMatches) !== false && !empty($nameMatches[1])) {
            $inputIds = array_merge($inputIds, $nameMatches[1]);
        }

        $inputIds = array_values(array_unique($inputIds));
        $inputIds = array_values(array_filter($inputIds, static fn ($id) => $id !== ''));

        if (!empty($inputIds)) {
            return $inputIds;
        }

        $matchedIds = [];

        if (preg_match_all('/"entry\.([0-9]+)"/i', $html, $jsonMatches) !== false && !empty($jsonMatches[1])) {
            $matchedIds = array_merge($matchedIds, $jsonMatches[1]);
        }

        if (preg_match_all('/entry\\\\u002e([0-9]+)/i', $html, $unicodeMatches) !== false && !empty($unicodeMatches[1])) {
            $matchedIds = array_merge($matchedIds, $unicodeMatches[1]);
        }

        $uniqueIds = array_values(array_unique($matchedIds));

        return array_values(array_filter($uniqueIds, static fn ($id) => $id !== ''));
    }

    /**
     * @param array<string, bool> $entryIdLookup
     * @param array<string, string> $titlesById
     */
    private function collectQuestionTitlesFromNode(mixed $node, array $entryIdLookup, array &$titlesById): bool
    {
        if (is_scalar($node)) {
            $value = (string) $node;
            if (isset($entryIdLookup[$value])) {
                return true;
            }

            if (
                preg_match('/entry\.([0-9]+)/i', $value, $entryMatch) === 1 &&
                isset($entryIdLookup[$entryMatch[1]])
            ) {
                return true;
            }

            if (
                preg_match('/entry\\\\u002e([0-9]+)/i', $value, $unicodeMatch) === 1 &&
                isset($entryIdLookup[$unicodeMatch[1]])
            ) {
                return true;
            }

            return false;
        }

        if (!is_array($node)) {
            return false;
        }

        $containsEntryId = false;
        foreach ($node as $child) {
            if ($this->collectQuestionTitlesFromNode($child, $entryIdLookup, $titlesById)) {
                $containsEntryId = true;
            }
        }

        $looksLikeQuestionNode =
            array_key_exists(0, $node) &&
            array_key_exists(1, $node) &&
            is_numeric($node[0]) &&
            is_string($node[1]) &&
            trim($node[1]) !== '';

        if ($looksLikeQuestionNode && $containsEntryId) {
            $questionId = (string) $node[0];
            if (!isset($titlesById[$questionId])) {
                $titlesById[$questionId] = trim($node[1]);
            }
        }

        if (array_key_exists(0, $node) && is_numeric($node[0]) && isset($entryIdLookup[(string) $node[0]])) {
            return true;
        }

        return $containsEntryId;
    }
}
