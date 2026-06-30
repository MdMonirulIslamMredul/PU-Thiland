@extends('admin.layouts.app')

@section('title', ln('Edit VIP Rule', 'ভিআইপি নিয়ম সম্পাদনা', '编辑VIP规则'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"> {{ ln('Edit VIP Rule', 'ভিআইপি নিয়ম সম্পাদনা', '编辑VIP规则') }}</h4>
            <a href="{{ route('admin.vip-rules.index') }}" class="btn btn-secondary">
                {{ ln('Back to VIP Rules', 'ভিআইপি নিয়মে ফিরে যান', '返回VIP规则') }}</a>
        </div>

        <form method="POST" action="{{ route('admin.vip-rules.update', $vipRule) }}">
            @csrf
            @method('PUT')
            @include('admin.vip-rules._form')
            <button class="btn btn-primary"> {{ ln('Update VIP Rule', 'ভিআইপি নিয়ম আপডেট করুন', '更新VIP规则') }}</button>
        </form>
    </div>
@endsection
