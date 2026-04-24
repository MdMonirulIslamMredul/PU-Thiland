@extends('frontend.layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="section-title">Shopping Cart</h1>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
            </div>

            @if ($items->isEmpty())
                <div class="alert alert-info">Your cart is empty.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if ($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}"
                                                    alt="{{ $item['title'] }}" class="rounded"
                                                    style="width: 72px; height: 72px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $item['title'] }}</h6>
                                                <a href="{{ route('products.show', $item['slug']) }}"
                                                    class="text-muted small">View product</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td style="min-width: 180px;">
                                        <form method="POST" action="{{ route('cart.update') }}"
                                            class="d-flex gap-2 align-items-center">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                            <input type="number" name="quantity" min="1" class="form-control"
                                                style="width: 90px;" value="{{ $item['quantity'] }}">
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.remove') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                    <div>
                        <p class="mb-1">Total items: {{ $items->sum('quantity') }}</p>
                        <p class="mb-1">Total weight: {{ $totalWeight }} Kg</p>
                        <h4 class="mb-0">Total: ${{ $total }}</h4>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">Checkout</a>
                </div>
            @endif
        </div>
    </section>
@endsection
