<div class="row g-3">
    @include('admin.partials.multilingual-field', [
        'name' => 'title',
        'label' => ln('Title', 'শিরোনাম', '标题'),
        'model' => $product ?? null,
        'colClass' => 'col-md-6',
        'requiredLocales' => ['bn', 'zh'],
    ])
    <div class="col-md-6"><label class="form-label"> {{ ln('Slug (in English)', 'স্লাগ (ইংরেজি)', 'URL Slug') }}
        </label><input name="slug" class="form-control" value="{{ old('slug', $product->slug ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Category', 'বিভাগ', '分类') }} </label>
        <select name="product_category_id" id="product_category_id" class="form-select">
            <option value=""> {{ ln('Select category', 'বিভাগ নির্বাচন করুন', '选择分类') }} </option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('product_category_id', $product->product_category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Subcategory', 'উপবিভাগ', '子分类') }} </label>
        <select name="product_subcategory_id" id="product_subcategory_id" class="form-select">
            <option value=""> {{ ln('Select subcategory', 'উপবিভাগ নির্বাচন করুন', '选择子分类') }} </option>
            @foreach ($subcategories as $subcategory)
                <option value="{{ $subcategory->id }}" data-category="{{ $subcategory->product_category_id }}"
                    {{ old('product_subcategory_id', $product->product_subcategory_id ?? '') == $subcategory->id ? 'selected' : '' }}>
                    {{ $subcategory->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Price', 'মূল্য', '价格') }} </label><input name="price"
            type="number" step="0.01" class="form-control" value="{{ old('price', $product->price ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Grade', 'গ্রেড', '等级') }} </label><input name="grade"
            class="form-control" value="{{ old('grade', $product->grade ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Open Price', 'খোলা মূল্য', '开盘价') }} </label><input
            name="open_price" type="number" step="0.01" class="form-control"
            value="{{ old('open_price', $product->open_price ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Quantity', 'পরিমাণ', '数量') }} </label><input
            name="quantity" type="number" step="0.01" class="form-control"
            value="{{ old('quantity', $product->quantity ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Unit Type', 'ইউনিট প্রকার', '单位类型') }} </label>
        <select name="unit_type" class="form-select">
            <option value=""> {{ ln('Select unit type', 'ইউনিট প্রকার নির্বাচন করুন', '选择单位类型') }} </option>
            <option value="piece" {{ old('unit_type', $product->unit_type ?? '') == 'piece' ? 'selected' : '' }}>
                {{ ln('Piece', 'পিস', '件') }} </option>
            <option value="weight" {{ old('unit_type', $product->unit_type ?? '') == 'weight' ? 'selected' : '' }}>
                {{ ln('Weight', 'ওজন', '重量') }}</option>
        </select>
    </div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Unit Name', 'ইউনিট নাম', '单位名称') }} </label><input
            name="unit_name" class="form-control" value="{{ old('unit_name', $product->unit_name ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Weight', 'ওজন', '重量') }} </label><input name="weight"
            type="number" step="0.01" class="form-control" value="{{ old('weight', $product->weight ?? '') }}">
    </div>
    <div class="col-12"><label class="form-label"> {{ ln('Specification', 'নির্দেশনা', '规格') }} </label>
        <textarea name="specification" class="form-control" rows="3">{{ old('specification', $product->specification ?? '') }}</textarea>
    </div>
    <div class="col-md-4"><label class="form-label"> {{ ln('Sort Order', 'সর্ট অর্ডার', '排序') }} </label><input
            name="sort_order" type="number" class="form-control"
            value="{{ old('sort_order', $product->sort_order ?? 0) }}"></div>
    <div class="col-md-2 form-check mt-4"><input class="form-check-input" type="checkbox" name="is_featured"
            value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}><label
            class="form-check-label"> {{ ln('Featured', 'ফিচারড', '特色') }} </label></div>
    <div class="col-md-2 form-check mt-4"><input class="form-check-input" type="checkbox" name="status" value="1"
            {{ old('status', $product->status ?? true) ? 'checked' : '' }}><label class="form-check-label">
            {{ ln('Active', 'সক্রিয়', '激活') }} </label></div>
    @include('admin.partials.multilingual-field', [
        'name' => 'short_description',
        'label' => ln('Short Description', 'সংক্ষিপ্ত বিবরণ', '简要描述'),
        'model' => $product ?? null,
        'type' => 'textarea',
        'rows' => 3,
        'colClass' => 'col-12',
        'requiredLocales' => ['bn', 'zh'],
    ])
    @include('admin.partials.multilingual-field', [
        'name' => 'description',
        'label' => ln('Description', 'বিবরণ', '描述'),
        'model' => $product ?? null,
        'type' => 'richtext',
        'rows' => 6,
        'colClass' => 'col-12',
        'requiredLocales' => ['bn', 'zh'],
    ])
    <div class="col-12"><label class="form-label"> {{ ln('Image', 'ছবি', '图片') }} </label><input type="file"
            name="image" class="form-control">

        @if (isset($product) && $product->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" style="max-width: 200px;">
            </div>
        @endif


        <!-- Image preview script -->
        <script>
            document.querySelector('input[name="image"]').addEventListener('change', function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var img = document.querySelector('.image-preview');
                    if (!img) {
                        img = document.createElement('img');
                        img.classList.add('image-preview', 'mt-2');
                        document.querySelector('input[name="image"]').parentNode.appendChild(img);
                    }
                    img.src = reader.result;
                    img.style.maxWidth = '200px';
                }
                reader.readAsDataURL(event.target.files[0]);
            });
        </script>


    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var categorySelect = document.getElementById('product_category_id');
            var subcategorySelect = document.getElementById('product_subcategory_id');
            if (!categorySelect || !subcategorySelect) {
                return;
            }

            var subcategories = Array.from(subcategorySelect.querySelectorAll('option'));

            function filterSubcategories() {
                var selectedCategory = categorySelect.value;
                subcategories.forEach(function(option) {
                    if (!option.value) {
                        option.hidden = false;
                        return;
                    }
                    option.hidden = selectedCategory ? option.dataset.category !== selectedCategory : false;
                });
            }

            categorySelect.addEventListener('change', filterSubcategories);
            filterSubcategories();
        });
    </script>
@endpush
