@extends('admin.layouts.app')

@section('title', ln('Permissions', 'অনুমতিসমূহ', '权限'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">{{ ln('Permissions', 'অনুমতিসমূহ', '权限') }}</h4>
            <a href="{{ route('admin.permissions.create') }}"
                class="btn btn-primary">{{ ln('Create Permission', 'অনুমতি তৈরি', '创建权限') }}</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ ln('Name', 'নাম', '名称') }}</th>
                    <th>{{ ln('Created', 'তৈরি', '创建时间') }}</th>
                    <th class="text-end">{{ ln('Actions', 'কার্যকলাপ', '操作') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.permissions.edit', $permission) }}"
                                class="btn btn-sm btn-outline-primary me-2">{{ ln('Edit', 'সম্পাদনা', '编辑') }}</a>
                            <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                    type="submit">{{ ln('Delete', 'মুছে ফেলুন', '删除') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">{{ $permissions->links() }}</div>
    </div>
@endsection
