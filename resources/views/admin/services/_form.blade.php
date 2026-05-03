<div class="row g-3">
    @include('admin.partials.multilingual-field', [
        'name' => 'title',
        'label' => 'Title',
        'model' => $service ?? null,
        'colClass' => 'col-md-6',
        'requiredLocales' => ['bn', 'zh'],
    ])
    <div class="col-md-6"><label class="form-label">Slug</label><input name="slug" class="form-control"
            value="{{ old('slug', $service->slug ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Sort Order</label><input name="sort_order" type="number"
            class="form-control" value="{{ old('sort_order', $service->sort_order ?? 0) }}"></div>
    <div class="col-md-4 form-check mt-4"><input class="form-check-input" type="checkbox" name="is_featured"
            value="1" {{ old('is_featured', $service->is_featured ?? false) ? 'checked' : '' }}><label
            class="form-check-label">Featured</label></div>
    <div class="col-md-4 form-check mt-4"><input class="form-check-input" type="checkbox" name="status" value="1"
            {{ old('status', $service->status ?? true) ? 'checked' : '' }}><label
            class="form-check-label">Active</label></div>
    @include('admin.partials.multilingual-field', [
        'name' => 'short_description',
        'label' => 'Short Description',
        'model' => $service ?? null,
        'type' => 'textarea',
        'rows' => 3,
        'colClass' => 'col-12',
        'requiredLocales' => ['bn', 'zh'],
    ])
    @include('admin.partials.multilingual-field', [
        'name' => 'description',
        'label' => 'Description',
        'model' => $service ?? null,
        'type' => 'richtext',
        'rows' => 6,
        'colClass' => 'col-12',
        'requiredLocales' => ['bn', 'zh'],
    ])
    <div class="col-12"><label class="form-label">Image</label><input type="file" name="image"
            class="form-control"></div>
</div>
