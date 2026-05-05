@extends('admin.layouts.app')
@section('title', 'Manage Complaint')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Complaint #{{ $complaint->id }}: {{ $complaint->subject }}</h2>

        <div class="d-flex align-items-center mb-0 gap-2">
            <form action="{{ route('admin.complaints.status', $complaint) }}" method="POST" class="d-flex">
                @csrf
                @method('PUT')
                <select name="status" class="form-select form-select-sm d-inline w-auto me-2" onchange="this.form.submit()">
                    <option value="open" {{ $complaint->status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Chat History</h5>
                </div>
                <div class="card-body bg-light" style="max-height: 500px; overflow-y: auto;">
                    @foreach ($complaint->messages as $msg)
                        <div
                            class="d-flex mb-3 {{ $msg->user->is_admin ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="px-3 py-2 rounded-3 shadow-sm border"
                                style="max-width: 75%; background-color: {{ $msg->user->is_admin ? '#daf4f0' : '#fff' }};">
                                <div class="fw-bold mb-1 small text-muted">
                                    {{ $msg->user->is_admin ? 'You (Admin)' : 'User: ' . $msg->user->name }}
                                    <span class="ms-2 fw-normal"
                                        style="font-size:0.7rem;">{{ $msg->created_at->format('d M, h:i A') }}</span>
                                </div>
                                <div class="mb-0 overflow-auto">{!! nl2br(e($msg->message)) !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($complaint->status === 'open')
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Leave a Reply</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.complaints.reply', $complaint) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="message" class="form-control" rows="4" placeholder="Write your reply..." required></textarea>
                                @error('message')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-info py-3 mb-0 text-center">
                    This ticket is currently closed. Re-open it to reply.
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Complaint Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">Customer</dt>
                        <dd class="col-sm-8">{{ $complaint->user->name }}</dd>

                        <dt class="col-sm-4 text-muted">Email</dt>
                        <dd class="col-sm-8"><a
                                href="mailto:{{ $complaint->user->email }}">{{ $complaint->user->email }}</a></dd>

                        <dt class="col-sm-4 text-muted">Created</dt>
                        <dd class="col-sm-8">{{ $complaint->created_at->format('M d, Y h:i A') }}</dd>

                        <dt class="col-sm-4 text-muted">Last Updated</dt>
                        <dd class="col-sm-8">{{ $complaint->updated_at->diffForHumans() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

@endsection
