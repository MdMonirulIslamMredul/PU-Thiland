<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomepageCarousel extends Model
{
    use HasTranslations;

    protected $translatable = [
        'title',
        'subtitle',
    ];
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
