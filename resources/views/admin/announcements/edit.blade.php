@extends('admin.layouts.app')

@section('title', ln('Edit Announcement', 'ঘোষণা সম্পাদনা করুন', '编辑公告'))

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>{{ ln('Edit Announcement', 'ঘোষণা সম্পাদনা করুন', '编辑公告') }}</h4>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
            {{ ln('Back to list', 'তালিকায় ফিরে যান', '返回列表') }}</a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.announcements._form')
            <button class="btn btn-primary"> {{ ln('Update Announcement', 'ঘোষণা আপডেট করুন', '更新公告') }}</button>
        </form>
    </div>
@endsection
