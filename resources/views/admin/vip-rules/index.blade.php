@extends('admin.layouts.app')

@section('title', 'VIP Rules')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">VIP Rules</h4>
            <a href="{{ route('admin.vip-rules.create') }}" class="btn btn-primary">Add VIP Rule</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Discount/kg</th>
                    <th>Sales Range (kg)</th>
                    <th>Recharge Min</th>
                    <th>Priority</th>
                    <th>Expiry Days</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                    <tr>
                        <td>{{ ucfirst($rule->level_name) }}</td>
                        <td>{{ number_format($rule->discount_per_kg, 2) }}</td>
                        <td>{{ number_format($rule->min_sales_kg, 2) }} -
                            {{ $rule->max_sales_kg !== null ? number_format($rule->max_sales_kg, 2) : '∞' }}</td>
                        <td>{{ number_format($rule->min_recharge_amount, 2) }}</td>
                        <td>{{ $rule->priority }}</td>
                        <td>{{ $rule->expiry_days }}</td>
                        <td>{{ $rule->is_active ? 'Active' : 'Disabled' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.vip-rules.edit', $rule) }}"
                                class="btn btn-sm btn-outline-primary me-2">Edit</a>
                            <form action="{{ route('admin.vip-rules.destroy', $rule) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No VIP rules configured yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
