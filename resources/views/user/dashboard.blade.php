@extends('user.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $activeTab = request('tab', 'dashboard');
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
                                <a href="{{ route('dashboard', ['tab' => 'dashboard']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'dashboard' ? 'active' : '' }}">Dashboard</a>
                                <a href="{{ route('dashboard', ['tab' => 'orders']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'orders' ? 'active' : '' }}">Purchase
                                    History</a>
                                <a href="{{ route('dashboard', ['tab' => 'profile']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'profile' ? 'active' : '' }}">My
                                    Profile</a>
                                <a href="{{ route('dashboard', ['tab' => 'addresses']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'addresses' ? 'active' : '' }}">Addresses</a>
                                <a href="{{ route('dashboard', ['tab' => 'recharge']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'recharge' ? 'active' : '' }}">Recharge</a>
                                <a href="{{ route('dashboard', ['tab' => 'password']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'password' ? 'active' : '' }}">Change
                                    Password</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    @if ($activeTab === 'dashboard')
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">Current Month Purchase</small>
                                    <h5 class="mb-0">৳
                                        {{ number_format($dashboardStats['currentMonthPurchaseAmount'] ?? 0, 2) }}</h5>
                                    <p class="small text-muted mb-0">
                                        {{ $dashboardStats['currentMonthPurchaseItems'] ?? 0 }} items</p>
                                    <p class="small text-muted mb-0">
                                        {{ number_format($dashboardStats['currentMonthPurchaseWeight'] ?? 0, 2) }} kg</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">Total Purchase</small>
                                    <h5 class="mb-0">৳
                                        {{ number_format($dashboardStats['totalPurchaseAmount'] ?? 0, 2) }}</h5>
                                    <p class="small text-muted mb-0">{{ $dashboardStats['totalPurchaseItems'] ?? 0 }} items
                                    </p>
                                    <p class="small text-muted mb-0">
                                        {{ number_format($dashboardStats['totalPurchaseWeight'] ?? 0, 2) }} kg</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">Available Recharge</small>
                                    <h5 class="mb-0">৳ {{ number_format($dashboardStats['availableRecharge'] ?? 0, 2) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">Current Month Recharge</small>
                                    <h5 class="mb-0">৳
                                        {{ number_format($dashboardStats['currentMonthRecharge'] ?? 0, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">Total Recharge</small>
                                    <h5 class="mb-0">৳ {{ number_format($dashboardStats['totalRecharge'] ?? 0, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">VIP Level</small>
                                    <h5 class="mb-0">{{ $dashboardStats['vipLevel'] ?? 'None' }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">VIP Discount</small>
                                    <h5 class="mb-0">{{ number_format($dashboardStats['vipDiscount'] ?? 0, 2) }}/kg</h5>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">Next VIP Level</small>
                                    <h5 class="mb-1">{{ $dashboardStats['nextVipLevel'] ?? 'Top level' }}</h5>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ ($dashboardStats['nextVipProgress'] ?? 1) * 100 }}%;"
                                            aria-valuenow="{{ ($dashboardStats['nextVipProgress'] ?? 1) * 100 }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small
                                        class="text-muted">{{ number_format(($dashboardStats['nextVipProgress'] ?? 1) * 100, 0) }}%
                                        to next level</small>
                                </div>
                            </div>
                        </div>
                    @endif


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
                                        <label class="form-label">Email</label>
                                        <div class="form-control-plaintext">{{ $user->email }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <div class="form-control-plaintext">{{ $user->phone ?: '—' }}</div>
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
                    @elseif ($activeTab === 'addresses')
                        <div class="card shadow-sm mb-4" id="addresses">
                            <div class="card-body">
                                <h4 class="mb-3">Saved Delivery Addresses</h4>

                                @if ($addresses->isNotEmpty())
                                    <div class="row g-3 mb-4">
                                        @foreach ($addresses as $address)
                                            <div class="col-md-6">
                                                <div class="border rounded p-3 h-100">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <h6 class="mb-1">{{ $address->recipient_name }}</h6>
                                                            <p class="mb-1 text-muted">{{ $address->phone }}</p>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-sm btn-outline-secondary"
                                                                type="button"
                                                                onclick="document.getElementById('edit-address-{{ $address->id }}').classList.toggle('d-none')">
                                                                Edit
                                                            </button>
                                                            <form
                                                                action="{{ route('dashboard.addresses.destroy', $address) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <p class="mb-0">{{ $address->address }}</p>
                                                    @if ($address->label)
                                                        <div class="small text-muted mt-2">{{ $address->label }}</div>
                                                    @endif

                                                    <div id="edit-address-{{ $address->id }}"
                                                        class="d-none mt-3 p-3 border rounded bg-light">
                                                        <form action="{{ route('dashboard.addresses.update', $address) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="mb-3">
                                                                <label class="form-label">Recipient's Name</label>
                                                                <input type="text" name="recipient_name"
                                                                    class="form-control"
                                                                    value="{{ old('recipient_name', $address->recipient_name) }}"
                                                                    required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Mobile Number</label>
                                                                <input type="text" name="phone" class="form-control"
                                                                    value="{{ old('phone', $address->phone) }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Delivery Address</label>
                                                                <textarea name="address" rows="3" class="form-control" required>{{ old('address', $address->address) }}</textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Address Label (optional)</label>
                                                                <input type="text" name="label" class="form-control"
                                                                    value="{{ old('label', $address->label) }}">
                                                            </div>

                                                            <button type="submit" class="btn btn-sm btn-primary">Save
                                                                Changes</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info mb-4">You have no saved delivery addresses yet.</div>
                                @endif

                                @if ($addresses->count() < 3)
                                    <div class="card border">
                                        <div class="card-body">
                                            <h5 class="mb-3">Add New Address</h5>
                                            <form action="{{ route('dashboard.addresses.store') }}" method="POST">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="recipient_name" class="form-label">Recipient's
                                                        Name</label>
                                                    <input id="recipient_name" name="recipient_name" type="text"
                                                        class="form-control @error('recipient_name') is-invalid @enderror"
                                                        value="{{ old('recipient_name') }}" required>
                                                    @error('recipient_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Mobile Number</label>
                                                    <input id="phone" name="phone" type="text"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        value="{{ old('phone') }}" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Delivery Address</label>
                                                    <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                                                        required>{{ old('address') }}</textarea>
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="label" class="form-label">Address Label
                                                        (optional)</label>
                                                    <input id="label" name="label" type="text"
                                                        class="form-control @error('label') is-invalid @enderror"
                                                        value="{{ old('label') }}">
                                                    @error('label')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-primary">Save Address</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">You already have 3 saved addresses. Delete one to add
                                        another.</div>
                                @endif
                            </div>
                        </div>
                    @elseif ($activeTab === 'recharge')
                        <div class="card shadow-sm mb-4" id="recharge">
                            <div class="card-body">
                                <h4 class="mb-3">Recharge Account</h4>
                                <div class="mb-3">
                                    <label class="form-label">Available Recharge Balance</label>
                                    <div class="form-control-plaintext fw-bold">৳
                                        {{ number_format($user->recharge_amount ?? 0, 2) }}</div>
                                </div>

                                <div class="mb-4">
                                    <h5 class="mb-3">New Recharge Request</h5>
                                    <form action="{{ route('dashboard.recharge-orders.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label">Select Payment Gateway</label>
                                            <select name="payment_gateway_id"
                                                class="form-select @error('payment_gateway_id') is-invalid @enderror"
                                                required>
                                                <option value="">Choose a gateway</option>
                                                @foreach ($paymentGateways as $gateway)
                                                    <option value="{{ $gateway->id }}"
                                                        {{ old('payment_gateway_id') == $gateway->id ? 'selected' : '' }}>
                                                        {{ $gateway->mfs_name }} - {{ $gateway->account_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('payment_gateway_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Amount</label>
                                            <input type="number" name="amount" step="0.01" min="1"
                                                class="form-control @error('amount') is-invalid @enderror"
                                                value="{{ old('amount') }}" required>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Payment Method</label>
                                            <select name="payment_method"
                                                class="form-select @error('payment_method') is-invalid @enderror" required>
                                                <option value="">Select a method</option>
                                                <option value="bkash"
                                                    {{ old('payment_method') === 'bkash' ? 'selected' : '' }}>Bkash
                                                </option>
                                                <option value="rocket"
                                                    {{ old('payment_method') === 'rocket' ? 'selected' : '' }}>Rocket
                                                </option>
                                                <option value="nagad"
                                                    {{ old('payment_method') === 'nagad' ? 'selected' : '' }}>Nagad
                                                </option>
                                                <option value="bank"
                                                    {{ old('payment_method') === 'bank' ? 'selected' : '' }}>Bank Account
                                                </option>
                                            </select>
                                            @error('payment_method')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Upload Payment Proof</label>
                                            <input type="file" name="payment_proof"
                                                class="form-control @error('payment_proof') is-invalid @enderror"
                                                accept="image/*" required>
                                            @error('payment_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Submit Recharge Request</button>
                                    </form>
                                </div>

                                <div>
                                    <h5 class="mb-3">Recent Recharge Requests</h5>
                                    @if ($rechargeOrders->isNotEmpty())
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Amount</th>
                                                        <th>Gateway</th>
                                                        <th>Method</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($rechargeOrders as $order)
                                                        <tr>
                                                            <td>{{ $order->id }}</td>
                                                            <td>৳ {{ number_format($order->amount, 2) }}</td>
                                                            <td>{{ $order->paymentGateway->mfs_name }}</td>
                                                            <td>{{ ucfirst($order->payment_method) }}</td>
                                                            <td>{{ ucfirst($order->status) }}</td>
                                                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">You have not submitted any recharge requests yet.
                                        </div>
                                    @endif
                                </div>
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
                                                    <th>Actions</th>
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
                                                        <td>
                                                            <a href="{{ route('user.orders.show', $order) }}"
                                                                class="btn btn-sm btn-outline-primary">View</a>

                                                            <form action="{{ route('cart.reorder') }}" method="POST"
                                                                class="d-inline-block">
                                                                @csrf
                                                                <input type="hidden" name="order_id"
                                                                    value="{{ $order->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-success">Reorder</button>
                                                            </form>
                                                        </td>

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
        </div>
    </section>
@endsection
