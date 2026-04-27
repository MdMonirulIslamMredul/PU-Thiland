<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Gallery extends Model
{
    use HasTranslations;

    protected $translatable = [
        'title',
        'description',
    ];
    protected $fillable = [
        'title',
        'type',
        'image',
        'video_url',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
