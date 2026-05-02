<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WarehouseInventoryItem;
use App\Models\WarehousePickingOrder;
use App\Services\WarehouseInventoryService;
use App\Services\WarehousePickingService;
use Illuminate\Http\RedirectResponse;

class WarehouseController extends Controller
{
    public function dashboard(WarehouseInventoryService $inventoryService)
    {
        $stats = $inventoryService->getDashboardStats();
        $pickingOrders = WarehousePickingOrder::with(['order.user', 'order.items.product'])
            ->orderByRaw("(CASE WHEN status='pending' THEN 0 WHEN status='picking' THEN 1 ELSE 2 END)")
            ->latest()
            ->limit(6)
            ->get();

        $pickingOrders->each(function (WarehousePickingOrder $pickingOrder) {
            $pickingOrder->setAttribute('completion_error', null);
            $pickingOrder->setAttribute('can_complete', false);

            if ($pickingOrder->status !== WarehousePickingOrder::STATUS_PICKING) {
                return;
            }

            if (!$pickingOrder->order) {
                $pickingOrder->setAttribute('completion_error', 'Related order not found.');
                return;
            }

            $productWeights = $pickingOrder->order->items->mapWithKeys(function ($item) {
                return [$item->product_id => (float) ($item->product?->weight ?? 0) * (float) $item->quantity];
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
                $pickingOrder->setAttribute('completion_error', implode(', ', $errors));
                return;
            }

            $pickingOrder->setAttribute('can_complete', true);
        });

        $confirmedOrders = Order::where('status', Order::STATUS_CONFIRMED)
            ->whereDoesntHave('warehousePickingOrder')
            ->with(['user', 'items.product'])
            ->latest()
            ->limit(6)
            ->get();

        $productIds = $confirmedOrders
            ->flatMap(fn($order) => $order->items->pluck('product_id'))
            ->filter()
            ->unique()
            ->values();

        $inventoryLevels = WarehouseInventoryItem::whereIn('product_id', $productIds)
            ->pluck('quantity_kg', 'product_id');

        $confirmedOrders->each(function ($order) use ($inventoryLevels) {
            $stockCheckNeeded = false;

            foreach ($order->items as $item) {
                $requiredWeight = (float) $item->quantity * (float) ($item->product?->weight ?? 0);
                $availableWeight = (float) ($inventoryLevels[$item->product_id] ?? 0);

                if ($requiredWeight > $availableWeight) {
                    $stockCheckNeeded = true;
                    break;
                }
            }

            $order->setAttribute('can_create_picking', !$stockCheckNeeded);
            $order->setAttribute('stock_check_needed', $stockCheckNeeded);
        });

        return view('admin.warehouse.dashboard', compact('stats', 'pickingOrders', 'confirmedOrders'));
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
        $order->loadMissing(['items.product']);

        $productIds = $order->items
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        $inventoryLevels = WarehouseInventoryItem::whereIn('product_id', $productIds)
            ->pluck('quantity_kg', 'product_id');

        foreach ($order->items as $item) {
            $requiredWeight = (float) $item->quantity * (float) ($item->product?->weight ?? 0);
            $availableWeight = (float) ($inventoryLevels[$item->product_id] ?? 0);

            if ($requiredWeight > $availableWeight) {
                return back()->with('error', 'Stock check needed before creating a picking order.');
            }
        }

        try {
            $pickingService->createPickingOrderFromOrder($order);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Picking order created from confirmed order.');
    }

    public function orderDetails(Order $order)
    {
        abort_unless($order->status === Order::STATUS_CONFIRMED, 404);

        $order->load(['user', 'userAddress', 'items.product']);

        $productIds = $order->items
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        $inventoryLevels = WarehouseInventoryItem::whereIn('product_id', $productIds)
            ->pluck('quantity_kg', 'product_id');

        $stockRows = $order->items->map(function ($item, $index) use ($inventoryLevels) {
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
                'product_name' => $item->product?->getTranslation('title', app()->getLocale(), false)
                    ?: $productTitleEn,
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

        $stockReady = $stockRows->isNotEmpty() && $stockRows->every(fn($row) => $row['ready']);
        $stockAlerts = $stockRows
            ->filter(fn($row) => !$row['ready'])
            ->map(fn($row) => $row['product_name'] . ' (short by ' . number_format($row['shortage_weight'], 3) . ' kg)')
            ->values();

        return view('admin.warehouse.orders.show', [
            'order' => $order,
            'stockRows' => $stockRows,
            'stockReady' => $stockReady,
            'stockAlerts' => $stockAlerts,
        ]);
    }
}
