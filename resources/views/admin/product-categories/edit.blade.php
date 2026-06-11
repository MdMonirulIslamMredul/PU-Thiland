@extends('admin.layouts.app')
@section('title', ln('Edit Product Category', 'পণ্য বিভাগ সম্পাদনা করুন', '编辑产品分类'))
@section('content')
    <div class="card p-4">
        <h4 class="mb-3"> {{ ln('Edit Product Category', 'পণ্য বিভাগ সম্পাদনা করুন', '编辑产品分类') }} </h4>
        <form method="POST" action="{{ route('admin.product-categories.update', $productCategory) }}">
            @csrf
            @method('PUT')
            @include('admin.product-categories._form')
            <button class="btn btn-primary mt-3"> {{ ln('Update', 'আপডেট করুন', '更新') }} </button>
        </form>
    </div>
@endsection
