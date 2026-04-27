<div class="row g-3">
    <div class="col-md-8">
        @include('admin.partials.translatable-field', [
            'name' => 'title',
            'label' => 'About Title',
            'model' => $about ?? null,
            'colClass' => 'col-md-12',
        ])
    </div>

    <div class="col-md-2">
        <label class="form-label">Years Experience</label>
        <input name="years_experience" type="number" min="0" class="form-control"
            value="{{ old('years_experience', $about->years_experience ?? '') }}">
    </div>

    <div class="col-md-2">
        <label class="form-label">Establishment Year</label>
        <input name="establishment_year" type="number" min="1800" class="form-control"
            value="{{ old('establishment_year', $about->establishment_year ?? '') }}">
    </div>

    <div class="col-12">
        @include('admin.partials.translatable-field', [
            'name' => 'page_details',
            'label' => 'Page Details',
            'model' => $about ?? null,
            'type' => 'textarea',
            'rows' => 6,
            'colClass' => 'col-12',
        ])
    </div>

    <div class="col-md-6">
        <label class="form-label">Banner Image</label>
        <input type="file" name="banner_image" class="form-control">
        @if (!empty($about->banner_image ?? null))
            <img src="{{ asset('storage/' . $about->banner_image) }}" class="img-fluid rounded mt-2"
                style="max-height: 120px; object-fit: cover;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_banner_image" value="1"
                    id="remove_banner_image">
                <label class="form-check-label" for="remove_banner_image">Remove image</label>
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">Key Values (one item per line)</label>
        <textarea name="key_values_text" class="form-control" rows="6">{{ old('key_values_text', isset($about) && is_array($about->key_values) ? implode(PHP_EOL, $about->key_values) : '') }}</textarea>
    </div>

    <div class="col-md-6">
        @include('admin.partials.translatable-field', [
            'name' => 'details1',
            'label' => 'Details 1',
            'model' => $about ?? null,
            'type' => 'textarea',
            'rows' => 5,
            'colClass' => 'col-md-12',
        ])
    </div>

    <div class="col-md-6">
        <label class="form-label">Image 1</label>
        <input type="file" name="image1" class="form-control">
        @if (!empty($about->image1 ?? null))
            <img src="{{ asset('storage/' . $about->image1) }}" class="img-fluid rounded mt-2"
                style="max-height: 120px; object-fit: cover;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_image1" value="1" id="remove_image1">
                <label class="form-check-label" for="remove_image1">Remove image</label>
            </div>
        @endif
    </div>

    <div class="col-md-6">
        @include('admin.partials.translatable-field', [
            'name' => 'details2',
            'label' => 'Details 2',
            'model' => $about ?? null,
            'type' => 'textarea',
            'rows' => 5,
            'colClass' => 'col-md-12',
        ])
    </div>

    <div class="col-md-6">
        <label class="form-label">Image 2</label>
        <input type="file" name="image2" class="form-control">
        @if (!empty($about->image2 ?? null))
            <img src="{{ asset('storage/' . $about->image2) }}" class="img-fluid rounded mt-2"
                style="max-height: 120px; object-fit: cover;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_image2" value="1" id="remove_image2">
                <label class="form-check-label" for="remove_image2">Remove image</label>
            </div>
        @endif
    </div>

    <div class="col-md-6">
        @include('admin.partials.translatable-field', [
            'name' => 'details3',
            'label' => 'Details 3',
            'model' => $about ?? null,
            'type' => 'textarea',
            'rows' => 5,
            'colClass' => 'col-md-12',
        ])
    </div>

    <div class="col-md-6">
        @include('admin.partials.translatable-field', [
            'name' => 'details4',
            'label' => 'Details 4',
            'model' => $about ?? null,
            'type' => 'textarea',
            'rows' => 5,
            'colClass' => 'col-md-12',
        ])
    </div>
</div>
