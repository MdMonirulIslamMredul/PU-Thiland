@extends('frontend.layouts.app')

@section('title', $about?->title ?? ($setting->about_title ?? 'About Us'))

@section('content')
    @php
        $bannerImage = !empty($about?->banner_image)
            ? asset('storage/' . $about->banner_image)
            : 'https://images.unsplash.com/photo-1497440001374-f26997328c1b?auto=format&fit=crop&w=1800&q=80';
        $image1 = !empty($about?->image1)
            ? asset('storage/' . $about->image1)
            : 'https://images.unsplash.com/photo-1509391366360-2e959784a276?auto=format&fit=crop&w=1200&q=80';
        $image2 = !empty($about?->image2)
            ? asset('storage/' . $about->image2)
            : 'https://images.unsplash.com/photo-1559302504-64aae6ca6b6d?auto=format&fit=crop&w=1200&q=80';
        $keyValues = collect($about?->key_values ?? [])
            ->filter()
            ->values();
    @endphp

    <section class="py-5 text-white"
        style="position: relative; background: url('{{ $bannerImage }}') center/cover no-repeat;">
        <div
            style="position: absolute; inset: 0; background: linear-gradient(110deg, rgba(2, 6, 23, 0.78), rgba(15, 118, 110, 0.48));">
        </div>
        <div class="container position-relative" data-aos="fade-up">
            <h1 class="display-5 fw-bold">{{ $about?->title ?? ($setting->about_title ?? 'About Our Company') }}</h1>
            <p class="lead mb-0" style="max-width: 760px;">
                {!! $about?->page_details ??
                    ($setting->about_content ?? 'Learn more about our company, our values, and what drives our work.') !!}
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="{{ $image1 }}" alt="About Image 1" class="img-fluid rounded-4 shadow-sm">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card p-4 h-100">
                        <h4>Details 1</h4>
                        <div>{!! $about?->details1 ?? '<p>Share your first key narrative here from admin.</p>' !!}</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 align-items-center mt-1">
                <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                    <img src="{{ $image2 }}" alt="About Image 2" class="img-fluid rounded-4 shadow-sm">
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <div class="card p-4 h-100">
                        <h4>Details 2</h4>
                        <div>{!! $about?->details2 ?? '<p>Share your second key narrative here from admin.</p>' !!}</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-md-6" data-aos="fade-up">
                    <div class="card p-4 h-100">
                        <h4>Details 3</h4>
                        <div>{!! $about?->details3 ?? '<p>Share your third key narrative here from admin.</p>' !!}</div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card p-4 h-100">
                        <h4>Details 4</h4>
                        <div>{!! $about?->details4 ?? '<p>Share your fourth key narrative here from admin.</p>' !!}</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-md-4" data-aos="zoom-in">
                    <div class="card p-4 text-center h-100">
                        <h3 class="mb-1">{{ $about?->years_experience ?? 0 }}+</h3>
                        <p class="mb-0 text-secondary">Years Experience</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="80">
                    <div class="card p-4 text-center h-100">
                        <h3 class="mb-1">{{ $about?->establishment_year ?? '-' }}</h3>
                        <p class="mb-0 text-secondary">Established</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="160">
                    <div class="card p-4 h-100">
                        <h5 class="mb-2">Key Values</h5>
                        @if ($keyValues->isNotEmpty())
                            <ul class="mb-0">
                                @foreach ($keyValues as $value)
                                    <li>{{ $value }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mb-0 text-secondary">Add key values from admin panel to show them here.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
