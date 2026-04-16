@extends('frontend.layouts.app')

@section('title', 'Products')

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="section-title mb-4">Products</h1>
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="card h-100">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                    alt="{{ $product->title }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light rounded mb-3"
                                    style="height: 180px;">
                                    <i class="bi bi-box-seam text-secondary" style="font-size: 4rem;"></i>
                                </div>
                                {{-- <span class="text-muted">No image</span> --}}
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5>{{ $product->title }}</h5>
                                <p class="text-secondary">{{ $product->short_description }}</p>
                                <a href="{{ route('products.show', $product->slug) }}"
                                    class="btn btn-outline-dark btn-sm mt-auto">Details</a>
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
