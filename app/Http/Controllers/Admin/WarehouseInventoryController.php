<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WarehouseInventoryItem;
use App\Services\WarehouseInventoryService;
use Illuminate\Http\Request;

class WarehouseInventoryController extends Controller
{
    public function index()
    {
        return view('admin.warehouse.inventory', [
            'inventoryItems' => WarehouseInventoryItem::with('product')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.warehouse.inventory.create', [
            'products' => Product::where('status', true)->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request, WarehouseInventoryService $inventoryService)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'grade' => ['nullable', 'string', 'max:255'],
            'specification' => ['nullable', 'string', 'max:255'],
            'quantity_kg' => ['required', 'numeric', 'min:0.001'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $inventory = $inventoryService->receiveStock(
            $product,
            (float) $data['quantity_kg'],
            (float) $data['unit_cost'],
            'manual_adjustment',
            null,
            $data['note'] ?? 'Warehouse stock created'
        );

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory item created and stock received.');
    }

    public function edit(WarehouseInventoryItem $inventory)
    {
        return view('admin.warehouse.inventory.edit', [
            'inventoryItem' => $inventory->load('product'),
            'products' => Product::where('status', true)->orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, WarehouseInventoryItem $inventory, WarehouseInventoryService $inventoryService)
    {
        $data = $request->validate([
            'grade' => ['nullable', 'string', 'max:255'],
            'specification' => ['nullable', 'string', 'max:255'],
            'quantity_kg' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ]);

        $delta = (float) $data['quantity_kg'] - (float) $inventory->quantity_kg;

        if ($delta > 0) {
            $inventoryService->receiveStock(
                $inventory->product,
                $delta,
                (float) $data['unit_cost'],
                'manual_adjustment',
                $inventory->id,
                $data['note'] ?? 'Warehouse stock adjustment received'
            );
        } elseif ($delta < 0) {
            $inventoryService->stockOut(
                $inventory,
                abs($delta),
                'manual_adjustment',
                $inventory->id,
                $data['note'] ?? 'Warehouse stock adjustment removed'
            );
        }

        $inventory->update([
            'grade' => $data['grade'],
            'specification' => $data['specification'],
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(WarehouseInventoryItem $inventory)
    {
        $inventory->delete();

        return back()->with('success', 'Inventory item deleted successfully.');
    }
}
