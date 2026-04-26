<?php

namespace App\Services;

use App\Models\Product;
use App\Models\WarehouseInventoryItem;
use App\Models\WarehouseStockMovement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WarehouseInventoryService
{
    public function getDashboardStats(): array
    {
        $totalQuantity = WarehouseInventoryItem::sum('quantity_kg');
        $totalValue = WarehouseInventoryItem::selectRaw('SUM(quantity_kg * avg_cost) as value')->value('value') ?? 0;

        return [
            'totalQuantity' => (float) $totalQuantity,
            'averageCost' => $totalQuantity > 0 ? (float) ($totalValue / $totalQuantity) : 0.0,
            'pendingPickingOrders' => app(WarehousePickingService::class)->countPendingOrders(),
        ];
    }

    public function getInventoryItems(): Collection
    {
        return WarehouseInventoryItem::with('product')->orderByDesc('quantity_kg')->get();
    }

    public function receiveStock(Product $product, float $quantityKg, float $unitCost, ?string $referenceType = null, ?int $referenceId = null, ?string $note = null): WarehouseStockMovement
    {
        if ($quantityKg <= 0 || $unitCost < 0) {
            throw new RuntimeException('Invalid stock receive values.');
        }

        return DB::transaction(function () use ($product, $quantityKg, $unitCost, $referenceType, $referenceId, $note) {
            $inventory = WarehouseInventoryItem::where('product_id', $product->id)->lockForUpdate()->first();

            if (!$inventory) {
                $inventory = WarehouseInventoryItem::create([
                    'product_id' => $product->id,
                    'grade' => $product->grade,
                    'specification' => $product->specification,
                    'quantity_kg' => 0,
                    'avg_cost' => 0,
                ]);
            }

            $currentQty = (float) $inventory->quantity_kg;
            $currentAvg = (float) $inventory->avg_cost;
            $newQuantity = $currentQty + $quantityKg;
            $newAvgCost = $newQuantity > 0 ? (($currentQty * $currentAvg) + ($quantityKg * $unitCost)) / $newQuantity : 0;

            $inventory->update([
                'quantity_kg' => $newQuantity,
                'avg_cost' => $newAvgCost,
            ]);

            return $this->createMovement($inventory, 'in', $quantityKg, $unitCost, $referenceType, $referenceId, $note);
        });
    }

    public function stockOut(WarehouseInventoryItem $inventory, float $quantityKg, ?string $referenceType = null, ?int $referenceId = null, ?string $note = null): WarehouseStockMovement
    {
        if ($quantityKg <= 0) {
            throw new RuntimeException('Invalid stock quantity.');
        }

        return DB::transaction(function () use ($inventory, $quantityKg, $referenceType, $referenceId, $note) {
            $inventory = WarehouseInventoryItem::where('id', $inventory->id)->lockForUpdate()->firstOrFail();

            if ($inventory->quantity_kg < $quantityKg) {
                throw new RuntimeException('Insufficient stock for product ' . $inventory->product->title . '.');
            }

            $inventory->update([
                'quantity_kg' => $inventory->quantity_kg - $quantityKg,
            ]);

            return $this->createMovement($inventory, 'out', $quantityKg, $inventory->avg_cost, $referenceType, $referenceId, $note);
        });
    }

    protected function createMovement(WarehouseInventoryItem $inventory, string $movementType, float $quantityKg, float $unitCost, ?string $referenceType = null, ?int $referenceId = null, ?string $note = null): WarehouseStockMovement
    {
        return WarehouseStockMovement::create([
            'warehouse_inventory_item_id' => $inventory->id,
            'movement_type' => $movementType,
            'quantity_kg' => $quantityKg,
            'unit_cost' => $unitCost,
            'total_cost' => $quantityKg * $unitCost,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'note' => $note,
        ]);
    }
}
