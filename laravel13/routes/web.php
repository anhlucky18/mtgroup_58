<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// XML Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('public.sitemap');

// Language Switcher
Route::get('/lang/{locale}', function (string $locale) {
    $validLocales = ['vi', 'en', 'en_US'];
    if (in_array($locale, $validLocales, true)) {
        session(['locale' => $locale === 'en' ? 'en_US' : $locale]);
    }
    return back();
})->name('language.switch');

// Contact Form Submissions
Route::post('/send-contact', [ContactController::class, 'send'])->name('public.send.contact');
Route::post('/contact/send', [ContactController::class, 'send']);

// English routes (Prefix /en)
Route::prefix('en')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('public.index.en');
    Route::get('/search', [PublicController::class, 'search'])->name('public.search.en');
    Route::get('/tag/{slug}', [PublicController::class, 'tag'])->name('public.tag.en');
    Route::get('/author/{username}', [PublicController::class, 'author'])->name('public.author.en');
    Route::get('/{slug}', [PublicController::class, 'handleSlug'])->name('public.single.en');
});

// Default routes (Vietnamese)
Route::get('/', [HomeController::class, 'index'])->name('public.index');
Route::get('/search', [PublicController::class, 'search'])->name('public.search');
Route::get('/tag/{slug}', [PublicController::class, 'tag'])->name('public.tag');
Route::get('/author/{username}', [PublicController::class, 'author'])->name('public.author');
Route::get('/{slug}', [PublicController::class, 'handleSlug'])->name('public.single');
