<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Order;
use App\Models\RechargeOrder;
use App\Models\UserAddress;
use App\Models\VipAudit;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'phone', 'phone_country', 'password', 'is_admin', 'recharge_amount'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function rechargeOrders(): HasMany
    {
        return $this->hasMany(RechargeOrder::class);
    }

    public function recharges(): HasMany
    {
        return $this->rechargeOrders();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function hasUnpaidOrPartialOrders(): bool
    {
        return $this->orders()
            ->whereIn('payment_status', [Order::PAYMENT_STATUS_UNPAID, Order::PAYMENT_STATUS_PARTIAL])
            ->exists();
    }

    public function vipAudits(): HasMany
    {
        return $this->hasMany(VipAudit::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function complaintMessages(): HasMany
    {
        return $this->hasMany(ComplaintMessage::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'vip_expiry_date' => 'datetime',
            'recharge_amount' => 'decimal:2',
        ];
    }
}
