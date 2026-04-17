<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FormLinkValidationService
{
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
        try {
            $response = Http::timeout(12)
                ->withHeaders(['Accept' => 'text/html'])
                ->withOptions([
                    'allow_redirects' => ['max' => 5],
                ])
                ->get($url);

            if ($response->failed()) {
                return null;
            }

            $html = $response->body();

            return $this->extractTitle($html);
        } catch (\Throwable) {
            return null;
        }
    }

    public function titlesMatch(string $expectedTitle, string $actualTitle): bool
    {
        return $this->normalizeTitle($expectedTitle) === $this->normalizeTitle($actualTitle);
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
}
