<h5 class="mt-4 mb-3">Tài Khoản Ngân Hàng</h5>
<div class="row g-3">
    <div class="col-6">
        <label class="form-label">Số Tài Khoản</label>
        <input type="text" readonly value="{{ $employee->bankAccount?->bank_account_number ?? '' }}"
            class="form-control">
    </div>
    <div class="col-6">
        <label class="form-label">Chủ Tài Khoản</label>
        <input type="text" readonly value="{{ $employee->full_name ?? '' }}" class="form-control">
    </div>
    <div class="col-6">
        <label class="form-label">Chi Nhánh</label>
        <input type="text" readonly value="{{ $employee->bankAccount?->bank_branch ?? '' }}" class="form-control">
    </div>
    <div class="col-6">
        <label class="form-label">Ngân Hàng</label>
        <input type="text" readonly value="{{ $employee->bankAccount?->bank_name ?? '' }}" class="form-control">
    </div>
</div>
