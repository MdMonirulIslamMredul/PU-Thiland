@extends('admin.layouts.app')

@section('title', ln('Warehouse Dashboard', 'গুদাম ড্যাশবোর্ড', 'Warehouse Dashboard'))

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100"
                style="background: linear-gradient(135deg, #0d6efd 0%, #2563eb 100%); color: white;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <small
                                class="text-uppercase text-white-50">{{ ln('Pending Picking Orders', 'বিচারাধীন পিকিং অর্ডার', '待拣货订单') }}</small>
                            <h4 class="mt-2 mb-2">{{ $stats['pendingPickingOrders'] }}</h4>
                            <p class="mb-0 text-white-75">
                                {{ ln('Orders waiting for warehouse allocation and processing.', 'গুদামের বরাদ্দ ও প্রক্রিয়াকরণের জন্য অপেক্ষারত অর্ডার।', '等待仓库分配和处理的订单。') }}
                            </p>
                        </div>
                        <div class="fs-3 opacity-75">
                            <i class="bi bi-truck"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <small
                                class="text-uppercase text-muted">{{ ln('Inventory Quantity', 'ইনভেন্টরি পরিমাণ', '库存数量') }}</small>
                            <h4 class="mt-2 mb-2">{{ number_format($stats['totalQuantity'], 3) }}
                                {{ ln('kg', 'কেজি', 'kg') }}</h4>
                            <p class="mb-0 text-muted">
                                {{ ln('Total available stock weight.', 'মোট উপলব্ধ স্টক ওজন।', '总可用库存重量。') }}</p>
                        </div>
                        <div class="fs-3 text-primary opacity-75">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <small
                                class="text-uppercase text-muted">{{ ln('Average Inventory Cost', 'গড় ইনভেন্টরি খরচ', '平均库存成本') }}</small>
                            <h4 class="mt-2 mb-2">৳ {{ number_format($stats['averageCost'], 4) }}
                                /{{ ln('kg', 'কেজি', 'kg') }}</h4>
                            <p class="mb-0 text-muted">
                                {{ ln('Weighted cost of stored items.', 'সংরক্ষিত আইটেমগুলির গড় খরচ।', '存储物品的加权成本。') }}
                            </p>
                        </div>
                        <div class="fs-3 text-success opacity-75">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-primary w-100 py-2 rounded-3">
                <i class="bi bi-box-seam me-2"></i>{{ ln('View Inventory', 'ইনভেন্টরি দেখুন', '查看库存') }}</a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('admin.picking-orders.index') }}" class="btn btn-primary w-100 py-2 rounded-3">
                <i class="bi bi-list-check me-2"></i>{{ ln('View Picking Orders', 'পিকিং অর্ডার দেখুন', '查看拣货订单') }}</a>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div>
                            <h5 class="mb-1">
                                {{ ln('Confirmed Orders Needing Picking', 'নিশ্চিত অর্ডার যা পিকিং প্রয়োজন', '需要拣货的已确认订单') }}
                            </h5>
                            <p class="small text-muted mb-0">
                                {{ ln('Confirmed orders not yet converted to a picking order.', 'নিশ্চিত অর্ডার যা এখনো পিকিং অর্ডারে পরিণত হয়নি।', '尚未转换为拣货订单的已确认订单。') }}
                            </p>
                        </div>
                        <a href="{{ route('admin.picking-orders.index') }}"
                            class="btn btn-sm btn-outline-secondary">{{ ln('See all', 'সব দেখুন', '查看全部') }}</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle mb-0">
                            <thead>
                                <tr class="table-light text-muted small text-uppercase">
                                    <th>{{ ln('Order', 'অর্ডার', '订单') }}</th>
                                    <th>{{ ln('Customer', 'গ্রাহক', '客户') }}</th>
                                    <th style="min-width: 220px;">{{ ln('Product Names', 'পণ্যের নাম', '产品名称') }}</th>
                                    <th style="min-width: 140px;">{{ ln('Quantity', 'পরিমাণ', '数量') }}</th>
                                    <th>{{ ln('Created', 'তৈরি হয়েছে', '创建时间') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($confirmedOrders as $order)
                                    <tr>
                                        @php
                                            $productNames = '-';
                                            $productQuantities = '-';

                                            if ($order->items->isNotEmpty()) {
                                                $productNames = $order->items
                                                    ->map(function ($item) {
                                                        $locale = app()->getLocale();

                                                        return $item->product?->getTranslation(
                                                            'title',
                                                            $locale,
                                                            false,
                                                        ) ?:
                                                            $item->product?->getTranslation('title', 'en', false) ?:
                                                            $item->product_name;
                                                    })
                                                    ->implode(', ');

                                                $productQuantities = $order->items
                                                    ->map(function ($item) {
                                                        return rtrim(
                                                            rtrim(
                                                                number_format((float) $item->quantity, 2, '.', ''),
                                                                '0',
                                                            ),
                                                            '.',
                                                        );
                                                    })
                                                    ->implode(', ');
                                            }
                                        @endphp
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td class="small">
                                            {{ $productNames === '-' ? ln('No items', 'কোনও আইটেম নেই', '无商品') : $productNames }}
                                        </td>
                                        <td class="small text-muted">{{ $productQuantities }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <div class="d-flex flex-wrap gap-2 justify-content-end align-items-center">
                                                <a href="{{ route('admin.warehouse.orders.show', $order) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    {{ ln('Review Details', 'বিস্তারিত দেখুন', '查看详情') }}</a>

                                                @if ($order->can_create_picking ?? true)
                                                    <form action="{{ route('admin.warehouse.create-picking', $order) }}"
                                                        method="POST" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            {{ ln('Create Picking', 'পিকিং তৈরি করুন', '创建拣货') }}
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($order->stock_check_needed ?? false)
                                                    <span class="badge text-bg-warning">
                                                        {{ ln('Stock check needed', 'স্টক যাচাই প্রয়োজন', '需要库存检查') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            {{ ln('No confirmed orders pending picking.', 'কোনও নিশ্চিত অর্ডার পিকিংয়ের জন্য অপেক্ষমাণ নেই।', '没有待拣货的已确认订单。') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div>
                            <h5 class="mb-1">{{ ln('Recent Picking Orders', 'সাম্প্রতিক পিকিং অর্ডার', '最近拣货订单') }}</h5>
                            <p class="small text-muted mb-0">
                                {{ ln('Latest picking order activity for the warehouse team.', 'গুদামের দলের জন্য সর্বশেষ পিকিং অর্ডার কার্যকলাপ।', '仓库团队的最新拣货订单活动。') }}
                            </p>
                        </div>
                        <a href="{{ route('admin.picking-orders.index') }}"
                            class="btn btn-sm btn-outline-secondary">{{ ln('See all', 'সব দেখুন', '查看全部') }}</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle mb-0">
                            <thead>
                                <tr class="table-light text-muted small text-uppercase">
                                    <th>{{ ln('Ref', 'রেফারেন্স', '参考') }}</th>
                                    <th>{{ ln('Customer', 'গ্রাহক', '客户') }}</th>
                                    <th style="min-width: 220px;">{{ ln('Product Names', 'পণ্যের নাম', '产品名称') }}</th>
                                    <th style="min-width: 140px;">{{ ln('Quantity', 'পরিমাণ', '数量') }}</th>
                                    <th>{{ ln('Status', 'স্থিতি', '状态') }}</th>
                                    <th>{{ ln('Weight', 'ওজন', '重量') }}</th>
                                    <th> {{ ln('Actions', 'কার্যকলাপ', '操作') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pickingOrders as $order)
                                    <tr>
                                        @php
                                            $productNames = '-';
                                            $productQuantities = '-';

                                            if ($order->order && $order->order->items->isNotEmpty()) {
                                                $productNames = $order->order->items
                                                    ->map(function ($item) {
                                                        $locale = app()->getLocale();

                                                        return $item->product?->getTranslation(
                                                            'title',
                                                            $locale,
                                                            false,
                                                        ) ?:
                                                            $item->product?->getTranslation('title', 'en', false) ?:
                                                            $item->product_name;
                                                    })
                                                    ->implode(', ');

                                                $productQuantities = $order->order->items
                                                    ->map(function ($item) {
                                                        return rtrim(
                                                            rtrim(
                                                                number_format((float) $item->quantity, 2, '.', ''),
                                                                '0',
                                                            ),
                                                            '.',
                                                        );
                                                    })
                                                    ->implode(', ');
                                            }
                                        @endphp
                                        <td>#{{ $order->order_id }}</td>
                                        <td>{{ $order->order?->user?->name ?? '—' }}</td>
                                        <td class="small">
                                            {{ $productNames === '-' ? ln('No items', 'কোনও আইটেম নেই', '无商品') : $productNames }}
                                        </td>
                                        <td class="small text-muted">{{ $productQuantities }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'picking' ? 'primary' : 'warning text-dark') }} text-uppercase">
                                                {{ $order->status }}</span>
                                        </td>
                                        <td>{{ number_format($order->total_weight_kg, 3) }} {{ ln('kg', 'কেজি', 'kg') }}
                                        </td>
                                        <td class="text-end">


                                            @if ($order->status === 'pending')
                                                <form action="{{ route('admin.warehouse.picking-orders.start', $order) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button
                                                        class="btn btn-sm btn-outline-primary">{{ ln('Start', 'শুরু', '开始') }}</button>
                                                </form>
                                            @endif

                                            @if ($order->status === 'picking')
                                                @if ($order->can_complete ?? false)
                                                    <form
                                                        action="{{ route('admin.warehouse.picking-orders.complete', $order) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button
                                                            class="btn btn-sm btn-outline-success">{{ ln('Complete', 'সম্পন্ন', '完成') }}</button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                                        title="{{ $order->completion_error ?? ln('Cannot complete this order.', 'এই অর্ডারটি সম্পন্ন করা যাবে না।', '无法完成此订单。') }}">{{ ln('Complete', 'সম্পন্ন', '完成') }}</button>
                                                    @if (!empty($order->completion_error))
                                                        <div class="small text-danger mt-1">{{ $order->completion_error }}
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif



                                            <a href="{{ route('admin.picking-orders.edit', $order) }}"
                                                class="btn btn-sm btn-outline-primary">{{ ln('Edit', 'সম্পাদনা', '编辑') }}</a>
                                            <a href="{{ route('admin.picking-orders.show', $order) }}"
                                                class="btn btn-sm btn-outline-secondary">{{ ln('View', 'দেখুন', '查看') }}</a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            {{ ln('No picking orders found.', 'কোন পিকিং অর্ডার পাওয়া যায়নি।', '未找到拣货订单。') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
