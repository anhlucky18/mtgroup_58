<?php

namespace App\View\Components;

use App\Services\ShortcodeParser;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContentRender extends Component
{
    public string $renderedHtml;

    public function __construct(public ?string $content = '')
    {
        $parser = new ShortcodeParser();
        $this->renderedHtml = $parser->parse($this->content);
    }

    public function render(): View|Closure|string
    {
        return view('components.content-render');
    }
}

