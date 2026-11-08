<?php

namespace App\View\Components;

use App\Models\Menu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class NavigationMenu extends Component
{
    public Collection $items;
    public ?Menu $menu = null;

    public function __construct(public string $location = 'main-menu')
    {
        $locale = app()->getLocale();
        $targetLocation = $this->location;

        if ($targetLocation === 'main-menu' && ($locale === 'en' || $locale === 'en_US')) {
            $targetLocation = 'main-menu-en';
        }

        $this->menu = Menu::where('location', $targetLocation)
            ->where('status', 'publish')
            ->first();

        if (!$this->menu && $targetLocation !== $this->location) {
            $this->menu = Menu::where('location', $this->location)
                ->where('status', 'publish')
                ->first();
        }

        if ($this->menu) {
            $this->items = $this->menu->items()
                ->whereNull('parent_id')
                ->with(['children.children'])
                ->orderBy('order')
                ->get();
        } else {
            $this->items = collect();
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.navigation-menu');
    }
}

