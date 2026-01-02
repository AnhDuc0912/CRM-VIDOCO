<h5 class="mt-4 mb-3">Tài Khoản Ngân Hàng</h5>
<div class="row g-3">
    <div class="col-6">
        <label class="form-label">Số Tài Khoản <span class="text-danger">*</span></label>
        <input type="text" name="bank_account[bank_account_number]"
            value="{{ old('bank_account.bank_account_number', $employee->bankAccount?->bank_account_number ?? '') ?? '' }}"
            class="form-control" required>
    </div>
    <div class="col-6">
        <label class="form-label">Chủ Tài Khoản <span class="text-danger">*</span></label>
        <input readonly type="text" name="bank_account[bank_account_name]"
            value="{{ old('bank_account.bank_account_name', $employee->full_name ?? '') ?? '' }}"
            class="form-control" required>
    </div>
    <div class="col-6">
        <label class="form-label">Chi Nhánh <span class="text-danger">*</span></label>
        <input type="text" name="bank_account[bank_branch]"
            value="{{ old('bank_account.bank_branch', $employee->bankAccount?->bank_branch ?? '') ?? '' }}"
            class="form-control" required>
    </div>
    <div class="col-6">
        <label class="form-label">Ngân Hàng <span class="text-danger">*</span></label>
        <input type="text" name="bank_account[bank_name]"
            value="{{ old('bank_account.bank_name', $employee->bankAccount?->bank_name ?? '') ?? '' }}"
            class="form-control" required>
    </div>
</div>
