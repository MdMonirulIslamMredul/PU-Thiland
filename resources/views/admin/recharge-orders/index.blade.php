@extends('admin.layouts.app')

@section('title', ln('Recharge Orders', 'রিচার্জ অর্ডার', '充值订单'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"> {{ ln('Recharge Orders', 'রিচার্জ অর্ডার', '充值订单') }}</h4>
            <div>
                <a href="{{ route('admin.recharge-orders.report', request()->except('page')) }}"
                    class="btn btn-sm btn-outline-primary">
                    {{ ln('Printable Report', 'প্রিন্টেবল রিপোর্ট', '可打印报告') }}</a>
            </div>
        </div>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label"> {{ ln('Status', 'স্ট্যাটাস', '状态') }}</label>
                <select name="status" class="form-select">
                    <option value="">{{ __('admin.all_statuses') }}</option>
                    <option value="pending" {{ ($filterStatus ?? '') === 'pending' ? 'selected' : '' }}>
                        {{ __('admin.pending') }}</option>
                    <option value="approved" {{ ($filterStatus ?? '') === 'approved' ? 'selected' : '' }}>
                        {{ __('admin.approved') }}</option>
                    <option value="rejected" {{ ($filterStatus ?? '') === 'rejected' ? 'selected' : '' }}>
                        {{ __('admin.rejected') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"> {{ ln('Search user', 'ব্যবহারকারী অনুসন্ধান', '搜索用户') }}</label>
                <input type="search" name="user_search" class="form-control" value="{{ $filterUserSearch ?? '' }}"
                    placeholder="{{ ln('Email or phone', 'ইমেল বা ফোন', '电子邮件或电话') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label"> {{ ln('Date From', 'তারিখ থেকে', '开始日期') }}</label>
                <input type="date" name="date_from" class="form-control" value="{{ $filterDateFrom ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label"> {{ ln('Date To', 'তারিখ পর্যন্ত', '结束日期') }}</label>
                <input type="date" name="date_to" class="form-control" value="{{ $filterDateTo ?? '' }}">
            </div>
            <div class="col-md-3 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary w-100"> {{ ln('Filter', 'ফিল্টার', '筛选') }}</button>
                <a href="{{ route('admin.recharge-orders.index') }}" class="btn btn-outline-secondary w-100">
                    {{ ln('Reset', 'রিসেট', '重置') }}</a>
            </div>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th> {{ ln('User', 'ব্যবহারকারী', '用户') }}</th>
                    <th> {{ ln('Amount', 'পরিমাণ', '金额') }}</th>
                    <th> {{ ln('Gateway', 'গেটওয়ে', '网关') }}</th>
                    <th> {{ ln('Payment Method', 'পেমেন্ট পদ্ধতি', '支付方式') }}</th>
                    <th> {{ ln('Status', 'স্ট্যাটাস', '状态') }}</th>
                    <th> {{ ln('Date', 'তারিখ', '日期') }}</th>
                    <th class="text-end"> {{ ln('Actions', 'অ্যাকশনস', '操作') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            {{ $order->user->name }}
                            <div class="small text-muted mt-1">
                                {{ $order->user->email }}@if ($order->user->phone)
                                    | {{ $order->user->phone }}
                                @endif
                            </div>
                        </td>
                        <td>{{ number_format($order->amount, 2) }}</td>
                        <td>{{ $order->paymentGateway->mfs_name }}</td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                        <td>
                            @php
                                $statusClass = match ($order->status) {
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    default => 'warning text-dark',
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }} text-uppercase">

                                {{ match ($order->status) {
                                    'approved' => __('admin.approved'),
                                    'rejected' => __('admin.rejected'),
                                    default => ucfirst($order->status),
                                } }}

                            </span>
                        </td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.recharge-orders.show', $order) }}"
                                class="btn btn-sm btn-outline-primary">{{ ln('View', 'দেখুন', '查看') }}</a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                {{ ln('No recharge orders found.', 'রিচার্জ অর্ডার পাওয়া যায়নি।', '未找到充值订单。') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endsection
