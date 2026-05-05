@extends('admin.layouts.app')
@section('title', 'Manage Complaints')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Complaints</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->id }}</td>
                                <td>{{ $complaint->user->name }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($complaint->subject, 50) }}</td>
                                <td>
                                    @if ($complaint->status === 'open')
                                        <span class="badge bg-warning text-dark">Open</span>
                                    @else
                                        <span class="badge bg-secondary">Closed</span>
                                    @endif
                                </td>
                                <td>{{ $complaint->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <a href="{{ route('admin.complaints.show', $complaint) }}"
                                        class="btn btn-sm btn-outline-primary">View / Reply</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No complaints found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($complaints->hasPages())
            <div class="card-footer border-0 bg-white">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>

@endsection
