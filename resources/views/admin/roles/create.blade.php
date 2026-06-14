@extends('admin.layouts.app')

@section('title', ln('Create Role', 'ভূমিকা তৈরি', '创建角色'))

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">{{ ln('Create Role', 'ভূমিকা তৈরি', '创建角色') }}</h4>

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            @include('admin.roles._form', ['role' => null])
            <button class="btn btn-primary mt-3">{{ ln('Save Role', 'ভূমিকা সংরক্ষণ', '保存角色') }}</button>
        </form>
    </div>
@endsection
