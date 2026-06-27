@extends('frontend.layouts.app')

@section('title', $announcement->title)
@section('meta_description', $announcement->short_description ?? '')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="mb-4">
                <a href="{{ route('announcements.index') }}"
                    class="btn btn-outline-secondary btn-sm">{{ __('site.announcements.back_to_list') }}</a>
            </div>

            <div class="card p-4">
                <div class="mb-3 d-flex flex-wrap gap-2 align-items-start">
                    <span class="badge bg-primary text-uppercase">{{ ucfirst($announcement->type) }}</span>
                    <span
                        class="badge bg-{{ $announcement->priority === 'urgent' ? 'danger' : ($announcement->priority === 'high' ? 'warning text-dark' : 'secondary') }} text-uppercase">{{ $announcement->priority }}</span>
                    <span class="badge bg-success">{{ __('site.announcements.published') }}
                        {{ $announcement->publish_date?->format('Y-m-d H:i') ?? __('site.announcements.now') }}</span>
                    @if ($announcement->expiry_date)
                        <span class="badge bg-secondary">Expires:
                            {{ $announcement->expiry_date->format('Y-m-d H:i') }}</span>
                    @endif
                </div>
                <h1 class="mb-3">{{ $announcement->title }}</h1>
                @if ($announcement->image)
                    <img src="{{ asset('storage/' . $announcement->image) }}" class="img-fluid rounded-4 mb-4"
                        alt="{{ $announcement->title }}">
                @endif
                <p class="lead">{{ $announcement->short_description }}</p>
                <div>{!! nl2br(e($announcement->body)) !!}</div>

                @if ($announcement->attachment)
                    <div class="mt-4">
                        <a href="{{ asset('storage/' . $announcement->attachment) }}" class="btn btn-outline-primary"
                            target="_blank">Download Attachment</a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
