<?php

namespace App\Models;

use App\Models\OrderPayment;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_SUCCESSFUL = 'successful';

    public const PAYMENT_STATUS_UNPAID = 'unpaid';
    public const PAYMENT_STATUS_PARTIAL = 'partial';
    public const PAYMENT_STATUS_PAID = 'paid';

    protected $fillable = [
        'user_id',
        'status',
        'vip_level',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_status',
        'total_weight',
        'vip_discount_amount',
        'payment_method',
        'payment_receipt',
        'recharge_used_amount',
        'note',
        'user_address_id',
        'delivery_recipient_name',
        'delivery_phone',
        'delivery_address',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'total_weight' => 'decimal:2',
        'vip_discount_amount' => 'decimal:2',
        'recharge_used_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userAddress(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function warehousePickingOrder(): HasOne
    {
        return $this->hasOne(WarehousePickingOrder::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === self::PAYMENT_STATUS_PAID;
    }

    public function getIsPartialAttribute(): bool
    {
        return $this->payment_status === self::PAYMENT_STATUS_PARTIAL;
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_SUCCESSFUL => 'Successful',
        ];
    }

    public static function paymentStatuses(): array
    {
        return [
            self::PAYMENT_STATUS_UNPAID => 'Unpaid',
            self::PAYMENT_STATUS_PARTIAL => 'Partial',
            self::PAYMENT_STATUS_PAID => 'Paid',
        ];
    }
}
