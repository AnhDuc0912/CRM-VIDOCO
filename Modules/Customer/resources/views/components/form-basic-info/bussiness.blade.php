@use('Modules\Core\Enums\GenderEnum')
@use('Modules\Customer\Enums\SourceCustomerEnum')
@use('Modules\Customer\Enums\SalutationEnum')

<div id="form-company" style="display:none;">
    <div class="row g-3 mb-4">
        <div class="col-3">
            <label class="form-label required">Nguồn khách hàng</label>
            <select class="form-select" name="company[source_customer]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>
                <option value="">-- Lựa chọn --</option>
                @foreach (SourceCustomerEnum::getLabel() as $key => $value)
                    <option value="{{ $key }}"
                        {{ old('company.source_customer') ? (old('company.source_customer') == $key ? 'selected' : '') : (!empty($customer) ? ($customer->source_customer == $key ? 'selected' : '') : '') }}>
                        {{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label class="form-label required">Nhân viên chăm sóc KH</label>
            <select class="form-select" name="company[person_incharge]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>
                <option value="">-- Lựa chọn --</option>
                @foreach ($salesPersons as $salesPerson)
                    <option value="{{ $salesPerson->id }}"
                        {{ old('company.person_incharge') ? (old('company.person_incharge') == $salesPerson->id ? 'selected' : '') : (!empty($customer) && $customer->person_incharge == $salesPerson->id ? 'selected' : '') }}>
                        {{ $salesPerson->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label class="form-label required">Nhân viên Sale</label>
            <select class="form-select" name="company[sales_person]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>
                <option value="">-- Lựa chọn --</option>
                @foreach ($salesPersons as $salesPerson)
                    <option value="{{ $salesPerson->id }}"
                        {{ old('company.sales_person') ? (old('company.sales_person') == $salesPerson->id ? 'selected' : '') : (!empty($customer) && $customer->sales_person == $salesPerson->id ? 'selected' : '') }}>
                        {{ $salesPerson->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label class="form-label required">Tên công ty</label>
            <input type="text" class="form-control" name="company[company_name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.company_name') ?? (!empty($customer) ? $customer->company_name : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label required">Mã số thuế</label>
            <input type="text" class="form-control" name="company[tax_code]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.tax_code') ?? (!empty($customer) ? $customer->tax_code : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Ngày thành lập</label>
            <input type="date" lang="eng"class="form-control" name="company[founding_date]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.founding_date') ?? (!empty($customer) ? $customer->founding_date : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Địa chỉ công ty</label>
            <input type="text" class="form-control" name="company[company_address]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.company_address') ?? (!empty($customer) ? $customer->company_address : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Người đại diện</label>
            <input type="text" class="form-control" name="company[last_name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.last_name') ?? (!empty($customer) ? $customer->last_name : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label required">Số điện thoại</label>
            <input type="text" class="form-control" name="company[phone]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.phone') ?? (!empty($customer) ? $customer->phone : '') }}" pattern="[0-9]*"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Chỉ được nhập số">
        </div>
        <div class="col-3">
            <label class="form-label">Số điện thoại khác</label>
            <input type="text" class="form-control" name="company[sub_phone]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.sub_phone') ?? (!empty($customer) ? $customer->sub_phone : '') }}"
                pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Chỉ được nhập số">
        </div>
        <div class="col-3">
            <label class="form-label required">Email</label>
            <input type="email" class="form-control" name="company[email]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.email') ?? (!empty($customer) ? $customer->email : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label ">Email khác</label>
            <input type="email" class="form-control" name="company[sub_email]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.sub_email') ?? (!empty($customer) ? $customer->sub_email : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Facebook</label>
            <input type="text" class="form-control" name="company[facebook]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.facebook') ?? (!empty($customer) ? $customer->facebook : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Zalo</label>
            <input type="text" class="form-control" name="company[zalo]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.zalo') ?? (!empty($customer) ? $customer->zalo : '') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="company[address]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.address') ?? (!empty($customer) ? $customer->address : '') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Ghi chú</label>
            <textarea class="form-control" name="company[note]" {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>{{ old('company.note') ?? (!empty($customer) ? $customer->note : '') }}</textarea>
        </div>
    </div>
    <hr>
    <h5 class="mt-4 mb-3">Thông tin Xuất Hóa Đơn</h5>
    <div class="row g-3 mb-4">
        <div class="col-6">
            <label class="form-label">Tên Công Ty</label>
            <input type="text" class="form-control" name="company[invoice_name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.invoice_name') ?? (!empty($customer) ? $customer->invoice_name : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Mã Số Thuế</label>
            <input type="text" class="form-control" name="company[invoice_tax_code]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.invoice_tax_code') ?? (!empty($customer) ? $customer->invoice_tax_code : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Email nhận hóa đơn</label>
            <input type="email" class="form-control" name="company[invoice_email]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('company.invoice_email') ?? (!empty($customer) ? $customer->invoice_email : '') }}">
        </div>
    </div>
    <hr>

    <h5 class="mt-4 mb-3">Tài Khoản Thanh Toán</h5>
    <div class="row g-3 mb-4">
        <div class="col-6">
            <label class="form-label">Số Tài Khoản</label>
            <input type="text" class="form-control" name="bank[company][account_number]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('bank.company.account_number') ?? (!empty($customer) && !empty($customer->bankAccounts) ? $customer->bankAccounts?->account_number : '') }}">
        </div>
        <div class="col-6">
            <label class="form-label">Chủ Tài Khoản</label>
            <input type="text" class="form-control" name="bank[company][account_name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('bank.company.account_name') ?? (!empty($customer) && !empty($customer->bankAccounts) ? $customer->bankAccounts?->account_name : '') }}">
        </div>
        <div class="col-6">
            <label class="form-label">Ngân hàng</label>
            <input type="text" class="form-control" name="bank[company][name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('bank.company.name') ?? (!empty($customer) && !empty($customer->bankAccounts) ? $customer->bankAccounts?->name : '') }}">
        </div>

        <div class="col-6">
            <label class="form-label">Chi Nhánh</label>
            <input type="text" class="form-control" name="bank[company][branch]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('bank.company.branch') ?? (!empty($customer) && !empty($customer->bankAccounts) ? $customer->bankAccounts?->branch : '') }}">
        </div>
    </div>
    @if (!request()->routeIs('customers.show'))
        <div class="row g-3 mb-4 text-center">
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Lưu dữ
                    liệu</button>
                @if (empty($customer))
                    <button class="btn btn-secondary" type="button" onclick="resetForms()">Reset</button>
                @endif
            </div>
        </div>
    @endif
</div>
