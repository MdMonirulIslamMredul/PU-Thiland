@extends('frontend.layouts.app')

@section('title', __('site.team.title'))

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="section-title mb-4">{{ __('site.team.heading') }}</h1>
            <div class="row g-4">
                @forelse($teamMembers as $member)
                    <div class="col-md-6 col-lg-3" data-aos="fade-up">
                        <div class="card h-100 text-center p-3">
                            @if ($member->photo)
                                <img src="{{ asset('storage/' . $member->photo) }}" class="rounded-circle mx-auto mb-3"
                                    style="width:90px;height:90px;object-fit:cover;" alt="{{ $member->name }}">
                            @else
                                <span class="text-muted"><i class="bi bi-person-circle" style="font-size: 4rem;"></i></span>
                                {{-- <span class="text-muted">No image</span> --}}
                            @endif
                            <h6>
                                {{ $member->name }}</h6><small class="text-secondary">{{ $member->designation }}</small>
                            <div class="d-flex justify-content-center gap-2 mt-2"><a
                                    href="{{ $member->facebook_url ?: '#' }}"><i class="bi bi-facebook"></i></a><a
                                    href="{{ $member->linkedin_url ?: '#' }}"><i class="bi bi-linkedin"></i></a><a
                                    href="{{ $member->twitter_url ?: '#' }}"><i class="bi bi-twitter-x"></i></a></div>
                        </div>
                </div>@empty<p>{{ __('site.team.no_team_members') }}</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $teamMembers->links() }}</div>
        </div>
    </section>
@endsection
