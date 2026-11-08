<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Slug extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'reference_id',
        'reference_type',
        'prefix',
        'locale',
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}

