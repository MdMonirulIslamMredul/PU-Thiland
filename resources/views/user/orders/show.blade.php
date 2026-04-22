@extends('user.layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="mb-2">Order #{{ $order->id }}</h1>
                    <p class="text-muted">Order placed on {{ $order->created_at->format('Y-m-d H:i') }}.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <h5 class="card-title">Account Menu</h5>
                            <div class="list-group list-group-flush mt-3">
                                <a href="{{ route('dashboard', ['tab' => 'orders']) }}"
                                    class="list-group-item list-group-item-action">Purchase History</a>
                                <a href="{{ route('dashboard', ['tab' => 'profile']) }}"
                                    class="list-group-item list-group-item-action">My Profile</a>
                                <a href="{{ route('dashboard', ['tab' => 'password']) }}"
                                    class="list-group-item list-group-item-action">Change Password</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h4 class="mb-1">Order Details</h4>
                                    <p class="text-muted mb-0">Status: {{ ucfirst($order->status) }}</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-secondary">Total:
                                        ${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>

                            @if ($order->note)
                                <div class="mb-3">
                                    <h6>Note</h6>
                                    <p>{{ $order->note }}</p>
                                </div>
                            @endif

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="card border p-3 h-100">
                                        <h6>Payment Method</h6>
                                        <p class="mb-0">
                                            {{ $order->payment_method ? ucfirst(str_replace('_', ' ', $order->payment_method)) : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border p-3 h-100">
                                        <h6>Recharge Used</h6>
                                        <p class="mb-0">৳ {{ number_format($order->recharge_used_amount ?? 0, 2) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border p-3 h-100">
                                        <h6>Payable Amount</h6>
                                        <p class="mb-0">
                                            ${{ number_format(max(0, ($order->total_amount ?? 0) - ($order->recharge_used_amount ?? 0)), 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
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

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('dashboard', ['tab' => 'orders']) }}"
                                    class="btn btn-outline-secondary">Back to Orders</a>
                                <form action="{{ route('cart.reorder') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <button type="submit" class="btn btn-success">Reorder</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
