<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RechargeOrder;
use App\Services\RechargeOrderService;
use Illuminate\Http\Request;

class RechargeOrderController extends Controller
{
    public function __construct(protected RechargeOrderService $rechargeOrderService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'user_search']);

        return view('admin.recharge-orders.index', [
            'orders' => $this->rechargeOrderService->getAll($filters),
            'filterStatus' => $filters['status'] ?? '',
            'filterDateFrom' => $filters['date_from'] ?? '',
            'filterDateTo' => $filters['date_to'] ?? '',
            'filterUserSearch' => $filters['user_search'] ?? '',
        ]);
    }

    public function report(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'user_search']);

        return view('admin.recharge-orders.report', [
            'orders' => $this->rechargeOrderService->getAll($filters),
            'filterStatus' => $filters['status'] ?? '',
            'filterDateFrom' => $filters['date_from'] ?? '',
            'filterDateTo' => $filters['date_to'] ?? '',
            'filterUserSearch' => $filters['user_search'] ?? '',
        ]);
    }

    public function show(RechargeOrder $rechargeOrder)
    {
        return view('admin.recharge-orders.show', [
            'order' => $rechargeOrder,
        ]);
    }

    public function update(Request $request, RechargeOrder $rechargeOrder)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'admin_note' => ['nullable', 'string'],
        ]);

        if ($data['status'] === RechargeOrder::STATUS_APPROVED) {
            $this->rechargeOrderService->approve($rechargeOrder, $request->user(), $data['admin_note'] ?? null);
        } elseif ($data['status'] === RechargeOrder::STATUS_REJECTED) {
            $this->rechargeOrderService->reject($rechargeOrder, $request->user(), $data['admin_note'] ?? null);
        }

        return back()->with('success', 'Recharge order status updated.');
    }
}
