@extends('admin.layouts.app')

@section('title', ln('Warehouse Order Review', 'গুদাম অর্ডার পর্যালোচনা', '仓库订单审核'))

@section('content')
    @php
        $totalRequiredWeight = $stockRows->sum('required_weight');
        $totalQuantity = $stockRows->sum('quantity');
        $totalLineAmount = $stockRows->sum('line_total');
    @endphp

    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h4 class="mb-0">{{ ln('Warehouse Order Review', 'গুদাম অর্ডার পর্যালোচনা', '仓库订单审核') }} #{{ $order->id }}</h4>
        <a href="{{ route('admin.picking-orders.index') }}"
            class="btn btn-outline-secondary">{{ ln('Back to Picking Orders', 'পিকিং অর্ডারে ফিরুন', '返回拣货订单') }}</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ ln('Order Status', 'অর্ডারের অবস্থা', '订单状态') }}</div>
                    <div class="mt-2"><span class="badge bg-info text-uppercase">{{ $order->status }}</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ ln('Stock Readiness', 'স্টক প্রস্তুতি', '库存准备情况') }}</div>
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
                    <div class="text-muted small">{{ ln('Required Weight', 'প্রয়োজনীয় ওজন', '所需重量') }}</div>
                    <div class="fs-5 mt-2">{{ number_format($totalRequiredWeight, 3) }} kg</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ ln('Total Quantity', 'মোট পরিমাণ', '总数量') }}</div>
                    <div class="fs-5 mt-2">{{ rtrim(rtrim(number_format($totalQuantity, 2, '.', ''), '0'), '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3 mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            @if ($stockReady)
                <form action="{{ route('admin.warehouse.create-picking', $order) }}" method="POST" class="m-0">
                    @csrf
                    <button class="btn btn-success">{{ ln('Create Picking', 'পিকিং তৈরি করুন', '创建拣货') }}</button>
                </form>
            @else
                <button class="btn btn-secondary" disabled>{{ ln('Create Picking', 'পিকিং তৈরি করুন', '创建拣货') }}</button>
                <span
                    class="text-danger small">{{ ln('Stock shortage must be resolved before creating a picking order.', 'পিকিং অর্ডার তৈরি করার আগে স্টক ঘাটতি সমাধান করতে হবে।', '创建拣货订单前必须解决库存短缺。') }}</span>
            @endif
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="mb-3">{{ ln('Order & Delivery Information', 'অর্ডার ও ডেলিভারি তথ্য', '订单和配送信息') }}</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Customer', 'গ্রাহক', '客户') }}</strong>
                <div>{{ $order->user?->name ?? ln('Unknown', 'অজানা', '未知') }}</div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Contact', 'যোগাযোগ', '联系方式') }}</strong>
                <div>{{ $order->user?->email ?? '-' }} @if ($order->user?->phone)
                        | {{ $order->user->phone }}
                    @endif
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Created At', 'তৈরির সময়', '创建时间') }}</strong>
                <div>{{ optional($order->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
            </div>

            <div class="col-md-4 mb-3">
                <strong>{{ ln('Payment Status', 'পেমেন্ট অবস্থা', '付款状态') }}</strong>
                <div>
                    @php
                        $paymentStatus = $order->payment_status ?? 'unpaid';
                    @endphp
                    {{ match ($paymentStatus) {
                        'unpaid' => ln('Unpaid', 'অপরিশোধিত', '未支付'),
                        'partial' => ln('Partial', 'আংশিক', '部分付款'),
                        'paid' => ln('Paid', 'পরিশোধিত', '已支付'),
                        default => ucfirst($paymentStatus),
                    } }}
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Total Amount', 'মোট পরিমাণ', '总金额') }}</strong>
                <div>৳ {{ number_format((float) $order->total_amount, 2) }}</div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>{{ ln('Due Amount', 'বকেয়া পরিমাণ', '应付金额') }}</strong>
                <div>৳ {{ number_format((float) ($order->due_amount ?? 0), 2) }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <strong>{{ ln('Delivery Recipient', 'প্রাপকের নাম', '收货人') }}</strong>
                <div>{{ $order->delivery_recipient_name ?? '-' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ ln('Delivery Phone', 'ডেলিভারি ফোন', '收货电话') }}</strong>
                <div>{{ $order->delivery_phone ?? '-' }}</div>
            </div>
            <div class="col-md-12 mb-0">
                <strong>{{ ln('Delivery Address', 'ডেলিভারি ঠিকানা', '收货地址') }}</strong>
                <div>{{ $order->delivery_address ?? '-' }}</div>
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
        <h5 class="mb-3">{{ ln('Products Checklist', 'পণ্য চেকলিস্ট', '商品清单') }}</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ ln('Product', 'পণ্য', '产品') }}</th>
                        <th class="text-end">{{ ln('Quantity', 'পরিমাণ', '数量') }}</th>
                        <th class="text-end">{{ ln('Unit Weight (kg)', 'একক ওজন (কেজি)', '单件重量（公斤）') }}</th>
                        <th class="text-end">{{ ln('Required (kg)', 'প্রয়োজনীয় (কেজি)', '所需（公斤）') }}</th>
                        <th class="text-end">{{ ln('Available (kg)', 'উপলব্ধ (কেজি)', '可用（公斤）') }}</th>
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
                            <td class="text-center"><span
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
