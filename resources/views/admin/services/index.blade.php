@extends('admin.layouts.app')
@section('title', 'Services')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Services</h4><a href="{{ route('admin.services.create') }}" class="btn btn-primary">Add Service</a>
    </div>
    <div class="card p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Title (BN / ZH)</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td>
                            <div><span
                                    class="badge text-bg-light me-1">BN</span>{{ $service->getTranslation('title', 'bn', false) ?: '-' }}
                            </div>
                            <div><span
                                    class="badge text-bg-light me-1">ZH</span>{{ $service->getTranslation('title', 'zh', false) ?: '-' }}
                            </div>
                        </td>
                        <td>
                            @if ($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}"
                                    class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>

                        <td>{{ $service->status ? 'Active' : 'Inactive' }}</td>
                        <td class="text-end"><a href="{{ route('admin.services.edit', $service) }}"
                                class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="d-inline">
                                @csrf @method('DELETE')<button onclick="return confirm('Delete item?')"
                                    class="btn btn-sm btn-outline-danger">Delete</button></form>
                        </td>
                </tr>@empty<tr>
                        <td colspan="3">No services found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>{{ $services->links() }}
    </div>
@endsection
