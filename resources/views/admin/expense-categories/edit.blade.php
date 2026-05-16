@extends('admin.layouts.app')
@section('title', ln('Edit Expense Category', 'ব্যয় বিভাগ সম্পাদনা', '编辑费用类别'))
@section('content')
    <div class="card p-4">
        <h4 class="mb-3"> {{ ln('Edit Expense Category', 'ব্যয় বিভাগ সম্পাদনা', '编辑费用类别') }}</h4>

        <form method="POST" action="{{ route('admin.expense-categories.update', $expenseCategory) }}">
            @csrf
            @method('PUT')

            @include('admin.partials.multilingual-field', [
                'name' => 'name',
                'label' => ln('Category Name', 'বিভাগের নাম', '类别名称'),
                'model' => $expenseCategory,
                'requiredLocales' => ['en', 'bn', 'zh'],
            ])

            <div class="mb-3">
                <label class="form-label"> {{ ln('Code', 'কোড', '代码') }}</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $expenseCategory->code) }}"
                    required>
                @error('code')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $expenseCategory->is_active) ? 'checked' : '' }}>
                <label class="form-check-label"> {{ ln('Active', 'সক্রিয়', '激活') }}</label>
            </div>

            <button class="btn btn-primary"> {{ ln('Update', 'আপডেট', '更新') }}</button>
        </form>
    </div>
@endsection
