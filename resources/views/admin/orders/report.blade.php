@extends('admin.layouts.app')
@section('title', ln('Order Report', 'অর্ডার রিপোর্ট', '订单报告'))

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
                grid-template-columns: repeat(5, minmax(0, 1fr));
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
            <h3 class="mb-1">{{ ln('Order Report', 'অর্ডার রিপোর্ট', '订单报告') }}</h3>
            <p class="text-muted mb-0">
                {{ ln('Review filtered orders and print a clean report view.', 'ফিল্টার করা অর্ডারগুলি পর্যালোচনা করুন এবং একটি পরিষ্কার রিপোর্ট প্রিন্ট করুন।', '查看过滤后的订单并打印简洁报告视图。') }}
            </p>
        </div>
        <div class="no-print">
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer-fill"></i> {{ ln('Print Report', 'রিপোর্ট প্রিন্ট করুন', '打印报告') }}
            </button>
            <a href="{{ route('admin.orders.index', request()->query()) }}" class="btn btn-outline-secondary">
                {{ ln('Back to Orders', 'অর্ডার তালিকায় ফিরে যান', '返回订单列表') }}</a>
        </div>
    </div>

    <div class="report-box">
        <div class="report-summary">
            <div class="report-summary-item">
                <strong>{{ ln('Order Status', 'অর্ডার অবস্থা', '订单状态') }}</strong>
                <div>
                    {{ $status ? $statuses[$status] ?? $status : ln('All order statuses', 'সব অর্ডার অবস্থা', '所有订单状态') }}
                </div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('Payment Status', 'পেমেন্ট অবস্থা', '支付状态') }}</strong>
                <div>
                    {{ $payment_status ? $paymentStatuses[$payment_status] ?? $payment_status : ln('All payment statuses', 'সব পেমেন্ট অবস্থা', '所有支付状态') }}
                </div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('Search user', 'ব্যবহারকারী অনুসন্ধান করুন', '搜索用户') }}</strong>
                <div>{{ $user_search ?: ln('Any', 'যে কোনও', '任何') }}</div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('Start date', 'শুরুর তারিখ', '开始日期') }}</strong>
                <div>{{ $start_date ?: ln('Any', 'যে কোনও', '任何') }}</div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('End date', 'শেষের তারিখ', '结束日期') }}</strong>
                <div>{{ $end_date ?: ln('Any', 'যে কোনও', '任何') }}</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>{{ ln('Order', 'অর্ডার', '订单') }}</th>
                        <th>{{ ln('User', 'ব্যবহারকারী', '用户') }}</th>
                        <th>{{ ln('Total', 'মোট', '总计') }}</th>
                        <th>{{ ln('Payment Status', 'পেমেন্ট অবস্থা', '支付状态') }}</th>
                        <th>{{ ln('Order Status', 'অর্ডার অবস্থা', '订单状态') }}</th>
                        <th>{{ ln('Created', 'তৈরি হয়েছে', '创建时间') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                {{ $order->user->name }}
                                <div class="small text-muted mt-1">
                                    {{ $order->user->email }}@if ($order->user->phone)
                                        | {{ $order->user->phone }}
                                    @endif
                                </div>
                            </td>
                            <td>৳{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                {{ ucfirst($order->payment_status ?? 'unpaid') }}
                                @if ($order->payment_status === 'partial')
                                    <div>
                                        {{ ln('Paid', 'পরিশোধিত', '已付') }}:
                                        ৳{{ number_format($order->paid_amount ?? 0, 2) }}
                                    </div>
                                    <div>
                                        {{ ln('Due', 'বকেয়া', '欠款') }}: ৳{{ number_format($order->due_amount ?? 0, 2) }}
                                    </div>
                                @elseif($order->payment_status === 'unpaid')
                                    <div>
                                        {{ ln('Due', 'বকেয়া', '欠款') }}:
                                        ৳{{ number_format($order->due_amount ?? $order->total_amount, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    {{ ln('No orders found.', 'কোন অর্ডার পাওয়া যায়নি।', '未找到订单。') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
