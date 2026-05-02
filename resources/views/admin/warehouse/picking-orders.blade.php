@extends('admin.layouts.app')

@section('title', ln('Warehouse Picking Orders', 'গুদাম পিকিং অর্ডার', '仓库拣货订单'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>{{ ln('Warehouse Picking Orders', 'গুদাম পিকিং অর্ডার', '仓库拣货订单') }}</h4>
        <a href="{{ route('admin.warehouse.dashboard') }}"
            class="btn btn-outline-secondary">{{ ln('Back to Dashboard', 'ড্যাশবোর্ডে ফিরুন', '返回仪表板') }}</a>
    </div>

    <div class="card p-3 mb-4">
        <h5>{{ ln('Create Picking Order', 'পিকিং অর্ডার তৈরি করুন', '创建拣货订单') }}</h5>
        @if (empty($availableOrders) || $availableOrders->isEmpty())
            <div class="alert alert-secondary mb-0">
                {{ ln('No confirmed orders are available for warehouse picking.', 'গুদাম পিকিংয়ের জন্য কোনও নিশ্চিত অর্ডার নেই।', '没有可用于仓库拣货的已确认订单。') }}
            </div>
        @else
            <div class="row g-3">
                @foreach ($availableOrders as $order)
                    <div class="col-md-6">
                        <div class="card p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>{{ ln('Order', 'অর্ডার', '订单') }} #{{ $order->id }}</strong>
                                    <div class="small text-muted">{{ $order->user->name }} |
                                        {{ $order->created_at->format('Y-m-d') }}</div>
                                </div>
                                <span class="badge bg-info text-dark">{{ ln('Confirmed', 'নিশ্চিত', '已确认') }}</span>
                            </div>
                            <p class="mb-2">{{ ln('Total', 'মোট', '总计') }}:
                                ${{ number_format($order->total_amount, 2) }}</p>
                            <a href="{{ route('admin.warehouse.orders.show', $order) }}"
                                class="btn btn-sm btn-outline-primary">
                                {{ ln('Review Details', 'বিস্তারিত দেখুন', '查看详情') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="card p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ ln('Order', 'অর্ডার', '订单') }}</th>
                    <th>{{ ln('Customer', 'গ্রাহক', '客户') }}</th>
                    <th>{{ ln('Status', 'স্থিতি', '状态') }}</th>
                    <th>{{ ln('Weight (kg)', 'ওজন (কেজি)', '重量（公斤）') }}</th>
                    <th>{{ ln('Started', 'শুরু হয়েছে', '已开始') }}</th>
                    <th>{{ ln('Completed', 'সম্পন্ন হয়েছে', '已完成') }}</th>
                    <th class="text-end">{{ ln('Actions', 'অ্যাকশন', '操作') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pickingOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>#{{ $order->order_id }}</td>
                        <td>{{ $order->order?->user?->name ?? ln('Unknown', 'অজানা', '未知') }}</td>
                        <td>
                            @php
                                $badge = match ($order->status) {
                                    'completed' => 'success',
                                    'picking' => 'primary',
                                    default => 'warning text-dark',
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }} text-uppercase">{{ $order->status }}</span>
                        </td>
                        <td>{{ number_format($order->total_weight_kg, 3) }}</td>
                        <td>{{ optional($order->started_at)->format('Y-m-d') ?? '—' }}</td>
                        <td>{{ optional($order->completed_at)->format('Y-m-d') ?? '—' }}</td>
                        <td class="text-end">


                            @if ($order->status === 'pending')
                                <form action="{{ route('admin.warehouse.picking-orders.start', $order) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary">{{ ln('Start', 'শুরু', '开始') }}</button>
                                </form>
                            @endif

                            @if ($order->status === 'picking')
                                @if ($order->can_complete ?? false)
                                    <form action="{{ route('admin.warehouse.picking-orders.complete', $order) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button
                                            class="btn btn-sm btn-outline-success">{{ ln('Complete', 'সম্পন্ন', '完成') }}</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                        title="{{ $order->completion_error ?? ln('Cannot complete this order.', 'এই অর্ডার সম্পন্ন করা যাচ্ছে না।', '无法完成此订单。') }}">{{ ln('Complete', 'সম্পন্ন', '完成') }}</button>
                                    @if (!empty($order->completion_error))
                                        <div class="small text-danger mt-1">{{ $order->completion_error }}</div>
                                    @endif
                                @endif
                            @endif


                            <a href="{{ route('admin.picking-orders.edit', $order) }}"
                                class="btn btn-sm btn-outline-primary">{{ ln('Edit', 'সম্পাদনা', '编辑') }}</a>
                            <a href="{{ route('admin.picking-orders.show', $order) }}"
                                class="btn btn-sm btn-outline-secondary">{{ ln('View', 'দেখুন', '查看') }}</a>
                            <form action="{{ route('admin.picking-orders.destroy', $order) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('{{ ln('Delete this picking order?', 'এই পিকিং অর্ডার মুছবেন?', '删除此拣货订单？') }}')">{{ ln('Delete', 'মুছুন', '删除') }}</button>
                            </form>


                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            {{ ln('No picking orders found.', 'কোনও পিকিং অর্ডার পাওয়া যায়নি।', '未找到拣货订单。') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
