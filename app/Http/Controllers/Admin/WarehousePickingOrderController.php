<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\WarehouseInventoryItem;
use App\Models\WarehousePickingOrder;
use App\Services\WarehousePickingService;
use Illuminate\Http\Request;

class WarehousePickingOrderController extends Controller
{
    public function index()
    {
        $pickingOrders = WarehousePickingOrder::with(['order.user', 'order.items.product'])
            ->orderByRaw("(CASE WHEN status='pending' THEN 0 WHEN status='picking' THEN 1 ELSE 2 END)")
            ->latest()
            ->get();

        $pickingOrders->each(function (WarehousePickingOrder $pickingOrder) {
            $pickingOrder->completion_error = null;
            $pickingOrder->can_complete = false;

            if ($pickingOrder->status !== WarehousePickingOrder::STATUS_PICKING) {
                return;
            }

            if (!$pickingOrder->order) {
                $pickingOrder->completion_error = 'Related order not found.';
                return;
            }

            $productWeights = $pickingOrder->order->items->mapWithKeys(function ($item) {
                return [$item->product_id => $this->calculateItemWeight($item->product?->weight ?? 0, $item->quantity)];
            });

            $inventoryLevels = WarehouseInventoryItem::whereIn('product_id', $productWeights->keys()->all())
                ->pluck('quantity_kg', 'product_id')
                ->toArray();

            $errors = [];
            foreach ($productWeights as $productId => $requiredQty) {
                $productName = $pickingOrder->order->items->firstWhere('product_id', $productId)->product?->title ?? 'Unknown product';
                if (!array_key_exists($productId, $inventoryLevels)) {
                    $errors[] = "No inventory for {$productName}";
                    continue;
                }

                if ($inventoryLevels[$productId] < $requiredQty) {
                    $errors[] = "Insufficient stock for {$productName}";
                }
            }

            if (!empty($errors)) {
                $pickingOrder->completion_error = implode(', ', $errors);
                return;
            }

            $pickingOrder->can_complete = true;
        });

        return view('admin.warehouse.picking-orders', [
            'pickingOrders' => $pickingOrders,
            'availableOrders' => Order::where('status', Order::STATUS_CONFIRMED)
                ->whereDoesntHave('warehousePickingOrder')
                ->with('user')
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }

    public function show(WarehousePickingOrder $pickingOrder)
    {
        $pickingOrder->load([
            'assignedTo',
            'order.user',
            'order.userAddress',
            'order.items.product',
            'order.payments.paymentGateway',
        ]);

        $salesOrder = $pickingOrder->order;
        $stockRows = collect();
        $stockReady = false;
        $stockAlerts = collect();

        if ($salesOrder && $salesOrder->items->isNotEmpty()) {
            $productIds = $salesOrder->items
                ->pluck('product_id')
                ->filter()
                ->unique()
                ->values();

            $inventoryLevels = WarehouseInventoryItem::whereIn('product_id', $productIds)
                ->pluck('quantity_kg', 'product_id');

            $stockRows = $salesOrder->items->map(function ($item, $index) use ($inventoryLevels) {
                $quantity = (float) $item->quantity;
                $unitWeight = (float) ($item->product?->weight ?? 0);
                $requiredWeight = $quantity * $unitWeight;
                $availableWeight = (float) ($inventoryLevels[$item->product_id] ?? 0);
                $shortageWeight = max(0, $requiredWeight - $availableWeight);
                $productTitleEn = $item->product?->getTranslation('title', 'en', false) ?: ($item->product_name ?: 'Unknown Product');
                $productTitleBn = $item->product?->getTranslation('title', 'bn', false) ?: $productTitleEn;
                $productTitleZh = $item->product?->getTranslation('title', 'zh', false) ?: $productTitleEn;

                return [
                    'index' => $index + 1,
                    'product_name' => $item->product?->getTranslation('title', app()->getLocale(), false) ?: $productTitleEn,
                    'product_name_en' => $productTitleEn,
                    'product_name_bn' => $productTitleBn,
                    'product_name_zh' => $productTitleZh,
                    'quantity' => $quantity,
                    'unit_weight' => $unitWeight,
                    'required_weight' => $requiredWeight,
                    'available_weight' => $availableWeight,
                    'shortage_weight' => $shortageWeight,
                    'unit_price' => (float) $item->product_price,
                    'line_total' => (float) ($item->total_price ?? ((float) $item->product_price * $quantity)),
                    'ready' => $shortageWeight <= 0,
                ];
            });

            $stockReady = $stockRows->every(fn($row) => $row['ready']);

            $stockAlerts = $stockRows
                ->filter(fn($row) => !$row['ready'])
                ->map(fn($row) => $row['product_name'] . ' (short by ' . number_format($row['shortage_weight'], 3) . ' kg)')
                ->values();
        }

        return view('admin.warehouse.picking-orders.show', [
            'order' => $pickingOrder,
            'stockRows' => $stockRows,
            'stockReady' => $stockReady,
            'stockAlerts' => $stockAlerts,
        ]);
    }

    protected function calculateItemWeight(float $weight, float $quantity): float
    {
        return max(0.0, $weight * $quantity);
    }

    public function edit(WarehousePickingOrder $pickingOrder)
    {
        return view('admin.warehouse.picking-orders.edit', [
            'order' => $pickingOrder,
            'statuses' => [
                WarehousePickingOrder::STATUS_PENDING => 'Pending',
                WarehousePickingOrder::STATUS_PICKING => 'Picking',
                WarehousePickingOrder::STATUS_COMPLETED => 'Completed',
            ],
            'admins' => User::where('is_admin', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, WarehousePickingOrder $pickingOrder, WarehousePickingService $pickingService)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', [WarehousePickingOrder::STATUS_PENDING, WarehousePickingOrder::STATUS_PICKING, WarehousePickingOrder::STATUS_COMPLETED])],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'note' => ['nullable', 'string'],
        ]);

        try {
            if ($data['status'] === WarehousePickingOrder::STATUS_PICKING && $pickingOrder->status === WarehousePickingOrder::STATUS_PENDING) {
                $pickingService->startPicking($pickingOrder);
            }

            if ($data['status'] === WarehousePickingOrder::STATUS_COMPLETED && $pickingOrder->status !== WarehousePickingOrder::STATUS_COMPLETED) {
                $pickingService->completePicking($pickingOrder);
            }
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $pickingOrder->update([
            'assigned_to' => $data['assigned_to'],
            'note' => $data['note'],
        ]);

        return redirect()->route('admin.picking-orders.index')->with('success', 'Picking order updated successfully.');
    }

    public function destroy(WarehousePickingOrder $pickingOrder)
    {
        $pickingOrder->delete();

        return back()->with('success', 'Picking order deleted successfully.');
    }
}
