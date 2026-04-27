@extends('admin.layouts.app')
@section('title', 'Order #' . $order->id)
@section('content')
    @php
        $vipDiscountRate = $vipDiscountRate ?? null;
    @endphp

    <div class="mb-4 p-4 rounded-3 bg-white shadow-sm">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <h4 class="mb-2">Order #{{ $order->id }}</h4>
                <p class="mb-1">Placed by <strong>{{ $order->user->name }}</strong> ({{ $order->user->email }})</p>
                @if ($order->user->phone)
                    <p class="mb-1">Phone: <strong>{{ $order->user->phone }}</strong></p>
                @endif
                <p class="mb-0 text-muted">
                    Status:
                    <span
                        class="badge bg-{{ $order->status === 'confirmed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>
            <div class="text-md-end">
                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary btn-lg">Update Status</a>
                <p class="text-muted small mt-2 mb-0">Created {{ $order->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small mb-3">Payment Status</h6>
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
                    <h6 class="text-uppercase text-muted small mb-3">Paid</h6>
                    <p class="h5 mb-0">৳ {{ number_format($order->paid_amount ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small mb-3">Due</h6>
                    <p class="h5 mb-0">৳ {{ number_format($order->due_amount ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small mb-3">Payment Method</h6>
                    <p class="mb-0">
                        {{ $order->payment_method ? ucfirst(str_replace('_', ' ', $order->payment_method)) : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if ($order->status === 'confirmed' && $order->warehousePickingOrder)
        <div class="mb-3">
            <div class="alert alert-info mb-0">
                Picking order already created for this order. Current status:
                <strong>{{ $order->warehousePickingOrder->status }}</strong>
            </div>
        </div>
    @endif

    @if ($order->payments->isNotEmpty())
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-4">Payment History</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Gateway</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>
                                        @if ($payment->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($payment->status === 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
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
                                                        <option value="confirmed">Confirm</option>
                                                        <option value="failed">Reject</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                </div>
                                                <div class="mt-2">
                                                    <textarea name="note" rows="2" class="form-control form-control-sm" placeholder="Rejection reason (optional)"></textarea>
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
                <h5 class="mb-4">Update Payment Status</h5>
                <form method="POST" action="{{ route('admin.orders.payment-status.update', $order) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">New payment status</label>
                            <select name="payment_status" class="form-select">
                                @foreach (App\Models\Order::paymentStatuses() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ $order->payment_status === $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">Save payment status</button>
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
                    <h5 class="mb-4">Delivery Information</h5>
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">Recipient</dt>
                        <dd class="col-7">{{ $order->delivery_recipient_name ?? $order->user->name }}</dd>

                        <dt class="col-5 text-muted">Phone</dt>
                        <dd class="col-7">{{ $order->delivery_phone ?? $order->user->phone }}</dd>

                        <dt class="col-5 text-muted">Address</dt>
                        <dd class="col-7">{{ $order->delivery_address ?? 'N/A' }}</dd>

                        @if ($order->userAddress)
                            <dt class="col-5 text-muted">Saved Address</dt>
                            <dd class="col-7">{{ $order->userAddress->label ?? 'Default' }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-4">Quick Summary</h5>
                    <div class="row g-2">
                        <div class="col-6 text-muted">Items</div>
                        <div class="col-6 text-end">{{ $order->items->sum('quantity') }}</div>

                        <div class="col-6 text-muted">VIP Level</div>
                        <div class="col-6 text-end">{{ $order->vip_level ? ucfirst($order->vip_level) : 'None' }}</div>

                        <div class="col-6 text-muted">Subtotal</div>
                        <div class="col-6 text-end">
                            ${{ number_format(($order->total_amount ?? 0) + ($order->vip_discount_amount ?? 0), 2) }}</div>


                        <div class="col-6 text-muted"> VIP Discount
                            @if ($vipDiscountRate)
                                ({{ number_format($vipDiscountRate, 2) }}×{{ number_format($order->total_weight ?? 0, 2) }}kg)
                            @endif
                        </div>
                        <div class="col-6 text-end">

                            ${{ number_format($order->vip_discount_amount ?? 0, 2) }}

                        </div>


                        <div class="col-6 text-muted">Order Total</div>
                        <div class="col-6 text-end fw-semibold">${{ number_format($order->total_amount, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-4">Payment Details</h5>
                    <dl class="row mb-0">
                        <dt class="col-6 text-muted">Method</dt>
                        <dd class="col-6 text-end">
                            {{ $order->payment_method ? ucfirst(str_replace('_', ' ', $order->payment_method)) : 'N/A' }}
                        </dd>

                        <dt class="col-6 text-muted">Total Amount</dt>
                        <dd class="col-6 text-end">${{ number_format($order->total_amount ?? 0, 2) }}</dd>

                        <dt class="col-6 text-muted">Recharge Used</dt>
                        <dd class="col-6 text-end">৳ {{ number_format($order->recharge_used_amount ?? 0, 2) }}</dd>

                        <dt class="col-6 text-muted">Payable Amount</dt>
                        <dd class="col-6 text-end">
                            ${{ number_format(max(0, ($order->total_amount ?? 0) - ($order->recharge_used_amount ?? 0)), 2) }}
                        </dd>
                    </dl>
                    <p class="small text-muted mt-3 mb-0">
                        @if ($order->payment_method === 'wallet')
                            Amount deducted from customer's recharge balance.
                        @else
                            Payment receipt is required for gateway payments.
                        @endif
                    </p>
                    @if ($order->payment_receipt)
                        <div class="mt-4">
                            <h6 class="mb-2">Receipt</h6>
                            <a href="{{ asset('storage/' . $order->payment_receipt) }}" target="_blank">
                                <img src="{{ asset('storage/' . $order->payment_receipt) }}" alt="Payment Receipt"
                                    class="img-fluid rounded" style="max-height: 220px; object-fit: contain;" />
                            </a>
                            <p class="small text-muted mt-2 mb-0">Click image to open full size.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($order->note)
            <div class="col-12">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3">Order Note</h5>
                        <p class="mb-0">{{ $order->note }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-4">Order Details</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>${{ number_format($item->product_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <h5 class="mb-0">Total:
                    ${{ number_format(($order->total_amount ?? 0) + ($order->vip_discount_amount ?? 0), 2) }}</h5>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-4">Order Weight Details</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Unit Weight</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ number_format($item->product->weight, 2) }} kg</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->product->weight * $item->quantity, 2) }} kg</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <h5 class="mb-0">Total: {{ number_format($order->total_weight, 2) }} kg</h5>
            </div>
        </div>
    </div>

@endsection
