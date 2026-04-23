@extends('admin.layouts.app')

@section('title', 'Create Announcement')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Create Announcement</h4>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">Back to list</a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.announcements._form')
            <button class="btn btn-primary">Save Announcement</button>
        </form>
    </div>
@endsection
