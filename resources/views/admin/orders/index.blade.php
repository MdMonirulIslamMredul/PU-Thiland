@extends('admin.layouts.app')
@section('title', ln('Orders', 'অর্ডারস', '订单'))
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4> {{ ln('Orders', 'অর্ডারস', '订单') }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.report', request()->except('page')) }}" class="btn btn-sm btn-outline-primary">
                {{ ln('Printable Report', 'প্রিন্টেবল রিপোর্ট', '可打印报告') }}</a>
            {{-- <a href="{{ route('admin.orders.export.pdf', request()->except('page')) }}"
                class="btn btn-sm btn-outline-secondary">
                {{ ln('Export PDF', 'পিডিএফ ', '导出 PDF') }}</a> --}}
        </div>
    </div>
    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label"> {{ ln('Order Status', 'অর্ডার অবস্থা', '订单状态') }}</label>
                <select name="status" class="form-select">
                    <option value=""> {{ ln('All order statuses', 'সব অর্ডার অবস্থা', '所有订单状态') }}</option>
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label"> {{ ln('Payment Status', 'পেমেন্ট অবস্থা', '支付状态') }}</label>
                <select name="payment_status" class="form-select">
                    <option value=""> {{ ln('All payment statuses', 'সব পেমেন্ট অবস্থা', '所有支付状态') }}</option>
                    @foreach ($paymentStatuses as $key => $label)
                        <option value="{{ $key }}" {{ $payment_status === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label"> {{ ln('Search user', 'ব্যবহারকারী অনুসন্ধান করুন', '搜索用户') }}</label>
                <input type="search" name="user_search" value="{{ $user_search }}" class="form-control"
                    placeholder="{{ ln('Email or phone', 'ইমেল বা ফোন', '电子邮件或电话') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label"> {{ ln('Start date', 'শুরুর তারিখ', '开始日期') }}</label>
                <input type="date" name="start_date" value="{{ $start_date }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label"> {{ ln('End date', 'শেষের তারিখ', '结束日期') }}</label>
                <input type="date" name="end_date" value="{{ $end_date }}" class="form-control">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"> {{ ln('Filter', 'ফিল্টার', '筛选') }}</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    {{ ln('Reset', 'রিসেট', '重置') }}</a>
            </div>
        </form>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th> {{ ln('Order', 'অর্ডার', '订单') }}</th>
                        <th> {{ ln('User', 'ব্যবহারকারী', '用户') }}</th>
                        <th> {{ ln('Total', 'মোট', '总计') }}</th>
                        <th> {{ ln('Payment Status', 'পেমেন্ট অবস্থা', '支付状态') }}</th>
                        <th> {{ ln('Status', 'অবস্থা', '状态') }}</th>
                        <th> {{ ln('Created', 'তৈরি হয়েছে', '创建时间') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                {{ $order->user->name }}
                                <div class="small text-muted mt-1">
                                    {{ $order->user->email }}@if ($order->user->phone)
                                        | {{ $order->user->phone }}
                                    @endif
                                </div>
                            </td>
                            <td> ৳{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($order->payment_status ?? 'unpaid') }}</span>

                                @if ($order->payment_status === 'partial')
                                    <div class="small mt-1">
                                        <span class="text-success">Paid:
                                            ৳{{ number_format($order->paid_amount ?? 0, 2) }}</span>
                                        <span class="text-danger ms-3">Due:
                                            ৳{{ number_format($order->due_amount ?? 0, 2) }}</span>
                                    </div>
                                @elseif($order->payment_status === 'unpaid')
                                    <div class="small mt-1 text-danger">Due:
                                        ৳{{ number_format($order->due_amount ?? $order->total_amount, 2) }}</div>
                                @endif

                                @if (!empty($order->pending_payments_count) && $order->pending_payments_count > 0)
                                    <div class="small mt-1">
                                        <span
                                            class="badge bg-warning text-dark">{{ ln('Pending payment', 'পেমেন্ট অপেক্ষমান', '待处理付款') }}</span>
                                    </div>
                                @endif
                            </td>
                            <td><span
                                    class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'confirmed' ? 'info' : ($order->status === 'successful' ? 'success' : 'danger')) }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="text-end">


                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    {{ ln('View', 'দেখুন', '查看') }}</a>

                                @if ($order->status === 'pending')
                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PUT') {{-- Use PUT or PATCH for updates --}}
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-sm btn-outline-info">
                                            {{ ln('Confirm', 'নিশ্চিত করুন', '确认') }}
                                        </button>
                                    </form>
                                @endif



                                @if ($order->status === 'confirmed' && $order->warehousePickingOrder)
                                    <span class="badge bg-info text-dark">
                                        {{ ln('Picking created', 'পিকিং তৈরি হয়েছে', '已创建拣货') }}</span>
                                @endif
                                <a href="{{ route('admin.orders.edit', $order) }}"
                                    class="btn btn-sm btn-outline-secondary"> {{ ln('Update', 'আপডেট', '更新') }}</a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7"> {{ ln('No orders found.', 'কোন অর্ডার পাওয়া যায়নি.', '未找到订单.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    @endsection
