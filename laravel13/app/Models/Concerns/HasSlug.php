<?php

namespace App\Models\Concerns;

use App\Models\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saved(function (Model $model) {
            if (! empty($model->slug)) {
                Slug::updateOrCreate(
                    [
                        'reference_type' => get_class($model),
                        'reference_id' => $model->id,
                    ],
                    [
                        'key' => $model->slug,
                        'prefix' => method_exists($model, 'getSlugPrefix') ? $model->getSlugPrefix() : ($model->slug_prefix ?? ''),
                        'locale' => $model->locale ?? 'vi',
                    ]
                );
            }
        });

        static::deleted(function (Model $model) {
            Slug::where('reference_type', get_class($model))
                ->where('reference_id', $model->id)
                ->delete();
        });
    }

    public function slugModel(): MorphOne
    {
        return $this->morphOne(Slug::class, 'reference');
    }
}

