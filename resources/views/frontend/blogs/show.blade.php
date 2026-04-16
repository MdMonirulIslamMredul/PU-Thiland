@extends('frontend.layouts.app')

@section('title', $blog->meta_title ?? $blog->title)
@section('meta_description', $blog->meta_description ?? '')
@section('meta_keywords', $blog->seo_keywords ?? '')

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="mb-3">{{ $blog->title }}</h1>
            @if ($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" class="img-fluid rounded-4 mb-4" alt="{{ $blog->title }}">
            @endif
            <div class="card p-4">{!! $blog->body !!}</div>
        </div>
    </section>
@endsection
