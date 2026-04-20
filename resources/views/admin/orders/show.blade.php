@extends('admin.layouts.app')
@section('title', 'Order #' . $order->id)
@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h4>Order #{{ $order->id }}</h4>
            <p class="mb-1">Placed by {{ $order->user->name }} ({{ $order->user->email }})</p>
            <p class="text-muted mb-0">Status: <strong>{{ ucfirst($order->status) }}</strong></p>
        </div>
        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">Update Status</a>
    </div>

    <div class="card p-3 mb-3">
        <h5>Order Items</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>${{ number_format($item->product_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-end">
            <h5>Total: ${{ number_format($order->total_amount, 2) }}</h5>
        </div>
    </div>

    @if ($order->note)
        <div class="card p-3">
            <h5>Order Note</h5>
            <p>{{ $order->note }}</p>
        </div>
    @endif
@endsection
