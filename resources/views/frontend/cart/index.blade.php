@extends('frontend.layouts.app')

@section('title', __('site.cart.title'))

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="section-title">{{ __('site.cart.heading') }}</h1>
                <a href="{{ route('products.index') }}"
                    class="btn btn-outline-secondary">{{ __('site.cart.continue_shopping') }}</a>
            </div>

            @if ($items->isEmpty())
                <div class="alert alert-info">{{ __('site.cart.your_cart_empty') }}</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('site.cart.product') }}</th>
                                <th>{{ __('site.cart.price') }}</th>
                                <th>{{ __('site.cart.quantity') }}</th>
                                <th>{{ __('site.cart.subtotal') }}</th>
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
                                                    class="text-muted small">{{ __('site.cart.view_product') }}</a>
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
                                            <button type="submit"
                                                class="btn btn-sm btn-primary">{{ __('site.cart.update') }}</button>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.remove') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger">{{ __('site.cart.remove') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                    <div>
                        <p class="mb-1">{{ __('site.cart.total_items') }}: {{ $items->sum('quantity') }}</p>
                        <p class="mb-1">{{ __('site.cart.total_weight') }}: {{ $totalWeight }} Kg</p>
                        <h4 class="mb-0">{{ __('site.cart.total') }}: ${{ $total }}</h4>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">{{ __('site.cart.checkout') }}</a>
                </div>
            @endif
        </div>
    </section>
@endsection
