<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'locale',
        'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->orderBy('order');
    }

    public function rootItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->whereNull('parent_id')->orderBy('order');
    }
}

