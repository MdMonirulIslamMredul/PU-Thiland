@extends('admin.layouts.app')

@section('title', 'Edit Picking Order')

@section('content')
    <div class="mb-4">
        <h4>Edit Picking Order #{{ $order->id }}</h4>
    </div>

    <div class="card p-4">
        <form action="{{ route('admin.picking-orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Order Reference</label>
                <input type="text" class="form-control" value="{{ $order->order?->order_number ?? 'N/A' }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" {{ old('status', $order->status) == $value ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Assigned To</label>
                <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                    <option value="">Unassigned</option>
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->id }}"
                            {{ old('assigned_to', $order->assigned_to) == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }}</option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Note</label>
                <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="4">{{ old('note', $order->note) }}</textarea>
                @error('note')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.picking-orders.index') }}" class="btn btn-secondary">Cancel</a>
                <button class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
@endsection
