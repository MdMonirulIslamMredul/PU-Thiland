<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_name',
        'discount_per_kg',
        'min_sales_kg',
        'max_sales_kg',
        'min_recharge_amount',
        'priority',
        'is_active',
        'expiry_days',
    ];

    protected $casts = [
        'discount_per_kg' => 'decimal:2',
        'min_sales_kg' => 'decimal:2',
        'max_sales_kg' => 'decimal:2',
        'min_recharge_amount' => 'decimal:2',
        'priority' => 'integer',
        'is_active' => 'boolean',
        'expiry_days' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
