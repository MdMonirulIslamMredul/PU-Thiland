@extends('admin.layouts.app')
@section('title', ln('Dashboard', 'ড্যাশবোর্ড', '仪表板'))
@section('content')

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Current Month Sales (Taka)', 'বর্তমান মাসের বিক্রয় (টাকা)', '本月销售额 (塔卡)') }}</small>
                <h4>{{ number_format($currentMonthSales, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Current Month Sales (kg)', 'বর্তমান মাসের বিক্রয় (কেজি)', '本月销售额 (公斤)') }}</small>
                <h4>{{ number_format($currentMonthKg, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Current Year Sales (Taka)', 'বর্তমান বছরের বিক্রয় (টাকা)', '今年销售额 (塔卡)') }}</small>
                <h4>{{ number_format($currentYearSales, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Current Year Sales (kg)', 'বর্তমান বছরের বিক্রয় (কেজি)', '今年销售额 (公斤)') }}</small>
                <h4>{{ number_format($currentYearKg, 2) }}</h4>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln("Today's Total Profit (Taka)", 'আজকের মোট মুনাফা (টাকা)', '今日总利润 (塔卡)') }}</small>
                <h4>{{ number_format($todaySales, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small>
                    {{ ln('Current Month Total Profit (Taka)', 'বর্তমান মাসের মোট মুনাফা (টাকা)', '本月总利润 (塔卡)') }}</small>
                <h4>{{ number_format($currentMonthSales, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small>
                    {{ ln('Current Year Total Profit (Taka)', 'বর্তমান বছরের মোট মুনাফা (টাকা)', '今年总利润 (塔卡)') }}</small>
                <h4>{{ number_format($currentYearSales, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Total Assets', 'মোট সম্পদ', '总资产') }}</small>
                <h4>{{ number_format($totalAssets, 2) }}</h4>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Today Net Profit', 'আজকের নিট মুনাফা', '今日净利润') }}</small>
                <h4>{{ number_format($todayNetProfit, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Current Month Net Profit', 'বর্তমান মাসের নিট মুনাফা', '本月净利润') }}</small>
                <h4>{{ number_format($currentMonthNetProfit, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Current Year Net Profit', 'বর্তমান বছরের নিট মুনাফা', '今年净利润') }}</small>
                <h4>{{ number_format($currentYearNetProfit, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Current Month Expenses', 'বর্তমান মাসের ব্যয়', '本月费用') }}</small>
                <h4>{{ number_format($currentMonthExpenses, 2) }}</h4>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card p-3">
                <small
                    class="text-muted">{{ ln('Net profit = Total profit - All expenses', 'নিট মুনাফা = মোট মুনাফা - সমস্ত ব্যয়', '净利润 = 总利润 - 所有费用') }}</small>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Today Expenses', 'আজকের ব্যয়', '今日费用') }}</small>
                <h4>{{ number_format($todayExpenses, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Current Year Expenses', 'বর্তমান বছরের ব্যয়', '今年费用') }}</small>
                <h4>{{ number_format($currentYearExpenses, 2) }}</h4>
            </div>
        </div>
    </div>


    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Avg Price / kg This Month', 'বর্তমান মাসের গড় মূল্য (কেজি)', '本月平均价格 / 公斤') }}</small>
                <h4>{{ $currentMonthKg > 0 ? number_format($currentMonthAvgPrice, 2) : '0.00' }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Avg Price / kg This Year', 'বর্তমান বছরের গড় মূল্য (কেজি)', '今年平均价格 / 公斤') }}</small>
                <h4>{{ $currentYearKg > 0 ? number_format($currentYearAvgPrice, 2) : '0.00' }}</h4>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3">
                <small> {{ ln('Outstanding Orders', 'অপূর্ণ অর্ডার', '未完成订单') }}</small>
                <h4>{{ $outstandingOrders }}</h4>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5> {{ ln('Profit Trend (last 12 months)', 'মুনাফার ট্রেন্ড (শেষ 12 মাস)', '利润趋势 (最近 12 个月)') }}</h5>
        <div class="d-flex align-items-end gap-2" style="min-height: 200px;">
            @php
                $maxTrend = max(collect($salesTrend)->max('sales') ?: 1, collect($salesTrend)->max('netProfit') ?: 1);
            @endphp
            @foreach ($salesTrend as $period)
                <div class="text-center" style="flex:1;">
                    <div class="d-flex flex-column justify-content-end" style="height: 150px; gap: 4px;">
                        <div class="mx-auto bg-success"
                            style="height: {{ max(0, ($period['netProfit'] / $maxTrend) * 100) }}%; width: 40%; border-radius: 6px 6px 0 0;">
                        </div>
                        <div class="mx-auto bg-primary"
                            style="height: {{ max(0, ($period['sales'] / $maxTrend) * 100) }}%; width: 40%; border-radius: 6px 6px 0 0;">
                        </div>
                    </div>
                    <div class="mt-2 small">{{ $period['label'] }}</div>
                    <div class="small text-muted">{{ ln('Sales', 'বিক্রয়', '销售') }}:
                        {{ number_format($period['sales'], 0) }}</div>
                    <div class="small text-muted">{{ ln('Net', 'নিট', '净利润') }}:
                        {{ number_format($period['netProfit'], 0) }}</div>
                </div>
            @endforeach
        </div>
        <div class="mt-3 small text-muted">
            <span class="badge bg-primary">&nbsp;</span> {{ ln('Total profit', 'মোট মুনাফা', '总利润') }}
            <span class="badge bg-success ms-2">&nbsp;</span> {{ ln('Net profit', 'নিট মুনাফা', '净利润') }}
        </div>
    </div>

    <div class="card p-4">
        <h5> {{ ln('Recent Contacts', 'সাম্প্রতিক যোগাযোগ', '最近联系人') }}</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th> {{ ln('Name', 'নাম', '名称') }}</th>
                        <th> {{ ln('Email', 'ইমেইল', '邮箱') }}</th>
                        <th> {{ ln('Message', 'বার্তা', '消息') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentContacts as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($contact->message, 60) }}</td>
                    </tr>@empty<tr>
                            <td colspan="3">
                                {{ ln('No contact submissions yet.', 'এখনও কোন যোগাযোগ সাবমিট হয়নি।', '暂无联系人提交。') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
