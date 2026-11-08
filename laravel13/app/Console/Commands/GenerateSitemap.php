<?php

namespace App\Console\Commands;

use App\Http\Controllers\SitemapController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'app:generate-sitemap';
    protected $description = 'Generate public/sitemap.xml from published database records';

    public function handle(SitemapController $controller): int
    {
        $this->info('Generating static XML sitemap...');

        $response = $controller->index();
        $content = $response->getContent();

        $path = public_path('sitemap.xml');
        File::put($path, $content);

        $size = number_format(strlen($content) / 1024, 2);
        $this->info("Successfully generated {$path} ({$size} KB).");

        return Command::SUCCESS;
    }
}

