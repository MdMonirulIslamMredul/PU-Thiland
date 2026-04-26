@extends('admin.layouts.app')

@section('title', 'Warehouse Picking Orders')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Warehouse Picking Orders</h4>
        <a href="{{ route('admin.warehouse.dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <div class="card p-3 mb-4">
        <h5>Create Picking Order</h5>
        @if (empty($availableOrders) || $availableOrders->isEmpty())
            <div class="alert alert-secondary mb-0">
                No confirmed orders are available for warehouse picking.
            </div>
        @else
            <div class="row g-3">
                @foreach ($availableOrders as $order)
                    <div class="col-md-6">
                        <div class="card p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>Order #{{ $order->id }}</strong>
                                    <div class="small text-muted">{{ $order->user->name }} |
                                        {{ $order->created_at->format('Y-m-d') }}</div>
                                </div>
                                <span class="badge bg-info text-dark">Confirmed</span>
                            </div>
                            <p class="mb-2">Total: ${{ number_format($order->total_amount, 2) }}</p>
                            <form action="{{ route('admin.warehouse.create-picking', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">Create Picking</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="card p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Weight (kg)</th>
                    <th>Started</th>
                    <th>Completed</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pickingOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>#{{ $order->order_id }}</td>
                        <td>{{ $order->order?->user?->name ?? 'Unknown' }}</td>
                        <td>
                            @php
                                $badge = match ($order->status) {
                                    'completed' => 'success',
                                    'picking' => 'primary',
                                    default => 'warning text-dark',
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }} text-uppercase">{{ $order->status }}</span>
                        </td>
                        <td>{{ number_format($order->total_weight_kg, 3) }}</td>
                        <td>{{ optional($order->started_at)->format('Y-m-d') ?? '—' }}</td>
                        <td>{{ optional($order->completed_at)->format('Y-m-d') ?? '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.picking-orders.edit', $order) }}"
                                class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('admin.picking-orders.show', $order) }}"
                                class="btn btn-sm btn-outline-secondary">View</a>
                            <form action="{{ route('admin.picking-orders.destroy', $order) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this picking order?')">Delete</button>
                            </form>

                            @if ($order->status === 'pending')
                                <form action="{{ route('admin.warehouse.picking-orders.start', $order) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary">Start</button>
                                </form>
                            @endif

                            @if ($order->status === 'picking')
                                @if ($order->can_complete ?? false)
                                    <form action="{{ route('admin.warehouse.picking-orders.complete', $order) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success">Complete</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                        title="{{ $order->completion_error ?? 'Cannot complete this order.' }}">Complete</button>
                                    @if (!empty($order->completion_error))
                                        <div class="small text-danger mt-1">{{ $order->completion_error }}</div>
                                    @endif
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No picking orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
