<div class="mb-3">
    <label class="form-label">Level Name</label>
    <input type="text" name="level_name" value="{{ old('level_name', $vipRule->level_name ?? '') }}"
        class="form-control @error('level_name') is-invalid @enderror" placeholder="e.g. vip, silver, gold">
    @error('level_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row gx-3">
    <div class="col-md-6 mb-3">
        <label class="form-label">Discount Per Kg</label>
        <input type="number" step="0.01" name="discount_per_kg"
            value="{{ old('discount_per_kg', $vipRule->discount_per_kg ?? 0) }}"
            class="form-control @error('discount_per_kg') is-invalid @enderror">
        @error('discount_per_kg')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Expiry Days</label>
        <input type="number" name="expiry_days" value="{{ old('expiry_days', $vipRule->expiry_days ?? 30) }}"
            class="form-control @error('expiry_days') is-invalid @enderror">
        @error('expiry_days')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row gx-3">
    <div class="col-md-6 mb-3">
        <label class="form-label">Minimum Sales (kg)</label>
        <input type="number" step="0.01" name="min_sales_kg"
            value="{{ old('min_sales_kg', $vipRule->min_sales_kg ?? 0) }}"
            class="form-control @error('min_sales_kg') is-invalid @enderror">
        @error('min_sales_kg')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Maximum Sales (kg)</label>
        <input type="number" step="0.01" name="max_sales_kg"
            value="{{ old('max_sales_kg', $vipRule->max_sales_kg ?? '') }}"
            class="form-control @error('max_sales_kg') is-invalid @enderror">
        @error('max_sales_kg')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row gx-3">
    <div class="col-md-6 mb-3">
        <label class="form-label">Minimum Recharge Amount</label>
        <input type="number" step="0.01" name="min_recharge_amount"
            value="{{ old('min_recharge_amount', $vipRule->min_recharge_amount ?? 0) }}"
            class="form-control @error('min_recharge_amount') is-invalid @enderror">
        @error('min_recharge_amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Priority</label>
        <input type="number" name="priority" value="{{ old('priority', $vipRule->priority ?? 0) }}"
            class="form-control @error('priority') is-invalid @enderror">
        @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
        {{ old('is_active', $vipRule->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>
