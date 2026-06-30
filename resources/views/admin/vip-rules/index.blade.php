@extends('admin.layouts.app')

@section('title', ln('VIP Rules', 'ভিআইপি নিয়ম', 'VIP规则'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"> {{ ln('VIP Rules', 'ভিআইপি নিয়ম', 'VIP规则') }}</h4>
            <a href="{{ route('admin.vip-rules.create') }}" class="btn btn-primary">
                {{ ln('Add VIP Rule', 'ভিআইপি নিয়ম যোগ করুন', '添加VIP规则') }}</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ ln('Level', 'স্তর', '等级') }}</th>
                    <th>{{ ln('Discount/kg', 'ডিসকাউন্ট/কেজি', '折扣/kg') }}</th>
                    <th>{{ ln('Sales Range (kg)', 'বিক্রয় পরিসর (কেজি)', '销售范围 (kg)') }}</th>
                    <th>{{ ln('Recharge Min', 'রিচার্জ সর্বনিম্ন', '充值最低') }}</th>
                    <th>{{ ln('Priority', 'অগ্রাধিকার', '优先级') }}</th>
                    <th>{{ ln('Expiry Days', 'মেয়াদ শেষ দিন', '过期天数') }}</th>
                    <th>{{ ln('Status', 'অবস্থা', '状态') }}</th>
                    <th class="text-end">{{ ln('Actions', 'কার্যক্রম', '操作') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                    <tr>
                        <td>{{ ucfirst($rule->level_name) }}</td>
                        <td>{{ number_format($rule->discount_per_kg, 2) }}</td>
                        <td>{{ number_format($rule->min_sales_kg, 2) }} -
                            {{ $rule->max_sales_kg !== null ? number_format($rule->max_sales_kg, 2) : '∞' }}</td>
                        <td>{{ number_format($rule->min_recharge_amount, 2) }}</td>
                        <td>{{ $rule->priority }}</td>
                        <td>{{ $rule->expiry_days }}</td>
                        <td>{{ $rule->is_active ? ln('Active', 'সক্রিয়', '激活') : ln('Disabled', 'অক্রিয়', '禁用') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.vip-rules.edit', $rule) }}"
                                class="btn btn-sm btn-outline-primary me-2"> {{ ln('Edit', 'সম্পাদনা', '编辑') }}</a>
                            <form action="{{ route('admin.vip-rules.destroy', $rule) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                    {{ ln('Delete', 'মুছে ফেলুন', '删除') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            {{ ln('No VIP rules configured yet.', 'কোন ভিআইপি নিয়ম কনফিগার করা হয়নি।', '暂无VIP规则配置。') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
