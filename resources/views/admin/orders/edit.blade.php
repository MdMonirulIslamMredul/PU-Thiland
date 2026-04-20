@extends('admin.layouts.app')
@section('title', 'Update Order #' . $order->id)
@section('content')
    <div class="mb-3">
        <h4>Update Order Status</h4>
    </div>
    <div class="card p-4">
        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary ms-2">Back</a>
        </form>
    </div>
@endsection
