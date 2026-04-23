@extends('admin.layouts.app')

@section('title', 'Edit Announcement')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Edit Announcement</h4>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">Back to list</a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.announcements._form')
            <button class="btn btn-primary">Update Announcement</button>
        </form>
    </div>
@endsection
