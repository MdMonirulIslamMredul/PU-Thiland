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
                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"> {{ ln('Save', 'সংরক্ষণ', '保存') }}</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary ms-2">
                {{ ln('Back', 'পিছনে', '返回') }}</a>
        </form>
    </div>
@endsection
