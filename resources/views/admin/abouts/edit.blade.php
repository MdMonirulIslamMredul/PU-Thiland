@extends('admin.layouts.app')
@section('title', 'Edit About Page')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Manage About Page</h4>
        <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.abouts._form')
            <button class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('.about-editor').forEach((editor) => {
            ClassicEditor.create(editor).catch((error) => console.error(error));
        });
    </script>
@endpush
