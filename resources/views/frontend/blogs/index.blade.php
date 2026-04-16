@extends('frontend.layouts.app')

@section('title', 'Blog')

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="section-title mb-4">Latest Blogs</h1>
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
                                    class="btn btn-outline-dark btn-sm mt-auto">Read More</a>
                            </div>
                        </div>
                </div>@empty<p>No blog posts available.</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $blogs->links() }}</div>
        </div>
    </section>
@endsection
