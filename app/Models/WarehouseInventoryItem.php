<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseInventoryItem extends Model
{
    protected $fillable = [
        'product_id',
        'grade',
        'specification',
        'quantity_kg',
        'avg_cost',
    ];

    protected $casts = [
        'quantity_kg' => 'decimal:3',
        'avg_cost' => 'decimal:4',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(WarehouseStockMovement::class);
    }
}
