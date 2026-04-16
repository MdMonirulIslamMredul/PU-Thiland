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
                    <div>{!! nl2br(e($product->description)) !!}</div>
                </div>
            </div>
        </div>
    </section>
@endsection
