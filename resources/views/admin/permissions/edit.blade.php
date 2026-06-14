@extends('admin.layouts.app')

@section('title', ln('Edit Permission', 'অনুমতি সম্পাদনা', '编辑权限'))

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">{{ ln('Edit Permission', 'অনুমতি সম্পাদনা', '编辑权限') }}</h4>

        <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">{{ ln('Permission name', 'অনুমতি নাম', '权限名称') }}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
            </div>
            <button class="btn btn-primary">{{ ln('Update Permission', 'অনুমতি আপডেট', '更新权限') }}</button>
        </form>
    </div>
@endsection
