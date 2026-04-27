@extends('admin.layouts.app')

@section('title', 'Recharge Order Details')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Recharge Order #{{ $order->id }}</h4>
            <a href="{{ route('admin.recharge-orders.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>User</h5>
                    <p class="mb-1">{{ $order->user->name }}</p>
                    <p class="text-muted mb-0">{{ $order->user->email }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Gateway</h5>
                    <p class="mb-1">{{ $order->paymentGateway->mfs_name }}</p>
                    <p class="text-muted mb-0">{{ $order->paymentGateway->account_number }}</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <h6>Amount</h6>
                    <p>{{ number_format($order->amount, 2) }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6>Payment Method</h6>
                    <p>{{ ucfirst($order->payment_method) }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6>Status</h6>
                    <p>{{ ucfirst($order->status) }}</p>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h5>Payment Proof</h5>
            @if ($order->payment_proof)
                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment proof" class="img-fluid rounded"
                    style="max-height: 320px;">
            @else
                <p class="text-muted">No proof uploaded.</p>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.recharge-orders.update', $order) }}">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Order Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $order->status === 'approved' ? 'selected' : '' }}>Approve</option>
                        <option value="rejected" {{ $order->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Admin Note</label>
                    <textarea name="admin_note" class="form-control" rows="3">{{ old('admin_note', $order->admin_note) }}</textarea>
                </div>
            </div>

            <button class="btn btn-primary">Update Status</button>
        </form>
    </div>
@endsection
