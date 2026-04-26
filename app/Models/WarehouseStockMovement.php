<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WarehouseStockMovement extends Model
{
    protected $fillable = [
        'warehouse_inventory_item_id',
        'movement_type',
        'quantity_kg',
        'unit_cost',
        'total_cost',
        'reference_type',
        'reference_id',
        'note',
    ];

    protected $casts = [
        'quantity_kg' => 'decimal:3',
        'unit_cost' => 'decimal:4',
        'total_cost' => 'decimal:4',
    ];

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(WarehouseInventoryItem::class, 'warehouse_inventory_item_id');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
