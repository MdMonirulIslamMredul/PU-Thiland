@extends('admin.layouts.app')
@section('title', ln('Product Categories', 'পণ্য বিভাগ', '产品分类'))
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4> {{ ln('Product Categories', 'পণ্য বিভাগ', '产品分类') }} </h4>
        <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
            {{ ln('Add Category', 'নতুন বিভাগ যোগ করুন', '添加分类') }} </a>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th> {{ ln('Name', 'নাম', '名称') }} (EN / BN / ZH)</th>
                        <th> {{ ln('Slug', 'স্লাগ', 'Slug') }}</th>
                        <th> {{ ln('Sort Order', 'সর্ট অর্ডার', '排序') }}</th>
                        <th> {{ ln('Status', 'স্ট্যাটাস', '状态') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <div><span
                                        class="badge text-bg-light me-1">EN</span>{{ $category->getTranslation('name', 'en', false) ?: '-' }}
                                </div>
                                <div><span
                                        class="badge text-bg-light me-1">BN</span>{{ $category->getTranslation('name', 'bn', false) ?: '-' }}
                                </div>
                                <div><span
                                        class="badge text-bg-light me-1">ZH</span>{{ $category->getTranslation('name', 'zh', false) ?: '-' }}
                                </div>
                            </td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->sort_order }}</td>
                            <td>{{ $category->status ? ln('Active', 'সক্রিয়', '激活') : ln('Inactive', 'নিষ্ক্রিয়', '未激活') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.product-categories.edit', $category) }}"
                                    class="btn btn-sm btn-outline-primary"> {{ ln('Edit', 'সম্পাদনা করুন', '编辑') }} </a>
                                <form method="POST" action="{{ route('admin.product-categories.destroy', $category) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete category?')"
                                        class="btn btn-sm btn-outline-danger"> {{ ln('Delete', 'মুছে ফেলুন', '删除') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"> {{ ln('No categories found.', 'কোন বিভাগ পাওয়া যায়নি.', '未找到分类.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $categories->links() }}
    </div>
@endsection
