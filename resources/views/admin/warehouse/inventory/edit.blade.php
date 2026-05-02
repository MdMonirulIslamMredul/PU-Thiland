@extends('admin.layouts.app')

@section('title', ln('Edit Warehouse Inventory Item', 'গুদামের আইটেম সম্পাদনা করুন', '编辑仓库库存项目'))

@section('content')
    <div class="mb-4">
        <h4>{{ ln('Edit Warehouse Inventory Item', 'গুদামের আইটেম সম্পাদনা করুন', '编辑仓库库存项目') }}</h4>
    </div>

    <div class="card p-4">
        <form action="{{ route('admin.inventory.update', $inventoryItem) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">{{ ln('Product', 'পণ্য', '产品') }}</label>
                <input type="text" class="form-control" value="{{ $inventoryItem->product?->title ?? 'Unknown' }}" readonly>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ ln('Grade', 'গ্রেড', '等级') }}</label>
                    <input type="text" name="grade" value="{{ old('grade', $inventoryItem->grade) }}"
                        class="form-control @error('grade') is-invalid @enderror">
                    @error('grade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ ln('Specification', 'নির্দিষ্টকরণ', '规格') }}</label>
                    <input type="text" name="specification"
                        value="{{ old('specification', $inventoryItem->specification) }}"
                        class="form-control @error('specification') is-invalid @enderror">
                    @error('specification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ ln('Quantity', 'পরিমাণ', '数量') }} ({{ ln('kg', 'কেজি', 'kg') }})</label>
                    <input type="number" step="0.001" name="quantity_kg"
                        value="{{ old('quantity_kg', $inventoryItem->quantity_kg) }}"
                        class="form-control @error('quantity_kg') is-invalid @enderror" required>
                    @error('quantity_kg')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ ln('Unit Cost', 'একক খরচ', '单价') }}
                        ({{ ln('BDT', 'টাকা', 'BDT') }})</label>
                    <input type="number" step="0.01" min="0" name="unit_cost"
                        value="{{ old('unit_cost', number_format((float) $inventoryItem->avg_cost, 2, '.', '')) }}"
                        class="form-control @error('unit_cost') is-invalid @enderror" required>
                    @error('unit_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ ln('Note', 'নোট', '备注') }}</label>
                <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="3">{{ old('note') }}</textarea>
                @error('note')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
                    {{ ln('Cancel', 'বাতিল', '取消') }}</a>
                <button class="btn btn-primary">
                    {{ ln('Update Inventory Item', 'সংগ্রহস্থলের আইটেম আপডেট করুন', '更新库存项目') }}</button>
            </div>
        </form>
    </div>
@endsection
