<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" value="{{ old('title', $announcement->title ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input name="slug" class="form-control" value="{{ old('slug', $announcement->slug ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" required>
            @foreach (['notice' => 'Notice', 'announcement' => 'Announcement', 'update' => 'Update'] as $key => $label)
                <option value="{{ $key }}"
                    {{ old('type', $announcement->type ?? 'announcement') === $key ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Priority</label>
        <select name="priority" class="form-select" required>
            @foreach (['normal' => 'Normal', 'high' => 'High', 'urgent' => 'Urgent'] as $key => $label)
                <option value="{{ $key }}"
                    {{ old('priority', $announcement->priority ?? 'normal') === $key ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @foreach (['draft' => 'Draft', 'published' => 'Published'] as $key => $label)
                <option value="{{ $key }}"
                    {{ old('status', $announcement->status ?? 'draft') === $key ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Publish Date</label>
        <input type="datetime-local" name="publish_date" class="form-control"
            value="{{ old('publish_date', isset($announcement->publish_date) ? $announcement->publish_date->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Expiry Date</label>
        <input type="datetime-local" name="expiry_date" class="form-control"
            value="{{ old('expiry_date', isset($announcement->expiry_date) ? $announcement->expiry_date->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Short Description</label>
        <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $announcement->short_description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Full Details</label>
        <textarea name="body" class="form-control" rows="6">{{ old('body', $announcement->body ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control">
        @if (!empty($announcement->image))
            <div class="mt-2"><small>Current image: <a href="{{ asset('storage/' . $announcement->image) }}"
                        target="_blank">View</a></small></div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Attachment</label>
        <input type="file" name="attachment" class="form-control">
        @if (!empty($announcement->attachment))
            <div class="mt-2"><small>Current file: <a href="{{ asset('storage/' . $announcement->attachment) }}"
                        target="_blank">Download</a></small></div>
        @endif
    </div>
</div>
