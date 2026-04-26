<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WarehousePickingOrder;
use App\Services\WarehouseInventoryService;
use App\Services\WarehousePickingService;
use Illuminate\Http\RedirectResponse;

class WarehouseController extends Controller
{
    public function dashboard(WarehouseInventoryService $inventoryService)
    {
        $stats = $inventoryService->getDashboardStats();

        return view('admin.warehouse.dashboard', compact('stats'));
    }

    public function inventory(WarehouseInventoryService $inventoryService)
    {
        return view('admin.warehouse.inventory', [
            'inventoryItems' => $inventoryService->getInventoryItems(),
        ]);
    }

    public function pickingOrders(WarehousePickingService $pickingService)
    {
        return view('admin.warehouse.picking-orders', [
            'pickingOrders' => WarehousePickingOrder::with(['order.user'])->orderByRaw("(CASE WHEN status='pending' THEN 0 WHEN status='picking' THEN 1 ELSE 2 END)")->latest()->get(),
            'availableOrders' => Order::where('status', Order::STATUS_CONFIRMED)
                ->whereDoesntHave('warehousePickingOrder')
                ->with('user')
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }

    public function startPicking(WarehousePickingOrder $warehousePickingOrder, WarehousePickingService $pickingService): RedirectResponse
    {
        try {
            $pickingService->startPicking($warehousePickingOrder);
            return back()->with('success', 'Picking order started.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function completePicking(WarehousePickingOrder $warehousePickingOrder, WarehousePickingService $pickingService): RedirectResponse
    {
        try {
            $pickingService->completePicking($warehousePickingOrder);
            return back()->with('success', 'Picking order completed and stock deducted.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function createPickingFromOrder(Order $order, WarehousePickingService $pickingService): RedirectResponse
    {
        $pickingService->createPickingOrderFromOrder($order);

        return back()->with('success', 'Picking order created from confirmed order.');
    }
}
