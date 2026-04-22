@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small>Current Month Sales (Taka)</small>
                <h4>{{ number_format($currentMonthSales, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small>Current Month Sales (kg)</small>
                <h4>{{ number_format($currentMonthKg, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small>Current Year Sales (Taka)</small>
                <h4>{{ number_format($currentYearSales, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small>Current Year Sales (kg)</small>
                <h4>{{ number_format($currentYearKg, 2) }}</h4>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small>Avg Price / kg This Month</small>
                <h4>{{ $currentMonthKg > 0 ? number_format($currentMonthAvgPrice, 2) : '0.00' }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small>Avg Price / kg This Year</small>
                <h4>{{ $currentYearKg > 0 ? number_format($currentYearAvgPrice, 2) : '0.00' }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small>Outstanding Orders</small>
                <h4>{{ $outstandingOrders }}</h4>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5>Sales Trend (last 6 months)</h5>
        <div class="d-flex align-items-end gap-2" style="min-height: 180px;">
            @php
                $maxTrend = collect($salesTrend)->max('sales') ?: 1;
            @endphp
            @foreach ($salesTrend as $period)
                <div class="text-center" style="flex:1;">
                    <div class="bg-primary"
                        style="height: {{ ($period['sales'] / $maxTrend) * 100 }}%; width: 100%; border-radius: 6px 6px 0 0;">
                    </div>
                    <div class="mt-2 small">{{ $period['label'] }}</div>
                    <div class="small text-muted">{{ number_format($period['sales'], 0) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card p-4">
        <h5>Recent Contacts</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentContacts as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($contact->message, 60) }}</td>
                    </tr>@empty<tr>
                            <td colspan="3">No contact submissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
