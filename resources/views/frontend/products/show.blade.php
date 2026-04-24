@extends('frontend.layouts.app')

@section('title', $product->title)

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded-4"
                            alt="{{ $product->title }}">
                    @endif
                </div>
                <div class="col-lg-6">
                    <h1>{{ $product->title }}</h1>
                    <p class="lead">{{ $product->short_description }}</p>
                    @if ($product->price)
                        <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                    @endif
                    @if ($product->unit_name || $product->unit_type)
                        <p><strong>Unit:</strong>
                            {{ $product->unit_name ?? ucfirst($product->unit_type) }}
                            @if ($product->unit_type && !$product->unit_name)
                                ({{ ucfirst($product->unit_type) }})
                            @endif
                        </p>
                    @endif
                    @if ($product->weight)
                        <p><strong>Weight:</strong> {{ number_format($product->weight, 2) }}
                            {{ $product->unit_name ?? ucfirst($product->unit_type) }}</p>
                    @endif
                    <div>{!! nl2br(e($product->description)) !!}</div>
                    <form method="POST" action="{{ route('cart.add') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <input type="number" name="quantity" class="form-control" min="1" value="1"
                                    style="width: 90px;">
                            </div>
                            <div class="col-auto d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                                <button type="submit" name="buy_now" value="1" class="btn btn-success">Buy
                                    Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
