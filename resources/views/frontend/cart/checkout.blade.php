@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card p-4">
                        <h3 class="section-title mb-3">Checkout</h3>
                        <p class="text-muted">Please review your cart and place your order.</p>

                        <div class="mb-4">
                            <h5>Customer</h5>
                            <p class="mb-1">{{ $user->name }}</p>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>

                        <form method="POST" action="{{ route('checkout.place') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <h5 class="mb-3">Delivery Address</h5>

                                @if ($addresses->isNotEmpty())
                                    <div class="mb-3">
                                        <label class="form-label">Saved Addresses</label>
                                        @foreach ($addresses as $address)
                                            <div class="form-check mb-2 p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="address_id"
                                                    id="address-{{ $address->id }}" value="{{ $address->id }}"
                                                    {{ old('address_id') == $address->id ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2" for="address-{{ $address->id }}">
                                                    <strong>{{ $address->recipient_name }}</strong>
                                                    <div>{{ $address->phone }}</div>
                                                    <div class="text-muted">{{ $address->address }}</div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-2">New Address</label>
                                        <small class="text-muted">Max saved addresses: 3</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Recipient's name</label>
                                        <input type="text" name="delivery_recipient_name"
                                            value="{{ old('delivery_recipient_name') }}"
                                            class="form-control @error('delivery_recipient_name') is-invalid @enderror"
                                            placeholder="Recipient's name">
                                        @error('delivery_recipient_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Mobile number</label>
                                        <input type="text" name="delivery_phone" value="{{ old('delivery_phone') }}"
                                            class="form-control @error('delivery_phone') is-invalid @enderror"
                                            placeholder="Mobile number">
                                        @error('delivery_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Delivery address</label>
                                        <textarea name="delivery_address" rows="3" class="form-control @error('delivery_address') is-invalid @enderror"
                                            placeholder="Street, city, postal code, etc.">{{ old('delivery_address') }}</textarea>
                                        @error('delivery_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="save_address"
                                            id="save_address" value="1" {{ old('save_address') ? 'checked' : '' }}
                                            {{ $addresses->count() >= 3 ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="save_address">
                                            Save this address for future orders
                                        </label>
                                    </div>
                                    @if ($addresses->count() >= 3)
                                        <div class="form-text text-danger">You already have 3 saved addresses. Delete one in
                                            your account to save a new address.</div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="mb-3">Payment</h5>

                                <div class="mb-3">
                                    <label class="form-label">Recharge Balance</label>
                                    <div class="form-control-plaintext fw-bold">৳
                                        {{ number_format($user->recharge_amount ?? 0, 2) }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select id="payment_method" name="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror">
                                        <option value="">Select payment method</option>
                                        <option value="wallet" {{ old('payment_method') === 'wallet' ? 'selected' : '' }}
                                            {{ ($user->recharge_amount ?? 0) <= 0 ? 'disabled' : '' }}>
                                            Use Recharge Balance (৳ {{ number_format($user->recharge_amount ?? 0, 2) }})
                                        </option>
                                        @foreach ($paymentGateways as $gateway)
                                            <option value="{{ strtolower($gateway->mfs_name) }}"
                                                data-id="{{ $gateway->id }}" data-name="{{ $gateway->mfs_name }}"
                                                data-account-name="{{ $gateway->account_name }}"
                                                data-account-number="{{ $gateway->account_number }}"
                                                data-bank-name="{{ $gateway->bank_name }}"
                                                {{ old('payment_method') === strtolower($gateway->mfs_name) ? 'selected' : '' }}>
                                                {{ $gateway->mfs_name }} — {{ $gateway->account_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" id="payment_gateway_id" name="payment_gateway_id"
                                    value="{{ old('payment_gateway_id') }}">

                                <div id="payment_gateway_details" class="mb-4 d-none border rounded p-3">
                                    <h6 class="mb-2">Gateway Details</h6>
                                    <p class="mb-1"><strong id="gateway_name"></strong></p>
                                    <p class="mb-1">Account Name: <span id="gateway_account_name"></span></p>
                                    <p class="mb-1">Account Number: <span id="gateway_account_number"></span></p>
                                    <p class="mb-0 text-muted">Bank: <span id="gateway_bank_name"></span></p>
                                </div>

                                <div id="payment_receipt_upload" class="mb-3 d-none">
                                    <label class="form-label">Upload Payment Receipt</label>
                                    <input type="file" name="payment_proof" accept="image/*"
                                        class="form-control @error('payment_proof') is-invalid @enderror">
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <p class="small text-muted">
                                    If you choose recharge balance, available balance will be deducted from your account.
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Order Note (optional)</label>
                                <textarea name="note" class="form-control" rows="4" placeholder="Add a note for your order...">{{ old('note') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Place Order</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card p-4">
                        <h5 class="mb-3">Order Summary</h5>
                        @foreach ($items as $item)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item['title'] }}</h6>
                                    <p class="mb-1 text-muted">Qty: {{ $item['quantity'] }}</p>
                                    <p class="mb-0">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach

                        @if (!empty($vipRule))
                            <div class="alert alert-info">
                                <strong>{{ ucfirst($vipRule->level_name) }} VIP</strong> active. Discount
                                {{ number_format($vipRule->discount_per_kg, 2) }} per kg.
                            </div>
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total weight</span>
                            <span>{{ $totalWeight }} Kg</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Subtotal</span>
                            <span>${{ $total }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>VIP Discount</span>
                            <span>${{ $vipDiscount }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Recharge Balance</span>
                            <span>৳ {{ number_format($user->recharge_amount ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Final Total</strong>
                            <span>${{ $finalTotal }}</span>
                        </div>

                        <div class="mt-4">
                            <h6>Available Payment Gateways</h6>
                            @if ($paymentGateways->isNotEmpty())
                                @foreach ($paymentGateways as $gateway)
                                    <div class="mb-2 p-3 border rounded">
                                        <strong>{{ $gateway->mfs_name }}</strong>
                                        <div>{{ $gateway->account_name }}</div>
                                        <div>{{ $gateway->account_number }}</div>
                                        @if ($gateway->bank_name)
                                            <div class="text-muted">{{ $gateway->bank_name }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="text-muted">No payment gateways are configured yet.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethod = document.getElementById('payment_method');
            const paymentGatewayIdField = document.getElementById('payment_gateway_id');
            const gatewayDetails = document.getElementById('payment_gateway_details');
            const gatewayReceiptUpload = document.getElementById('payment_receipt_upload');
            const gatewayName = document.getElementById('gateway_name');
            const gatewayAccountName = document.getElementById('gateway_account_name');
            const gatewayAccountNumber = document.getElementById('gateway_account_number');
            const gatewayBankName = document.getElementById('gateway_bank_name');

            function updateGatewayDetails() {
                const selectedOption = paymentMethod.selectedOptions[0];
                const method = paymentMethod.value;

                if (!method || method === 'wallet' || !selectedOption || !selectedOption.dataset.id) {
                    gatewayDetails.classList.add('d-none');
                    gatewayReceiptUpload.classList.add('d-none');
                    paymentGatewayIdField.value = '';
                    return;
                }

                paymentGatewayIdField.value = selectedOption.dataset.id;
                gatewayName.textContent = selectedOption.dataset.name || 'N/A';
                gatewayAccountName.textContent = selectedOption.dataset.accountName || 'N/A';
                gatewayAccountNumber.textContent = selectedOption.dataset.accountNumber || 'N/A';
                gatewayBankName.textContent = selectedOption.dataset.bankName || 'N/A';
                gatewayDetails.classList.remove('d-none');
                gatewayReceiptUpload.classList.remove('d-none');
            }

            paymentMethod.addEventListener('change', updateGatewayDetails);

            updateGatewayDetails();
        });
    </script>
@endsection
