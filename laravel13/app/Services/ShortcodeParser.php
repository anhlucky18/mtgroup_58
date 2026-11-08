<?php

namespace App\Services;

use App\Models\ContentBlock;
use Illuminate\Support\Facades\View;

class ShortcodeParser
{
    protected int $recursionDepth = 0;
    protected int $maxRecursionDepth = 3;

    public function parse(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        if ($this->recursionDepth >= $this->maxRecursionDepth) {
            return $content;
        }

        $this->recursionDepth++;

        // 1. [static-block alias="..."] or [static-block alias='...']
        $content = preg_replace_callback(
            '/\[static-block\s+alias=["\']([^"\']+)["\']\s*\]/i',
            function ($matches) {
                $alias = trim($matches[1]);
                $locale = app()->getLocale();
                if ($locale === 'en') {
                    $locale = 'en_US';
                }

                $block = ContentBlock::where('alias', $alias)
                    ->where('locale', $locale)
                    ->where('status', 'publish')
                    ->first();

                if (!$block && $locale !== 'vi') {
                    $block = ContentBlock::where('alias', $alias)
                        ->where('locale', 'vi')
                        ->where('status', 'publish')
                        ->first();
                }

                if (!$block) {
                    $block = ContentBlock::where('alias', $alias)
                        ->where('status', 'publish')
                        ->first();
                }

                if ($block && !empty($block->content)) {
                    $subParser = new self();
                    $subParser->recursionDepth = $this->recursionDepth;
                    return $subParser->parse($block->content);
                }

                return '';
            },
            $content
        );

        // 2. [services]...[/services] or [services]
        $content = preg_replace_callback('/\[services\](.*?)\[\/services\]|\[services\]/is', function () {
            return View::exists('partials.services-content') ? View::make('partials.services-content')->render() : '';
        }, $content);

        // 3. [products]...[/products] or [products]
        $content = preg_replace_callback('/\[products\](.*?)\[\/products\]|\[products\]/is', function () {
            return View::exists('partials.products-content') ? View::make('partials.products-content')->render() : '';
        }, $content);

        // 4. [projects]...[/projects] or [projects]
        $content = preg_replace_callback('/\[projects\](.*?)\[\/projects\]|\[projects\]/is', function () {
            return View::exists('partials.projects-content') ? View::make('partials.projects-content')->render() : '';
        }, $content);

        // 5. [contacts]...[/contacts] or [contacts]
        $content = preg_replace_callback('/\[contacts\](.*?)\[\/contacts\]|\[contacts\]/is', function () {
            return View::exists('partials.inquiry-content') ? View::make('partials.inquiry-content')->render() : '';
        }, $content);

        // 6. [google-map]Address[/google-map]
        $content = preg_replace_callback('/\[google-map\](.*?)\[\/google-map\]/is', function ($matches) {
            $address = urlencode(trim($matches[1]));
            return '<div class="tn-ggmap"><div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://maps.google.com/maps?q=' . $address . '&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen></iframe></div></div>';
        }, $content);

        // 7. [youtube-video]Url[/youtube-video]
        $content = preg_replace_callback('/\[youtube-video\](.*?)\[\/youtube-video\]/is', function ($matches) {
            $url = trim($matches[1]);
            // Convert youtube watch url to embed url
            if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $url, $m)) {
                $url = 'https://www.youtube.com/embed/' . $m[1];
            }
            return '<div class="embed-responsive embed-responsive-16by9 mb30"><iframe allowfullscreen="true" frameborder="0" height="315" src="' . e($url) . '" width="420"></iframe></div>';
        }, $content);

        $this->recursionDepth--;

        return $content;
    }
}
