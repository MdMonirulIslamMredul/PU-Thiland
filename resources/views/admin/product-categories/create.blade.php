@extends('admin.layouts.app')
@section('title', ln('Create Product Category', 'পণ্য বিভাগ তৈরি করুন', '创建产品分类'))
@section('content')
    <div class="card p-4">
        <h4 class="mb-3"> {{ ln('Create Product Category', 'পণ্য বিভাগ তৈরি করুন', '创建产品分类') }} </h4>
        <form method="POST" action="{{ route('admin.product-categories.store') }}">
            @csrf
            @include('admin.product-categories._form')
            <button class="btn btn-primary mt-3"> {{ ln('Save', 'সংরক্ষণ করুন', '保存') }} </button>
        </form>
    </div>
@endsection
