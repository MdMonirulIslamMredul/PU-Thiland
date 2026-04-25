@extends('admin.layouts.app')

@section('title', 'Add VIP Rule')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Add VIP Rule</h4>
            <a href="{{ route('admin.vip-rules.index') }}" class="btn btn-secondary">Back to VIP Rules</a>
        </div>

        <form method="POST" action="{{ route('admin.vip-rules.store') }}">
            @csrf
            @include('admin.vip-rules._form')
            <button class="btn btn-primary">Create VIP Rule</button>
        </form>
    </div>
@endsection
