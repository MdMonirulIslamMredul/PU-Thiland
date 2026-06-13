@extends('admin.layouts.app')
@section('title', ln('Edit Product', 'সম্পাদনা পণ্য', '编辑产品'))
@section('content')
    <div class="card p-4">
        <h4 class="mb-3"> {{ ln('Edit Product', 'সম্পাদনা পণ্য', '编辑产品') }}</h4>
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">@csrf
            @method('PUT')@include('admin.products._form')<button class="btn btn-primary mt-3">
                {{ ln('Update', 'আপডেট', '更新') }}</button></form>
    </div>
@endsection
