@extends('admin.layouts.app')

@section('title', ln('Roles', 'ভূমিকা', '角色'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">{{ ln('Roles', 'ভূমিকা', '角色') }}</h4>
            <a href="{{ route('admin.roles.create') }}"
                class="btn btn-primary">{{ ln('Create Role', 'ভূমিকা তৈরি', '创建角色') }}</a>
        </div>

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
                    <th>{{ ln('Permissions', 'অনুমতিসমূহ', '权限') }}</th>
                    <th>{{ ln('Created', 'তৈরি', '创建时间') }}</th>
                    <th class="text-end">{{ ln('Actions', 'কার্যক্রম', '操作') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->permissions_count }}</td>
                        <td>{{ $role->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.roles.edit', $role) }}"
                                class="btn btn-sm btn-outline-primary me-2">{{ ln('Edit', 'সম্পদনা', '编辑') }}</a>
                            @if ($role->name !== 'Admin')
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                        type="submit">{{ ln('Delete', 'মুছে ফেলুন', '删除') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">{{ $roles->links() }}</div>
    </div>
@endsection
