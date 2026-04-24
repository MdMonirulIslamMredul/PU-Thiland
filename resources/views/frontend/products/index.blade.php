@extends('frontend.layouts.app')

@section('title', 'Products')

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="section-title mb-4">Products</h1>
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="card h-100 shadow-sm border-0 overflow-hidden">
                            <div class="position-relative" style="min-height: 240px;">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="card-img-top h-100 w-100 object-fit-cover" alt="{{ $product->title }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light h-100">
                                        <i class="bi bi-box-seam text-secondary" style="font-size: 4rem;"></i>
                                    </div>
                                @endif
                                @if ($product->price)
                                    <span class="badge bg-dark text-white position-absolute top-0 end-0 m-3 px-3 py-2 fs-6">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                @else
                                    <span
                                        class="badge bg-secondary text-white position-absolute top-0 end-0 m-3 px-3 py-2 fs-6">
                                        Contact for price
                                    </span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-2">{{ $product->title }}</h5>
                                <p class="text-muted mb-3">{{ $product->short_description }}</p>
                                @if ($product->unit_name || $product->unit_type)
                                    <p class="text-secondary small mb-2">
                                        Unit: {{ $product->unit_name ?? ucfirst($product->unit_type) }}
                                    </p>
                                @endif
                                @if ($product->weight)
                                    <p class="text-secondary small mb-2">
                                        Weight: {{ number_format($product->weight, 2) }} Kg
                                    </p>
                                @endif
                                <div class="mb-3">
                                    @if ($product->open_price && $product->price && $product->open_price > $product->price)
                                        <div class="small text-muted text-decoration-line-through">
                                            ${{ number_format($product->open_price, 2) }}
                                        </div>
                                        <div class="fs-5 fw-bold text-dark">
                                            ${{ number_format($product->price, 2) }}
                                        </div>
                                    @elseif ($product->price)
                                        <div class="fs-5 fw-bold text-dark">
                                            ${{ number_format($product->price, 2) }}
                                        </div>
                                    @else
                                        <div class="fs-6 text-secondary">Price available on request</div>
                                    @endif
                                </div>
                                <div class="mt-auto d-grid gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}"
                                        class="btn btn-outline-dark btn-sm">View details</a>
                                    <form method="POST" action="{{ route('cart.add') }}" class="d-flex gap-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Add to
                                            Cart</button>
                                        <button type="submit" name="buy_now" value="1"
                                            class="btn btn-success btn-sm flex-grow-1">Buy Now</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No products found.</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $products->links() }}</div>
        </div>
    </section>
@endsection
