@extends('core::layouts.app')

@section('title', 'Chỉnh sửa yêu cầu nghỉ phép')

@section('content')
<div class="page-content">
     <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Đề Xuất Nghỉ Phép</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh Sửa Đề Xuất Nghỉ Phép</li>
                    </ol>
                </nav>
            </div>
          
        </div>
    <div class="row">

        <!-- Thông tin nghỉ phép -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="fs-5 fw-semibold me-2 text-primary"><i class="bx bx-time-five"></i></div>
                        <h5 class="mb-0">Thông tin nghỉ phép</h5>
                    </div>

                    <div class="bg-light p-3 rounded-3 text-center mb-3">
                        <h3 class="fw-bold text-primary mb-0">{{ $used ?? 0 }} / {{ $total ?? 12 }}</h3>
                        <small class="text-muted">Đã dùng / Tổng ngày phép</small>
                    </div>

                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Kế hoạch</span>
                            <strong>{{ $plan ?? 0 }} ngày</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Làm việc ở nhà</span>
                            <strong>{{ $home ?? 0 }} ngày</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Ngoại lệ</span>
                            <strong>{{ $special ?? 0 }} ngày</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Form chỉnh sửa -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="fs-5 fw-semibold me-2 text-success"><i class="bx bx-edit-alt"></i></div>
                        <h5 class="mb-0">Chỉnh sửa yêu cầu nghỉ phép</h5>
                    </div>

                    <form action="{{ route('dayoff.update', $dayoff->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Loại nghỉ -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Loại nghỉ</label>
                            <div class="btn-group d-flex flex-wrap gap-2" role="group">
                                <input type="radio" class="btn-check type-radio" name="type" id="type1" value="ke_hoach"
                                    {{ $dayoff->type == 'ke_hoach' ? 'checked' : '' }}>
                                <label class="btn btn-outline-info" for="type1">Kế hoạch</label>

                                <input type="radio" class="btn-check type-radio" name="type" id="type2" value="lam_viec_o_nha"
                                    {{ $dayoff->type == 'lam_viec_o_nha' ? 'checked' : '' }}>
                                <label class="btn btn-outline-info" for="type2">Làm việc ở nhà</label>

                                <input type="radio" class="btn-check type-radio" name="type" id="type3" value="ngoai_le"
                                    {{ $dayoff->type == 'ngoai_le' ? 'checked' : '' }}>
                                <label class="btn btn-outline-info" for="type3">Ngoại lệ</label>
                            </div>
                        </div>

                        <!-- Hình thức (ngoại lệ) -->
                        <div class="mb-4 mode-section {{ $dayoff->type == 'ngoai_le' ? '' : 'd-none' }}">
                            <label class="form-label fw-semibold">Hình thức ngoại lệ</label>
                            <div class="btn-group d-flex flex-wrap gap-2" role="group">
                                <input type="radio" class="btn-check" name="mode" id="mode1" value="den_muon"
                                    {{ $dayoff->mode == 'den_muon' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary" for="mode1">Đến muộn</label>

                                <input type="radio" class="btn-check" name="mode" id="mode2" value="ve_som"
                                    {{ $dayoff->mode == 've_som' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary" for="mode2">Về sớm</label>

                                <input type="radio" class="btn-check" name="mode" id="mode3" value="ra_ngoai"
                                    {{ $dayoff->mode == 'ra_ngoai' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary" for="mode3">Ra ngoài</label>
                            </div>
                        </div>

                        <!-- Ngày, buổi, giờ -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Ngày</label>
                                <input type="date" name="date" value="{{ $dayoff->date }}" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Buổi</label>
                                <div class="btn-group d-flex gap-2" role="group">
                                    <input type="radio" class="btn-check" name="session" id="session1" value="AM"
                                        {{ $dayoff->session == 'AM' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger" for="session1">AM</label>

                                    <input type="radio" class="btn-check" name="session" id="session2" value="PM"
                                        {{ $dayoff->session == 'PM' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger" for="session2">PM</label>

                                     <input type="radio" class="btn-check" name="session" id="session3" value="FULL"
                                        {{ $dayoff->session == 'FULL' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger" for="session3">Cả Ngày</label>
                                </div>
                            </div>
                            <div class="col-md-4 time-section {{ $dayoff->type == 'ngoai_le' ? '' : 'd-none' }}">
                                <label class="form-label fw-semibold">Giờ</label>
                                <input type="time" name="time" value="{{ $dayoff->time }}" class="form-control">
                            </div>
                        </div>

                        <!-- Lý do -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Lý do</label>
                            <div class="btn-group d-flex flex-wrap gap-2" role="group">
                                <input type="radio" class="btn-check" name="reason_type" id="reason1" value="tac_duong"
                                    {{ $dayoff->reason_type == 'tac_duong' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning" for="reason1">Tắc đường</label>

                                <input type="radio" class="btn-check" name="reason_type" id="reason2" value="nghi_om"
                                    {{ $dayoff->reason_type == 'nghi_om' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning" for="reason2">Nghỉ ốm</label>

                                <input type="radio" class="btn-check" name="reason_type" id="reason3" value="viec_khan_cap"
                                    {{ $dayoff->reason_type == 'viec_khan_cap' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning" for="reason3">Việc khẩn cấp</label>

                                <input type="radio" class="btn-check" name="reason_type" id="reason4" value="khac"
                                    {{ $dayoff->reason_type == 'khac' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning" for="reason4">Khác</label>
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ghi chú</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Ghi chú thêm...">{{ $dayoff->note }}</textarea>
                        </div>

                        <!-- Nút hành động -->
                        <div class="text-end">
                            <a href="{{ route('dayoff.index') }}" class="btn btn btn-warning me-2">
                                <i class="bx bx-arrow-back"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-save"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const typeRadios = document.querySelectorAll('.type-radio');
    const modeSection = document.querySelector('.mode-section');
    const timeSection = document.querySelector('.time-section');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'ngoai_le') {
                modeSection.classList.remove('d-none');
                timeSection.classList.remove('d-none');
            } else {
                modeSection.classList.add('d-none');
                timeSection.classList.add('d-none');
            }
        });
    });
});
</script>
@endpush
@endsection
