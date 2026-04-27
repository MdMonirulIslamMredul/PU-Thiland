@extends('admin.layouts.app')
@section('title', 'Edit Blog')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Blog</h4>
        <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">@csrf
            @method('PUT')@include('admin.blogs._form')<button class="btn btn-primary mt-3">Update</button></form>
    </div>
@endsection
