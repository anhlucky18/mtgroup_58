<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Render dynamic sitemap.xml
     */
    public function index(): Response
    {
        $baseUrl = rtrim(config('app.url', 'https://minhthanhgroup.com'), '/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . PHP_EOL;
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

        // 1. Homepage & Static index routes
        $xml .= "    <url>\n        <loc>{$baseUrl}</loc>\n        <changefreq>daily</changefreq>\n        <priority>1.0</priority>\n    </url>\n";
        $xml .= "    <url>\n        <loc>{$baseUrl}/en</loc>\n        <changefreq>daily</changefreq>\n        <priority>0.8</priority>\n    </url>\n";

        // 2. Published Pages
        $pages = Page::where('status', 'publish')->orderBy('updated_at', 'desc')->get();
        foreach ($pages as $page) {
            $lastmod = $page->updated_at ? $page->updated_at->toAtomString() : now()->toAtomString();
            $loc = "{$baseUrl}/{$page->slug}";
            $xml .= "    <url>\n        <loc>{$loc}</loc>\n        <lastmod>{$lastmod}</lastmod>\n        <changefreq>weekly</changefreq>\n        <priority>0.8</priority>\n    </url>\n";
        }

        // 3. Published Categories
        $categories = Category::where('status', 'publish')->orderBy('updated_at', 'desc')->get();
        foreach ($categories as $category) {
            $lastmod = $category->updated_at ? $category->updated_at->toAtomString() : now()->toAtomString();
            $loc = "{$baseUrl}/{$category->slug}";
            $xml .= "    <url>\n        <loc>{$loc}</loc>\n        <lastmod>{$lastmod}</lastmod>\n        <changefreq>weekly</changefreq>\n        <priority>0.8</priority>\n    </url>\n";
        }

        // 4. Published Posts
        $posts = Post::where('status', 'publish')->orderBy('updated_at', 'desc')->get();
        foreach ($posts as $post) {
            $lastmod = $post->updated_at ? $post->updated_at->toAtomString() : ($post->published_at ? $post->published_at->toAtomString() : now()->toAtomString());
            $loc = "{$baseUrl}/{$post->slug}";
            $priority = $post->is_featured ? '0.9' : '0.7';
            $xml .= "    <url>\n        <loc>{$loc}</loc>\n        <lastmod>{$lastmod}</lastmod>\n        <changefreq>monthly</changefreq>\n        <priority>{$priority}</priority>\n    </url>\n";
        }

        // 5. Published Tags
        $tags = Tag::where('status', 'publish')->orderBy('updated_at', 'desc')->get();
        foreach ($tags as $tag) {
            $lastmod = $tag->updated_at ? $tag->updated_at->toAtomString() : now()->toAtomString();
            $loc = "{$baseUrl}/tag/{$tag->slug}";
            $xml .= "    <url>\n        <loc>{$loc}</loc>\n        <lastmod>{$lastmod}</lastmod>\n        <changefreq>monthly</changefreq>\n        <priority>0.5</priority>\n    </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}

