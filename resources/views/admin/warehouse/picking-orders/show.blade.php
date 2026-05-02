@extends('admin.layouts.app')

@section('title', ln('Picking Order Details', 'পিকিং অর্ডারের বিবরণ', '拣货订单详情'))

@section('content')
    @php
        $salesOrder = $order->order;
        $totalRequiredWeight = $stockRows->sum('required_weight');
        $totalQuantity = $stockRows->sum('quantity');
        $totalLineAmount = $stockRows->sum('line_total');
    @endphp

    <div class="mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h4 class="mb-0">{{ ln('Picking Order', 'পিকিং অর্ডার', '拣货订单') }} #{{ $order->id }}</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.picking-orders.edit', $order) }}"
                    class="btn btn-outline-primary">{{ ln('Update Picking', 'পিকিং আপডেট করুন', '更新拣货') }}</a>
                <a href="{{ route('admin.picking-orders.index') }}"
                    class="btn btn-outline-secondary">{{ ln('Back to Picking Orders', 'পিকিং অর্ডারে ফিরুন', '返回拣货订单') }}</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ ln('Picking Status', 'পিকিং অবস্থা', '拣货状态') }}</div>
                    <div class="mt-2">
                        <span
                            class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'picking' ? 'primary' : 'warning text-dark') }} text-uppercase">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Stock Readiness</div>
                    <div class="mt-2">
                        @if ($stockRows->isEmpty())
                            <span class="badge bg-secondary">{{ ln('No Items', 'কোনও আইটেম নেই', '无商品') }}</span>
                        @elseif ($stockReady)
                            <span class="badge bg-success">{{ ln('Ready', 'প্রস্তুত', '就绪') }}</span>
                        @else
                            <span class="badge bg-danger">{{ ln('Shortage', 'ঘাটতি', '短缺') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ ln('Total Required Weight', 'মোট প্রয়োজনীয় ওজন', '所需总重量') }}</div>
                    <div class="fs-5 mt-2">{{ number_format($totalRequiredWeight, 3) }} kg</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ ln('Total Item Quantity', 'মোট আইটেম পরিমাণ', '商品总数量') }}</div>
                    <div class="fs-5 mt-2">{{ rtrim(rtrim(number_format($totalQuantity, 2, '.', ''), '0'), '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3 mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            @if ($order->status === 'pending')
                <form action="{{ route('admin.warehouse.picking-orders.start', $order) }}" method="POST" class="m-0">
                    @csrf
                    <button class="btn btn-primary">{{ ln('Start Picking', 'পিকিং শুরু করুন', '开始拣货') }}</button>
                </form>
            @endif

            @if ($order->status === 'picking')
                @if ($stockReady)
                    <form action="{{ route('admin.warehouse.picking-orders.complete', $order) }}" method="POST"
                        class="m-0">
                        @csrf
                        <button class="btn btn-success">{{ ln('Complete Picking', 'পিকিং সম্পন্ন করুন', '完成拣货') }}</button>
                    </form>
                @else
                    <button class="btn btn-secondary"
                        disabled>{{ ln('Complete Picking', 'পিকিং সম্পন্ন করুন', '完成拣货') }}</button>
                    <span
                        class="text-danger small">{{ ln('Stock shortage must be resolved before completion.', 'সম্পন্ন করার আগে স্টক ঘাটতি সমাধান করতে হবে।', '完成前必须解决库存短缺。') }}</span>
                @endif
            @endif
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="mb-3">{{ ln('Warehouse Picking Information', 'গুদাম পিকিং তথ্য', '仓库拣货信息') }}</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Order Reference', 'অর্ডার রেফারেন্স', '订单参考') }}</strong>
                <div>#{{ $order->order_id }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Customer', 'গ্রাহক', '客户') }}</strong>
                <div>{{ $order->order?->user?->name ?? 'Unknown' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Status', 'স্থিতি', '状态') }}</strong>
                <div
                    class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'picking' ? 'primary' : 'warning text-dark') }} text-uppercase">
                    {{ $order->status }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Assigned To', 'নির্ধারিত ব্যক্তি', '分配给') }}</strong>
                <div>{{ $order->assignedTo?->name ?? 'Unassigned' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Weight', 'ওজন', '重量') }}</strong>
                <div>{{ number_format($order->total_weight_kg, 3) }} kg</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Started At', 'শুরুর সময়', '开始时间') }}</strong>
                <div>{{ optional($order->started_at)->format('Y-m-d H:i') ?? '—' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Completed At', 'সম্পন্ন সময়', '完成时间') }}</strong>
                <div>{{ optional($order->completed_at)->format('Y-m-d H:i') ?? '—' }}</div>
            </div>
            <div class="col-md-12 mb-3">
                <strong>{{ ln('Note', 'নোট', '备注') }}</strong>
                <div>{{ $order->note ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="mb-3">{{ ln('Order Summary', 'অর্ডার সারাংশ', '订单摘要') }}</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Order ID', 'অর্ডার আইডি', '订单编号') }}</strong>
                <div>#{{ $salesOrder?->id ?? '-' }}</div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Order Status', 'অর্ডার অবস্থা', '订单状态') }}</strong>
                <div>{{ $salesOrder ? ucfirst($salesOrder->status) : '-' }}</div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Payment Status', 'পেমেন্ট অবস্থা', '付款状态') }}</strong>
                <div>{{ $salesOrder ? ucfirst($salesOrder->payment_status ?? 'unpaid') : '-' }}</div>
            </div>

            <div class="col-md-4 mb-3">
                <strong>{{ ln('Total Amount', 'মোট পরিমাণ', '总金额') }}</strong>
                <div>৳ {{ $salesOrder ? number_format((float) $salesOrder->total_amount, 2) : '0.00' }}</div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Paid Amount', 'পরিশোধিত পরিমাণ', '已付金额') }}</strong>
                <div>৳ {{ $salesOrder ? number_format((float) ($salesOrder->paid_amount ?? 0), 2) : '0.00' }}</div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Due Amount', 'বকেয়া পরিমাণ', '应付金额') }}</strong>
                <div>৳ {{ $salesOrder ? number_format((float) ($salesOrder->due_amount ?? 0), 2) : '0.00' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <strong>{{ ln('Customer Contact', 'গ্রাহকের যোগাযোগ', '客户联系方式') }}</strong>
                <div>{{ $salesOrder?->user?->email ?? '-' }} @if ($salesOrder?->user?->phone)
                        | {{ $salesOrder->user->phone }}
                    @endif
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Delivery Recipient', 'প্রাপকের নাম', '收货人') }}</strong>
                <div>{{ $salesOrder?->delivery_recipient_name ?? '-' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Delivery Phone', 'ডেলিভারি ফোন', '收货电话') }}</strong>
                <div>{{ $salesOrder?->delivery_phone ?? '-' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Created At', 'তৈরির সময়', '创建时间') }}</strong>
                <div>{{ optional($salesOrder?->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
            </div>
            <div class="col-md-12 mb-3">
                <strong>{{ ln('Delivery Address', 'ডেলিভারি ঠিকানা', '收货地址') }}</strong>
                <div>{{ $salesOrder?->delivery_address ?? '-' }}</div>
            </div>
            <div class="col-md-12 mb-0">
                <strong>{{ ln('Order Instruction Note', 'অর্ডার নির্দেশনা নোট', '订单备注') }}</strong>
                <div>{{ $salesOrder?->note ?? '—' }}</div>
            </div>
        </div>
    </div>

    @if ($stockAlerts->isNotEmpty())
        <div class="alert alert-danger">
            <strong>{{ ln('Stock Alerts:', 'স্টক সতর্কতা:', '库存提醒：') }}</strong>
            <ul class="mb-0 mt-2">
                @foreach ($stockAlerts as $alert)
                    <li>{{ $alert }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-4">
        <h5 class="mb-3">{{ ln('Products Picking Checklist', 'পণ্য পিকিং চেকলিস্ট', '商品拣货清单') }}</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ ln('Product', 'পণ্য', '产品') }}</th>
                        <th class="text-end">{{ ln('Quantity', 'পরিমাণ', '数量') }}</th>
                        <th class="text-end">{{ ln('Unit Weight (kg)', 'একক ওজন (কেজি)', '单件重量（公斤）') }}</th>
                        <th class="text-end">{{ ln('Required Weight (kg)', 'প্রয়োজনীয় ওজন (কেজি)', '所需重量（公斤）') }}</th>
                        <th class="text-end">{{ ln('Available Stock (kg)', 'উপলব্ধ স্টক (কেজি)', '可用库存（公斤）') }}</th>
                        <th class="text-end">{{ ln('Shortage (kg)', 'ঘাটতি (কেজি)', '短缺（公斤）') }}</th>
                        <th class="text-end">{{ ln('Unit Price (৳)', 'একক মূল্য (৳)', '单价（৳）') }}</th>
                        <th class="text-end">{{ ln('Line Total (৳)', 'সারির মোট (৳)', '行总计（৳）') }}</th>
                        <th class="text-center">{{ ln('Ready', 'প্রস্তুত', '就绪') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockRows as $row)
                        <tr>
                            <td>{{ $row['index'] }}</td>
                            <td>{{ ln($row['product_name_en'] ?? $row['product_name'], $row['product_name_bn'] ?? $row['product_name'], $row['product_name_zh'] ?? $row['product_name']) }}
                            </td>
                            <td class="text-end">{{ rtrim(rtrim(number_format($row['quantity'], 2, '.', ''), '0'), '.') }}
                            </td>
                            <td class="text-end">{{ number_format($row['unit_weight'], 3) }}</td>
                            <td class="text-end">{{ number_format($row['required_weight'], 3) }}</td>
                            <td class="text-end">{{ number_format($row['available_weight'], 3) }}</td>
                            <td class="text-end {{ $row['shortage_weight'] > 0 ? 'text-danger fw-semibold' : '' }}">
                                {{ number_format($row['shortage_weight'], 3) }}</td>
                            <td class="text-end">{{ number_format($row['unit_price'], 2) }}</td>
                            <td class="text-end">{{ number_format($row['line_total'], 2) }}</td>
                            <td class="text-center">
                                <span
                                    class="badge bg-{{ $row['ready'] ? 'success' : 'danger' }}">{{ $row['ready'] ? ln('Yes', 'হ্যাঁ', '是') : ln('No', 'না', '否') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                {{ ln('No product items found for this order.', 'এই অর্ডারের জন্য কোনও পণ্য আইটেম পাওয়া যায়নি।', '未找到此订单的商品项目。') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($stockRows->isNotEmpty())
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2" class="text-end">{{ ln('Totals', 'মোট', '合计') }}</th>
                            <th class="text-end">{{ rtrim(rtrim(number_format($totalQuantity, 2, '.', ''), '0'), '.') }}
                            </th>
                            <th></th>
                            <th class="text-end">{{ number_format($totalRequiredWeight, 3) }}</th>
                            <th class="text-end">{{ number_format($stockRows->sum('available_weight'), 3) }}</th>
                            <th class="text-end">{{ number_format($stockRows->sum('shortage_weight'), 3) }}</th>
                            <th></th>
                            <th class="text-end">{{ number_format($totalLineAmount, 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
