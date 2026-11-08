<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected static ?array $cachedSettings = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        if (static::$cachedSettings === null) {
            try {
                static::$cachedSettings = static::pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                return $default;
            }
        }

        return static::$cachedSettings[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        if (static::$cachedSettings !== null) {
            static::$cachedSettings[$key] = $value ?? '';
        }
    }
}
