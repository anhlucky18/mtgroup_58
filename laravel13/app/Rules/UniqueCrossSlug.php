<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UniqueCrossSlug implements ValidationRule
{
    public function __construct(protected ?Model $record = null)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            return;
        }

        $query = DB::table('slugs')->where('key', $value);

        if ($this->record && $this->record->exists) {
            $query->where(function ($q) {
                $q->where('reference_type', '!=', get_class($this->record))
                    ->orWhere('reference_id', '!=', $this->record->getKey());
            });
        }

        if ($query->exists()) {
            $fail("Đường dẫn (slug) '{$value}' đã được sử dụng bởi một bài viết, chuyên mục hoặc trang tĩnh khác trong hệ thống.");
        }
    }
}

