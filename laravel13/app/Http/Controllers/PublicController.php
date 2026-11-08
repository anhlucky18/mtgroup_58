<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Slug;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Dynamic Slug Catch-all Resolution (Post, Category, Page, Tag)
     */
    public function handleSlug(?string $locale = null, ?string $slug = null): View
    {
        // If route matches without locale prefix, $locale contains the slug
        $actualSlug = $slug ?: $locale;
        if (empty($actualSlug)) {
            abort(404);
        }

        $slugRecord = Slug::where('key', $actualSlug)->first();

        if ($slugRecord) {
            $refType = $slugRecord->reference_type;
            $refId = $slugRecord->reference_id;

            if ($refType === Post::class) {
                $post = Post::where('id', $refId)->where('status', 'publish')->first();
                if ($post) {
                    $post->increment('views');
                    return view('post', compact('post'));
                }
            } elseif ($refType === Category::class) {
                $category = Category::where('id', $refId)->where('status', 'publish')->first();
                if ($category) {
                    // Include posts in this category and its direct children
                    $catIds = array_merge([$category->id], $category->children()->pluck('id')->toArray());
                    $posts = Post::where('status', 'publish')
                        ->whereHas('categories', function ($q) use ($catIds) {
                            $q->whereIn('categories.id', $catIds);
                        })
                        ->latest('created_at')
                        ->paginate(12);

                    return view('category', compact('category', 'posts'));
                }
            } elseif ($refType === Page::class) {
                $page = Page::where('id', $refId)->where('status', 'publish')->first();
                if ($page) {
                    $template = $page->template ?: 'default';
                    if ($template !== 'default' && view()->exists($template)) {
                        return view($template, compact('page'));
                    }
                    return view('page', compact('page'));
                }
            } elseif ($refType === Tag::class) {
                $tag = Tag::where('id', $refId)->where('status', 'publish')->first();
                if ($tag) {
                    $posts = $tag->posts()->where('status', 'publish')->latest('created_at')->paginate(12);
                    return view('tag', compact('tag', 'posts'));
                }
            }
        }

        // Direct Model Fallback (in case slug is directly on model)
        $post = Post::where('slug', $actualSlug)->where('status', 'publish')->first();
        if ($post) {
            $post->increment('views');
            return view('post', compact('post'));
        }

        $category = Category::where('slug', $actualSlug)->where('status', 'publish')->first();
        if ($category) {
            $catIds = array_merge([$category->id], $category->children()->pluck('id')->toArray());
            $posts = Post::where('status', 'publish')
                ->whereHas('categories', function ($q) use ($catIds) {
                    $q->whereIn('categories.id', $catIds);
                })
                ->latest('created_at')
                ->paginate(12);
            return view('category', compact('category', 'posts'));
        }

        $page = Page::where('slug', $actualSlug)->where('status', 'publish')->first();
        if ($page) {
            $template = $page->template ?: 'default';
            if ($template !== 'default' && view()->exists($template)) {
                return view($template, compact('page'));
            }
            return view('page', compact('page'));
        }

        $tag = Tag::where('slug', $actualSlug)->where('status', 'publish')->first();
        if ($tag) {
            $posts = $tag->posts()->where('status', 'publish')->latest('created_at')->paginate(12);
            return view('tag', compact('tag', 'posts'));
        }

        abort(404);
    }

    /**
     * Search posts by keyword
     */
    public function search(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));
        $query = Post::where('status', 'publish');

        if (!empty($q)) {
            $terms = explode(' ', $q);
            $query->where(function ($sub) use ($terms, $q) {
                $sub->where('name', 'LIKE', '%' . $q . '%')
                    ->orWhere('description', 'LIKE', '%' . $q . '%');
                foreach ($terms as $t) {
                    $t = trim($t);
                    if (!empty($t)) {
                        $sub->orWhere('name', 'LIKE', '%' . $t . '%');
                    }
                }
            });
        }

        $posts = $query->latest('created_at')->paginate(12)->withQueryString();

        $currentLocale = app()->getLocale();
        $categories = Category::where('status', 'publish')
            ->where(function ($sq) use ($currentLocale) {
                $sq->where('locale', $currentLocale)->orWhere('locale', 'vi');
            })
            ->orderBy('order')
            ->get();

        $tags = Tag::where('status', 'publish')
            ->where(function ($sq) use ($currentLocale) {
                $sq->where('locale', $currentLocale)->orWhere('locale', 'vi');
            })
            ->limit(20)
            ->get();

        return view('search', compact('posts', 'categories', 'tags'));
    }

    /**
     * Tag archive
     */
    public function tag(string $slug): View
    {
        $tag = Tag::where('slug', $slug)->where('status', 'publish')->firstOrFail();
        $posts = $tag->posts()->where('status', 'publish')->latest('created_at')->paginate(12);

        return view('tag', compact('tag', 'posts'));
    }

    /**
     * Author archive
     */
    public function author(string $username): View
    {
        $author = User::where('name', $username)
            ->orWhere('id', $username)
            ->firstOrFail();

        $posts = Post::where('author_id', $author->id)
            ->where('status', 'publish')
            ->latest('created_at')
            ->paginate(12);

        return view('author', compact('author', 'posts'));
    }
}

