@extends('admin.layouts.app')

@section('title', ln('Payment Gateways', 'পেমেন্ট গেটওয়ে', '支付网关'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"> {{ ln('Payment Gateways', 'পেমেন্ট গেটওয়ে', '支付网关') }}</h4>
            <a href="{{ route('admin.payment-gateways.create') }}" class="btn btn-primary">
                {{ ln('Add Payment Gateway', 'পেমেন্ট গেটওয়ে যোগ করুন', '添加支付网关') }}</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover">
            <thead>
                <tr>
                    <th> {{ ln('MFS / Bank', 'এমএফএস / ব্যাংক', 'MFS / 银行') }}</th>
                    <th> {{ ln('Account Name', 'অ্যাকাউন্টের নাম', '账户名称') }}</th>
                    <th> {{ ln('Account Number', 'অ্যাকাউন্ট নম্বর', '账户号码') }}</th>
                    <th> {{ ln('Bank Name', 'ব্যাংকের নাম', '银行名称') }}</th>
                    <th> {{ ln('Status', 'স্ট্যাটাস', '状态') }}</th>
                    <th class="text-end"> {{ ln('Actions', 'করণীয়', '操作') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gateways as $gateway)
                    <tr>
                        <td>{{ $gateway->mfs_name }}</td>
                        <td>{{ $gateway->account_name }}</td>
                        <td>{{ $gateway->account_number }}</td>
                        <td>{{ $gateway->bank_name ?: '—' }}</td>
                        <td>{{ $gateway->is_active ? ln('Active', 'সক্রিয়', '激活') : ln('Inactive', 'নিষ্ক্রিয়', '未激活') }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.payment-gateways.edit', $gateway) }}"
                                class="btn btn-sm btn-outline-primary me-2"> {{ ln('Edit', 'সম্পাদনা', '编辑') }}</a>
                            <form action="{{ route('admin.payment-gateways.destroy', $gateway) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    {{ ln('Delete', 'মুছে ফেলুন', '删除') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            {{ ln('No payment gateways configured yet.', 'এখনও কোন পেমেন্ট গেটওয়ে কনফিগার করা হয়নি।', '尚未配置任何支付网关。') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
