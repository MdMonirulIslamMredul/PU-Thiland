<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="product_category_id" class="form-select" required>
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('product_category_id', $productSubcategory->product_category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    @include('admin.partials.translatable-field', [
        'name' => 'name',
        'label' => 'Name',
        'model' => $productSubcategory ?? null,
        'colClass' => 'col-md-6',
    ])
    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control"
            value="{{ old('slug', $productSubcategory->slug ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control"
            value="{{ old('sort_order', $productSubcategory->sort_order ?? 0) }}">
    </div>
    <div class="col-md-3 form-check mt-4">
        <input class="form-check-input" type="checkbox" name="status" value="1"
            {{ old('status', $productSubcategory->status ?? true) ? 'checked' : '' }}>
        <label class="form-check-label">Active</label>
    </div>
    <div class="col-12">
        @include('admin.partials.translatable-field', [
            'name' => 'description',
            'label' => 'Description',
            'model' => $productSubcategory ?? null,
            'type' => 'textarea',
            'rows' => 4,
            'colClass' => 'col-12',
        ])
    </div>
</div>
