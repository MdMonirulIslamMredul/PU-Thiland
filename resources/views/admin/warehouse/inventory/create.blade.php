@extends('admin.layouts.app')

@section('title', ln('Add Warehouse Inventory Item', 'গুদামের আইটেম যোগ করুন', '添加仓库库存项目'))

@section('content')
    <div class="mb-4">
        <h4>{{ ln('Add Warehouse Inventory Item', 'গুদামের আইটেম যোগ করুন', '添加仓库库存项目') }}</h4>
    </div>

    <div class="card p-4">
        <form action="{{ route('admin.inventory.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ ln('Product', 'পণ্য', '产品') }}</label>
                <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                    <option value=""> {{ ln('Select product', 'পণ্য নির্বাচন করুন', '选择产品') }} </option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->title }}</option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ ln('Grade', 'গ্রেড', '等级') }}</label>
                    <input type="text" name="grade" value="{{ old('grade') }}"
                        class="form-control @error('grade') is-invalid @enderror">
                    @error('grade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ ln('Specification', 'নির্দিষ্টকরণ', '规格') }}</label>
                    <input type="text" name="specification" value="{{ old('specification') }}"
                        class="form-control @error('specification') is-invalid @enderror">
                    @error('specification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ ln('Quantity', 'পরিমাণ', '数量') }} ({{ ln('kg', 'কেজি', 'kg') }})</label>
                    <input type="number" step="0.001" name="quantity_kg" value="{{ old('quantity_kg') }}"
                        class="form-control @error('quantity_kg') is-invalid @enderror" required>
                    @error('quantity_kg')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ ln('Unit Cost', 'একক খরচ', '单价') }}
                        ({{ ln('BDT', 'টাকা', 'BDT') }})</label>
                    <input type="number" step="0.01" name="unit_cost" value="{{ old('unit_cost') }}"
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
                <a href="{{ route('admin.inventory.index') }}"
                    class="btn btn-secondary">{{ ln('Cancel', 'বাতিল', '取消') }}</a>
                <button
                    class="btn btn-primary">{{ ln('Save Inventory Item', 'সংগ্রহস্থলের আইটেম সংরক্ষণ করুন', '保存库存项目') }}</button>
            </div>
        </form>
    </div>
@endsection
