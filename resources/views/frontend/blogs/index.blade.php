@extends('frontend.layouts.app')

@section('title', __('site.blogs.title'))

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="section-title mb-4">{{ __('site.blogs.heading') }}</h1>
            <div class="row g-4">
                @forelse($blogs as $blog)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="card h-100">
                            @if ($blog->image)
                                <img src="{{ asset('storage/' . $blog->image) }}" class="card-img-top"
                                    alt="{{ $blog->title }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5>{{ $blog->title }}</h5>
                                <p>{{ $blog->excerpt }}</p><a href="{{ route('blogs.show', $blog->slug) }}"
                                    class="btn btn-outline-dark btn-sm mt-auto">{{ __('site.blogs.read_more') }}</a>
                            </div>
                        </div>
                </div>@empty<p>{{ __('site.blogs.no_posts') }}</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $blogs->links() }}</div>
        </div>
    </section>
@endsection
