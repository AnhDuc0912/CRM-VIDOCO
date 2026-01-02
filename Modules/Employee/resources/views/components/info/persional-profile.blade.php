@use('Modules\Core\Enums\GenderEnum')

<h5 class="mb-3">Sơ yếu lý lịch</h5>
<div class="row mb-4">
    <div class="col-12 col-lg-6">
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label">Họ và</label>
                <input type="text" readonly
                    value="{{ $employee->first_name ?? '' }}"
                    class="form-control">
            </div>
            <div class="col-6">
                <label class="form-label">Tên</label>
                <input type="text" readonly
                    value="{{ $employee->last_name ?? '' }}"
                    class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Ngày sinh</label>
                <input type="text" readonly
                    value="{{ $employee->birthday ? format_date($employee->birthday) : '' }}"
                    class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Giới tính</label>
                <input type="text" readonly
                    value="{{ $employee->gender == GenderEnum::MALE ? 'Nam' : 'Nữ' }}"
                    class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Số CCCD/Hộ Chiếu</label>
                <input type="text" readonly
                    value="{{ $employee->citizen_id_number ?? '' }}"
                    class="form-control">
            </div>
            <div class="col-6">
                <label class="form-label">Ngày cấp</label>
                <input type="text" readonly
                    value="{{ $employee->citizen_id_created_date ? format_date($employee->citizen_id_created_date) : '' }}"
                    class="form-control">
            </div>
            <div class="col-6">
                <label class="form-label">Nơi Cấp</label>
                <input type="text" readonly
                    value="{{ $employee->citizen_id_created_place ?? '' }}"
                    class="form-control">
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Điện thoại</label>
                <input type="text" readonly
                    value="{{ $employee->phone ?? '' }}" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Email Cá Nhân</label>
                <input type="text" readonly
                    value="{{ $employee->email_personal ?? '' }}"
                    class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Email Công Việc</label>
                <input type="text" readonly
                    value="{{ $employee->email_work ?? '' }}"
                    class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Địa chỉ hiện tại</label>
                <input type="text" readonly
                    value="{{ $employee->current_address ?? '' }}"
                    class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Địa chỉ thường trú</label>
                <input type="text" readonly
                    value="{{ $employee->permanent_address ?? '' }}"
                    class="form-control">
            </div>
        </div>
    </div>
</div>
