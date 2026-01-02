@use('Modules\Core\Enums\GenderEnum')

<h5 class="mb-3">Sơ yếu lý lịch</h5>
<div class="row mb-4">
    <div class="col-12 col-lg-6">
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label">Họ và <span class="text-danger">*</span></label>
                <input type="text" name="profile[first_name]"
                    value="{{ old('profile.first_name', $employee->first_name ?? '') ?? '' }}"
                    class="form-control @error('profile.first_name') is-invalid @enderror" required>
                @error('profile.first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <label class="form-label">Tên <span class="text-danger">*</span></label>
                <input type="text" name="profile[last_name]"
                    value="{{ old('profile.last_name', $employee->last_name ?? '') ?? '' }}"
                    class="form-control @error('profile.last_name') is-invalid @enderror" required>
                @error('profile.last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                <input type="date" name="profile[birthday]"
                    value="{{ old('profile.birthday', $employee->birthday ? date('Y-m-d', strtotime($employee->birthday)) : '') }}"
                    max="{{ date('Y-m-d') }}"
                    class="form-control @error('profile.birthday') is-invalid @enderror" required>
                @error('profile.birthday')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Giới tính <span class="text-danger">*</span></label>
                <select name="profile[gender]" class="form-select @error('profile.gender') is-invalid @enderror" required>
                    <option value="1"
                        {{ old('profile.gender', $employee->gender ?? '') == GenderEnum::MALE ? 'selected' : '' }}>
                        Nam</option>
                    <option value="2"
                        {{ old('profile.gender', $employee->gender ?? '') == GenderEnum::FEMALE ? 'selected' : '' }}>
                        Nữ</option>
                </select>
                @error('profile.gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Số CCCD/Hộ Chiếu <span class="text-danger">*</span></label>
                <input type="text" name="profile[citizen_id_number]"
                    value="{{ old('profile.citizen_id_number', $employee->citizen_id_number ?? '') ?? '' }}"
                    class="form-control @error('profile.citizen_id_number') is-invalid @enderror" required>
                @error('profile.citizen_id_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <label class="form-label">Ngày cấp <span class="text-danger">*</span></label>
                <input type="date" name="profile[citizen_id_created_date]"
                    value="{{ old('profile.citizen_id_created_date', $employee->citizen_id_created_date ?? '') }}"
                    max="{{ date('Y-m-d') }}"
                    class="form-control @error('profile.citizen_id_created_date') is-invalid @enderror" required>
                @error('profile.citizen_id_created_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <label class="form-label">Nơi Cấp <span class="text-danger">*</span></label>
                <input type="text" name="profile[citizen_id_created_place]"
                    value="{{ old('profile.citizen_id_created_place', $employee->citizen_id_created_place ?? '') ?? '' }}"
                    class="form-control @error('profile.citizen_id_created_place') is-invalid @enderror" required>
                @error('profile.citizen_id_created_place')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="profile[phone]"
                    value="{{ old('profile.phone', $employee->phone ?? '') ?? '' }}"
                    class="form-control @error('profile.phone') is-invalid @enderror" required>
                @error('profile.phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Email Cá Nhân</label>
                <input type="text" name="profile[email_personal]"
                    value="{{ old('profile.email_personal', $employee->email_personal ?? '') ?? '' }}"
                    class="form-control @error('profile.email_personal') is-invalid @enderror">
                @error('profile.email_personal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Email Công Việc <span class="text-danger">*</span></label>
                <input type="text" name="profile[email_work]"
                    value="{{ old('profile.email_work', $employee->email_work ?? '') ?? '' }}"
                    class="form-control @error('profile.email_work') is-invalid @enderror" required>
                @error('profile.email_work')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Địa chỉ hiện tại <span class="text-danger">*</span></label>
                <input type="text" name="profile[current_address]"
                    value="{{ old('profile.current_address', $employee->current_address ?? '') ?? '' }}"
                    class="form-control @error('profile.current_address') is-invalid @enderror" required>
                @error('profile.current_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Địa chỉ thường trú <span class="text-danger">*</span></label>
                <input type="text" name="profile[permanent_address]"
                    value="{{ old('profile.permanent_address', $employee->permanent_address ?? '') ?? '' }}"
                    class="form-control @error('profile.permanent_address') is-invalid @enderror" required>
                @error('profile.permanent_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
