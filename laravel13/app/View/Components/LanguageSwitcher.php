<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LanguageSwitcher extends Component
{
    public string $currentLocale;
    public string $currentLocaleName;
    public string $currentLocaleFlag;
    public array $supportedLocales = [];

    public function __construct()
    {
        $this->currentLocale = app()->getLocale() === 'en_US' ? 'en' : (app()->getLocale() ?: 'vi');

        $this->supportedLocales = [
            'vi' => [
                'name' => 'Tiếng Việt',
                'flag' => 'vn',
                'url'  => route('language.switch', ['locale' => 'vi']),
            ],
            'en' => [
                'name' => 'English',
                'flag' => 'us',
                'url'  => route('language.switch', ['locale' => 'en']),
            ],
        ];

        $current = $this->supportedLocales[$this->currentLocale] ?? $this->supportedLocales['vi'];
        $this->currentLocaleName = $current['name'];
        $this->currentLocaleFlag = $current['flag'];
    }

    public function render(): View|Closure|string
    {
        return view('components.language-switcher');
    }
}

