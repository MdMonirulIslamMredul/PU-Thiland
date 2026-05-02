@extends('admin.layouts.app')

@section('title', ln('Warehouse Inventory', 'গুদাম ইনভেন্টরি', '仓库库存'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>{{ ln('Warehouse Inventory', 'গুদাম ইনভেন্টরি', '仓库库存') }}</h4>
        <div>
            <a href="{{ route('admin.warehouse.dashboard') }}"
                class="btn btn-outline-secondary me-2">{{ ln('Back to Dashboard', 'ড্যাশবোর্ডে ফিরুন', '返回仪表板') }}</a>
            <a href="{{ route('admin.inventory.create') }}"
                class="btn btn-primary">{{ ln('Add Inventory Item', 'ইনভেন্টরি আইটেম যোগ করুন', '添加库存项目') }}</a>
        </div>
    </div>

    <div class="card p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>{{ ln('Product', 'পণ্য', '产品') }}</th>
                    <th>{{ ln('Grade', 'গ্রেড', '等级') }}</th>
                    <th>{{ ln('Specification', 'স্পেসিফিকেশন', '规格') }}</th>
                    <th>{{ ln('Quantity (kg)', 'পরিমাণ (কেজি)', '数量（公斤）') }}</th>
                    <th>{{ ln('Avg Cost (৳/kg)', 'গড় খরচ (৳/কেজি)', '平均成本（৳/公斤）') }}</th>
                    <th>{{ ln('Total Value', 'মোট মূল্য', '总价值') }}</th>
                    <th class="text-end">{{ ln('Actions', 'অ্যাকশন', '操作') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventoryItems as $item)
                    <tr>
                        <td>{{ $item->product?->getTranslation('title', app()->getLocale(), false) ?: $item->product?->getTranslation('title', 'en', false) ?: ln('Unknown', 'অজানা', '未知') }}
                        </td>
                        <td>{{ $item->grade ?? '—' }}</td>
                        <td>{{ $item->specification ?? '—' }}</td>
                        <td>{{ number_format($item->quantity_kg, 3) }}</td>
                        <td>{{ number_format($item->avg_cost, 4) }}</td>
                        <td>৳ {{ number_format($item->quantity_kg * $item->avg_cost, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.inventory.edit', $item) }}"
                                class="btn btn-sm btn-outline-primary">{{ ln('Edit', 'সম্পাদনা', '编辑') }}</a>
                            <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('{{ ln('Delete this inventory item?', 'এই ইনভেন্টরি আইটেম মুছবেন?', '删除此库存项目？') }}')">{{ ln('Delete', 'মুছুন', '删除') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            {{ ln('No inventory items found.', 'কোনও ইনভেন্টরি আইটেম পাওয়া যায়নি।', '未找到库存项目。') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
