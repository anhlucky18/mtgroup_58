<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SeoMeta extends Component
{
    public string $title;
    public string $description;
    public string $image;
    public string $url;

    public function __construct(public mixed $model = null)
    {
        $siteTitle = setting('site_title', 'Minh Thành Group');
        $siteDescription = setting('seo_description', setting('site_slogan', 'Cung cấp giải pháp quan trắc môi trường tự động'));

        if ($this->model) {
            $rawTitle = $this->model->seo_title ?: $this->model->name ?: $siteTitle;
            $this->title = $rawTitle . ' | ' . $siteTitle;
            $this->description = $this->model->seo_description ?: ($this->model->description ?: $siteDescription);
            $this->image = get_object_image($this->model->image ?? null);
        } else {
            $this->title = setting('seo_title') ?: $siteTitle;
            $this->description = $siteDescription;
            $this->image = get_object_image(setting('theme-ripple-logo', '/storage/uploads/gst-logo-01.png'));
        }

        $this->url = url()->current();
    }

    public function render(): View|Closure|string
    {
        return view('components.seo-meta');
    }
}

