<div class="row g-3">
    @include('admin.partials.multilingual-field', [
        'name' => 'name',
        'label' => ln('Category Name', 'বিভাগের নাম', '分类名称'),
        'model' => $productCategory ?? null,
        'colClass' => 'col-md-6',
        'requiredLocales' => ['bn', 'zh'],
    ])
    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $productCategory->slug ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label"> {{ ln('Sort Order', 'সর্ট অর্ডার', '排序') }} </label>
        <input type="number" name="sort_order" class="form-control"
            value="{{ old('sort_order', $productCategory->sort_order ?? 0) }}">
    </div>
    <div class="col-md-4 form-check mt-4">
        <input class="form-check-input" type="checkbox" name="status" value="1"
            {{ old('status', $productCategory->status ?? true) ? 'checked' : '' }}>
        <label class="form-check-label"> {{ ln('Active', 'সক্রিয়', '激活') }} </label>
    </div>
    @include('admin.partials.multilingual-field', [
        'name' => 'description',
        'label' => ln('Description', 'বিবরণ', '描述'),
        'model' => $productCategory ?? null,
        'type' => 'textarea',
        'rows' => 4,
        'colClass' => 'col-12',
        'requiredLocales' => ['bn', 'zh'],
    ])
</div>
