<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Layanan;
use Illuminate\Support\Facades\DB;

class SitemapService
{
    public function generate(): void
    {
        $baseUrl = rtrim(config('app.url') ?: 'https://surveycenter.co.id', '/');

        $this->writeArticleSitemap($baseUrl);
        $this->writePageSitemap($baseUrl);
        $this->writeCategorySitemap($baseUrl);
        $this->writeSitemapIndex($baseUrl);
    }

    private function writeArticleSitemap(string $baseUrl): void
    {
        $entries = Article::query()
            ->published()
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->get()
            ->map(function (Article $article) use ($baseUrl) {
                return [
                    'loc' => $baseUrl . '/' . ltrim($article->slug, '/'),
                    'lastmod' => $article->updated_at,
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            })
            ->all();

        $this->writeUrlSet(public_path('sitemap-artikel.xml'), $entries);
    }

    private function writePageSitemap(string $baseUrl): void
    {
        $staticPages = [
            ['path' => '/', 'priority' => '1.0'],
            ['path' => '/about', 'priority' => '0.8'],
            ['path' => '/pricing', 'priority' => '0.8'],
            ['path' => '/blog', 'priority' => '0.9'],
            ['path' => '/contact', 'priority' => '0.8'],
            ['path' => '/login', 'priority' => '0.5'],
            ['path' => '/register', 'priority' => '0.5'],
        ];

        $entries = [];
        $now = now();

        foreach ($staticPages as $page) {
            $entries[] = [
                'loc' => $baseUrl . $page['path'],
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => $page['priority'],
            ];
        }

        $layananEntries = Layanan::query()
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select(['slug', 'updated_at'])
            ->get();

        foreach ($layananEntries as $layanan) {
            $entries[] = [
                'loc' => $baseUrl . '/layanan/' . ltrim($layanan->slug, '/'),
                'lastmod' => $layanan->updated_at ?? $now,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        $this->writeUrlSet(public_path('sitemap-halaman.xml'), $entries);
    }

    private function writeCategorySitemap(string $baseUrl): void
    {
        $categories = Article::query()
            ->published()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select(['category', DB::raw('MAX(updated_at) as lastmod')])
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $entries = $categories->map(function ($item) use ($baseUrl) {
            return [
                'loc' => $baseUrl . '/blog/category/' . rawurlencode($item->category),
                'lastmod' => $item->lastmod,
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        })->all();

        $this->writeUrlSet(public_path('sitemap-kategori.xml'), $entries);
    }

    private function writeSitemapIndex(string $baseUrl): void
    {
        $writer = new \XMLWriter();
        $writer->openURI(public_path('sitemap.xml'));
        $writer->startDocument('1.0', 'UTF-8');
        $writer->setIndent(true);

        $writer->startElement('sitemapindex');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $sitemaps = [
            'sitemap-artikel.xml',
            'sitemap-halaman.xml',
            'sitemap-kategori.xml',
        ];

        $now = now()->toAtomString();

        foreach ($sitemaps as $file) {
            $writer->startElement('sitemap');
            $writer->writeElement('loc', $baseUrl . '/' . $file);
            $writer->writeElement('lastmod', $now);
            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();
        $writer->flush();
    }

    private function writeUrlSet(string $path, array $entries): void
    {
        $writer = new \XMLWriter();
        $writer->openURI($path);
        $writer->startDocument('1.0', 'UTF-8');
        $writer->setIndent(true);

        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($entries as $entry) {
            $writer->startElement('url');
            $writer->writeElement('loc', $entry['loc']);
            $writer->writeElement('lastmod', $this->formatLastmod($entry['lastmod'] ?? null));
            $writer->writeElement('changefreq', $entry['changefreq'] ?? 'monthly');
            $writer->writeElement('priority', $entry['priority'] ?? '0.7');
            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();
        $writer->flush();
    }

    private function formatLastmod($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        if (is_string($value) && $value !== '') {
            return date(DATE_ATOM, strtotime($value));
        }

        return now()->toAtomString();
    }
}
