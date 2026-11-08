<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Collection;

if (!function_exists('theme_asset')) {
    function theme_asset(string $path): string
    {
        return asset('themes/ripple/' . ltrim($path, '/'));
    }
}

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('theme_option')) {
    function theme_option(string $key, $default = null)
    {
        // Check theme-specific key first (Botble ripple convention)
        $themeKey = 'theme-ripple-' . $key;
        $val = setting($themeKey);
        if ($val !== null && $val !== '') {
            return $val;
        }

        // Fallback to direct key
        $val = setting($key);
        if ($val !== null && $val !== '') {
            return $val;
        }

        return $default;
    }
}

if (!function_exists('get_object_image')) {
    function get_object_image(?string $imagePath, ?string $size = null): string
    {
        if (empty($imagePath)) {
            return theme_asset('images/no-image.jpg');
        }
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }

        $cleanPath = preg_replace('#^/?storage/#', '', ltrim($imagePath, '/'));

        // Size mapping consistent with Botble CMS legacy sizes
        $sizes = [
            'thumb'    => '150x150',
            'featured' => '560x380',
            'medium'   => '540x360',
        ];

        if ($size && !in_array($size, ['origin', 'original', 'full'], true)) {
            $dimension = $sizes[$size] ?? $size;

            $info = pathinfo($cleanPath);
            $dirname = ($info['dirname'] && $info['dirname'] !== '.') ? $info['dirname'] . '/' : '';
            $filename = $info['filename'];
            $extension = isset($info['extension']) ? '.' . $info['extension'] : '';

            if (!str_ends_with($filename, '-' . $dimension)) {
                $sizedPath = $dirname . $filename . '-' . $dimension . $extension;

                // Check if sized file exists in public/storage or storage/app/public
                if (file_exists(public_path('storage/' . $sizedPath)) || file_exists(storage_path('app/public/' . $sizedPath))) {
                    $cleanPath = $sizedPath;
                } elseif (isset($info['extension'])) {
                    // Try alternative case for extension (e.g. .PNG vs .png)
                    $altExt = ctype_upper($info['extension']) ? strtolower($info['extension']) : strtoupper($info['extension']);
                    $altSizedPath = $dirname . $filename . '-' . $dimension . '.' . $altExt;
                    if (file_exists(public_path('storage/' . $altSizedPath)) || file_exists(storage_path('app/public/' . $altSizedPath))) {
                        $cleanPath = $altSizedPath;
                    }
                }
            }
        }

        return asset('storage/' . $cleanPath);
    }
}

if (!function_exists('date_from_database')) {
    function date_from_database($date, string $format = 'M d, Y'): string
    {
        if (empty($date)) {
            return '';
        }
        try {
            return Carbon::parse($date)->format($format);
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }
}

if (!function_exists('is_plugin_active')) {
    function is_plugin_active(string $plugin): bool
    {
        return in_array($plugin, ['blog', 'contact', 'language', 'page']);
    }
}

if (!function_exists('language_flag')) {
    function language_flag(string $flag, string $name = ''): string
    {
        $flag = strtolower(trim($flag));
        if ($flag === 'vi' || $flag === 'vi_vn') {
            $flag = 'vn';
        } elseif ($flag === 'en' || $flag === 'en_us') {
            $flag = 'us';
        }

        $url = asset('vendor/core/images/flags/' . $flag . '.png');
        return '<img src="' . e($url) . '" alt="' . e($name) . '" title="' . e($name) . '" style="display:inline-block; vertical-align:middle; margin-right:5px; width:16px; height:11px;">';
    }
}

if (!function_exists('get_related_posts')) {
    function get_related_posts($postId, int $limit = 2): Collection
    {
        $post = Post::find($postId);
        if (!$post) {
            return collect();
        }

        $categoryIds = $post->categories()->pluck('categories.id')->toArray();

        $query = Post::where('status', 'publish')
            ->where('id', '!=', $postId)
            ->where('locale', $post->locale ?? app()->getLocale());

        if (!empty($categoryIds)) {
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        $results = $query->latest('created_at')->limit($limit)->get();

        if ($results->count() < $limit) {
            $more = Post::where('status', 'publish')
                ->where('id', '!=', $postId)
                ->whereNotIn('id', $results->pluck('id'))
                ->where('locale', $post->locale ?? app()->getLocale())
                ->latest('created_at')
                ->limit($limit - $results->count())
                ->get();
            $results = $results->merge($more);
        }

        return $results;
    }
}

if (!function_exists('get_featured_posts')) {
    function get_featured_posts(int $limit = 5): Collection
    {
        return Post::where('status', 'publish')
            ->where('is_featured', true)
            ->where('locale', app()->getLocale())
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }
}

