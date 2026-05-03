@extends('frontend.layouts.app')

@section('title', __('site.services.title'))

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="section-title mb-4">{{ __('site.services.heading') }}</h1>
            <div class="row g-4">
                @forelse($services as $service)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="card h-100 p-3">
                            @if ($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top mb-3 rounded"
                                    alt="{{ $service->title }}" style="height: 180px; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light rounded mb-3"
                                    style="height: 180px;">
                                    <i class="bi bi-tools text-secondary" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                            <h5>{{ $service->title }}</h5>
                            <p>{{ $service->short_description }}</p>
                            <a href="{{ route('services.show', $service->slug) }}"
                                class="btn btn-outline-dark btn-sm mt-auto">{{ __('site.services.details') }}</a>
                        </div>
                    </div>
                @empty
                    <p>{{ __('site.services.no_services_found') }}</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $services->links() }}</div>
        </div>
    </section>
@endsection
