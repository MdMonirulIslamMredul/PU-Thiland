<?php

namespace App\Services;

use App\Models\RechargeOrder;
use App\Models\User;
use App\Repositories\RechargeOrderRepository;
use Carbon\Carbon;

class RechargeOrderService
{
    public function __construct(
        protected RechargeOrderRepository $repository,
        protected VipCalculationService $vipCalculationService
    ) {}

    public function getAll(array $filters = []): \Illuminate\Support\Collection
    {
        return $this->repository->all($filters);
    }

    public function getById(int $id): ?RechargeOrder
    {
        return $this->repository->find($id);
    }

    public function getForUser(User $user): \Illuminate\Support\Collection
    {
        return $this->repository->userOrders($user->id);
    }

    public function createRequest(array $data): RechargeOrder
    {
        return $this->repository->create($data);
    }

    public function approve(RechargeOrder $order, User $admin, ?string $note = null): RechargeOrder
    {
        if ($order->status !== RechargeOrder::STATUS_PENDING) {
            return $order;
        }

        $order->status = RechargeOrder::STATUS_APPROVED;
        $order->approved_by = $admin->id;
        $order->approved_at = Carbon::now();
        $order->admin_note = $note;
        $order->save();

        $order->user->increment('recharge_amount', $order->amount);
        $this->vipCalculationService->recalculateForUser(
            $order->user,
            'system',
            'VIP recalculated after recharge approval'
        );

        return $order;
    }

    public function reject(RechargeOrder $order, User $admin, ?string $note = null): RechargeOrder
    {
        if ($order->status !== RechargeOrder::STATUS_PENDING) {
            return $order;
        }

        $order->status = RechargeOrder::STATUS_REJECTED;
        $order->approved_by = $admin->id;
        $order->approved_at = Carbon::now();
        $order->admin_note = $note;
        $order->save();

        return $order;
    }
}
