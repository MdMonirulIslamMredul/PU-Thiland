@extends('frontend.layouts.app')

@section('title', __('site.checkout.title'))

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card p-4">
                        <h3 class="section-title mb-3">{{ __('site.checkout.heading') }}</h3>
                        <p class="text-muted">{{ __('site.checkout.please_review') }}</p>

                        @if (!empty($hasDueOrders))
                            <div class="alert alert-warning">
                                <strong>Notice:</strong> You have {{ $dueOrdersCount }} outstanding order(s) with a total
                                due amount of ৳{{ $dueAmountTotal }}.
                                Please settle existing dues before placing a new order.
                            </div>
                        @endif

                        <div class="mb-4">
                            <h5>{{ __('site.checkout.customer') }}</h5>
                            <p class="mb-1">{{ $user->name }}</p>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>

                        <form method="POST" action="{{ route('checkout.place') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <h5 class="mb-3">{{ __('site.checkout.delivery_address') }}</h5>

                                @if ($addresses->isNotEmpty())
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('site.checkout.saved_addresses') }}</label>
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

                                @if ($addresses->count() < 3)
                                    <div id="new_address_section" class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="form-label mb-2">{{ __('site.checkout.new_address') }}</label>
                                            <small class="text-muted">{{ __('site.checkout.max_saved_addresses') }}</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{{ __('site.checkout.recipient_name') }}</label>
                                            <input type="text" name="delivery_recipient_name"
                                                value="{{ old('delivery_recipient_name') }}"
                                                class="form-control @error('delivery_recipient_name') is-invalid @enderror"
                                                placeholder="Recipient's name">
                                            @error('delivery_recipient_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{{ __('site.checkout.mobile_number') }}</label>
                                            <input type="text" name="delivery_phone" value="{{ old('delivery_phone') }}"
                                                class="form-control @error('delivery_phone') is-invalid @enderror"
                                                placeholder="{{ __('site.checkout.mobile_number') }}">
                                            @error('delivery_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label
                                                class="form-label">{{ __('site.checkout.delivery_address_label') }}</label>
                                            <textarea name="delivery_address" rows="3" class="form-control @error('delivery_address') is-invalid @enderror"
                                                placeholder="{{ __('site.checkout.delivery_address_label') }}">{{ old('delivery_address') }}</textarea>
                                            @error('delivery_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="save_address"
                                                id="save_address" value="1"
                                                {{ old('save_address') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="save_address">
                                                {{ __('site.checkout.save_address') }}
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-text text-muted mb-3">
                                        You already have 3 saved addresses. Please select one from your existing addresses.
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <h5 class="mb-3">{{ __('site.checkout.payment') }}</h5>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('site.checkout.recharge_balance') }}</label>
                                    <div class="form-control-plaintext fw-bold">৳
                                        {{ number_format($user->recharge_amount ?? 0, 2) }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('site.checkout.payment_method') }}</label>
                                    <select id="payment_method" name="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror">
                                        <option value="">{{ __('site.checkout.select_payment_method') }}</option>
                                        <option value="wallet" {{ old('payment_method') === 'wallet' ? 'selected' : '' }}
                                            {{ ($user->recharge_amount ?? 0) <= 0 ? 'disabled' : '' }}>
                                            {{ __('site.checkout.use_recharge_balance') }} (৳
                                            {{ number_format($user->recharge_amount ?? 0, 2) }})
                                        </option>
                                        <option value="partial"
                                            {{ old('payment_method') === 'partial' ? 'selected' : '' }}>
                                            Partial Payment
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

                                @php
                                    $checkoutFinalTotal = (float) $finalTotal;
                                @endphp

                                <div id="partial_payment_section" class="mb-4 d-none border rounded p-3">
                                    <h6 class="mb-3">Partial payment details</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Partial payment method</label>
                                        <select id="partial_payment_method" name="partial_payment_method"
                                            class="form-select @error('partial_payment_method') is-invalid @enderror">
                                            <option value="">{{ __('site.checkout.select_payment_method') }}</option>
                                            <option value="wallet"
                                                {{ old('partial_payment_method') === 'wallet' ? 'selected' : '' }}>
                                                {{ __('site.checkout.use_recharge_balance') }} (৳
                                                {{ number_format($user->recharge_amount ?? 0, 2) }})
                                            </option>
                                            @foreach ($paymentGateways as $gateway)
                                                <option value="{{ strtolower($gateway->mfs_name) }}"
                                                    data-id="{{ $gateway->id }}" data-name="{{ $gateway->mfs_name }}"
                                                    data-account-name="{{ $gateway->account_name }}"
                                                    data-account-number="{{ $gateway->account_number }}"
                                                    data-bank-name="{{ $gateway->bank_name }}"
                                                    {{ old('partial_payment_method') === strtolower($gateway->mfs_name) ? 'selected' : '' }}>
                                                    {{ $gateway->mfs_name }} — {{ $gateway->account_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('partial_payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Payment amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="number" step="0.01" min="0.01" id="payment_amount"
                                                name="payment_amount"
                                                value="{{ old('payment_amount', number_format($checkoutFinalTotal, 2, '.', '')) }}"
                                                class="form-control @error('payment_amount') is-invalid @enderror"
                                                placeholder="0.00">
                                            @error('payment_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text">
                                            Enter the amount you want to pay now. The remaining amount will remain due.
                                        </div>
                                        <div class="form-text">
                                            Due after this payment: ৳<span
                                                id="computed_due_amount">{{ number_format(max(0, $checkoutFinalTotal - (float) old('payment_amount', $checkoutFinalTotal)), 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="payment_gateway_id" name="payment_gateway_id"
                                    value="{{ old('payment_gateway_id') }}">

                                <div id="payment_gateway_details" class="mb-4 d-none border rounded p-3">
                                    <h6 class="mb-2">{{ __('site.checkout.gateway_details') }}</h6>
                                    <p class="mb-1"><strong id="gateway_name"></strong></p>
                                    <p class="mb-1">{{ __('site.checkout.account_name') }}: <span
                                            id="gateway_account_name"></span></p>
                                    <p class="mb-1">{{ __('site.checkout.account_number') }}: <span
                                            id="gateway_account_number"></span></p>
                                    <p class="mb-0 text-muted">{{ __('site.checkout.bank') }}: <span
                                            id="gateway_bank_name"></span></p>
                                </div>

                                <div id="payment_receipt_upload" class="mb-3 d-none">
                                    <label class="form-label">{{ __('site.checkout.upload_payment_receipt') }}</label>
                                    <input type="file" name="payment_proof" accept="image/*"
                                        class="form-control @error('payment_proof') is-invalid @enderror">
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <p class="small text-muted">
                                    {{ __('site.checkout.if_use_recharge') }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('site.checkout.order_note') }}</label>
                                <textarea name="note" class="form-control" rows="4" placeholder="{{ __('site.checkout.order_note') }}">{{ old('note') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary"
                                {{ !empty($hasDueOrders) ? 'disabled' : '' }}>{{ __('site.checkout.place_order') }}</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card p-4">
                        <h5 class="mb-3">{{ __('site.checkout.order_summary') }}</h5>

                        <div class="table-responsive mb-3">
                            <table class="table table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('site.checkout.title') }}</th>
                                        <th>{{ __('site.checkout.weight') }}</th>
                                        <th>{{ __('site.checkout.qty') }}</th>

                                        <th class="text-end">{{ __('site.checkout.weight') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $item['title'] }}</td>
                                            <td>{{ $item['weight'] }} Kg</td>
                                            <td>{{ $item['quantity'] }}</td>

                                            <td class="text-end">
                                                {{ number_format($item['weight'] * $item['quantity'], 2) }} kg</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ __('site.cart.subtotal') }}</span>
                                <span>{{ number_format($totalWeight, 2) }} Kg</span>
                            </div>
                        </div>





                        <div class="table-responsive mb-3">
                            <table class="table table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('site.checkout.title') }}</th>
                                        <th>{{ __('site.checkout.unitprice') }}</th>
                                        <th>{{ __('site.checkout.qty') }}</th>

                                        <th class="text-end">{{ __('site.checkout.price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $item['title'] }}</td>
                                            <td>৳{{ number_format($item['price'], 2) }}</td>
                                            <td>{{ $item['quantity'] }}</td>

                                            <td class="text-end">
                                                ৳{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ __('site.cart.subtotal') }}</span>
                                <span>৳{{ $total }}</span>
                            </div>
                        </div>

                        @if (!empty($vipRule))
                            <div class="alert alert-info">
                                <strong>{{ ucfirst($vipRule->level_name) }} VIP</strong> active.
                                Discount {{ number_format($vipRule->discount_per_kg, 2) }} per kg,
                                saving ৳{{ number_format($vipDiscount, 2) }} on this order.
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ __('site.checkout.recharge_balance') }}</span>
                            <span>৳ {{ number_format($user->recharge_amount ?? 0, 2) }}</span>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ __('site.cart.subtotal') }}</span>
                            <span>৳{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ __('site.checkout.vip_discount') }}</span>
                            <span>৳{{ number_format($vipDiscount, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ __('site.checkout.final_total') }}</strong>
                            <span>৳{{ number_format($finalTotal, 2) }}</span>
                        </div>

                        <div class="mt-4">
                            <h6>{{ __('site.checkout.available_payment_gateways') }}</h6>
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
                                <div class="text-muted">{{ __('site.checkout.no_payment_gateways') }}</div>
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
            const partialPaymentSection = document.getElementById('partial_payment_section');
            const partialPaymentMethod = document.getElementById('partial_payment_method');
            const newAddressSection = document.getElementById('new_address_section');
            const addressRadios = document.querySelectorAll('input[name="address_id"]');
            const paymentGatewayIdField = document.getElementById('payment_gateway_id');
            const paymentAmountField = document.getElementById('payment_amount');
            const dueAmountText = document.getElementById('computed_due_amount');
            const finalTotalValue = parseFloat('{{ $checkoutFinalTotal }}') || 0;
            const gatewayDetails = document.getElementById('payment_gateway_details');
            const gatewayReceiptUpload = document.getElementById('payment_receipt_upload');
            const gatewayName = document.getElementById('gateway_name');
            const gatewayAccountName = document.getElementById('gateway_account_name');
            const gatewayAccountNumber = document.getElementById('gateway_account_number');
            const gatewayBankName = document.getElementById('gateway_bank_name');

            function updateDueAmount() {
                const paymentValue = parseFloat(paymentAmountField.value) || 0;
                const due = Math.max(0, finalTotalValue - paymentValue);
                dueAmountText.textContent = due.toFixed(2);
            }

            function updateNewAddressVisibility() {
                if (!newAddressSection || !addressRadios.length) {
                    return;
                }

                const selectedAddress = Array.from(addressRadios).some(radio => radio.checked);
                newAddressSection.classList.toggle('d-none', selectedAddress);
            }

            function updateGatewayDetails() {
                const method = paymentMethod.value;
                const isPartial = method === 'partial';
                const actualMethod = isPartial ? partialPaymentMethod.value : method;
                const selectedOption = isPartial ? partialPaymentMethod.selectedOptions[0] : paymentMethod
                    .selectedOptions[0];

                partialPaymentSection.classList.toggle('d-none', !isPartial);

                if (!actualMethod || actualMethod === 'wallet' || !selectedOption || !selectedOption.dataset.id) {
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
            partialPaymentMethod.addEventListener('change', updateGatewayDetails);
            if (paymentAmountField) {
                paymentAmountField.addEventListener('input', updateDueAmount);
            }
            if (addressRadios.length) {
                addressRadios.forEach(radio => radio.addEventListener('change', updateNewAddressVisibility));
            }

            updateGatewayDetails();
            updateDueAmount();
            updateNewAddressVisibility();
        });
    </script>
@endsection
