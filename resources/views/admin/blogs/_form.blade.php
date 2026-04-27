<div class="row g-3">
    @include('admin.partials.multilingual-field', [
        'name' => 'title',
        'label' => 'Title',
        'model' => $blog ?? null,
        'colClass' => 'col-md-6',
        'requiredLocales' => ['bn', 'zh'],
    ])
    <div class="col-md-6"><label class="form-label">Slug</label><input name="slug" class="form-control"
            value="{{ old('slug', $blog->slug ?? '') }}"></div>
    @include('admin.partials.multilingual-field', [
        'name' => 'excerpt',
        'label' => 'Excerpt',
        'model' => $blog ?? null,
        'type' => 'textarea',
        'rows' => 2,
        'colClass' => 'col-12',
        'requiredLocales' => ['bn', 'zh'],
    ])
    @include('admin.partials.multilingual-field', [
        'name' => 'body',
        'label' => 'Body',
        'model' => $blog ?? null,
        'type' => 'richtext',
        'rows' => 8,
        'colClass' => 'col-12',
        'requiredLocales' => ['bn', 'zh'],
    ])
    @include('admin.partials.multilingual-field', [
        'name' => 'meta_title',
        'label' => 'Meta Title',
        'model' => $blog ?? null,
        'colClass' => 'col-md-4',
        'requiredLocales' => ['bn', 'zh'],
    ])
    @include('admin.partials.multilingual-field', [
        'name' => 'seo_keywords',
        'label' => 'SEO Keywords',
        'model' => $blog ?? null,
        'colClass' => 'col-md-4',
        'requiredLocales' => ['bn', 'zh'],
    ])
    <div class="col-md-4"><label class="form-label">Publish Date</label><input type="datetime-local" name="published_at"
            class="form-control"
            value="{{ old('published_at', isset($blog->published_at) ? $blog->published_at->format('Y-m-d\\TH:i') : '') }}">
    </div>
    @include('admin.partials.multilingual-field', [
        'name' => 'meta_description',
        'label' => 'Meta Description',
        'model' => $blog ?? null,
        'type' => 'textarea',
        'rows' => 2,
        'colClass' => 'col-12',
        'requiredLocales' => ['bn', 'zh'],
    ])
    <div class="col-md-6 form-check mt-4"><input class="form-check-input" type="checkbox" name="is_published"
            value="1" {{ old('is_published', $blog->is_published ?? true) ? 'checked' : '' }}><label
            class="form-check-label">Published</label></div>
    <div class="col-md-6"><label class="form-label">Featured Image</label><input type="file" name="image"
            class="form-control"></div>
</div>
