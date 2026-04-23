<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'body',
        'image',
        'attachment',
        'type',
        'priority',
        'status',
        'publish_date',
        'expiry_date',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('publish_date')->orWhere('publish_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
            });
    }

    public function scopeOrderByPriority($query)
    {
        return $query->orderByRaw("(CASE WHEN priority='urgent' THEN 1 WHEN priority='high' THEN 2 ELSE 3 END)");
    }

    public function isActive(): bool
    {
        return $this->status === 'published'
            && ($this->publish_date === null || $this->publish_date->lte(now()))
            && ($this->expiry_date === null || $this->expiry_date->gte(now()));
    }
}
