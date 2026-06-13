@extends('admin.layouts.app')
@section('title', ln('Create Product', 'পণ্য তৈরি করুন', '创建产品'))
@section('content')
    <div class="card p-4">
        <h4 class="mb-3"> {{ ln('Create Product', 'পণ্য তৈরি করুন', '创建产品') }} </h4>
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.products._form')
            <button class="btn btn-primary mt-3"> {{ ln('Save', 'সংরক্ষণ করুন', '保存') }} </button>
        </form>
    </div>
@endsection
