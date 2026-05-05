@extends('admin.layouts.app')

@section('title', ln('Create Announcement', 'ঘোষণা যোগ করুন', '创建公告'))

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>{{ ln('Create Announcement', 'ঘোষণা যোগ করুন', '创建公告') }}</h4>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
            {{ ln('Back to list', 'তালিকায় ফিরে যান', '返回列表') }}</a>
    </div>

    <div class="card p-3">
        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.announcements._form')
            <button class="btn btn-primary"> {{ ln('Save Announcement', 'ঘোষণা সংরক্ষণ করুন', '保存公告') }}</button>
        </form>
    </div>
@endsection
