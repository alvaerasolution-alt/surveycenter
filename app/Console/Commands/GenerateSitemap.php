<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap index and split sitemap files';

    public function handle(SitemapService $sitemapService): int
    {
        $sitemapService->generate();

        $this->info('Sitemaps generated successfully.');

        return self::SUCCESS;
    }
}
