@extends('frontend.layouts.app')

@section('title', $service->title)

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    @if ($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" class="img-fluid rounded-4"
                            alt="{{ $service->title }}">
                    @endif
                </div>
                <div class="col-lg-6">
                    <h1>{{ $service->title }}</h1>
                    <p class="lead">{{ $service->short_description }}</p>
                    <div>{!! nl2br(e($service->description)) !!}</div>
                </div>
            </div>
        </div>
    </section>
@endsection
