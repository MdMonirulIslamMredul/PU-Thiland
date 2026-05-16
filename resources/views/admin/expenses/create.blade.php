@extends('admin.layouts.app')
@section('title', ln('Create Expense', 'ব্যয় তৈরি করুন', '创建费用'))
@section('content')
    <div class="card p-4">
        <h4 class="mb-3"> {{ ln('Create Expense', 'ব্যয় তৈরি করুন', '创建费用') }}</h4>

        <form method="POST" action="{{ route('admin.expenses.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label"> {{ ln('Category', 'বিভাগ', '类别') }}</label>
                <select name="expense_category_id" class="form-select" required>
                    <option value=""> {{ ln('Select category', 'বিভাগ নির্বাচন করুন', '选择类别') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('expense_category_id') == $category->id ? 'selected' : '' }}>
                            {{ ln($category->getTranslation('name', 'en', false), $category->getTranslation('name', 'bn', false), $category->getTranslation('name', 'zh', false)) }}
                        </option>
                    @endforeach
                </select>
                @error('expense_category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"> {{ ln('Amount', 'পরিমাণ', '金额') }}</label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}"
                        required>
                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label"> {{ ln('Expense Date', 'ব্যয়ের তারিখ', '费用日期') }}</label>
                    <input type="date" name="expense_date" class="form-control"
                        value="{{ old('expense_date', now()->format('Y-m-d')) }}" required>
                    @error('expense_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label"> {{ ln('Reference', 'রেফারেন্স', '参考') }}</label>
                    <input type="text" name="reference" class="form-control" value="{{ old('reference') }}">
                    @error('reference')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @include('admin.partials.multilingual-field', [
                'name' => 'description',
                'label' => ln('Description', 'বিবরণ', '描述'),
                'requiredLocales' => ['en', 'bn', 'zh'],
                'type' => 'textarea',
                'rows' => 4,
            ])

            <div class="mb-3">
                <label class="form-label"> {{ ln('Attachment', 'সংযুক্তি', '附件') }}</label>
                <input type="file" name="attachment" class="form-control">
                @error('attachment')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary"> {{ ln('Save', 'সংরক্ষণ করুন', '保存') }}</button>
        </form>
    </div>
@endsection
