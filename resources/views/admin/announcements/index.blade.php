@extends('admin.layouts.app')

@section('title', ln('Announcements', 'ঘোষণা', '公告'))

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4> {{ ln('Announcements', 'ঘোষণা', '公告') }}</h4>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
            {{ ln('Add Announcement', 'ঘোষণা যোগ করুন', '添加公告') }}</a>
    </div>

    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('admin.announcements.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label"> {{ ln('Type', 'প্রকার', '类型') }}</label>
                <select name="type" class="form-select">
                    <option value=""> {{ ln('All types', 'সব প্রকার', '所有类型') }}</option>
                    @foreach (['notice' => 'Notice', 'announcement' => 'Announcement', 'update' => 'Update'] as $key => $label)
                        <option value="{{ $key }}"
                            {{ isset($filterType) && $filterType === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"> {{ ln('Publish Date From', 'প্রকাশের তারিখ থেকে', '发布日期从') }}</label>
                <input type="date" name="date_from" class="form-control" value="{{ $filterDateFrom ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label"> {{ ln('Publish Date To', 'প্রকাশের তারিখ পর্যন্ত', '发布日期到') }}</label>
                <input type="date" name="date_to" class="form-control" value="{{ $filterDateTo ?? '' }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"> {{ ln('Filter', 'ফিল্টার', '筛选') }}</button>
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
                    {{ ln('Reset', 'রিসেট', '重置') }}</a>
            </div>
        </form>
    </div>

    <div class="card p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ ln('Title', 'শিরোনাম', '标题') }} (EN / BN / ZH)</th>
                    <th>{{ ln('Type', 'প্রকার', '类型') }}</th>
                    <th>{{ ln('Priority', 'অগ্রাধিকার', '优先级') }}</th>
                    <th>{{ ln('Status', 'অবস্থা', '状态') }}</th>
                    <th>{{ ln('Publish', 'প্রকাশ', '发布') }}</th>
                    <th>{{ ln('Expiry', 'মেয়াদ শেষ', '过期') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                    <tr>
                        <td>
                            <div><span class="badge text-bg-light me-1">
                                    {{ ln('EN', 'English', '英文') }}</span>{{ $announcement->getTranslation('title', 'en', false) ?: '-' }}
                            </div>
                            <div><span class="badge text-bg-light me-1">
                                    {{ ln('BN', 'বাংলা', '中文') }}</span>{{ $announcement->getTranslation('title', 'bn', false) ?: '-' }}
                            </div>
                            <div><span class="badge text-bg-light me-1">
                                    {{ ln('ZH', '中文', 'Chinese') }}</span>{{ $announcement->getTranslation('title', 'zh', false) ?: '-' }}
                            </div>
                        </td>
                        <td>{{ ucfirst($announcement->type) }}</td>
                        <td>{{ ucfirst($announcement->priority) }}</td>
                        <td>{{ ucfirst($announcement->status) }}</td>
                        <td>{{ $announcement->publish_date?->format('Y-m-d H:i') ?? 'Immediate' }}</td>
                        <td>{{ $announcement->expiry_date?->format('Y-m-d H:i') ?? 'None' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                class="btn btn-sm btn-outline-primary"> {{ ln('Edit', 'সম্পাদনা', '编辑') }}</a>
                            <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete announcement?')"
                                    class="btn btn-sm btn-outline-danger"> {{ ln('Delete', 'মুছে ফেলুন', '删除') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"> {{ ln('No announcements found.', 'কোন ঘোষণা পাওয়া যায়নি।', '未找到公告。') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $announcements->links() }}
    </div>
@endsection
