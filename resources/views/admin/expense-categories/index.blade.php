@extends('admin.layouts.app')
@section('title', ln('Expense Categories', 'ব্যয় বিভাগ', '费用类别'))
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4> {{ ln('Expense Categories', 'ব্যয় বিভাগ', '费用类别') }}</h4>
        <a href="{{ route('admin.expense-categories.create') }}" class="btn btn-primary">
            {{ ln('Add Category', 'বিভাগ যোগ করুন', '添加类别') }}
        </a>
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th> {{ ln('Name', 'নাম', '名称') }}</th>
                        <th> {{ ln('Code', 'কোড', '代码') }}</th>
                        <th> {{ ln('Status', 'অবস্থা', '状态') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ ln($category->getTranslation('name', 'en', false), $category->getTranslation('name', 'bn', false), $category->getTranslation('name', 'zh', false)) }}
                            </td>
                            <td>{{ $category->code }}</td>
                            <td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.expense-categories.edit', $category) }}"
                                    class="btn btn-sm btn-outline-primary"> {{ ln('Edit', 'সম্পাদনা', '编辑') }}</a>
                                <form method="POST" action="{{ route('admin.expense-categories.destroy', $category) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete category?')"
                                        class="btn btn-sm btn-outline-danger">
                                        {{ ln('Delete', 'মুছে ফেলুন', '删除') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                {{ ln('No expense categories found.', 'কোন ব্যয় বিভাগ পাওয়া যায়নি.', '未找到费用类别。') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $categories->links() }}
    </div>
@endsection
