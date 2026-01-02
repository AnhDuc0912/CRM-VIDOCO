@extends('core::layouts.app')
@use('Modules\Core\Enums\AccountStatusEnum')
@use('Modules\Core\Enums\GenderEnum')
@use('Modules\Employee\Enums\EmployeeFileTypeEnum')
@use('App\Helpers\FileHelper')

@section('title', 'Cập nhật thông tin nhân sự')

@section('content')
 <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Nhân Sự</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cập Nhật Nhân Sự</li>
                    </ol>
                </nav>
            </div>
        </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST" id="employee-create-form"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#hosonhansu"><span
                                class="p-tab-name">Hồ Sơ Nhân
                                Sự</span><i class='bx bx-donate-blood font-24 d-sm-none'></i></a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" id="profile-tab" data-bs-toggle="tab"
                            href="#hosocongviec"><span class="p-tab-name">Hồ sơ công việc</span><i
                                class='bx bxs-user-rectangle font-24 d-sm-none'></i></a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" id="employee_files-tab" data-bs-toggle="tab"
                            href="#employee_files"><span class="p-tab-name">Upload file</span><i
                                class='bx bxs-user-rectangle font-24 d-sm-none'></i></a>
                    </li>
                </ul>
                <div class="card shadow-none border mb-0 radius-15">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="hosonhansu">
                                @include('employee::components.form.info.persional-profile')
                                <hr>
                                @include('employee::components.form.info.dependent')
                                <hr>
                                @include('employee::components.form.info.bank-account')
                            </div>
                            <div class="tab-pane fade" id="hosocongviec">
                                <div class="card shadow-none mb-0">
                                    <h5 class="mb-3">Thông tin công việc hiện tại
                                    </h5>
                                    <div class="row mb-4">
                                        @include('employee::components.form.job-profile.info-current-job', [
                                            'employee' => $employee,
                                        ])
                                    </div>

                                    <hr>
                                    <h5 class="mt-4 mb-3">Thông tin lương & chế độ
                                    </h5>
                                    <p>Chính sách tiền lương là bảo mật. Không chia
                                        sẻ
                                        thông tin thu nhập cho những người không
                                        liên quan. </p>
                                    @include(
                                        'employee::components.form.job-profile.benefit',
                                        compact('employee'))
                                </div>
                            </div>
                            <div class="tab-pane fade" id="employee_files">
                                <div class="card shadow-none border mb-0 radius-15">
                                    <div class="card-body">
                                        <h5 class="mb-3">Hình ảnh Cá Nhân</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-12">
                                                <input id="file-upload" type="file" name="files[avatar]"
                                                    accept=".jpg, .png, image/jpeg, image/png">
                                                @if ($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path)
                                                    <div class="position-relative d-inline-block">
                                                        <img width="200" height="200"
                                                            src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') }}"
                                                            alt="avatar" class="img-fluid rounded" id="avatar-image">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm position-absolute"
                                                            style="top: 5px; right: 5px; width: 28px; height: 28px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                                                            onclick="confirmDelete('{{ route('employees.remove-file', ['employeeId' => $employee->id, 'fileId' => $employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->id]) }}', 'Bạn có chắc chắn muốn xóa file này không?')"
                                                            title="Xóa hình ảnh">
                                                            <i class="bx bx-x"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <h5 class="mb-3">CCCD/CMND</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-6">
                                                <strong class="mb-4 font-weight-bold">Mặt Trước</strong>
                                                <input id="file-upload2" type="file" name="files[id_card_front]"
                                                    accept=".jpg, .png, image/jpeg, image/png">
                                                <div class="mt-4">
                                                    @if ($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_FRONT)->first()?->path)
                                                        <div class="position-relative d-inline-block">
                                                            <img width="200" height="200"
                                                                src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_FRONT)->first()?->path ?? '') }}"
                                                                alt="avatar" class="img-fluid rounded" id="avatar-image">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm position-absolute"
                                                                style="top: 5px; right: 5px; width: 28px; height: 28px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                                                                onclick="confirmDelete('{{ route('employees.remove-file', ['employeeId' => $employee->id, 'fileId' => $employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_FRONT)->first()?->id]) }}', 'Bạn có chắc chắn muốn xóa file này không?')"
                                                                title="Xóa hình ảnh">
                                                                <i class="bx bx-x"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                Mặt Sau:
                                                <input id="file-upload3" type="file" name="files[id_card_back]"
                                                    accept=".jpg, .png, image/jpeg, image/png">
                                                <div class="mt-4">
                                                    @if ($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_BACK)->first()?->path)
                                                        <div class="position-relative d-inline-block">
                                                            <img width="200" height="200"
                                                                src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_BACK)->first()?->path ?? '') }}"
                                                                alt="avatar" class="img-fluid rounded" id="avatar-image">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm position-absolute"
                                                                style="top: 5px; right: 5px; width: 28px; height: 28px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                                                                onclick="confirmDelete('{{ route('employees.remove-file', ['employeeId' => $employee->id, 'fileId' => $employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_BACK)->first()?->id]) }}', 'Bạn có chắc chắn muốn xóa file này không?')"
                                                                title="Xóa hình ảnh">
                                                                <i class="bx bx-x"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="mb-3">Tài liệu khác</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <input id="file-upload4" type="file" name="files[other][]"
                                            accept=".jpg, .png, image/jpeg, image/png, .pdf, .doc, .docx, .xls, .xlsx"
                                            multiple>
                                        <div class="file-preview" id="filePreview">
                                            @foreach ($employee->files?->where('type', EmployeeFileTypeEnum::OTHER) as $file)
                                                @if ($file->extension == 'jpeg' || $file->extension == 'png' || $file->extension == 'jpg')
                                                    <div class="file-item">
                                                        <div class="file-image">
                                                            <img src="{{ FileHelper::getFileUrl($file->path) }}"
                                                                alt="Preview">
                                                        </div>
                                                        <a class="remove-btn" href="javascript:void(0)"
                                                            onclick="confirmDelete('{{ route('employees.remove-file', ['employeeId' => $employee->id, 'fileId' => $file->id]) }}', 'Bạn có chắc chắn muốn xóa file này không?')">&times;</a>
                                                    </div>
                                                @else
                                                    <div class="file-item">
                                                        <div
                                                            class="file-image d-flex align-items-center justify-content-center">
                                                            <div class="file-icon text-primary">
                                                                {{ $file->extension ?? '' }}
                                                            </div>
                                                        </div>
                                                        <a class="remove-btn" href="javascript:void(0)"
                                                            onclick="confirmDelete('{{ route('employees.remove-file', ['employeeId' => $employee->id, 'fileId' => $file->id]) }}', 'Bạn có chắc chắn muốn xóa file này không?')">&times;</a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-4 text-center mt-3">
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Lưu Dữ Liệu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('modules/employee/js/validation/employee-validation.js') }}"></script>

    <script>
        $(document).ready(function() {
            const firstName = $('input[name="profile[first_name]"]');
            const lastName = $('input[name="profile[last_name]"]');
            const fullName = $(
                'input[name="bank_account[bank_account_name]"]');

            firstName.on('change', function() {
                fullName.val($(this).val() + ' ' + lastName.val());
            });

            lastName.on('change', function() {
                fullName.val(firstName.val() + ' ' + $(this).val());
            });

            // Auto fill manager info when select manager
            const managerSelect = $('select[name="job[manager_id]"]');
            const managerEmail = $('input[name="job[manager_email]"]');
            const managerPhone = $('input[name="job[manager_phone]"]');

            // Tạo object chứa thông tin manager
            const managersData = {
                @foreach ($managers as $manager)
                    {{ $manager->id }}: {
                        email: '{{ $manager->email_work ?? '' }}',
                        phone: '{{ $manager->phone ?? '' }}'
                    },
                @endforeach
            };

            managerSelect.on('change', function() {
                const selectedManagerId = $(this).val();
                if (selectedManagerId && managersData[
                        selectedManagerId]) {
                    managerEmail.val(managersData[selectedManagerId]
                        .email);
                    managerPhone.val(managersData[selectedManagerId]
                        .phone);
                } else {
                    managerEmail.val('');
                    managerPhone.val('');
                }
            });
        });
    </script>
@endpush
