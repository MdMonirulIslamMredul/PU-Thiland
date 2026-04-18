@extends('admin.layouts.app')
@section('title', 'Products')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Products</h4>
        <div>
            <a href="{{ route('admin.products.export.excel', request()->query()) }}"
                class="btn btn-outline-success me-2">Export Excel</a>
            <a href="{{ route('admin.products.export.pdf', request()->query()) }}"
                class="btn btn-outline-secondary me-2">Export PDF</a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
        </div>
    </div>

    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="search" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Search title, slug, description...">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Any status</option>
                    <option value="1" {{ $status === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $status === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort_by" class="form-select">
                    <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Newest</option>
                    <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Title</option>
                    <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}>Price</option>
                    <option value="status" {{ $sortBy === 'status' ? 'selected' : '' }}>Status</option>
                </select>
            </div>
            <div class="col-md-1">
                <select name="order" class="form-select">
                    <option value="desc" {{ $order === 'desc' ? 'selected' : '' }}>Desc</option>
                    <option value="asc" {{ $order === 'asc' ? 'selected' : '' }}>Asc</option>
                </select>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->title }}</td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td>{{ $product->subcategory?->name ?? '-' }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->status ? 'Active' : 'Inactive' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete item?')"
                                        class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $products->links() }}
    </div>
@endsection
