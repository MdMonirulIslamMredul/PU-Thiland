@extends('admin.layouts.app')

@section('title', 'Warehouse Inventory')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Warehouse Inventory</h4>
        <div>
            <a href="{{ route('admin.warehouse.dashboard') }}" class="btn btn-outline-secondary me-2">Back to Dashboard</a>
            <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">Add Inventory Item</a>
        </div>
    </div>

    <div class="card p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Grade</th>
                    <th>Specification</th>
                    <th>Quantity (kg)</th>
                    <th>Avg Cost (৳/kg)</th>
                    <th>Total Value</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventoryItems as $item)
                    <tr>
                        <td>{{ $item->product?->title ?? 'Unknown' }}</td>
                        <td>{{ $item->grade ?? '—' }}</td>
                        <td>{{ $item->specification ?? '—' }}</td>
                        <td>{{ number_format($item->quantity_kg, 3) }}</td>
                        <td>{{ number_format($item->avg_cost, 4) }}</td>
                        <td>৳ {{ number_format($item->quantity_kg * $item->avg_cost, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.inventory.edit', $item) }}"
                                class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this inventory item?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No inventory items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
