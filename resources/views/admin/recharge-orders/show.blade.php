@extends('admin.layouts.app')

@section('title', ln('Recharge Order Details', 'রিচার্জ অর্ডার বিবরণ', '充值订单详情'))

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                {{ ln('Recharge Order #' . $order->id, 'রিচার্জ অর্ডার #' . $order->id, '充值订单 #' . $order->id) }}
            </h4>

            <a href="{{ route('admin.recharge-orders.index') }}" class="btn btn-secondary">
                {{ ln('Back', 'ফিরে যান', '返回') }}</a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5> {{ ln('User', 'ব্যবহারকারী', '用户') }}</h5>
                    <p class="mb-1">{{ $order->user->name }}</p>
                    <p class="text-muted mb-0">{{ $order->user->email }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5> {{ ln('Gateway', 'গেটওয়ে', '网关') }}</h5>
                    <p class="mb-1">{{ $order->paymentGateway->mfs_name }}</p>
                    <p class="text-muted mb-0">{{ $order->paymentGateway->account_number }}</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <h6> {{ ln('Amount', 'পরিমাণ', '金额') }}</h6>
                    <p>{{ number_format($order->amount, 2) }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6> {{ ln('Payment Method', 'পেমেন্ট পদ্ধতি', '支付方式') }}</h6>
                    <p>{{ ucfirst($order->payment_method) }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6> {{ ln('Status', 'স্ট্যাটাস', '状态') }}</h6>
                    <p>{{ match ($order->status) {
                        'approved' => __('admin.approved'),
                        'rejected' => __('admin.rejected'),
                        default => ucfirst($order->status),
                    } }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h5> {{ ln('Payment Proof', 'পেমেন্ট প্রোফ', '支付凭证') }}</h5>
            @if ($order->payment_proof)
                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment proof" class="img-fluid rounded"
                    style="max-height: 320px;">
            @else
                <p class="text-muted"> {{ ln('No proof uploaded.', 'প্রোফ আপলোড করা হয়নি।', '未上传凭证。') }}</p>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.recharge-orders.update', $order) }}">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label"> {{ ln('Order Status', 'অর্ডার স্ট্যাটাস', '订单状态') }}</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                            Pending</option>
                        <option value="approved" {{ $order->status === 'approved' ? 'selected' : '' }}>
                            Approved</option>
                        <option value="rejected" {{ $order->status === 'rejected' ? 'selected' : '' }}>
                            Rejected</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label"> {{ ln('Admin Note', 'অ্যাডমিন নোট', '管理员备注') }}</label>
                    <textarea name="admin_note" class="form-control" rows="3">{{ old('admin_note', $order->admin_note) }}</textarea>
                </div>
            </div>

            <button class="btn btn-primary"> {{ ln('Update Status', 'স্ট্যাটাস আপডেট করুন', '更新状态') }}</button>
        </form>
    </div>
@endsection
