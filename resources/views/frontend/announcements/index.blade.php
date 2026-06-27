@extends('frontend.layouts.app')

@section('title', __('site.announcements.title'))

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-3">
                <div>
                    <span class="section-subtitle-modern">{{ __('site.announcements.news') }}</span>
                    <h2 class="section-title-modern">{{ __('site.announcements.important_notices') }}</h2>
                </div>
                <a href="{{ route('announcements.index') }}"
                    class="btn btn-outline-dark btn-sm">{{ __('site.announcements.reset_filters') }}</a>
            </div>

            <div class="card border-0 bg-light p-4 mb-4">
                <form method="GET" action="{{ route('announcements.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('site.announcements.type') }}</label>
                        <select name="type" class="form-select">
                            <option value="">{{ __('site.announcements.all_types') }}</option>
                            @foreach (['notice' => __('site.announcements.notice'), 'announcement' => __('site.announcements.announcement'), 'update' => __('site.announcements.update')] as $key => $label)
                                <option value="{{ $key }}"
                                    {{ isset($filterType) && $filterType === $key ? 'selected' : '' }}>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('site.announcements.publish_from') }}</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $filterDateFrom ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('site.announcements.publish_to') }}</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $filterDateTo ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">{{ __('site.announcements.apply') }}</button>
                    </div>
                </form>
            </div>

            <div class="row g-4">
                @forelse($announcements as $announcement)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="card h-100">
                            @if ($announcement->image)
                                <img src="{{ asset('storage/' . $announcement->image) }}" class="card-img-top"
                                    alt="{{ $announcement->title }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge bg-primary text-uppercase">{{ $announcement->type }}</span>
                                    <span
                                        class="badge bg-{{ $announcement->priority === 'urgent' ? 'danger' : ($announcement->priority === 'high' ? 'warning text-dark' : 'secondary') }} text-uppercase">{{ $announcement->priority }}</span>
                                </div>
                                <h5 class="card-title">{{ $announcement->title }}</h5>
                                <p class="card-text">{{ $announcement->short_description }}</p>
                                <div class="mt-auto">
                                    <a href="{{ route('announcements.show', $announcement) }}"
                                        class="btn btn-outline-dark btn-sm">{{ __('site.announcements.read_details') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">{{ __('site.announcements.no_announcements') }}</div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">{{ $announcements->links() }}</div>
        </div>
    </section>
@endsection
