@extends('admin.layouts.app')

@section('title', 'Warehouse Dashboard')

@section('content')
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4">
                <h5 class="mb-3">Pending Picking Orders</h5>
                <p class="display-6 mb-0">{{ $stats['pendingPickingOrders'] }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h5 class="mb-3">Total Inventory Quantity</h5>
                <p class="display-6 mb-0">{{ number_format($stats['totalQuantity'], 3) }} kg</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h5 class="mb-3">Average Inventory Cost</h5>
                <p class="display-6 mb-0">৳ {{ number_format($stats['averageCost'], 4) }} /kg</p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-primary w-100">View Inventory</a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('admin.picking-orders.index') }}" class="btn btn-outline-secondary w-100">View Picking
                    Orders</a>
            </div>
        </div>
    </div>
@endsection
