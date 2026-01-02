@extends('core::layouts.app')

@use('Modules\Core\Enums\AccountStatusEnum')
@use('Modules\Employee\Enums\EmployeeFileTypeEnum')
@use('App\Helpers\FileHelper')

@section('title', 'Hồ sơ nhân sự')

@section('content')
 <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Nhân Sự</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thông Tin Nhân Sự</li>
                    </ol>
                </nav>
            </div>
        </div>
    <div class="user-profile-page">
        <div class="card radius-15">
            <div class="card-body">
                @include('employee::components.infomation', compact('employee'))
                <!--end row-->
                <ul class="nav nav-pills">
                    <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#hosonhansu"><span
                                class="p-tab-name">Hồ Sơ Nhân Sự</span><i
                                class='bx bx-donate-blood font-24 d-sm-none'></i></a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" id="profile-tab" data-bs-toggle="tab"
                            href="#hosocongviec"><span class="p-tab-name">Hồ sơ công việc</span><i
                                class='bx bxs-user-rectangle font-24 d-sm-none'></i></a>
                    </li>

                    <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#Edit-Profile"><span
                                class="p-tab-name">Cập Nhật
                                Tài Khoản</span><i class='bx bx-message-edit font-24 d-sm-none'></i></a>
                    </li>

                    <li class="nav-item"> <a class="nav-link" id="employee_files-tab" data-bs-toggle="tab"
                            href="#employee_files"><span class="p-tab-name">Upload file</span><i
                                class='bx bxs-user-rectangle font-24 d-sm-none'></i></a>
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    @include('employee::components.tabs.info', compact('employee'))

                    @include('employee::components.tabs.job-profile', compact('employee'))

                    @include('employee::components.tabs.update-account', compact('employee'))

                    <div class="tab-pane fade" id="employee_files">
                        <div class="card shadow-none border mb-0 radius-15">
                            <div class="card-body">
                                <h5 class="mb-3">Hình ảnh Cá Nhân</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        @if ($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path)
                                            <div class="position-relative d-inline-block">
                                                <img width="200" height="200"
                                                    src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') }}"
                                                    alt="avatar" class="img-fluid rounded" id="avatar-image">
                                            </div>
                                            <div class="d-flex align-items-center mt-2">
                                                <a href="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') }}"
                                                    class="btn btn-info" download>
                                                    <i class="bx bx-download"></i>
                                                    <span>Tải xuống</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <h5 class="mb-3">CCCD/CMND</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="d-flex flex-column">
                                            <strong class="mb-4 font-weight-bold">Mặt Trước</strong>
                                            @if ($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_FRONT)->first()?->path)
                                                <div class="position-relative d-inline-block">
                                                    <img width="200" height="200"
                                                        src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_FRONT)->first()?->path ?? '') }}"
                                                        alt="avatar" class="img-fluid rounded" id="avatar-image">
                                                </div>
                                                <div class="d-flex align-items-center mt-2">
                                                    <a href="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_FRONT)->first()?->path ?? '') }}"
                                                        class="btn btn-info" download>
                                                        <i class="bx bx-download"></i>
                                                        <span>Tải xuống</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex flex-column">
                                            <strong class="mb-4 font-weight-bold">Mặt Sau</strong>
                                            @if ($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_BACK)->first()?->path)
                                                <div class="position-relative d-inline-block">
                                                    <img width="200" height="200"
                                                        src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_BACK)->first()?->path ?? '') }}"
                                                        alt="avatar" class="img-fluid rounded" id="avatar-image">
                                                </div>
                                                <div class="d-flex align-items-center mt-2">
                                                    <a href="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::ID_CARD_BACK)->first()?->path ?? '') }}"
                                                        class="btn btn-info" download>
                                                        <i class="bx bx-download"></i>
                                                        <span>Tải xuống</span>
                                                    </a>
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
                                <div class="file-preview" id="filePreview">
                                    @foreach ($employee->files?->where('type', EmployeeFileTypeEnum::OTHER) as $file)
                                        @if ($file->extension == 'jpeg' || $file->extension == 'png' || $file->extension == 'jpg')
                                            <div class="file-item">
                                                <div class="file-image">
                                                    <img src="{{ FileHelper::getFileUrl($file->path) }}" alt="Preview">
                                                </div>
                                            </div>
                                        @else
                                            <div class="file-item">
                                                <div class="file-image d-flex align-items-center justify-content-center">
                                                    <div class="file-icon text-primary">
                                                        {{ $file->extension ?? '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div class="d-flex align-items-center mt-2">
                                        <a href="{{ route('employees.download-other-files', $employee->id) }}"
                                            class="btn btn-info" download>
                                            <i class="bx bx-download"></i>
                                            <span>Tải xuống</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
