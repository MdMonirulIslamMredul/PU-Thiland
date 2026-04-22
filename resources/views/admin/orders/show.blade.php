@extends('admin.layouts.app')
@section('title', 'Order #' . $order->id)
@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h4>Order #{{ $order->id }}</h4>
            <p class="mb-1">Placed by {{ $order->user->name }} ({{ $order->user->email }})</p>
            @if ($order->user->phone)
                <p class="mb-1">Phone: {{ $order->user->phone }}</p>
            @endif
            <p class="text-muted mb-0">Status: <strong>{{ ucfirst($order->status) }}</strong></p>
        </div>
        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">Update Status</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card p-3 h-100">
                <h5>Delivery Information</h5>
                <p class="mb-1"><strong>Recipient:</strong> {{ $order->delivery_recipient_name ?? $order->user->name }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $order->delivery_phone ?? $order->user->phone }}</p>
                <p class="mb-0"><strong>Address:</strong></p>
                <p class="text-muted">{{ $order->delivery_address ?? 'N/A' }}</p>
                @if ($order->userAddress)
                    <p class="small text-muted mt-2">Saved Address: {{ $order->userAddress->label ?? 'Default' }}</p>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-3 h-100">
                <h5>Order Summary</h5>
                <p class="mb-2"><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                <p class="mb-2"><strong>Items:</strong> {{ $order->items->sum('quantity') }}</p>
                <p class="mb-2"><strong>VIP Level:</strong> {{ $order->vip_level ? ucfirst($order->vip_level) : 'None' }}
                </p>
                <p class="mb-2"><strong>VIP Discount:</strong> ${{ number_format($order->vip_discount_amount ?? 0, 2) }}
                </p>
                <p class="mb-0"><strong>Order Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-3 h-100">
                <h5>Payment Details</h5>
                <p class="mb-2"><strong>Method:</strong>
                    {{ $order->payment_method ? ucfirst(str_replace('_', ' ', $order->payment_method)) : 'N/A' }}</p>
                <p class="mb-2"><strong>Total Weight:</strong> {{ number_format($order->total_weight ?? 0, 2) }} kg</p>
                <p class="mb-2"><strong>Recharge Used:</strong> ৳
                    {{ number_format($order->recharge_used_amount ?? 0, 2) }}</p>
                <p class="mb-2"><strong>Payable Amount:</strong>
                    ${{ number_format(max(0, ($order->total_amount ?? 0) - ($order->recharge_used_amount ?? 0)), 2) }}</p>
                @if ($order->payment_method === 'wallet')
                    <p class="text-muted small">Amount deducted from customer's recharge balance.</p>
                @else
                    <p class="text-muted small">Payment receipt is required for gateway payments.</p>
                @endif
                @if ($order->payment_receipt)
                    <div class="mt-3">
                        <h6 class="mb-2">Payment Receipt</h6>
                        <a href="{{ asset('storage/' . $order->payment_receipt) }}" target="_blank">
                            <img src="{{ asset('storage/' . $order->payment_receipt) }}" alt="Payment Receipt"
                                class="img-fluid rounded" style="max-height: 250px; object-fit: contain;" />
                        </a>
                        <p class="small text-muted mt-2">Click image to open full size.</p>
                    </div>
                @endif
            </div>
        </div>
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
