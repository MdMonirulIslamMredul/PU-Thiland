@extends('admin.layouts.app')

@section('title', ln('Edit Payment Gateway', 'পেমেন্ট গেটওয়ে সম্পাদনা', '编辑支付网关'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"> {{ ln('Edit Payment Gateway', 'পেমেন্ট গেটওয়ে সম্পাদনা', '编辑支付网关') }}</h4>
            <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-secondary">
                {{ ln('Back', 'ফিরে যান', '返回') }}</a>
        </div>

        <form method="POST" action="{{ route('admin.payment-gateways.update', $paymentGateway) }}">
            @csrf
            @method('PUT')
            @include('admin.payment-gateways._form')
            <button class="btn btn-primary"> {{ ln('Update Gateway', 'গেটওয়ে আপডেট করুন', '更新网关') }}</button>
        </form>
    </div>
@endsection
