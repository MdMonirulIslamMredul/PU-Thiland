@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card p-4">
                        <h3 class="section-title mb-3">Checkout</h3>
                        <p class="text-muted">Please review your cart and place your order.</p>

                        <div class="mb-4">
                            <h5>Customer</h5>
                            <p class="mb-1">{{ $user->name }}</p>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>

                        <form method="POST" action="{{ route('checkout.place') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Order Note (optional)</label>
                                <textarea name="note" class="form-control" rows="4" placeholder="Add a note for your order..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Place Order</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card p-4">
                        <h5 class="mb-3">Order Summary</h5>
                        @foreach ($items as $item)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item['title'] }}</h6>
                                    <p class="mb-1 text-muted">Qty: {{ $item['quantity'] }}</p>
                                    <p class="mb-0">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach

                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Total</strong>
                            <span>${{ $total }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
