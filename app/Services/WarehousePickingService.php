<?php

namespace App\Services;

use App\Models\Order;
use App\Models\WarehousePickingOrder;
use App\Models\WarehouseInventoryItem;
use App\Models\WarehouseStockMovement;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WarehousePickingService
{
    public function createPickingOrderFromOrder(Order $order): WarehousePickingOrder
    {
        if ($order->status !== Order::STATUS_CONFIRMED) {
            throw new RuntimeException('Only confirmed orders can be sent to warehouse picking.');
        }

        return WarehousePickingOrder::firstOrCreate([
            'order_id' => $order->id,
        ], [
            'status' => WarehousePickingOrder::STATUS_PENDING,
            'total_weight_kg' => $this->calculateOrderWeight($order),
        ]);
    }

    public function startPicking(WarehousePickingOrder $pickingOrder): WarehousePickingOrder
    {
        if ($pickingOrder->status !== WarehousePickingOrder::STATUS_PENDING) {
            throw new RuntimeException('Picking order is not in a pending state.');
        }

        $pickingOrder->update([
            'status' => WarehousePickingOrder::STATUS_PICKING,
            'started_at' => now(),
        ]);

        return $pickingOrder;
    }

    public function completePicking(WarehousePickingOrder $pickingOrder): WarehousePickingOrder
    {
        if ($pickingOrder->status === WarehousePickingOrder::STATUS_COMPLETED) {
            throw new RuntimeException('Picking order has already been completed.');
        }

        return DB::transaction(function () use ($pickingOrder) {
            $order = $pickingOrder->order()->with('items.product')->first();

            if (!$order) {
                throw new RuntimeException('Associated order not found for this picking order.');
            }

            $items = $order->items;

            foreach ($items as $item) {
                if (!$item->product) {
                    throw new RuntimeException('Order item product not found.');
                }

                $quantityKg = $this->calculateItemWeight($item->product->weight, $item->quantity);
                if ($quantityKg <= 0) {
                    continue;
                }

                $inventory = WarehouseInventoryItem::where('product_id', $item->product_id)->lockForUpdate()->first();
                if (!$inventory) {
                    throw new RuntimeException('Inventory item not found for product ' . $item->product->title . '.');
                }

                if ($inventory->quantity_kg < $quantityKg) {
                    throw new RuntimeException('Insufficient stock for ' . $item->product->title . '.');
                }

                $inventory->update([
                    'quantity_kg' => $inventory->quantity_kg - $quantityKg,
                ]);

                WarehouseStockMovement::create([
                    'warehouse_inventory_item_id' => $inventory->id,
                    'movement_type' => 'out',
                    'quantity_kg' => $quantityKg,
                    'unit_cost' => $inventory->avg_cost,
                    'total_cost' => $quantityKg * $inventory->avg_cost,
                    'reference_type' => Order::class,
                    'reference_id' => $order->id,
                    'note' => 'Order picking completion',
                ]);
            }

            $pickingOrder->update([
                'status' => WarehousePickingOrder::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);

            if ($order->status !== Order::STATUS_SUCCESSFUL) {
                $order->update(['status' => Order::STATUS_SUCCESSFUL]);
            }

            return $pickingOrder;
        });
    }

    public function countPendingOrders(): int
    {
        $pendingPickingOrders = WarehousePickingOrder::whereIn('status', [
            WarehousePickingOrder::STATUS_PENDING,
            WarehousePickingOrder::STATUS_PICKING,
        ])->count();

        $confirmedOrdersWithoutPicking = Order::where('status', Order::STATUS_CONFIRMED)
            ->whereDoesntHave('warehousePickingOrder')
            ->count();

        return $pendingPickingOrders + $confirmedOrdersWithoutPicking;
    }

    protected function calculateOrderWeight(Order $order): float
    {
        return $order->items->reduce(function ($carry, $item) {
            return $carry + $this->calculateItemWeight($item->product->weight ?? 0, $item->quantity);
        }, 0.0);
    }

    protected function calculateItemWeight(float $weight, float $quantity): float
    {
        return max(0.0, $weight * $quantity);
    }
}
