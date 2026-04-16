@extends('frontend.layouts.app')

@section('title', 'Video Gallery')

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="section-title mb-4">Video Gallery</h1>
            <div class="row g-4">
                @forelse($items as $item)
                    <div class="col-md-6" data-aos="fade-up">
                        <div class="card p-3">
                            <h6>{{ $item->title }}</h6>
                            <div class="ratio ratio-16x9 mt-2"><iframe
                                    src="{{ str_replace('watch?v=', 'embed/', $item->video_url) }}" allowfullscreen></iframe>
                            </div>
                        </div>
                </div>@empty<p>No videos yet.</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </section>
@endsection
