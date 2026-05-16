@extends('admin.layouts.app')
@section('title', ln('Expenses', 'ব্যয়', '费用'))
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4> {{ ln('Expenses', 'ব্যয়', '费用') }}</h4>
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">
            {{ ln('Add Expense', 'ব্যয় যোগ করুন', '添加费用') }}</a>
    </div>

    <div class="card p-4 mb-4">
        <form method="GET" action="{{ route('admin.expenses.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label"> {{ ln('Category', 'বিভাগ', '类别') }}</label>
                <select name="expense_category_id" class="form-select">
                    <option value=""> {{ ln('All categories', 'সব বিভাগ', '所有类别') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('expense_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"> {{ ln('From Date', 'শুরুর তারিখ', '开始日期') }}</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label"> {{ ln('To Date', 'শেষের তারিখ', '结束日期') }}</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary"> {{ ln('Filter', 'ফিল্টার', '筛选') }}</button>
            </div>
        </form>
    </div>

    <div class="mb-3">
        <strong> {{ ln('Total Expense', 'মোট খরচ', '总费用') }}:</strong> {{ number_format($totalExpense, 2) }}
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th> {{ ln('Date', 'তারিখ', '日期') }}</th>
                        <th> {{ ln('Category', 'বিভাগ', '类别') }}</th>
                        <th> {{ ln('Amount', 'পরিমাণ', '金额') }}</th>
                        <th> {{ ln('Reference', 'রেফারেন্স', '参考') }}</th>
                        <th> {{ ln('Description', 'বিবরণ', '描述') }}</th>
                        <th> {{ ln('Attachment', 'সংযুক্তি', '附件') }}</th>
                        <th> {{ ln('Created By', 'তৈরি করেছেন', '创建者') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                            <td>{{ $expense->expenseCategory->name }}</td>
                            <td>{{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->reference }}</td>
                            <td>{{ $expense->description }}</td>
                            <td>
                                @if ($expense->attachment)
                                    <a href="{{ asset('storage/' . $expense->attachment) }}" target="_blank">View</a>
                                @endif
                            </td>
                            <td>{{ $expense->creator?->name ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.expenses.edit', $expense) }}"
                                    class="btn btn-sm btn-outline-primary"> {{ ln('Edit', 'সম্পাদনা', '编辑') }}</a>
                                <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete expense?')"
                                        class="btn btn-sm btn-outline-danger">
                                        {{ ln('Delete', 'মুছে ফেলুন', '删除') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">{{ ln('No expenses found.', 'কোন ব্যয় পাওয়া যায়নি.', '未找到费用。') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $expenses->links() }}
    </div>
@endsection
