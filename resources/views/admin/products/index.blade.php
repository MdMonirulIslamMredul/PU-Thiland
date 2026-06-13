@extends('admin.layouts.app')
@section('title', ln('Products', 'পণ্যসমূহ', '产品'))
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4> {{ ln('Products', 'পণ্যসমূহ', '产品') }}</h4>
        <div>
            {{-- <a href="{{ route('admin.products.export.excel', request()->query()) }}" class="btn btn-outline-success me-2">
                {{ ln('Export Excel', 'এক্সেল রপ্তানি', '导出 Excel') }}</a>
            <a href="{{ route('admin.products.export.pdf', request()->query()) }}" class="btn btn-outline-secondary me-2">
                {{ ln('Export PDF', 'পিডিএফ রপ্তানি', '导出 PDF') }}</a> --}}
            <a href="{{ route('admin.products.report', request()->query()) }}" class="btn btn-outline-primary me-2">
                {{ ln('Printable Report', 'প্রিন্টেবল রিপোর্ট', '可打印报告') }}</a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                {{ ln('Add Product', 'পণ্য যোগ করুন', '添加产品') }}</a>
        </div>
    </div>

    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="search" name="search" value="{{ $search }}" class="form-control"
                    placeholder= "{{ ln('Search title, slug, description...', 'শিরোনাম, স্লাগ, বিবরণ অনুসন্ধান...', '搜索标题、别名、描述...') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value=""> {{ ln('All Categories', 'সব বিভাগ', '所有分类') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value=""> {{ ln('Any status', 'কোনও অবস্থা', '任何状态') }}</option>
                    <option value="1" {{ $status === '1' ? 'selected' : '' }}> {{ ln('Active', 'সক্রিয়', '激活') }}
                    </option>
                    <option value="0" {{ $status === '0' ? 'selected' : '' }}>
                        {{ ln('Inactive', 'নিষ্ক্রিয়', '未激活') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort_by" class="form-select">
                    <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>
                        {{ ln('Newest', 'সবচেয়ে নতুন', '最新') }}</option>
                    <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}> {{ ln('Title', 'শিরোনাম', '标题') }}
                    </option>
                    <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}> {{ ln('Price', 'মূল্য', '价格') }}
                    </option>
                    <option value="status" {{ $sortBy === 'status' ? 'selected' : '' }}>
                        {{ ln('Status', 'অবস্থা', '状态') }}</option>
                </select>
            </div>
            <div class="col-md-1">
                <select name="order" class="form-select">
                    <option value="desc" {{ $order === 'desc' ? 'selected' : '' }}> {{ ln('Desc', 'অবস্থা', '降序') }}
                    </option>
                    <option value="asc" {{ $order === 'asc' ? 'selected' : '' }}> {{ ln('Asc', 'অবস্থা', '升序') }}
                    </option>
                </select>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary"> {{ ln('Filter', 'ফিল্টার', '筛选') }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    {{ ln('Reset', 'রিসেট', '重置') }}</a>
            </div>
        </form>
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th> {{ ln('Title ( EN / BN / ZH)', 'শিরোনাম (English / বাংলা / চীনা)', '标题 (英文 / 孟加拉语 / 中文)') }}
                        </th>
                        <th> {{ ln('Category', 'বিভাগ', '分类') }}</th>
                        <th> {{ ln('Subcategory', 'উপবিভাগ', '子分类') }}</th>
                        <th> {{ ln('Price', 'মূল্য', '价格') }}</th>
                        <th> {{ ln('Weight', 'ওজন', '重量') }}</th>
                        <th> {{ ln('Status', 'অবস্থা', '状态') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div><span
                                        class="badge text-bg-light me-1">EN</span>{{ $product->getTranslation('title', 'en', false) ?: '-' }}
                                </div>
                                <div><span
                                        class="badge text-bg-light me-1">BN</span>{{ $product->getTranslation('title', 'bn', false) ?: '-' }}
                                </div>
                                <div><span
                                        class="badge text-bg-light me-1">ZH</span>{{ $product->getTranslation('title', 'zh', false) ?: '-' }}
                                </div>
                            </td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td>{{ $product->subcategory?->name ?? '-' }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->weight ? number_format($product->weight, 2) : '-' }}</td>
                            <td>{{ $product->status ? ln('Active', 'সক্রিয়', '激活') : ln('Inactive', 'নিষ্ক্রিয়', '未激活') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="btn btn-sm btn-outline-primary"> {{ ln('Edit', 'সম্পদনা', '编辑') }}</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete item?')" class="btn btn-sm btn-outline-danger">
                                        {{ ln('Delete', 'মুছে ফেলুন', '删除') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"> {{ ln('No products found.', 'কোনও পণ্য পাওয়া যায়নি।', '未找到产品。') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $products->links() }}
    </div>
@endsection
