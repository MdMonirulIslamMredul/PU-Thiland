<div class="row g-3">
    @include('admin.partials.multilingual-field', [
        'name' => 'title',
        'label' => ln('Title', 'শিরোনাম', '标题'),
        'model' => $announcement ?? null,
        'colClass' => 'col-md-6',
        'requiredLocales' => ['en', 'bn', 'zh'],
    ])
    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input name="slug" class="form-control" value="{{ old('slug', $announcement->slug ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label"> {{ ln('Type', 'প্রকার', '类型') }}</label>
        <select name="type" class="form-select" required>
            @foreach (['notice' => 'Notice', 'announcement' => 'Announcement', 'update' => 'Update'] as $key => $label)
                <option value="{{ $key }}"
                    {{ old('type', $announcement->type ?? 'announcement') === $key ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label"> {{ ln('Priority', 'অগ্রাধিকার', '优先级') }}</label>
        <select name="priority" class="form-select" required>
            @foreach (['normal' => 'Normal', 'high' => 'High', 'urgent' => 'Urgent'] as $key => $label)
                <option value="{{ $key }}"
                    {{ old('priority', $announcement->priority ?? 'normal') === $key ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label"> {{ ln('Status', 'অবস্থা', '状态') }}</label>
        <select name="status" class="form-select" required>
            @foreach (['draft' => 'Draft', 'published' => 'Published'] as $key => $label)
                <option value="{{ $key }}"
                    {{ old('status', $announcement->status ?? 'draft') === $key ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label"> {{ ln('Publish Date', 'প্রকাশের তারিখ', '发布日期') }}</label>
        <input type="datetime-local" name="publish_date" class="form-control"
            value="{{ old('publish_date', isset($announcement->publish_date) ? $announcement->publish_date->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label"> {{ ln('Expiry Date', 'মেয়াদ শেষের তারিখ', '过期日期') }}</label>
        <input type="datetime-local" name="expiry_date" class="form-control"
            value="{{ old('expiry_date', isset($announcement->expiry_date) ? $announcement->expiry_date->format('Y-m-d\TH:i') : '') }}">
    </div>
    @include('admin.partials.multilingual-field', [
        'name' => 'short_description',
        'label' => ln('Short Description', 'সংক্ষিপ্ত বিবরণ', '简短描述'),
        'model' => $announcement ?? null,
        'type' => 'textarea',
        'rows' => 3,
        'colClass' => 'col-12',
        'requiredLocales' => ['en', 'bn', 'zh'],
    ])
    @include('admin.partials.multilingual-field', [
        'name' => 'body',
        'label' => ln('Full Details', 'পূর্ণ বিবরণ', '详细信息'),
        'model' => $announcement ?? null,
        'type' => 'richtext',
        'rows' => 6,
        'colClass' => 'col-12',
        'requiredLocales' => ['en', 'bn', 'zh'],
    ])
    <div class="col-md-6">
        <label class="form-label"> {{ ln('Image', 'ছবি', '图片') }}</label>
        <input type="file" name="image" class="form-control">
        @if (!empty($announcement->image))
            <div class="mt-2"><small> {{ ln('Current image', 'বর্তমান ছবি', '当前图片') }}: <a
                        href="{{ asset('storage/' . $announcement->image) }}" target="_blank">
                        {{ ln('View', 'দেখুন', '查看') }}</a></small></div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label"> {{ ln('Attachment', 'সংযুক্তি', '附件') }}</label>
        <input type="file" name="attachment" class="form-control">
        @if (!empty($announcement->attachment))
            <div class="mt-2"><small> {{ ln('Current file', 'বর্তমান ফাইল', '当前文件') }}: <a
                        href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank">
                        {{ ln('Download', 'ডাউনলোড', '下载') }}</a></small></div>
        @endif
    </div>
</div>
