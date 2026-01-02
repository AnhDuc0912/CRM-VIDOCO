@php
    use Modules\Employee\Enums\JobLevelEnum;
    use Modules\Employee\Enums\JobPositionEnum;
    $groups = [
        1 => '-- Lãnh đạo --',
        2 => '-- Quản lý --',
        3 => '-- Chuyên viên --',
        4 => '-- Nhân viên --',
        5 => '-- Thực tập --',
    ];
@endphp
<div class="col-12 col-lg-6">
    <div class="row g-3">
        <div class="col-6">
            <label class="form-label">Mã Nhân Sự</label>
            <input type="text" class="form-control" readonly value="{{ $employee->code ?? '' }}">
        </div>
        <div class="col-6">
            <label class="form-label">Mã QR Chấm Công</label>
            <input type="text" class="form-control" readonly value="{{ $employee->qr_code ?? '' }}">
        </div>
            <div class="col-6">
            <label class="form-label">Vị trí công việc</label>
            <input type="text" class="form-control" value="{{ $employee->position?->name }}" disabled>
        </div>
         <div class="col-6">
            <label class="form-label">Cấp bậc</label>
            <input type="text" class="form-control" value="{{ $employee->level_company?->name }}" disabled>
        </div>
        <div class="col-6">
            <label class="form-label">Ngày bắt đầu làm việc</label>
            <input type="text" class="form-control" readonly
                value="{{ $employee->start_date ? $employee->start_date->format('d/m/Y') : '' }}">
        </div>
        <div class="col-6">
            <label class="form-label">Ngày ký HĐ Chính thức</label>
            <input type="text" class="form-control" readonly
                value="{{ $employee->contracts()->latest()?->first()?->start_date ? format_date($employee->contracts()->latest()?->first()?->start_date) : '' }}">
        </div>
        <div class="col-6">
            <label class="form-label">Loại Hợp Đồng</label>
            <input type="text" class="form-control" readonly
                value="{{ $employee->contracts()->latest()?->first()?->contract_type ?? '' }}">
        </div>
        <div class="col-6">
            <label class="form-label">Ngày Kết Thúc Hợp Đồng</label>
            <input type="text" class="form-control" readonly
                value="{{ $employee->contracts()->latest()?->first()?->end_date ? format_date($employee->contracts()->latest()?->first()?->end_date) : '' }}">
        </div>
    </div>
</div>
<div class="col-12 col-lg-6">
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Thuộc Phòng Ban</label>
            <input type="text" class="form-control" readonly value="{{ $employee->department?->name ?? '' }}">
        </div>
        <div class="col-12">
            <label class="form-label">Người quản lý trực tiếp</label>
            <input type="text" class="form-control" readonly value="{{ $employee->manager?->full_name ?? '' }}">
        </div>
        <div class="col-12">
            <label class="form-label">Email Quản lý</label>
            <input type="text" class="form-control" readonly value="{{ $employee->manager?->email_work ?? '' }}">
        </div>
        <div class="col-12">
            <label class="form-label">Điện thoại Quản lý</label>
            <input type="text" class="form-control" readonly value="{{ $employee->manager?->phone ?? '' }}">
        </div>
    </div>
</div>
