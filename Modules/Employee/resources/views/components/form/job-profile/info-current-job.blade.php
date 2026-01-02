@use('Modules\Employee\Enums\ContractTypeEnum')
@use('Modules\Employee\Enums\JobLevelEnum')
@use('Modules\Employee\Enums\JobPositionEnum')
@php
    $groups = [
        1 => '-- Lãnh đạo --',
        2 => '-- Quản lý --',
        3 => '-- Chuyên viên --',
        4 => '-- Nhân viên --',
        5 => '-- Thực tập --',
    ];
    $latestContract = $employee->contracts()->latest()?->first();
@endphp
<div class="col-12 col-lg-6">
    <div class="row g-3">
        {{-- <div class="col-6">
            <label class="form-label">Mã Nhân Sự</label>
            <input type="text" readonly value="{{ $employee->code ?? $code }}"
                name="job[code]"
                class="form-control">
        </div>
        <div class="col-6">
            <label class="form-label">Mã QR Chấm
                Công</label>
            <input type="text" readonly
                name="job[qr_code]"
                value="{{ $employee->qr_code ?? $code }}" class="form-control">
        </div> --}}
        <div class="col-6">
            <label class="form-label">Cấp bậc <span class="text-danger">*</span></label>
            <select name="job[level]" class="form-select" id="level_select" required>
                <option value="">-- Lựa chọn --</option>
                @foreach ($levels as $level)
                    <option value="{{ $level->id }}"
                           {{ old('job.level', $employee->level ?? '') == $level->id ? 'selected' : '' }}>
                        {{ $level->name }}
                    </option>
                @endforeach

            </select>
        </div>
        <div class="col-6">
            <label class="form-label">Vị trí công việc <span class="text-danger">*</span></label>
            <select name="job[current_position]" class="form-select" id="current_position_select" required>
                <option value="">-- Lựa chọn --</option>
                @foreach ($positions as $position)
                    <option value="{{ $position->id }}"
                           {{ old('job.position', $employee->current_position ?? '') == $position->id ? 'selected' : '' }}>
                        {{ $position->name }}
                    </option>
                @endforeach
            </select>   

        </div>
        <div class="col-6">
            <label class="form-label">Ngày bắt đầu làm việc <span class="text-danger">*</span></label>
            <input type="date" name="job[start_date]" required
                value="{{ old('job.start_date', $employee->start_date ? $employee->start_date->format('Y-m-d') : '') }}"
                class="form-control">
        </div>
        <div class="col-6">
            <label class="form-label">Ngày ký HĐ Chính thức <span class="text-danger">*</span></label>
            <input type="date" name="contract[start_date]" required
                value="{{ old('contract.start_date', $latestContract?->start_date ?? '') }}" class="form-control">
        </div>
        <div class="col-6">
            <label class="form-label">Loại Hợp Đồng <span class="text-danger">*</span></label>
            <select name="contract[contract_type]" id="contract_type_select" class="form-select" required>
                <option value="">Chọn loại hợp đồng</option>
                @foreach (ContractTypeEnum::getValues() as $type)
                    <option value="{{ $type }}"
                        {{ old('contract.contract_type', $latestContract?->contract_type ?? '') == $type ? 'selected' : '' }}>
                        {{ ContractTypeEnum::getLabel($type) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label class="form-label">Ngày Kết Thúc Hợp Đồng</label>
            <input type="date" name="contract[end_date]" id="contract_end_date"
                value="{{ old('contract.end_date', $latestContract?->end_date ? date('Y-m-d', strtotime($latestContract?->end_date)) : '') }}"
                class="form-control">
        </div>
    </div>
</div>
<div class="col-12 col-lg-6">
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Thuộc Phòng Ban <span class="text-danger">*</span></label>
            <select name="job[department_id]" id="department_id" class="form-select" required>
                <option value="">Chọn phòng ban</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('job.department_id', $employee->department_id ?? '') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Người quản lý trực tiếp <span class="text-danger">*</span></label>
            <select name="job[manager_id]" class="form-select" required>
                <option value="">Chọn người quản lý</option>
                @foreach ($managers as $manager)
                    <option value="{{ $manager->id }}"
                        {{ (int) old('job.manager_id', $employee->manager_id ?? '') == (int) $manager->id ? 'selected' : '' }}>
                        {{ $manager->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Email Quản
                lý</label>
            <input typ  e="email" name="job[manager_email]"
                value="{{ old('job.manager_email', $employee->manager?->email_work ?? '') }}" class="form-control"
                readonly>
        </div>
        <div class="col-12">
            <label class="form-label">Điện thoại
                Quản lý</label>
            <input type="text" name="job[manager_phone]"
                value="{{ old('job.manager_phone', $employee->manager?->phone ?? '') }}" class="form-control" readonly>
        </div>
    </div>
</div>

