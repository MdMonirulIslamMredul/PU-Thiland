@extends('admin.layouts.app')

@section('title', ln('Edit Role', 'ভূমিকা সম্পাদনা', '编辑角色'))

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">{{ ln('Edit Role', 'ভূমিকা সম্পাদনা', '编辑角色') }}</h4>

        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.roles._form', ['role' => $role])
            <button class="btn btn-primary mt-3">{{ ln('Update Role', 'ভূমিকা আপডেট', '更新角色') }}</button>
        </form>
    </div>
@endsection
