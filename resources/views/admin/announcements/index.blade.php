@extends('admin.layouts.app')

@section('title', 'Announcements')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Announcements</h4>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">Add Announcement</a>
    </div>

    <div class="card p-3 mb-3">
        <form method="GET" action="{{ route('admin.announcements.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All types</option>
                    @foreach (['notice' => 'Notice', 'announcement' => 'Announcement', 'update' => 'Update'] as $key => $label)
                        <option value="{{ $key }}"
                            {{ isset($filterType) && $filterType === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Publish Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ $filterDateFrom ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Publish Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ $filterDateTo ?? '' }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="card p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Publish</th>
                    <th>Expiry</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->title }}</td>
                        <td>{{ ucfirst($announcement->type) }}</td>
                        <td>{{ ucfirst($announcement->priority) }}</td>
                        <td>{{ ucfirst($announcement->status) }}</td>
                        <td>{{ $announcement->publish_date?->format('Y-m-d H:i') ?? 'Immediate' }}</td>
                        <td>{{ $announcement->expiry_date?->format('Y-m-d H:i') ?? 'None' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete announcement?')"
                                    class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No announcements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $announcements->links() }}
    </div>
@endsection
