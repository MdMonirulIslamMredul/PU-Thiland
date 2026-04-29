<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TeamMember extends Model
{
    use HasTranslations;

    protected $translatable = [
        'name',
        'designation',
        'bio',
    ];
    protected $fillable = [
        'name',
        'designation',
        'bio',
        'photo',
        'facebook_url',
        'linkedin_url',
        'twitter_url',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
