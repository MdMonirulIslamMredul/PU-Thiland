@extends('frontend.layouts.app')

@section('title', 'Contact Us')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7" data-aos="fade-right">
                    <h2 class="section-title mb-3">Contact Us</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" class="card p-4">
                        @csrf
                        <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name"
                                class="form-control" value="{{ old('name') }}" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email"
                                class="form-control" value="{{ old('email') }}" required></div>
                        <div class="mb-3"><label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                        </div>
                        <button class="btn btn-dark">Send Message</button>
                    </form>
                </div>
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="card p-4 mb-3">
                        <h5>Contact Info</h5>
                        <p class="mb-1"><strong>Email:</strong> {{ $setting->contact_email }}</p>
                        <p class="mb-1"><strong>Phone:</strong> {{ $setting->contact_phone }}</p>
                        <p class="mb-0"><strong>Address:</strong> {{ $setting->contact_address }}</p>
                    </div>
                    @if ($setting?->google_map_embed)
                        <iframe src="{{ $setting->google_map_embed }}" width="100%" height="300"
                            style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy"></iframe>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
