@extends('admin.layouts.app')

@section('title', ln('Add Payment Gateway', 'পেমেন্ট গেটওয়ে যোগ করুন', '添加支付网关'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"> {{ ln('Add Payment Gateway', 'পেমেন্ট গেটওয়ে যোগ করুন', '添加支付网关') }}</h4>
            <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-secondary">
                {{ ln('Back', 'ফিরে যান', '返回') }}</a>
        </div>

        <form method="POST" action="{{ route('admin.payment-gateways.store') }}">
            @csrf
            @include('admin.payment-gateways._form')
            <button class="btn btn-primary"> {{ ln('Create Gateway', 'গেটওয়ে তৈরি করুন', '创建网关') }}</button>
        </form>
    </div>
@endsection
