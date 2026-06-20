<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\VipAuditService;
use App\Services\VipRuleService;
use Carbon\Carbon;


class VipCalculationService
{
    public function __construct(
        protected VipRuleService $ruleService,
        protected VipAuditService $auditService
    ) {}

    public function getUserMonthlySalesKg(User $user): float
    {
        $startOfMonth = Carbon::now()->startOfMonth();

        $orders = $user->orders()
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_SUCCESSFUL])
            ->where('paid_amount', '>', 0)
            ->where('created_at', '>=', $startOfMonth)
            ->with(['items.product'])
            ->get();

        return $orders->flatMap(fn(Order $order) => $order->items)
            ->sum(fn(OrderItem $item) => $this->getOrderItemWeightKg($item));
    }

    public function getUserMonthlyRecharge(User $user): float
    {
        $startOfMonth = Carbon::now()->startOfMonth();

        return (float) $user->rechargeOrders()
            ->where('status', 'approved')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('amount');
    }

    public function getEffectiveVipRuleForUser(User $user): ?\App\Models\VipRule
    {
        $currentRule = null;

        if ($user->vip_level && $user->vip_expiry_date) {
            $expiry = Carbon::parse($user->vip_expiry_date);
            if ($expiry->isFuture()) {
                $currentRule = $this->ruleService->getActiveRules()->first(fn($rule) => $rule->level_name === $user->vip_level);
            }
        }

        if ($currentRule) {
            return $currentRule;
        }

        return $this->recalculateForUser($user, 'system', 'VIP recalculated for pricing');
    }

    public function recalculateForUser(User $user, string $changedBy = 'system', ?string $reason = null): ?\App\Models\VipRule
    {
        $oldLevel = $user->vip_level;
        $currentExpiry = $user->vip_expiry_date ? Carbon::parse($user->vip_expiry_date) : null;
        $isExpired = ! $currentExpiry || $currentExpiry->isPast();

        $monthlySalesKg = $this->getUserMonthlySalesKg($user);
        $monthlyRecharge = $this->getUserMonthlyRecharge($user);
        $matchedRule = $this->ruleService->findMatchingRule($monthlySalesKg, $monthlyRecharge);
        $newLevel = $matchedRule?->level_name;

        if (! $isExpired && $oldLevel === $newLevel) {
            return $matchedRule;
        }

        $user->vip_level = $matchedRule?->level_name;
        $user->vip_expiry_date = $matchedRule ? Carbon::now()->addDays($matchedRule->expiry_days) : null;
        $user->save();

        if ($oldLevel !== $newLevel) {
            $this->auditService->logChange(
                $user,
                $oldLevel,
                $newLevel,
                $changedBy,
                $reason ?? ($isExpired ? 'VIP expiry recalc' : 'VIP status recalculation')
            );
        }

        return $matchedRule;
    }

    public function calculateOrderDiscount(User $user, array $cartItems): float
    {
        $rule = $this->getEffectiveVipRuleForUser($user);
        if (! $rule) {
            return 0.0;
        }

        $products = Product::whereIn('id', collect($cartItems)->pluck('id'))->get()->keyBy('id');

        $totalWeight = collect($cartItems)
            ->sum(function (array $item) use ($products) {
                $quantity = (float) ($item['quantity'] ?? 0);
                $itemWeight = (float) ($item['weight'] ?? 0);

                if ($itemWeight > 0) {
                    return $itemWeight * $quantity;
                }

                $product = $products->get($item['id']);
                return $this->getProductWeightKg($product, $quantity);
            });

        return max(0.0, $totalWeight * (float) $rule->discount_per_kg);
    }

    protected function getProductWeightKg(?Product $product, float $quantity): float
    {
        if (! $product) {
            return 0.0;
        }

        $unitType = strtolower((string) $product->unit_type);
        $unitName = strtolower((string) $product->unit_name);

        if ($unitType === 'weight' || str_contains($unitName, 'kg')) {
            return (float) $product->weight * $quantity;
        }

        return 0.0;
    }

    protected function getOrderItemWeightKg(OrderItem $item): float
    {
        return $this->getProductWeightKg($item->product, (float) $item->quantity);
    }
}
