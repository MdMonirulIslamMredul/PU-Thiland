@extends('frontend.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $activeTab = request('tab', 'orders');
    @endphp

    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="mb-2">Welcome, {{ $user->name }}</h1>
                    <p class="text-muted">Manage your profile, password, and purchase history from one place.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <h5 class="card-title">Account Menu</h5>
                            <div class="list-group list-group-flush mt-3">
                                <a href="{{ route('dashboard', ['tab' => 'orders']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'orders' ? 'active' : '' }}">Purchase
                                    History</a>
                                <a href="{{ route('dashboard', ['tab' => 'profile']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'profile' ? 'active' : '' }}">My
                                    Profile</a>
                                <a href="{{ route('dashboard', ['tab' => 'password']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'password' ? 'active' : '' }}">Change
                                    Password</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    @if ($activeTab === 'profile')
                        <div class="card shadow-sm mb-4" id="profile">
                            <div class="card-body">
                                <h4 class="mb-3">My Profile</h4>
                                <form action="{{ route('dashboard.profile.update') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $user->name) }}"
                                            class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $user->email) }}"
                                            class="form-control @error('email') is-invalid @enderror" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                        </div>
                    @elseif ($activeTab === 'password')
                        <div class="card shadow-sm mb-4" id="password">
                            <div class="card-body">
                                <h4 class="mb-3">Change Password</h4>
                                <form action="{{ route('dashboard.password.update') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" id="current_password" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror" required>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-warning">Change Password</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="card shadow-sm" id="orders">
                            <div class="card-body">
                                <h4 class="mb-3">Purchase History</h4>
                                @if (isset($orders) && $orders->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Items</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        <td>#{{ $order->id }}</td>
                                                        <td>{{ $order->items_count }}</td>
                                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                                        <td>{{ ucfirst($order->status) }}</td>
                                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info mb-0">You haven't placed any orders yet.</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
