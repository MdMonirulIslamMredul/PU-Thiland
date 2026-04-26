<?php

namespace App\Models;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehousePickingOrder extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PICKING = 'picking';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'order_id',
        'status',
        'assigned_to',
        'total_weight_kg',
        'started_at',
        'completed_at',
        'note',
    ];

    protected $casts = [
        'total_weight_kg' => 'decimal:3',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
