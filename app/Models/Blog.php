<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Blog extends Model
{
    use HasTranslations;

    protected $translatable = [
        'title',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
        'seo_keywords',
    ];
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'image',
        'meta_title',
        'meta_description',
        'seo_keywords',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
