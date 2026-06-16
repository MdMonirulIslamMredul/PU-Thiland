@extends('admin.layouts.app')

@section('title', ln('Edit User Roles', 'ব্যবহারকারী ভূমিকা সম্পাদনা', '编辑用户角色'))

@section('content')
    <div class="card p-4">
        <h4 class="mb-3"> {{ ln('Edit Admin Roles', 'অ্যাডমিন ভূমিকা সম্পাদনা', '编辑管理员角色') }}</h4>

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">{{ ln('Name', 'নাম', '姓名') }}</label>
                <input type="text" class="form-control" value="{{ old('name', $user->name) }}" name="name">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ ln('Email', 'ইমেল', '电子邮件') }}</label>
                <input type="text" class="form-control" value="{{ old('email', $user->email) }}" name="email">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ ln('Phone', 'ফোন', '电话') }}</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ ln('Assign Roles', 'ভূমিকা নির্ধারণ', '分配角色') }}</label>
                <div class="row g-2">
                    @foreach ($roles as $role)
                        <div class="col-md-4">
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                <span class="form-check-label">{{ $role->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button class="btn btn-primary">{{ ln('Update Roles', 'ভূমিকা আপডেট', '更新角色') }}</button>
        </form>
    </div>
@endsection
