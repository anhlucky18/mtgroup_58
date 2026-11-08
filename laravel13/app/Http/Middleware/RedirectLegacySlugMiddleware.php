<?php

namespace App\Http\Middleware;

use App\Models\Slug;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectLegacySlugMiddleware
{
    /**
     * Handle an incoming request and redirect legacy / non-canonical slug patterns.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only inspect GET / HEAD requests
        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $path = $request->path(); // e.g. "tin-tuc" or "post/tin-tuc" or "/"
        
        // Skip root, admin, filament, api, livewire, storage, and theme assets
        if ($path === '/' || 
            str_starts_with($path, 'admin') || 
            str_starts_with($path, 'livewire') || 
            str_starts_with($path, 'storage') || 
            str_starts_with($path, 'themes') || 
            str_starts_with($path, 'vendor') ||
            str_starts_with($path, 'api') ||
            str_starts_with($path, 'sanctum') ||
            str_starts_with($path, 'sitemap')
        ) {
            return $next($request);
        }

        // 1. Check for legacy .html suffix (e.g., /san-pham.html -> /san-pham)
        if (str_ends_with($path, '.html')) {
            $cleanPath = substr($path, 0, -5);
            $query = $request->getQueryString();
            $redirectUrl = '/' . $cleanPath . ($query ? '?' . $query : '');
            return redirect($redirectUrl, 301);
        }

        // 2. Handle legacy prefix wrappers like /post/{slug}, /category/{slug}, /page/{slug}
        $legacyPrefixes = ['post/', 'posts/', 'category/', 'categories/', 'page/', 'pages/'];
        foreach ($legacyPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $subSlug = substr($path, strlen($prefix));
                $slugRecord = Slug::where('key', $subSlug)->first();
                if ($slugRecord) {
                    $target = $slugRecord->prefix ? '/' . $slugRecord->prefix . '/' . $slugRecord->key : '/' . $slugRecord->key;
                    $query = $request->getQueryString();
                    return redirect($target . ($query ? '?' . $query : ''), 301);
                }
            }
        }

        // 3. If someone accesses a tag-only slug directly at root `/{tag-slug}`, redirect 301 to `/tag/{tag-slug}`
        if (!str_contains($path, '/')) {
            $tagSlug = Slug::where('key', $path)->where('prefix', 'tag')->first();
            $directSlug = Slug::where('key', $path)->whereNull('prefix')->first();
            if ($tagSlug && !$directSlug) {
                $query = $request->getQueryString();
                return redirect('/tag/' . $path . ($query ? '?' . $query : ''), 301);
            }
        }

        return $next($request);
    }
}
