@extends('admin.layouts.app')

@section('title', ln('Create Permission', 'অনুমতি তৈরি', '创建权限'))

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">{{ ln('Create Permission', 'অনুমতি তৈরি', '创建权限') }}</h4>

        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ ln('Permission name', 'অনুমতি নাম', '权限名称') }}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <button class="btn btn-primary">{{ ln('Save Permission', 'অনুমতি সংরক্ষণ', '保存权限') }}</button>
        </form>
    </div>
@endsection
