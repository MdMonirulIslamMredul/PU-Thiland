<div class="mb-3">
    <label class="form-label"> {{ ln('MFS or Bank Name', 'এমএফএস বা ব্যাংকের নাম', 'MFS 或银行名称') }}</label>
    <input type="text" name="mfs_name" value="{{ old('mfs_name', $paymentGateway->mfs_name ?? '') }}"
        class="form-control @error('mfs_name') is-invalid @enderror" required>
    @error('mfs_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label"> {{ ln('Account Name', 'অ্যাকাউন্টের নাম', '账户名称') }}</label>
    <input type="text" name="account_name" value="{{ old('account_name', $paymentGateway->account_name ?? '') }}"
        class="form-control @error('account_name') is-invalid @enderror" required>
    @error('account_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label"> {{ ln('Account Number', 'অ্যাকাউন্ট নম্বর', '账户号码') }}</label>
    <input type="text" name="account_number"
        value="{{ old('account_number', $paymentGateway->account_number ?? '') }}"
        class="form-control @error('account_number') is-invalid @enderror" required>
    @error('account_number')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label"> {{ ln('Bank Name', 'ব্যাংকের নাম', '银行名称') }}</label>
    <input type="text" name="bank_name" value="{{ old('bank_name', $paymentGateway->bank_name ?? '') }}"
        class="form-control @error('bank_name') is-invalid @enderror">
    @error('bank_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
        {{ old('is_active', $paymentGateway->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active"> {{ ln('Active', 'সক্রিয়', '激活') }}</label>
</div>

<div class="mb-3">
    <label class="form-label"> {{ ln('Notes', 'নোটস', '备注') }}</label>
    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $paymentGateway->notes ?? '') }}</textarea>
    @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
