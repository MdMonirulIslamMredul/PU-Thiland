@extends('admin.layouts.app')

@section('title', 'Picking Order Details')

@section('content')
    <div class="mb-4">
        <h4>Picking Order #{{ $order->id }}</h4>
        <a href="{{ route('admin.picking-orders.index') }}" class="btn btn-outline-secondary">Back to Picking Orders</a>
    </div>

    <div class="card p-4 mb-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>Order Reference</strong>
                <div>#{{ $order->order_id }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Customer</strong>
                <div>{{ $order->order?->user?->name ?? 'Unknown' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Status</strong>
                <div
                    class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'picking' ? 'primary' : 'warning text-dark') }} text-uppercase">
                    {{ $order->status }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Assigned To</strong>
                <div>{{ $order->assignedTo?->name ?? 'Unassigned' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Weight</strong>
                <div>{{ number_format($order->total_weight_kg, 3) }} kg</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Started At</strong>
                <div>{{ optional($order->started_at)->format('Y-m-d H:i') ?? '—' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Completed At</strong>
                <div>{{ optional($order->completed_at)->format('Y-m-d H:i') ?? '—' }}</div>
            </div>
            <div class="col-md-12 mb-3">
                <strong>Note</strong>
                <div>{{ $order->note ?? '—' }}</div>
            </div>
        </div>
    </div>
@endsection
