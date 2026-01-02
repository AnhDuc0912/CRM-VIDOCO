@use('Modules\Core\Enums\GenderEnum')
@use('Modules\Customer\Enums\SourceCustomerEnum')
@use('Modules\Customer\Enums\SalutationEnum')
@use('Modules\Customer\Enums\CustomerTypeEnum')

@php
    if (!empty($customer) && $customer->customer_type == CustomerTypeEnum::COMPANY) {
        $customer = null;
    }
@endphp

<div id="form-individual">
    <div class="row g-3 mb-4">
        <div class="col-3">
            <label class="form-label required">Nguồn khách hàng</label>
            <select class="form-select" name="personal[source_customer]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>
                <option value="">-- Lựa chọn --</option>
                @foreach (SourceCustomerEnum::getLabel() as $key => $value)
                    <option value="{{ $key }}"
                        {{ old('personal.source_customer') ? (old('personal.source_customer') == $key ? 'selected' : '') : (!empty($customer) ? ($customer->source_customer == $key ? 'selected' : '') : '') }}>
                        {{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label class="form-label required">Nhân viên chăm sóc KH</label>
            <select class="form-select" name="personal[person_incharge]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>
                <option value="">-- Lựa chọn --</option>
                @foreach ($salesPersons as $salesPerson)
                    <option value="{{ $salesPerson->id }}"
                        {{ old('personal.person_incharge') ? (old('personal.person_incharge') == $salesPerson->id ? 'selected' : '') : (!empty($customer) ? ($customer->person_incharge == $salesPerson->id ? 'selected' : '') : '') }}>
                        {{ $salesPerson->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label class="form-label required">Nhân viên Sale</label>
            <select class="form-select" name="personal[sales_person]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>
                <option value="">-- Lựa chọn --</option>
                @foreach ($salesPersons as $salesPerson)
                    <option value="{{ $salesPerson->id }}"
                        {{ old('personal.sales_person') ? (old('personal.sales_person') == $salesPerson->id ? 'selected' : '') : (!empty($customer) ? ($customer->sales_person == $salesPerson->id ? 'selected' : '') : '') }}>
                        {{ $salesPerson->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label class="form-label required">Xưng hô</label>
            <select class="form-select" name="personal[salutation]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>
                <option value="">-- Lựa chọn --</option>
                @foreach (SalutationEnum::getLabels() as $key => $value)
                    <option value="{{ $key }}"
                        {{ old('personal.salutation') ? (old('personal.salutation') == $key ? 'selected' : '') : (!empty($customer) ? ($customer->salutation == $key ? 'selected' : '') : '') }}>
                        {{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label class="form-label required">Họ và tên đệm</label>
            <input type="text" class="form-control" name="personal[last_name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.last_name') ? old('personal.last_name') : (!empty($customer) ? $customer->last_name : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label required">Tên</label>
            <input type="text" class="form-control" name="personal[first_name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.first_name') ? old('personal.first_name') : (!empty($customer) ? $customer->first_name : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" lang="eng"class="form-control" name="personal[birthday]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.birthday') ? old('personal.birthday') : (!empty($customer) ? $customer->birthday : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">CMND/CCCD</label>
            <input type="text" class="form-control" name="personal[identity_card]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.identity_card') ? old('personal.identity_card') : (!empty($customer) ? $customer->identity_card : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label required">Giới tính</label>
            <select class="form-select" name="personal[gender]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>
                <option value="">-- Lựa chọn --</option>
                @foreach (GenderEnum::getLabel() as $key => $value)
                    <option value="{{ $key }}"
                        {{ old('personal.gender') ? (old('personal.gender') == $key ? 'selected' : '') : (!empty($customer) ? ($customer->gender == $key ? 'selected' : '') : '') }}>
                        {{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label class="form-label required">Số điện thoại</label>
            <input type="text" class="form-control" name="personal[phone]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.phone') ? old('personal.phone') : (!empty($customer) ? $customer->phone : '') }}"
                pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Chỉ được nhập số">
        </div>
        <div class="col-3">
            <label class="form-label">Số điện thoại khác</label>
            <input type="text" class="form-control" name="personal[sub_phone]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.sub_phone') ? old('personal.sub_phone') : (!empty($customer) ? $customer->sub_phone : '') }}"
                pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Chỉ được nhập số">
        </div>
        <div class="col-3">
            <label class="form-label required">Email</label>
            <input type="email" class="form-control" name="personal[email]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.email') ? old('personal.email') : (!empty($customer) ? $customer->email : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Email khác</label>
            <input type="email" class="form-control" name="personal[sub_email]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.sub_email') ? old('personal.sub_email') : (!empty($customer) ? $customer->sub_email : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Facebook</label>
            <input type="text" class="form-control" name="personal[facebook]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.facebook') ? old('personal.facebook') : (!empty($customer) ? $customer->facebook : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Zalo</label>
            <input type="text" class="form-control" name="personal[zalo]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.zalo') ? old('personal.zalo') : (!empty($customer) ? $customer->zalo : '') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="personal[address]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.address') ? old('personal.address') : (!empty($customer) ? $customer->address : '') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Ghi chú</label>
            <textarea class="form-control" name="personal[note]" {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>{{ old('personal.note') ? old('personal.note') : (!empty($customer) ? $customer->note : '') }}</textarea>
        </div>
    </div>
    <hr>
    <h5 class="mt-4 mb-3">Thông tin Xuất Hóa Đơn</h5>
    <div class="row g-3 mb-4">
        <div class="col-6">
            <label class="form-label">Tên Cá Nhân</label>
            <input type="text" class="form-control" name="personal[invoice_name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.invoice_name') ?? (!empty($customer) ? $customer->invoice_name : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Mã Số Thuế</label>
            <input type="text" class="form-control" name="personal[invoice_tax_code]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.invoice_tax_code') ?? (!empty($customer) ? $customer->invoice_tax_code : '') }}">
        </div>
        <div class="col-3">
            <label class="form-label">Email nhận hóa đơn</label>
            <input type="email" class="form-control" name="personal[invoice_email]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('personal.invoice_email') ?? (!empty($customer) ? $customer->invoice_email : '') }}">
        </div>
    </div>
    <hr>
    <h5 class="mt-4 mb-3">Tài Khoản Thanh Toán</h5>
    <div class="row g-3 mb-4">
        <div class="col-6">
            <label class="form-label">Số Tài Khoản</label>
            <input type="text" class="form-control" name="bank[personal][account_number]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('bank.personal.account_number') ?? (!empty($customer) && !empty($customer->bankAccounts) ? $customer->bankAccounts?->account_number : '') }}">
        </div>
        <div class="col-6">
            <label class="form-label">Chủ Tài Khoản</label>
            <input type="text" class="form-control" name="bank[personal][account_name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('bank.personal.account_name') ?? (!empty($customer) && !empty($customer->bankAccounts) ? $customer->bankAccounts?->account_name : '') }}">
        </div>
        <div class="col-6">
            <label class="form-label">Ngân hàng</label>
            <input type="text" class="form-control" name="bank[personal][name]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('bank.personal.name') ?? (!empty($customer) && !empty($customer->bankAccounts) ? $customer->bankAccounts?->name : '') }}">
        </div>
        <div class="col-6">
            <label class="form-label">Chi Nhánh</label>
            <input type="text" class="form-control" name="bank[personal][branch]"
                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                value="{{ old('bank.personal.branch') ?? (!empty($customer) && !empty($customer->bankAccounts) ? $customer->bankAccounts?->branch : '') }}">
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
