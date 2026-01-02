@extends('core::layouts.app')
@use('Modules\Core\Enums\AccountStatusEnum')
@use('Modules\Core\Enums\GenderEnum')
@use('Modules\Employee\Models\Employee')

@php
    $employee = $employee ?? new Employee();
@endphp

@section('title', 'Thêm nhân sự')

@section('content')
 <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Nhân Sự</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm Nhân Sự</li>
                    </ol>
                </nav>
            </div>
        </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('employees.store') }}" method="POST" id="employee-create-form"
                enctype="multipart/form-data">
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
                                            'employee' => new Employee(),
                                            'code' => $code,
                                        ])
                                    </div>

                                    <hr>
                                    <h5 class="mt-4 mb-3">Thông tin lương & chế độ
                                    </h5>
                                    <p>Chính sách tiền lương là bảo mật. Không chia
                                        sẻ
                                        thông tin thu nhập cho những người không
                                        liên quan. </p>
                                    <hr>
                                    @include(
                                        'employee::components.form.job-profile.benefit',
                                        $employee = new Employee())
                                </div>
                            </div>
                            <div class="tab-pane fade" id="employee_files">
                                <div class="card shadow-none border mb-0 radius-15">
                                    <div class="card-body">
                                        <h5 class="mb-3">Hình ảnh Cá Nhân</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-12">
                                                <input id="file-upload" type="file" name="files[avatar]"
                                                    accept=".jpg, .png, image/jpeg, image/png" multiple>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5 class="mb-3">CCCD/CMND</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-6">
                                                Mặt Trước:
                                                <input id="file-upload2" type="file" name="files[id_card_front]"
                                                    accept=".jpg, .png, image/jpeg, image/png">
                                            </div>
                                            <div class="col-6">
                                                Mặt Sau:
                                                <input id="file-upload3" type="file" name="files[id_card_back]"
                                                    accept=".jpg, .png, image/jpeg, image/png">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="mb-3">Tài liệu khác</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <input id="file-upload4" type="file" name="files[other]"
                                            accept=".jpg, .png, image/jpeg, image/png, .pdf, .doc, .docx, .xls, .xlsx"
                                            multiple>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Button submit chung cho cả form -->
                <div class="d-flex justify-content-center mt-3">
                    <button type="submit" class="btn btn-info min-w-5" id="submit-btn">
                        Lưu dữ liệu
                    </button>
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
