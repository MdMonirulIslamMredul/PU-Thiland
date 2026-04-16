<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'image',
        'is_featured',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
