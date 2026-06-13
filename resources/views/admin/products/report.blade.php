@extends('admin.layouts.app')
@section('title', ln('Product Report', 'পণ্য রিপোর্ট', '产品报告'))

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
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
            <h3 class="mb-1">{{ ln('Product Report', 'পণ্য রিপোর্ট', '产品报告') }}</h3>
            <p class="text-muted mb-0">
                {{ ln('Review filtered products and print a clean report view.', 'ফিল্টার করা পণ্যগুলি পর্যালোচনা করুন এবং একটি পরিষ্কার রিপোর্ট প্রিন্ট করুন।', '查看过滤后的产品并打印简洁报告视图。') }}
            </p>
        </div>
        <div class="no-print">
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer-fill"></i> {{ ln('Print Report', 'রিপোর্ট প্রিন্ট করুন', '打印报告') }}
            </button>
            <a href="{{ route('admin.products.index', request()->query()) }}" class="btn btn-outline-secondary">
                {{ ln('Back to Products', 'পণ্য তালিকায় ফিরে যান', '返回产品列表') }}</a>
        </div>
    </div>

    <div class="report-box">
        <div class="report-summary">
            <div class="report-summary-item">
                <strong>{{ ln('Search', 'অনুসন্ধান', '搜索') }}</strong>
                <div>{{ $search ?: ln('Any', 'যে কোনও', '任何') }}</div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('Category', 'বিভাগ', '分类') }}</strong>
                <div>
                    {{ optional($categories->firstWhere('id', $categoryId))->name ?: ln('All Categories', 'সব বিভাগ', '所有分类') }}
                </div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('Status', 'অবস্থা', '状态') }}</strong>
                <div>
                    @if ($status === '1')
                        {{ ln('Active', 'সক্রিয়', '激活') }}
                    @elseif ($status === '0')
                        {{ ln('Inactive', 'নিষ্ক্রিয়', '未激活') }}
                    @else
                        {{ ln('Any', 'যে কোনও', '任何') }}
                    @endif
                </div>
            </div>
            <div class="report-summary-item">
                <strong>{{ ln('Sort', 'সাজানো', '排序') }}</strong>
                <div>{{ ln(ucfirst($sortBy), ucfirst($sortBy), ucfirst($sortBy)) }} / {{ strtoupper($order) }}</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>{{ ln('Title EN', 'শিরোনাম EN', '标题 EN') }}</th>
                        <th>{{ ln('Title BN', 'শিরোনাম BN', '标题 BN') }}</th>
                        <th>{{ ln('Title ZH', 'শিরোনাম ZH', '标题 ZH') }}</th>
                        <th>{{ ln('Category', 'বিভাগ', '分类') }}</th>
                        <th>{{ ln('Subcategory', 'উপবিভাগ', '子分类') }}</th>
                        <th>{{ ln('Price', 'মূল্য', '价格') }}</th>
                        <th>{{ ln('Quantity', 'পরিমাণ', '数量') }}</th>
                        <th>{{ ln('Weight', 'ওজন', '重量') }}</th>
                        <th>{{ ln('Status', 'অবস্থা', '状态') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->getTranslation('title', 'en', false) ?: '-' }}</td>
                            <td>{{ $product->getTranslation('title', 'bn', false) ?: '-' }}</td>
                            <td>{{ $product->getTranslation('title', 'zh', false) ?: '-' }}</td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td>{{ $product->subcategory?->name ?? '-' }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->quantity ?? '-' }}</td>
                            <td>{{ $product->weight ? number_format($product->weight, 2) : '-' }}</td>
                            <td>{{ $product->status ? ln('Active', 'সক্রিয়', '激活') : ln('Inactive', 'নিষ্ক্রিয়', '未激活') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                {{ ln('No products found.', 'কোনও পণ্য পাওয়া যায়নি।', '未找到产品。') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
