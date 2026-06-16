@extends('admin.layouts.app')

@section('title', ln('Register User', 'ব্যবহারকারী নিবন্ধন', '注册用户'))

@section('content')
    <div class="card p-4">
        <h4 class="mb-3"> {{ ln('Register Admin / User', 'অ্যাডমিন / ইউজার নিবন্ধন', '注册管理员/用户') }} </h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ ln('Name', 'নাম', '姓名') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ ln('Email', 'ইমেল', '电子邮件') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ ln('Phone', 'ফোন', '电话') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ ln('Password', 'পাসওয়ার্ড', '密码') }}</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ ln('Confirm Password', 'পাসওয়ার্ড নিশ্চিত করুন', '确认密码') }}</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ ln('Select Roles', 'ভূমিকা নির্ধারণ', '选择角色') }}</label>
                    <div class="small text-muted mb-2">
                        {{ ln('If you select any role, this user will be created as an admin user. If no role is selected, the user will remain a regular customer/user.', 'যদি কোনো ভূমিকা নির্বাচন করা হয়, তবে এই ব্যবহারকারীকে একজন অ্যাডমিন ব্যবহারকারীরূপে তৈরি করা হবে। যদি কোনো ভূমিকা নির্বাচন না করা হয়, তবে ব্যবহারকারীটি একজন সাধারণ কাস্টমার/ব্যবহারকারীরূপে থাকবে।', '如果选择任何角色，此用户将被创建为管理员用户。如果没有选择任何角色，用户将保持为普通客户/用户。') }}
                    </div>
                    <div class="row g-2">
                        @foreach ($roles as $role)
                            <div class="col-md-4">
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="roles[]"
                                        value="{{ $role->name }}"
                                        {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                    <span class="form-check-label">{{ $role->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-3">{{ ln('Register User', 'ব্যবহারকারী নিবন্ধন', '注册用户') }}</button>
        </form>
    </div>
@endsection
