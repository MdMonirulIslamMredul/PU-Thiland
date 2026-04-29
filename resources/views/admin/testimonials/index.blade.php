@extends('admin.layouts.app')
@section('title', 'Testimonials')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Testimonials</h4>
            <div class="text-secondary">Manage client testimonials and control which ones appear on the homepage.</div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Add Testimonial</h5>
            <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    @include('admin.partials.translatable-field', [
                        'name' => 'name',
                        'label' => 'Client Name',
                        'model' => null,
                        'colClass' => 'col-md-4',
                    ])
                    @include('admin.partials.translatable-field', [
                        'name' => 'designation',
                        'label' => 'Designation / Company',
                        'model' => null,
                        'colClass' => 'col-md-4',
                    ])
                    <div class="col-md-2">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select">
                            @for ($rating = 1; $rating <= 5; $rating++)
                                <option value="{{ $rating }}" @selected((string) old('rating', '5') === (string) $rating)>{{ $rating }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Photo</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-12">
                        @include('admin.partials.translatable-field', [
                            'name' => 'message',
                            'label' => 'Message',
                            'type' => 'textarea',
                            'rows' => 4,
                            'model' => null,
                            'colClass' => 'col-12',
                        ])
                    </div>
                    <div class="col-12 d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1"
                                id="testimonialStatusAdd" checked>
                            <label class="form-check-label" for="testimonialStatusAdd">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Testimonial</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($testimonials as $testimonial)
            <div class="col-lg-6">
                <div class="card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">{{ $testimonial->name }}</h5>
                        <span class="badge {{ $testimonial->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $testimonial->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            @include('admin.partials.translatable-field', [
                                'name' => 'name',
                                'label' => 'Client Name',
                                'model' => $testimonial,
                                'colClass' => 'col-md-6',
                            ])
                            @include('admin.partials.translatable-field', [
                                'name' => 'designation',
                                'label' => 'Designation / Company',
                                'model' => $testimonial,
                                'colClass' => 'col-md-6',
                            ])
                            <div class="col-md-4">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select">
                                    @for ($rating = 1; $rating <= 5; $rating++)
                                        <option value="{{ $rating }}" @selected((string) old('rating', $testimonial->rating) === (string) $rating)>
                                            {{ $rating }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" value="1"
                                        id="testimonialStatus{{ $testimonial->id }}"
                                        {{ old('status', $testimonial->status) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="testimonialStatus{{ $testimonial->id }}">Active</label>
                                </div>
                            </div>
                            <div class="col-12">
                                @include('admin.partials.translatable-field', [
                                    'name' => 'message',
                                    'label' => 'Message',
                                    'type' => 'textarea',
                                    'rows' => 4,
                                    'model' => $testimonial,
                                    'colClass' => 'col-12',
                                ])
                            </div>
                            <div class="col-12">
                                <label class="form-label">Photo</label>
                                <input type="file" name="image" class="form-control mb-2">
                                <div class="d-flex align-items-center gap-3 p-3 border rounded bg-light">
                                    @if ($testimonial->image)
                                        <img src="{{ asset('storage/' . $testimonial->image) }}"
                                            alt="{{ $testimonial->name }}"
                                            style="width: 56px; height: 56px; object-fit: cover; border-radius: 999px;">
                                    @else
                                        <i class="bi bi-person-circle text-secondary" style="font-size: 2.4rem;"></i>
                                    @endif
                                    <div class="text-secondary small">Uploading a new photo replaces the current one.</div>
                                </div>
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirm('Delete this testimonial?')">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border mb-0">No testimonials found.</div>
            </div>
        @endforelse
    </div>
@endsection
