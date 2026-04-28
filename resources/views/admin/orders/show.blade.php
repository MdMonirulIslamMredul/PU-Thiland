@extends('admin.layouts.app')
@section('title', ln('Order #' . $order->id, 'অর্ডার #' . $order->id, '订单 #' . $order->id))
@section('content')
    @php
        $vipDiscountRate = $vipDiscountRate ?? null;
    @endphp

    <div class="mb-4 p-4 rounded-3 bg-white shadow-sm">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <h4 class="mb-2"> {{ ln('Order #' . $order->id, 'অর্ডার #' . $order->id, '订单 #' . $order->id) }}</h4>
                <p class="mb-1"> {{ ln('Placed by', 'অর্ডার করেছে', '下单人') }} <strong>{{ $order->user->name }}</strong>
                    ({{ $order->user->email }})</p>
                @if ($order->user->phone)
                    <p class="mb-1"> {{ ln('Phone', 'ফোন', '电话') }}: <strong>{{ $order->user->phone }}</strong></p>
                @endif
                <p class="mb-0 text-muted">
                    {{ ln('Status', 'অবস্থা', '状态') }}:
                    <span
                        class="badge bg-{{ $order->status === 'confirmed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>
            <div class="text-md-end">
                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary btn-lg">
                    {{ ln('Update Status', 'অবস্থা আপডেট করুন', '更新状态') }}</a>
                <p class="text-muted small mt-2 mb-0"> {{ ln('Created', 'তৈরি হয়েছে', '创建时间') }}
                    {{ $order->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small mb-3"> {{ ln('Payment Status', 'পেমেন্ট অবস্থা', '支付状态') }}
                    </h6>
                    <span
                        class="badge py-2 px-3 fs-6 bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'partial' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($order->payment_status ?? 'unpaid') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small mb-3"> {{ ln('Paid', 'পেইড', '已支付') }}</h6>
                    <p class="h5 mb-0">৳ {{ number_format($order->paid_amount ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small mb-3"> {{ ln('Due', 'ডিউ', '待支付') }}</h6>
                    <p class="h5 mb-0">৳ {{ number_format($order->due_amount ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small mb-3"> {{ ln('Payment Method', 'পেমেন্ট পদ্ধতি', '支付方式') }}
                    </h6>
                    <p class="mb-0">
                        {{ $order->payment_method ? ucfirst(str_replace('_', ' ', $order->payment_method)) : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if ($order->status === 'confirmed' && $order->warehousePickingOrder)
        <div class="mb-3">
            <div class="alert alert-info mb-0">
                {{ ln('Picking order already created for this order. Current status:', 'এই অর্ডারের জন্য ইতিমধ্যে পিকিং অর্ডার তৈরি হয়েছে। বর্তমান অবস্থা:', 'Picking order already created for this order. Current status:') }}
                <strong>{{ $order->warehousePickingOrder->status }}</strong>
            </div>
        </div>
    @endif

    @if ($order->payments->isNotEmpty())
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-4"> {{ ln('Payment History', 'পেমেন্ট ইতিহাস', '支付历史') }}</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th> {{ ln('Date', 'তারিখ', '日期') }}</th>
                                <th> {{ ln('Method', 'পদ্ধতি', '方式') }}</th>
                                <th> {{ ln('Status', 'অবস্থা', '状态') }}</th>
                                <th> {{ ln('Amount', 'পরিমাণ', '金额') }}</th>
                                <th> {{ ln('Gateway', 'গেটওয়ে', '网关') }}</th>
                                <th> {{ ln('Note', 'নোট', '备注') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>
                                        @if ($payment->status === 'pending')
                                            <span class="badge bg-warning"> {{ ln('Pending', 'পেন্ডিং', '待处理') }}</span>
                                        @elseif ($payment->status === 'confirmed')
                                            <span class="badge bg-success"> {{ ln('Confirmed', 'নিশ্চিত', '已确认') }}</span>
                                        @else
                                            <span class="badge bg-danger">
                                                {{ ln('Rejected', 'প্রত্যাখ্যান', '已拒绝') }}</span>
                                        @endif
                                    </td>
                                    <td>৳ {{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->paymentGateway?->mfs_name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($payment->status === 'pending')
                                            <form method="POST"
                                                action="{{ route('admin.orders.payments.update', [$order, $payment]) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="d-flex gap-2 align-items-start">
                                                    <select name="status" class="form-select form-select-sm">
                                                        <option value="confirmed"> {{ ln('Confirm', 'নিশ্চিত', '确认') }}
                                                        </option>
                                                        <option value="failed"> {{ ln('Reject', 'প্রত্যাখ্যান', '拒绝') }}
                                                        </option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        {{ ln('Save', 'সংরক্ষণ', '保存') }}</button>
                                                </div>
                                                <div class="mt-2">
                                                    <textarea name="note" rows="2" class="form-control form-control-sm"
                                                        placeholder=" {{ ln('Rejection reason (optional)', 'প্রত্যাখ্যানের কারণ (ঐচ্ছিক)', '拒绝原因 (可选)') }}"></textarea>
                                                </div>
                                            </form>
                                        @else
                                            {{ $payment->note ?? '-' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($order->payment_status === 'partial' || $order->payment_status === 'unpaid')
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-4"> {{ ln('Update Payment Status', 'পেমেন্ট অবস্থা আপডেট', '更新支付状态') }}</h5>
                <form method="POST" action="{{ route('admin.orders.payment-status.update', $order) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">
                                {{ ln('New payment status', 'নতুন পেমেন্ট অবস্থা', '新的支付状态') }}</label>
                            <select name="payment_status" class="form-select">
                                @foreach (App\Models\Order::paymentStatuses() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ $order->payment_status === $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary"> {{ ln('Save', 'সংরক্ষণ', '保存') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif




    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-4"> {{ ln('Delivery Information', 'ডেলিভারি তথ্য', '配送信息') }}</h5>
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted"> {{ ln('Recipient', 'প্রাপক', '收件人') }}</dt>
                        <dd class="col-7">{{ $order->delivery_recipient_name ?? $order->user->name }}</dd>

                        <dt class="col-5 text-muted"> {{ ln('Phone', 'ফোন', '电话') }}</dt>
                        <dd class="col-7">{{ $order->delivery_phone ?? $order->user->phone }}</dd>

                        <dt class="col-5 text-muted"> {{ ln('Address', 'ঠিকানা', '地址') }}</dt>
                        <dd class="col-7">{{ $order->delivery_address ?? 'N/A' }}</dd>

                        @if ($order->userAddress)
                            <dt class="col-5 text-muted"> {{ ln('Saved Address', 'সংরক্ষিত ঠিকানা', '保存的地址') }}</dt>
                            <dd class="col-7">{{ $order->userAddress->label ?? 'Default' }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-4"> {{ ln('Quick Summary', 'দ্রুত সারাংশ', '快速摘要') }}</h5>
                    <div class="row g-2">
                        <div class="col-6 text-muted"> {{ ln('Items', 'আইটেম', '项目') }}</div>
                        <div class="col-6 text-end">{{ $order->items->sum('quantity') }}</div>

                        <div class="col-6 text-muted"> {{ ln('VIP Level', 'ভিআইপি স্তর', 'VIP 级别') }}</div>
                        <div class="col-6 text-end">{{ $order->vip_level ? ucfirst($order->vip_level) : 'None' }}</div>

                        <div class="col-6 text-muted"> {{ ln('Subtotal', 'সাবটোটাল', '小计') }}</div>
                        <div class="col-6 text-end">
                            ৳ {{ number_format(($order->total_amount ?? 0) + ($order->vip_discount_amount ?? 0), 2) }}
                        </div>


                        <div class="col-6 text-muted"> {{ ln('VIP Discount', 'ভিআইপি ডিসকাউন্ট', 'VIP 折扣') }}
                            @if ($vipDiscountRate)
                                ({{ number_format($vipDiscountRate, 2) }}×{{ number_format($order->total_weight ?? 0, 2) }}
                                {{ __('admin.kg') }})
                            @endif
                        </div>
                        <div class="col-6 text-end">

                            ৳ {{ number_format($order->vip_discount_amount ?? 0, 2) }}

                        </div>


                        <div class="col-6 text-muted"> {{ ln('Order Total', 'অর্ডার মোট', '订单总额') }}</div>
                        <div class="col-6 text-end fw-semibold">৳ {{ number_format($order->total_amount, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-4"> {{ ln('Payment Details', 'পেমেন্ট বিবরণ', '支付详情') }}</h5>
                    <dl class="row mb-0">
                        <dt class="col-6 text-muted"> {{ ln('Method', 'পদ্ধতি', '方法') }}</dt>
                        <dd class="col-6 text-end">
                            {{ $order->payment_method ? ucfirst(str_replace('_', ' ', $order->payment_method)) : 'N/A' }}
                        </dd>

                        <dt class="col-6 text-muted"> {{ ln('Total Amount', 'মোট টাকা', '总金额') }}</dt>
                        <dd class="col-6 text-end">৳ {{ number_format($order->total_amount ?? 0, 2) }}</dd>

                        <dt class="col-6 text-muted"> {{ ln('Recharge Used', 'রিচার্জ ব্যবহৃত', '已使用充值') }}</dt>
                        <dd class="col-6 text-end">৳ {{ number_format($order->recharge_used_amount ?? 0, 2) }}</dd>

                        <dt class="col-6 text-muted"> ৳ {{ ln('Payable Amount', 'পেয়েবল এমাউন্ট', '应付金额') }}</dt>
                        <dd class="col-6 text-end">
                            ৳
                            {{ number_format(max(0, ($order->total_amount ?? 0) - ($order->recharge_used_amount ?? 0)), 2) }}
                        </dd>
                    </dl>
                    <p class="small text-muted mt-3 mb-0">
                        @if ($order->payment_method === 'wallet')
                            {{ ln('Amount deducted from customer\'s recharge balance.', 'কাস্টমারের রিচার্জ ব্যালেন্স থেকে অ্যামাউন্ট কাটা হবে।', '金额将从客户的充值余额中扣除。') }}
                        @else
                            {{ ln('Payment receipt is required for gateway payments.', 'গেটওয়ে পেমেন্টের জন্য পেমেন্ট রিসিপ্ট প্রয়োজন।', '网关支付需要支付收据。') }}
                        @endif
                    </p>
                    @if ($order->payment_receipt)
                        <div class="mt-4">
                            <h6 class="mb-2"> {{ ln('Payment Receipt', 'পেমেন্ট রিসিপ্ট', '支付收据') }}</h6>
                            <a href="{{ asset('storage/' . $order->payment_receipt) }}" target="_blank">
                                <img src="{{ asset('storage/' . $order->payment_receipt) }}" alt="Payment Receipt"
                                    class="img-fluid rounded" style="max-height: 220px; object-fit: contain;" />
                            </a>
                            <p class="small text-muted mt-2 mb-0">
                                {{ ln('Click image to open full size.', 'ছবি ক্লিক করে পূর্ণ আকারে খুলুন।', '点击图片查看完整尺寸。') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($order->note)
            <div class="col-12">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3"> {{ ln('Order Note', 'অর্ডার নোট', '订单备注') }}</h5>
                        <p class="mb-0">{{ $order->note }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-4"> {{ ln('Order Details', 'অর্ডার বিবরণ', '订单详情') }}</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th> {{ ln('Product', 'প্রোডাক্ট', '产品') }}</th>
                            <th> {{ ln('Unit Price', 'ইউনিট প্রাইস', '单价') }}</th>
                            <th> {{ ln('Qty', 'পরিমাণ', '数量') }}</th>
                            <th> {{ ln('Total', 'মোট', '总计') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>৳ {{ number_format($item->product_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>৳ {{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <h5 class="mb-0">Total:
                    ৳{{ number_format(($order->total_amount ?? 0) + ($order->vip_discount_amount ?? 0), 2) }}</h5>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-4"> {{ ln('Order Weight Details', 'অর্ডার ওজন বিবরণ', '订单重量详情') }}</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th> {{ ln('Product', 'প্রোডাক্ট', '产品') }}</th>
                            <th> {{ ln('Unit Weight', 'ইউনিট ওজন', '单价重量') }}</th>
                            <th> {{ ln('Qty', 'পরিমাণ', '数量') }}</th>
                            <th> {{ ln('Total', 'মোট', '总计') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ number_format($item->product->weight, 2) }} {{ __('admin.kg') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->product->weight * $item->quantity, 2) }}
                                    {{ __('admin.kg') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <h5 class="mb-0"> {{ ln('Total', 'মোট', '总计') }}: {{ number_format($order->total_weight, 2) }}
                    {{ __('admin.kg') }}
                </h5>
            </div>
        </div>
    </div>

@endsection
