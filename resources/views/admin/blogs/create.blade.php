@extends('admin.layouts.app')
@section('title', 'Create Blog')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Blog</h4>
        <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
            @csrf@include('admin.blogs._form')<button class="btn btn-primary mt-3">Save</button></form>
    </div>
@endsection
