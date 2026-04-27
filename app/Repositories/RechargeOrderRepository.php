<?php

namespace App\Repositories;

use App\Models\RechargeOrder;
use Illuminate\Support\Collection;

class RechargeOrderRepository
{
    public function all(array $filters = []): Collection
    {
        $query = RechargeOrder::with(['user', 'paymentGateway']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query
            ->orderByRaw("(CASE WHEN status='pending' THEN 0 WHEN status='approved' THEN 1 ELSE 2 END)")
            ->latest()
            ->get();
    }

    public function find(int $id): ?RechargeOrder
    {
        return RechargeOrder::with(['user', 'paymentGateway', 'approvedBy'])->find($id);
    }

    public function userOrders(int $userId): Collection
    {
        return RechargeOrder::with('paymentGateway')->where('user_id', $userId)->latest()->get();
    }

    public function create(array $data): RechargeOrder
    {
        return RechargeOrder::create($data);
    }

    public function update(RechargeOrder $order, array $data): RechargeOrder
    {
        $order->update($data);

        return $order;
    }
}
