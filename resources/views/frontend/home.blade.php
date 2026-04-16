@extends('frontend.layouts.app')

@section('title', $setting->meta_title ?? 'Home')

@push('styles')
    <style>
        .hero-slider .carousel-item {
            min-height: 82vh;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        @media (min-width: 992px) {
            .hero-slider .carousel-item {
                min-height: 100vh;
            }
        }

        .hero-slider .carousel-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(15, 23, 42, 0.75), rgba(15, 118, 110, 0.45));
        }

        .hero-slider .hero-content {
            position: relative;
            z-index: 2;
            color: #fff;
        }

        .hero-slider .hero-slide-inner {
            padding-top: 6rem !important;
            padding-bottom: 4rem;
        }

        @media (min-width: 992px) {
            .hero-slider .hero-slide-inner {
                padding-top: clamp(6rem, 14vh, 11rem) !important;
            }

            .hero-slider .hero-content {
                margin-top: 0;
            }
        }

        .hero-slider .btn-outline-light {
            --bs-btn-color: #ffffffb6;
            --bs-btn-border-color: rgba(255, 255, 255, 0.85);
            --bs-btn-hover-color: #0f172a;
            --bs-btn-hover-bg: #ffffff47;
            --bs-btn-hover-border-color: #ffffff;
            --bs-btn-active-color: #0f172a;
            --bs-btn-active-bg: #ffffff;
            --bs-btn-active-border-color: #ffffff;
            color: #ffffff;
        }

        .section-carousel .carousel-inner {
            overflow: hidden;
        }

        .section-carousel {
            overflow-x: clip;
        }

        .section-carousel .carousel-item {
            padding: 0 3rem;
        }

        .section-carousel .carousel-control-prev,
        .section-carousel .carousel-control-next {
            width: 3rem;
        }

        .section-carousel .carousel-control-prev-icon,
        .section-carousel .carousel-control-next-icon {
            background-color: rgba(15, 23, 42, 0.9);
            background-size: 1rem 1rem;
            border-radius: 999px;
            width: 2.5rem;
            height: 2.5rem;
        }

        .section-carousel .card {
            min-height: 100%;
        }

        .counter-section {
            position: relative;
        }

        .counter-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 1.8rem 1.4rem;
            text-align: center;
            box-shadow: 0 12px 30px rgba(2, 6, 23, 0.08);
            height: 100%;
        }

        .counter-icon {
            width: 72px;
            height: 72px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.12), rgba(245, 158, 11, 0.14));
            color: var(--brand);
        }

        .counter-number {
            font-size: clamp(2.2rem, 4vw, 3.4rem);
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.4rem;
            color: var(--ink);
        }

        .counter-title {
            margin-bottom: 0;
            color: #475569;
            font-weight: 600;
        }

        .testimonial-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(2, 6, 23, 0.08);
            padding: 1.5rem;
            height: 100%;
        }

        .testimonial-avatar {
            width: 64px;
            height: 64px;
            border-radius: 999px;
            object-fit: cover;
            border: 3px solid rgba(15, 118, 110, 0.15);
            background: #f8fafc;
        }

        .testimonial-stars {
            color: #f59e0b;
            letter-spacing: 1px;
        }

        .testimonial-message {
            color: #475569;
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        .testimonial-name {
            margin-bottom: 0.15rem;
            font-weight: 700;
            color: var(--ink);
        }

        .testimonial-role {
            margin-bottom: 0;
            color: #64748b;
            font-size: 0.95rem;
        }

        .faq-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(2, 6, 23, 0.08);
        }

        .faq-accordion .accordion-item {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            background: #f8fafc;
        }

        .faq-accordion .accordion-item+.accordion-item {
            margin-top: 0.85rem;
        }

        .faq-accordion .accordion-button {
            font-weight: 600;
            color: var(--ink);
            background: #f8fafc;
            box-shadow: none;
        }

        .faq-accordion .accordion-button:not(.collapsed) {
            color: var(--brand);
            background: #eefbf9;
        }

        .faq-accordion .accordion-body {
            color: #475569;
            line-height: 1.75;
        }

        @media (max-width: 991px) {
            .section-carousel .carousel-item {
                padding: 0 2rem;
            }
        }

        @media (max-width: 575px) {
            .section-carousel .carousel-item {
                padding: 0 1rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $fallbackSlides = [
            [
                'title' => 'Future-Ready Solar Energy Solutions',
                'subtitle' =>
                    'We design, install, and maintain high-performance solar systems for homes and businesses.',
                'image' =>
                    'https://images.unsplash.com/photo-1509391366360-2e959784a276?auto=format&fit=crop&w=1600&q=80',
            ],
            [
                'title' => 'Smart Solar Installations For Every Roof',
                'subtitle' =>
                    'From residential rooftops to large commercial sites, we deliver engineered systems built to last.',
                'image' =>
                    'https://images.unsplash.com/photo-1592833159155-c62df1b65634?auto=format&fit=crop&w=1600&q=80',
            ],
            [
                'title' => 'Lower Bills, Cleaner Future',
                'subtitle' =>
                    'Switch to reliable renewable power and reduce electricity costs with full support from our experts.',
                'image' =>
                    'https://images.unsplash.com/photo-1611365892117-00e8f8f7bc9d?auto=format&fit=crop&w=1600&q=80',
            ],
        ];

        $heroSlides = collect($heroSlides ?? [])
            ->values()
            ->map(function ($slide, $index) use ($fallbackSlides) {
                $fallback = $fallbackSlides[$index] ?? end($fallbackSlides);

                return [
                    'title' => $slide->title ?: $fallback['title'],
                    'subtitle' => $slide->subtitle ?: $fallback['subtitle'],
                    'image' => !empty($slide->image) ? asset('storage/' . $slide->image) : $fallback['image'],
                ];
            });

        if ($heroSlides->isEmpty()) {
            $heroSlides = collect($fallbackSlides);
        }

        $serviceSlides = $services->chunk(3)->values();
        $productSlides = $products->chunk(3)->values();
        $activeCounters = collect($counters ?? []);
        $testimonialSlides = collect($testimonials ?? [])
            ->chunk(3)
            ->values();
        $faqItems = collect($faqs ?? []);
    @endphp

    <section class="hero-slider" data-aos="fade-up">
        <div id="homepageHeroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($heroSlides as $index => $slide)
                    <button type="button" data-bs-target="#homepageHeroSlider" data-bs-slide-to="{{ $index }}"
                        class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach ($heroSlides as $index => $slide)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}"
                        style="background-image: url('{{ $slide['image'] }}');">
                        <div class="container h-100 d-flex align-items-start hero-slide-inner">
                            <div class="hero-content col-lg-8">
                                <h1 class="display-4 fw-bold">{{ $slide['title'] }}</h1>
                                <p class="lead mb-4">{{ $slide['subtitle'] }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ $setting->cta_button_link ?? route('contact.index') }}"
                                        class="btn btn-warning btn-lg">{{ $setting->cta_button_text ?? 'Get Consultation' }}</a>
                                    <a href="{{ route('services.index') }}" class="btn btn-outline-light btn-lg">Our
                                        Services</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#homepageHeroSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homepageHeroSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>



    <section class="section-modern section-modern-light" id="about-section">
        @php
            $img1 = !empty($about?->image1)
                ? asset('storage/' . $about->image1)
                : 'https://images.unsplash.com/photo-1595273670150-bd0c3c392e46?auto=format&fit=crop&w=1000&q=80';
            $img2 = !empty($about?->image2)
                ? asset('storage/' . $about->image2)
                : 'https://images.unsplash.com/photo-1497436072909-60f360e1d4b1?auto=format&fit=crop&w=1200&q=80';

            $defaultValues = ['Craftsmanship', 'Innovation', 'Sustainability', 'Customer-Centric'];
            $keyValues = collect($about?->key_values ?? [])
                ->filter()
                ->values();
            if ($keyValues->isEmpty()) {
                $keyValues = collect($defaultValues);
            }

            $splitAt = (int) ceil($keyValues->count() / 2);
            $leftValues = $keyValues->slice(0, $splitAt);
            $rightValues = $keyValues->slice($splitAt);
        @endphp

        <div class="container">
            <div class="section-header-modern" data-aos="fade-up" data-aos-duration="1200">
                {{-- <span class="section-subtitle-modern">About Us</span> --}}
                <h2 class="section-title-modern">{{ $about?->title ?? 'About Us' }}</h2>
            </div>

            <div class="row gx-5 align-items-center about-row">
                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
                    <div class="about-image-wrap" style="position:relative;">
                        <img src="{{ $img1 }}" alt="About image" class="about-image">

                        <div class="experience-badge">
                            <div class="exp-number">{{ $about?->years_experience ?? 10 }} +
                            </div>
                            <div class="exp-text">Years Experience</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="300">
                    <div class="modern-card" style="padding: 2.5rem 2rem 1rem;">
                        <span class="section-subtitle-modern">About Us</span>
                        <h2 class="section-title-modern about-title">{{ $about?->title ?? 'Company Overview' }}</h2>
                        <div class="content-description-modern about-text" style="font-size: 1.05rem; line-height: 1.8;">
                            {!! $about?->page_details ??
                                '<p>We build reliable renewable energy systems with long-term performance in mind.</p>' !!}
                        </div>

                        <div class="key-values-card" style="background-image: url('{{ $img2 }}')">
                            <div class="kv-overlay">
                                <h4 class="kv-title">Our Key Values</h4>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="kv-items">
                                            @foreach ($leftValues as $value)
                                                <li><i class="bi bi-arrow-right-short"></i> {{ $value }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="kv-items">
                                            @foreach ($rightValues as $value)
                                                <li><i class="bi bi-arrow-right-short"></i> {{ $value }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* About section custom styles */
        .about-image {
            width: 100%;
            height: auto;
            border-radius: 14px;
            display: block;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .experience-badge {
            position: absolute;
            bottom: -28px;
            left: 28px;
            background: rgba(123, 27, 27, 0.95);
            color: #fff;
            padding: 18px 26px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            min-width: 190px;
        }

        .experience-badge .exp-number {
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
        }

        .experience-badge .exp-text {
            font-size: 14px;
            opacity: 0.95;
            margin-top: 6px;
        }

        .about-title {
            color: #7b1b1b;
            font-size: 42px;
            margin-top: 8px;
            margin-bottom: 18px;
        }

        .key-values-card {
            margin-top: 28px;
            border-radius: 12px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            min-height: 170px;
            position: relative;
        }

        .key-values-card .kv-overlay {
            position: relative;
            z-index: 2;
            color: #fff;
            padding: 18px 22px;
        }

        .key-values-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.25));
        }

        .kv-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #fff;
        }

        .kv-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .kv-items li {
            color: #fff;
            padding: 6px 0;
            font-size: 15px;
        }

        .kv-items li i {
            margin-right: 8px;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .experience-badge {
                position: relative;
                bottom: 0;
                left: 0;
                margin-top: 18px;
            }

            .about-title {
                font-size: 32px;
            }
        }
    </style>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7" data-aos="fade-right">
                    <h2 class="section-title">Why Choose Us</h2>
                    <p class="text-secondary mb-0">Expert solar planning, precision installation, and long-term maintenance
                        to keep your power generation stable year-round.</p>
                </div>
                <div class="col-lg-5" data-aos="zoom-in">
                    <div class="card p-4">
                        <ul class="mb-0">
                            <li>Expert engineers and certified installers</li>
                            <li>End-to-end project management</li>
                            <li>Long-term maintenance support</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @if ($activeCounters->isNotEmpty())
        <section class="py-5 counter-section" data-aos="fade-up">
            <div class="container">
                <div class="text-center mb-4" data-aos="fade-up">
                    <span class="section-subtitle-modern">Our Success</span>
                    <h2 class="section-title-modern">We have a proven track record of success.</h2>
                </div>

                <div class="row g-4 justify-content-center">
                    @foreach ($activeCounters as $counter)
                        @php
                            $counterIconIsFile = $counter->icon && str_contains($counter->icon, '/');
                        @endphp
                        <div class="col-12 col-sm-6 col-lg-3" data-aos="zoom-in"
                            data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="counter-card">
                                <div class="counter-icon">
                                    @if ($counter->icon)
                                        @if ($counterIconIsFile)
                                            <img src="{{ asset('storage/' . $counter->icon) }}"
                                                alt="{{ $counter->title }}"
                                                style="width: 38px; height: 38px; object-fit: cover; border-radius: 10px;">
                                        @else
                                            <i class="bi {{ $counter->icon }}" style="font-size: 2rem;"></i>
                                        @endif
                                    @else
                                        <i class="bi bi-tools" style="font-size: 2rem;"></i>
                                    @endif
                                </div>
                                <div class="counter-number" data-counter-value="{{ $counter->value }}">0+</div>
                                <p class="counter-title">{{ $counter->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif



    <section class="py-5">
        <div class="container">
            <h2 class="section-title mb-4" data-aos="fade-up">Featured Services</h2>
            @if ($serviceSlides->isNotEmpty())
                <div id="featuredServicesCarousel" class="carousel slide section-carousel" data-bs-ride="carousel"
                    data-bs-interval="5000">
                    <div class="carousel-inner">
                        @foreach ($serviceSlides as $slideIndex => $serviceChunk)
                            <div class="carousel-item {{ $slideIndex === 0 ? 'active' : '' }}">
                                <div class="row g-4 justify-content-center">
                                    @foreach ($serviceChunk as $service)
                                        <div class="col-md-6 col-lg-4 d-flex" data-aos="fade-up">
                                            <div class="card h-100 p-3 w-100">
                                                @if ($service->image)
                                                    <img src="{{ asset('storage/' . $service->image) }}"
                                                        class="card-img-top mb-3 rounded" alt="{{ $service->title }}"
                                                        style="height: 180px; object-fit: cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-light rounded mb-3"
                                                        style="height: 180px;">
                                                        <i class="bi bi-tools text-secondary"
                                                            style="font-size: 4rem;"></i>
                                                    </div>
                                                @endif
                                                <h5>{{ $service->title }}</h5>
                                                <p class="text-secondary">{{ $service->short_description }}</p>
                                                <a href="{{ route('services.show', $service->slug) }}"
                                                    class="btn btn-outline-dark btn-sm mt-auto">View Details</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($serviceSlides->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#featuredServicesCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#featuredServicesCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            @else
                <div class="alert alert-light border mb-0">No services found.</div>
            @endif
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="section-title mb-4" data-aos="fade-up">Featured Products</h2>
            @if ($productSlides->isNotEmpty())
                <div id="featuredProductsCarousel" class="carousel slide section-carousel" data-bs-ride="carousel"
                    data-bs-interval="5000">
                    <div class="carousel-inner">
                        @foreach ($productSlides as $slideIndex => $productChunk)
                            <div class="carousel-item {{ $slideIndex === 0 ? 'active' : '' }}">
                                <div class="row g-4 justify-content-center">
                                    @foreach ($productChunk as $product)
                                        <div class="col-sm-6 col-lg-4 d-flex" data-aos="zoom-in">
                                            <div class="card h-100 w-100">
                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        class="card-img-top" alt="{{ $product->title }}">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-light rounded mb-3"
                                                        style="height: 180px;">
                                                        <i class="bi bi-box-seam text-secondary"
                                                            style="font-size: 4rem;"></i>
                                                    </div>
                                                    {{-- <span class="text-muted">No image</span> --}}
                                                @endif
                                                <div class="card-body d-flex flex-column">
                                                    <h6>{{ $product->title }}</h6>
                                                    <p class="small text-secondary">{{ $product->short_description }}</p>
                                                    <a href="{{ route('products.show', $product->slug) }}"
                                                        class="btn btn-outline-dark btn-sm mt-auto">Explore</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($productSlides->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#featuredProductsCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#featuredProductsCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            @else
                <div class="alert alert-light border mb-0">No products found.</div>
            @endif
        </div>
    </section>

    @if ($testimonialSlides->isNotEmpty())
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-4" data-aos="fade-up">
                    <span class="section-subtitle-modern">Happy Customers</span>
                    <h2 class="section-title-modern">What clients are expressing about us.</h2>
                </div>

                <div id="testimonialCarousel" class="carousel slide section-carousel" data-bs-ride="carousel"
                    data-bs-interval="6000" data-bs-pause="false">
                    <div class="carousel-indicators">
                        @foreach ($testimonialSlides as $index => $testimonialChunk)
                            <button type="button" data-bs-target="#testimonialCarousel"
                                data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Testimonial slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>

                    <div class="carousel-inner">
                        @foreach ($testimonialSlides as $slideIndex => $testimonialChunk)
                            <div class="carousel-item {{ $slideIndex === 0 ? 'active' : '' }}">
                                <div class="row g-4 justify-content-center">
                                    @foreach ($testimonialChunk as $testimonial)
                                        <div class="col-md-6 col-lg-4 d-flex" data-aos="fade-up">
                                            <div class="testimonial-card w-100">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    @if ($testimonial->image)
                                                        <img src="{{ asset('storage/' . $testimonial->image) }}"
                                                            alt="{{ $testimonial->name }}" class="testimonial-avatar">
                                                    @else
                                                        <div
                                                            class="testimonial-avatar d-flex align-items-center justify-content-center text-secondary">
                                                            <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h5 class="testimonial-name">{{ $testimonial->name }}</h5>
                                                        <p class="testimonial-role">{{ $testimonial->designation }}</p>
                                                    </div>
                                                </div>

                                                <div class="testimonial-stars mb-3"
                                                    aria-label="{{ $testimonial->rating }} out of 5 stars">
                                                    @for ($star = 1; $star <= 5; $star++)
                                                        <i
                                                            class="bi {{ $star <= $testimonial->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                    @endfor
                                                </div>

                                                <p class="testimonial-message mb-0">{{ $testimonial->message }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($testimonialSlides->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if ($faqItems->isNotEmpty())
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-4" data-aos="fade-up">
                    <span class="section-subtitle-modern">FAQ</span>
                    <h2 class="section-title-modern">Questions people ask before getting started.</h2>
                </div>

                <div class="faq-card p-3 p-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="accordion faq-accordion" id="homepageFaqAccordion">
                        @foreach ($faqItems as $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeading{{ $faq->id }}">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $faq->id }}"
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                        aria-controls="faqCollapse{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="faqCollapse{{ $faq->id }}"
                                    class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                    aria-labelledby="faqHeading{{ $faq->id }}"
                                    data-bs-parent="#homepageFaqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="py-5">
        <div class="container" data-aos="fade-up">
            <div class="card p-4 p-lg-5 text-center">
                <h3>{{ $setting->cta_title ?? 'Ready to switch to solar?' }}</h3>
                <p class="text-secondary">
                    {{ $setting->cta_text ?? 'Talk to our experts and get a customized clean-energy plan.' }}</p>
                <a href="{{ route('contact.index') }}" class="btn btn-dark">Contact Us</a>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            (function() {
                const counterSection = document.querySelector('.counter-section');

                if (!counterSection) {
                    return;
                }

                const counterElements = counterSection.querySelectorAll('[data-counter-value]');

                const animateCounter = (element) => {
                    if (element.dataset.counted === 'true') {
                        return;
                    }

                    const endValue = parseInt(element.dataset.counterValue || '0', 10);
                    const duration = 1500;
                    const startTime = performance.now();

                    const step = (now) => {
                        const progress = Math.min((now - startTime) / duration, 1);
                        const currentValue = Math.floor(progress * endValue);

                        element.textContent = `${currentValue.toLocaleString()}+`;

                        if (progress < 1) {
                            requestAnimationFrame(step);
                        } else {
                            element.textContent = `${endValue.toLocaleString()}+`;
                            element.dataset.counted = 'true';
                        }
                    };

                    requestAnimationFrame(step);
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            counterElements.forEach(animateCounter);
                            observer.disconnect();
                        }
                    });
                }, {
                    threshold: 0.35,
                });

                observer.observe(counterSection);
            })();
        </script>
    @endpush
@endsection
