@extends('admin.layouts.app')

@section('title', ln($pageTitle ?? 'Users', 'ব্যবহারকারী', '用户'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">{{ ln($pageTitle ?? 'Users', 'ব্যবহারকারী', '用户') }}</h4>
            <div class="d-flex gap-2">
                @if (request()->routeIs('admin.users.admins'))
                    <a href="{{ route('admin.users.index') }}"
                        class="btn btn-secondary">{{ ln('View All Users', 'সমস্ত ব্যবহারকারী দেখুন', '查看所有用户') }}</a>
                @else
                    <a href="{{ route('admin.users.admins') }}"
                        class="btn btn-secondary">{{ ln('View Admins', 'অ্যাডমিন দেখুন', '查看管理员') }}</a>
                @endif
                <a href="{{ route('admin.users.create') }}"
                    class="btn btn-primary">{{ ln('Register Admin / User', 'অ্যাডমিন / ইউজার নিবন্ধন', '注册管理员/用户') }}</a>
            </div>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="mb-4">
            <div class="input-group">
                <input type="search" name="q" value="{{ old('q', $search ?? request('q')) }}" class="form-control"
                    placeholder="{{ ln('Search users by name or email or phone number', 'নাম, ইমেল বা ফোন নম্বর দ্বারা ব্যবহারকারী খুঁজুন', '按姓名、电子邮件或电话号码搜索用户') }}">
                <button class="btn btn-outline-secondary" type="submit">{{ ln('Search', 'খুঁজুন', '搜索') }}</button>
            </div>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ ln('Name', 'নাম', '姓名') }}</th>
                    <th>{{ ln('Email', 'ইমেল', '电子邮件') }}</th>
                    <th>{{ ln('Phone', 'ফোন', '电话') }}</th>
                    <th>{{ ln('Roles', 'ভূমিকা', '角色') }}</th>
                    <th class="text-end">{{ ln('Actions', 'একশন', '操作') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?: '—' }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="btn btn-sm btn-outline-primary me-2">{{ ln('Edit', 'এডিট', '编辑') }}</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                    type="submit">{{ ln('Delete', 'মুছুন', '删除') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">{{ $users->links() }}</div>
    </div>
@endsection
