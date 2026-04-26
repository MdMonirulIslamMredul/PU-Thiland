@extends('admin.layouts.app')

@section('title', 'Edit Warehouse Inventory Item')

@section('content')
    <div class="mb-4">
        <h4>Edit Warehouse Inventory Item</h4>
    </div>

    <div class="card p-4">
        <form action="{{ route('admin.inventory.update', $inventoryItem) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Product</label>
                <input type="text" class="form-control" value="{{ $inventoryItem->product?->title ?? 'Unknown' }}" readonly>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Grade</label>
                    <input type="text" name="grade" value="{{ old('grade', $inventoryItem->grade) }}"
                        class="form-control @error('grade') is-invalid @enderror">
                    @error('grade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Specification</label>
                    <input type="text" name="specification"
                        value="{{ old('specification', $inventoryItem->specification) }}"
                        class="form-control @error('specification') is-invalid @enderror">
                    @error('specification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quantity (kg)</label>
                    <input type="number" step="0.001" name="quantity_kg"
                        value="{{ old('quantity_kg', $inventoryItem->quantity_kg) }}"
                        class="form-control @error('quantity_kg') is-invalid @enderror" required>
                    @error('quantity_kg')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Unit Cost</label>
                    <input type="number" step="0.01" name="unit_cost"
                        value="{{ old('unit_cost', $inventoryItem->avg_cost) }}"
                        class="form-control @error('unit_cost') is-invalid @enderror" required>
                    @error('unit_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Note</label>
                <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="3">{{ old('note') }}</textarea>
                @error('note')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Cancel</a>
                <button class="btn btn-primary">Update Inventory Item</button>
            </div>
        </form>
    </div>
@endsection
