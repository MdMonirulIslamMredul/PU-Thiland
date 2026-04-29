@extends('admin.layouts.app')
@section('title', ln('Recharge Orders Report', 'রিচার্জ অর্ডার রিপোর্ট', '充值订单报告'))

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Noto+Sans+SC&display=swap" rel="stylesheet">
    <style>
        body,
        table,
        th,
        td {
            font-family: 'Noto Sans Bengali', 'Noto Sans SC', 'Segoe UI', sans-serif;
            font-size: 0.95rem;
        }

        .report-header {
            margin-bottom: 1.5rem;
        }

        .report-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        .report-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .report-summary-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.9rem;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 0.9rem;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #cbd5e1;
            padding: 0.65rem 0.75rem;
            vertical-align: top;
            word-wrap: break-word;
            white-space: normal;
        }

        .report-table th {
            background: #f1f5f9;
            text-align: left;
        }

        .no-print {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                background: #fff !important;
                color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .no-print,
            .breadcrumb,
            .btn,
            .sidebar,
            .topbar,
            .alert,
            .pagination {
                display: none !important;
            }

            .content {
                background: none !important;
                min-height: auto !important;
                padding: 0 !important;
            }

            .report-box {
                border-radius: 0;
                padding: 0.75rem;
            }

            .report-summary {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 0.5rem;
                margin-bottom: 0.75rem;
            }

            .report-summary-item {
                padding: 0.65rem;
            }

            .report-table th,
            .report-table td {
                padding: 0.55rem 0.6rem;
                font-size: 0.82rem;
            }

            h3,
            p {
                margin: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="report-header d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
        <div>
            <h3 class="mb-1">{{ ln('Recharge Orders Report', 'রিচার্জ অর্ডার রিপোর্ট', '充值订单报告') }}</h3>
            <p class="text-muted mb-0">
                {{ ln('Review filtered recharge orders and print a clean report view.', 'ফিল্টার করা রিচার্জ অর্ডারগুলি পর্যালোচনা করুন এবং একটি পরিষ্কার রিপোর্ট প্রিন্ট করুন।', '查看过滤后的充值订单并打印简洁报告视图。') }}
            </p>
        </div>
        <div class="no-print">
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer-fill"></i> {{ ln('Print Report', 'রিপোর্ট প্রিন্ট করুন', '打印报告') }}
            </button>
            <a href="{{ route('admin.recharge-orders.index', request()->query()) }}" class="btn btn-outline-secondary">
                {{ ln('Back to Recharge Orders', 'রিচার্জ অর্ডার তালিকায় ফিরে যান', '返回充值订单列表') }}</a>
        </div>
    </div>

    <div class="report-box">
        <div class="report-summary">
            <div class="report-summary-item">
                <strong>{{ ln('Status', 'স্ট্যাটাস', '状态') }}</strong>
                <div>{{ $filterStatus ?: ln('All statuses', 'সব স্ট্যাটাস', '所有状态') }}</div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('Search user', 'ব্যবহারকারী অনুসন্ধান', '搜索用户') }}</strong>
                <div>{{ $filterUserSearch ?: ln('Any', 'যে কোনও', '任何') }}</div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('Date From', 'তারিখ থেকে', '开始日期') }}</strong>
                <div>{{ $filterDateFrom ?: ln('Any', 'যে কোনও', '任何') }}</div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('Date To', 'তারিখ পর্যন্ত', '结束日期') }}</strong>
                <div>{{ $filterDateTo ?: ln('Any', 'যে কোনও', '任何') }}</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>{{ ln('Order', 'অর্ডার', '订单') }}</th>
                        <th>{{ ln('User', 'ব্যবহারকারী', '用户') }}</th>
                        <th>{{ ln('Amount', 'পরিমাণ', '金额') }}</th>
                        <th>{{ ln('Gateway', 'গেটওয়ে', '网关') }}</th>
                        <th>{{ ln('Payment Method', 'পেমেন্ট পদ্ধতি', '支付方式') }}</th>
                        <th>{{ ln('Status', 'স্ট্যাটাস', '状态') }}</th>
                        <th>{{ ln('Date', 'তারিখ', '日期') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                {{ $order->user->name }}
                                <div class="small text-muted mt-1">
                                    {{ $order->user->email }}@if ($order->user->phone)
                                        | {{ $order->user->phone }}
                                    @endif
                                </div>
                            </td>
                            <td>{{ number_format($order->amount, 2) }}</td>
                            <td>{{ $order->paymentGateway->mfs_name }}</td>
                            <td>{{ ucfirst($order->payment_method) }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    {{ ln('No recharge orders found.', 'রিচার্জ অর্ডার পাওয়া যায়নি।', '未找到充值订单。') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
