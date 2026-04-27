@extends('admin.layouts.app')
@section('title', 'Orders')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Orders</h4>
        <a href="{{ route('admin.orders.export.pdf', request()->except('page')) }}"
            class="btn btn-sm btn-outline-secondary">Export PDF</a>
    </div>
    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Order Status</label>
                <select name="status" class="form-select">
                    <option value="">All order statuses</option>
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Payment Status</label>
                <select name="payment_status" class="form-select">
                    <option value="">All payment statuses</option>
                    @foreach ($paymentStatuses as $key => $label)
                        <option value="{{ $key }}" {{ $payment_status === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Start date</label>
                <input type="date" name="start_date" value="{{ $start_date }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">End date</label>
                <input type="date" name="end_date" value="{{ $end_date }}" class="form-control">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Payment Status</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                {{ $order->user->name }}
                                <div class="small text-muted mt-1">
                                    {{ $order->user->email }}@if ($order->user->phone)
                                        | {{ $order->user->phone }}
                                    @endif
                                </div>
                            </td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($order->payment_status ?? 'unpaid') }}</span>

                                @if ($order->payment_status === 'partial')
                                    <div class="small mt-1">
                                        <span class="text-success">Paid:
                                            ৳{{ number_format($order->paid_amount ?? 0, 2) }}</span>
                                        <span class="text-danger ms-3">Due:
                                            ৳{{ number_format($order->due_amount ?? 0, 2) }}</span>
                                    </div>
                                @elseif($order->payment_status === 'unpaid')
                                    <div class="small mt-1 text-danger">Due:
                                        ৳{{ number_format($order->due_amount ?? $order->total_amount, 2) }}</div>
                                @endif
                            </td>
                            <td><span
                                    class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'confirmed' ? 'info' : ($order->status === 'successful' ? 'success' : 'danger')) }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="btn btn-sm btn-outline-primary">View</a>
                                @if ($order->status === 'confirmed' && $order->warehousePickingOrder)
                                    <span class="badge bg-info text-dark">Picking created</span>
                                @endif
                                <a href="{{ route('admin.orders.edit', $order) }}"
                                    class="btn btn-sm btn-outline-secondary">Update</a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    @endsection
