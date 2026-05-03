@extends('admin.layouts.app')
@section('title', ln('Update Order #' . $order->id, 'অর্ডার আপডেট #' . $order->id, '更新订单 #' . $order->id))
@section('content')
    <div class="mb-3">
        <h4> {{ ln('Update Order Status', 'অর্ডার স্ট্যাটাস আপডেট', '更新订单状态') }}</h4>
    </div>
    <div class="card p-4">
        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label"> {{ ln('Status', 'স্ট্যাটাস', '状态') }}</label>
                <select name="status" class="form-select">
                    @foreach ($statuses as $key => $label)
                        @php
                            $isBlockedSuccessful =
                                $order->payment_status === \App\Models\Order::PAYMENT_STATUS_PARTIAL &&
                                $key === \App\Models\Order::STATUS_SUCCESSFUL;
                        @endphp
                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}
                            {{ $isBlockedSuccessful && $order->status !== $key ? 'disabled' : '' }}>
                            {{ $label }}{{ $isBlockedSuccessful ? ' (' . ln('blocked until due is paid', 'বকেয়া পরিশোধ না হওয়া পর্যন্ত নিষিদ্ধ', '待付清后可用') . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @if ($order->payment_status === \App\Models\Order::PAYMENT_STATUS_PARTIAL)
                    <div class="form-text text-danger">
                        {{ ln('This order has due payment, so it cannot be changed to Successful until fully paid.', 'এই অর্ডারে বকেয়া আছে, তাই পুরো পরিশোধ না হওয়া পর্যন্ত Successful করা যাবে না।', '此订单仍有欠款，未全部付清前不能设为成功。') }}
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary"> {{ ln('Save', 'সংরক্ষণ', '保存') }}</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary ms-2">
                {{ ln('Back', 'পিছনে', '返回') }}</a>
        </form>
    </div>
@endsection
