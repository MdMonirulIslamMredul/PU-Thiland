@extends('user.layouts.app')

@section('title', __('site.user.dashboard.title'))

@section('content')
    @php
        $activeTab = request('tab', 'dashboard');
    @endphp

    <section class="py-3">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="mb-2">{{ __('site.user.dashboard.welcome', ['name' => $user->name]) }}</h1>
                    <p class="text-muted">{{ __('site.user.dashboard.subtitle') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <h5 class="card-title">{{ __('site.user.dashboard.account_menu') }}</h5>
                            <div class="list-group list-group-flush mt-3">
                                <a href="{{ route('dashboard', ['tab' => 'dashboard']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'dashboard' ? 'active' : '' }}">{{ __('site.user.dashboard.dashboard_tab') }}</a>
                                <a href="{{ route('dashboard', ['tab' => 'orders']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'orders' ? 'active' : '' }}">{{ __('site.user.dashboard.purchase_history') }}</a>
                                <a href="{{ route('dashboard', ['tab' => 'profile']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'profile' ? 'active' : '' }}">{{ __('site.user.dashboard.profile') }}</a>
                                <a href="{{ route('dashboard', ['tab' => 'addresses']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'addresses' ? 'active' : '' }}">{{ __('site.user.dashboard.addresses') }}</a>
                                <a href="{{ route('dashboard', ['tab' => 'recharge']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'recharge' ? 'active' : '' }}">{{ __('site.user.dashboard.recharge') }}</a>
                                <a href="{{ route('dashboard', ['tab' => 'password']) }}"
                                    class="list-group-item list-group-item-action {{ $activeTab === 'password' ? 'active' : '' }}">{{ __('site.user.dashboard.change_password') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    @if ($activeTab === 'dashboard')
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small
                                        class="text-muted">{{ __('site.user.dashboard.current_month_purchase') }}</small>
                                    <h5 class="mb-0">৳
                                        {{ number_format($dashboardStats['currentMonthPurchaseAmount'] ?? 0, 2) }}</h5>
                                    <p class="small text-muted mb-0">
                                        {{ $dashboardStats['currentMonthPurchaseItems'] ?? 0 }}
                                        {{ __('site.user.dashboard.items') }}</p>
                                    <p class="small text-muted mb-0">
                                        {{ number_format($dashboardStats['currentMonthPurchaseWeight'] ?? 0, 2) }} kg</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">{{ __('site.user.dashboard.total_purchase') }}</small>
                                    <h5 class="mb-0">৳
                                        {{ number_format($dashboardStats['totalPurchaseAmount'] ?? 0, 2) }}</h5>
                                    <p class="small text-muted mb-0">{{ $dashboardStats['totalPurchaseItems'] ?? 0 }}
                                        {{ __('site.user.dashboard.items') }}
                                    </p>
                                    <p class="small text-muted mb-0">
                                        {{ number_format($dashboardStats['totalPurchaseWeight'] ?? 0, 2) }} kg</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">{{ __('site.user.dashboard.available_recharge') }}</small>
                                    <h5 class="mb-0">৳ {{ number_format($dashboardStats['availableRecharge'] ?? 0, 2) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small
                                        class="text-muted">{{ __('site.user.dashboard.current_month_recharge') }}</small>
                                    <h5 class="mb-0">৳
                                        {{ number_format($dashboardStats['currentMonthRecharge'] ?? 0, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">{{ __('site.user.dashboard.total_recharge') }}</small>
                                    <h5 class="mb-0">৳ {{ number_format($dashboardStats['totalRecharge'] ?? 0, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">{{ __('site.user.dashboard.vip_level') }}</small>
                                    <h5 class="mb-0">{{ $dashboardStats['vipLevel'] ?? __('site.user.dashboard.na') }}
                                    </h5>
                                    @if ($user->vip_level)
                                        <p class="small text-success mb-0">VIP active</p>
                                    @else
                                        <p class="small text-muted mb-0">VIP not active</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">VIP status</small>
                                    <h5 class="mb-0">
                                        @if ($user->vip_level && $user->vip_expiry_date && $user->vip_expiry_date->isFuture())
                                            <span class="badge bg-success">Active</span>
                                        @elseif ($user->vip_level)
                                            <span class="badge bg-warning">Expired</span>
                                        @else
                                            <span class="badge bg-secondary">None</span>
                                        @endif
                                    </h5>
                                    @if ($user->vip_expiry_date)
                                        <p class="small text-muted mb-0">Expires on
                                            {{ $user->vip_expiry_date->format('Y-m-d') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">{{ __('site.user.dashboard.vip_discount') }}</small>
                                    <h5 class="mb-0">
                                        ৳{{ number_format($dashboardStats['vipDiscount'] ?? 0, 2) }}/{{ __('site.products.kg') }}
                                    </h5>
                                    @if (($dashboardStats['vipDiscount'] ?? 0) <= 0)
                                        <p class="small text-muted mb-0">No VIP discount currently</p>
                                    @else
                                        <p class="small text-success mb-0">Discount applied</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card p-3">
                                    <small class="text-muted">{{ __('site.user.dashboard.next_vip_level') }}</small>
                                    <h5 class="mb-1">
                                        {{ $dashboardStats['nextVipLevel'] ?? __('site.user.dashboard.top_level') }}</h5>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ ($dashboardStats['nextVipProgress'] ?? 1) * 100 }}%;"
                                            aria-valuenow="{{ ($dashboardStats['nextVipProgress'] ?? 1) * 100 }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small
                                        class="text-muted">{{ __('site.user.dashboard.to_next_level', ['progress' => number_format(($dashboardStats['nextVipProgress'] ?? 1) * 100, 0)]) }}</small>
                                </div>
                            </div>
                        </div>
                    @endif


                    @if ($activeTab === 'profile')
                        <div class="card shadow-sm mb-4" id="profile">
                            <div class="card-body">
                                <h4 class="mb-3">{{ __('site.user.dashboard.profile') }}</h4>
                                <form action="{{ route('dashboard.profile.update') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name"
                                            class="form-label">{{ __('site.user.dashboard.name') }}</label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $user->name) }}"
                                            class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('site.user.dashboard.email') }}</label>
                                        <div class="form-control-plaintext">{{ $user->email }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('site.user.dashboard.phone') }}</label>
                                        <div class="form-control-plaintext">{{ $user->phone ?: '—' }}</div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-primary">{{ __('site.user.dashboard.update_profile') }}</button>
                                </form>
                            </div>
                        </div>
                    @elseif ($activeTab === 'password')
                        <div class="card shadow-sm mb-4" id="password">
                            <div class="card-body">
                                <h4 class="mb-3">{{ __('site.user.dashboard.change_password') }}</h4>
                                <form action="{{ route('dashboard.password.update') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="current_password"
                                            class="form-label">{{ __('site.user.dashboard.current_password') }}</label>
                                        <input type="password" id="current_password" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror" required>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password"
                                            class="form-label">{{ __('site.user.dashboard.new_password') }}</label>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation"
                                            class="form-label">{{ __('site.user.dashboard.confirm_new_password') }}</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" required>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-warning">{{ __('site.user.dashboard.change_password') }}</button>
                                </form>
                            </div>
                        </div>
                    @elseif ($activeTab === 'addresses')
                        <div class="card shadow-sm mb-4" id="addresses">
                            <div class="card-body">
                                <h4 class="mb-3">{{ __('site.user.dashboard.saved_delivery_addresses') }}</h4>

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
                                                                {{ __('site.user.dashboard.edit') }}</button>
                                                            <form
                                                                action="{{ route('dashboard.addresses.destroy', $address) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger">{{ __('site.user.dashboard.delete') }}</button>
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
                                                                <label
                                                                    class="form-label">{{ __('site.user.dashboard.recipient_name') }}</label>
                                                                <input type="text" name="recipient_name"
                                                                    class="form-control"
                                                                    value="{{ old('recipient_name', $address->recipient_name) }}"
                                                                    required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label
                                                                    class="form-label">{{ __('site.user.dashboard.mobile_number') }}</label>
                                                                <input type="text" name="phone" class="form-control"
                                                                    value="{{ old('phone', $address->phone) }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label
                                                                    class="form-label">{{ __('site.user.dashboard.delivery_address') }}</label>
                                                                <textarea name="address" rows="3" class="form-control" required>{{ old('address', $address->address) }}</textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label
                                                                    class="form-label">{{ __('site.user.dashboard.address_label_optional') }}</label>
                                                                value="{{ old('label', $address->label) }}">
                                                            </div>

                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary">{{ __('site.user.dashboard.save_changes') }}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info mb-4">{{ __('site.user.dashboard.no_saved_addresses') }}
                                    </div>
                                @endif

                                @if ($addresses->count() < 3)
                                    <div class="card border">
                                        <div class="card-body">
                                            <h5 class="mb-3">{{ __('site.user.dashboard.add_new_address') }}</h5>
                                            <form action="{{ route('dashboard.addresses.store') }}" method="POST">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="recipient_name"
                                                        class="form-label">{{ __('site.user.dashboard.recipient_name') }}</label>
                                                    <input id="recipient_name" name="recipient_name" type="text"
                                                        class="form-control @error('recipient_name') is-invalid @enderror"
                                                        value="{{ old('recipient_name') }}" required>
                                                    @error('recipient_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone"
                                                        class="form-label">{{ __('site.user.dashboard.mobile_number') }}</label>
                                                    <input id="phone" name="phone" type="text"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        value="{{ old('phone') }}" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="address"
                                                        class="form-label">{{ __('site.user.dashboard.delivery_address') }}</label>
                                                    <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                                                        required>{{ old('address') }}</textarea>
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="label"
                                                        class="form-label">{{ __('site.user.dashboard.address_label_optional') }}</label>
                                                    <input id="label" name="label" type="text"
                                                        class="form-control @error('label') is-invalid @enderror"
                                                        value="{{ old('label') }}">
                                                    @error('label')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <button type="submit"
                                                    class="btn btn-primary">{{ __('site.user.dashboard.save_address') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">{{ __('site.user.dashboard.delete_address_limit') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif ($activeTab === 'recharge')
                        <div class="card shadow-sm mb-4" id="recharge">
                            <div class="card-body">
                                <h4 class="mb-3">{{ __('site.user.dashboard.recharge_account') }}</h4>
                                <div class="mb-3">
                                    <label
                                        class="form-label">{{ __('site.user.dashboard.available_recharge_balance') }}</label>
                                    <div class="form-control-plaintext fw-bold">৳
                                        {{ number_format($user->recharge_amount ?? 0, 2) }}</div>
                                </div>

                                <div class="mb-4">
                                    <h5 class="mb-3">{{ __('site.user.dashboard.new_recharge_request') }}</h5>
                                    <form action="{{ route('dashboard.recharge-orders.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label
                                                class="form-label">{{ __('site.user.dashboard.select_payment_gateway') }}</label>
                                            <select name="payment_gateway_id"
                                                class="form-select @error('payment_gateway_id') is-invalid @enderror"
                                                required>
                                                <option value="">{{ __('site.user.dashboard.choose_gateway') }}
                                                </option>
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
                                            <label class="form-label">{{ __('site.user.dashboard.amount') }}</label>
                                            <input type="number" name="amount" step="0.01" min="1"
                                                class="form-control @error('amount') is-invalid @enderror"
                                                value="{{ old('amount') }}" required>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label
                                                class="form-label">{{ __('site.user.dashboard.payment_method') }}</label>
                                            <select name="payment_method"
                                                class="form-select @error('payment_method') is-invalid @enderror" required>
                                                <option value="">
                                                    {{ __('site.user.dashboard.choose_payment_method') }}</option>
                                                <option value="bkash"
                                                    {{ old('payment_method') === 'bkash' ? 'selected' : '' }}>
                                                    {{ __('site.user.dashboard.payment_methods.bkash') }}
                                                </option>
                                                <option value="rocket"
                                                    {{ old('payment_method') === 'rocket' ? 'selected' : '' }}>
                                                    {{ __('site.user.dashboard.payment_methods.rocket') }}
                                                </option>
                                                <option value="nagad"
                                                    {{ old('payment_method') === 'nagad' ? 'selected' : '' }}>
                                                    {{ __('site.user.dashboard.payment_methods.nagad') }}
                                                </option>
                                                <option value="bank"
                                                    {{ old('payment_method') === 'bank' ? 'selected' : '' }}>
                                                    {{ __('site.user.dashboard.payment_methods.bank') }}
                                                </option>
                                            </select>
                                            @error('payment_method')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label
                                                class="form-label">{{ __('site.user.dashboard.upload_payment_proof') }}</label>
                                            <input type="file" name="payment_proof"
                                                class="form-control @error('payment_proof') is-invalid @enderror"
                                                accept="image/*" required>
                                            @error('payment_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit"
                                            class="btn btn-primary">{{ __('site.user.dashboard.submit_recharge_request') }}</button>
                                    </form>
                                </div>

                                <div>
                                    <h5 class="mb-3">{{ __('site.user.dashboard.recent_recharge_requests') }}</h5>
                                    @if ($rechargeOrders->isNotEmpty())
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ __('site.user.dashboard.amount') }}</th>
                                                        <th>{{ __('site.user.dashboard.gateway') }}</th>
                                                        <th>{{ __('site.user.dashboard.method') }}</th>
                                                        <th>{{ __('site.user.dashboard.status') }}</th>
                                                        <th>{{ __('site.user.dashboard.date') }}</th>
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
                                        <div class="alert alert-info">
                                            {{ __('site.user.dashboard.no_recharge_requests') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card shadow-sm" id="orders">
                            <div class="card-body">
                                <h4 class="mb-3">{{ __('site.user.dashboard.purchase_history') }}</h4>
                                @if (isset($orders) && $orders->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('site.user.dashboard.order') }}</th>
                                                    <th>{{ __('site.user.dashboard.items') }}</th>
                                                    <th>{{ __('site.user.dashboard.total') }}</th>
                                                    <th>{{ __('site.user.dashboard.status') }}</th>
                                                    <!-- payment status added by me -->
                                                    <th>{{ __('site.user.dashboard.payment_status') }}</th>
                                                    <th>{{ __('site.user.dashboard.date') }}</th>
                                                    <th>{{ __('site.user.dashboard.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        <td>#{{ $order->id }}</td>
                                                        <td>{{ $order->items_count }}</td>
                                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                                        <td>{{ ucfirst($order->status) }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($order->payment_status ?? 'unpaid') }}</span>
                                                            @if ($order->payment_status === 'partial')
                                                                <div class="small mt-1">
                                                                    <span class="text-success">Paid:
                                                                        ৳{{ number_format($order->paid_amount ?? 0, 2) }}</span>
                                                                    <span class="text-danger ms-3">Due:
                                                                        ৳{{ number_format($order->due_amount ?? 0, 2) }}</span>
                                                                </div>
                                                            @elseif($order->payment_status === 'unpaid')
                                                                <div class="small mt-1 text-danger">Due:
                                                                    ৳{{ number_format($order->due_amount ?? $order->total_amount, 2) }}
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                                        <td>
                                                            <a href="{{ route('user.orders.show', $order) }}"
                                                                class="btn btn-sm btn-outline-primary">{{ __('site.user.dashboard.view') }}</a>

                                                            <form action="{{ route('cart.reorder') }}" method="POST"
                                                                class="d-inline-block">
                                                                @csrf
                                                                <input type="hidden" name="order_id"
                                                                    value="{{ $order->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-success">{{ __('site.user.dashboard.reorder') }}</button>
                                                            </form>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info mb-0">{{ __('site.user.dashboard.no_orders_yet') }}
                                    </div>
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
