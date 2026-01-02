@extends('core::layouts.app')

@section('title', 'Chi tiết nghỉ phép')

@section('content')
    @use('Modules\Employee\Enums\EmployeeFileTypeEnum')
    @use('App\Helpers\FileHelper')
    @use('Modules\Core\Enums\RoleEnum')
    @use('Modules\Core\Enums\PermissionEnum')
    <div class="page-content">
         <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Đề Xuất Nghỉ Phép</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi Tiết Đề Xuất Nghỉ Phép</li>
                    </ol>
                </nav>
            </div>
          
        </div>
        <div class="row">

            <!-- Cột trái: Thông tin nhân viên -->
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-sm radius-10">
                    <div class="card-body text-center">
                        <div class="fs-5 fw-semibold mb-3 text-primary d-flex align-items-center justify-content-center">
                            <i class="bx bx-user me-2"></i> Thông tin nhân viên
                        </div>

                        <div class="mb-3">
                            <img src="{{ FileHelper::getFileUrl($dayoff->user->employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                class="rounded-circle shadow-sm" width="100" height="100" alt="Avatar">
                        </div>
                        <h6 class="fw-bold mb-0">{{ $dayoff->user->employee->full_name ?? 'N/A' }}</h6>
                        <small class="text-muted">Mã NV: {{ $dayoff->user->employee->code ?? '—' }}</small>

                        <hr>

                        <ul class="list-group list-group-flush text-start small">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Ngày tạo</span>
                                <strong>{{ $dayoff->created_at->format('d/m/Y H:i') }}</strong>
                            </li>
                            @if ($dayoff->approved_by)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Duyệt bởi</span>
                                    <strong>{{ optional($dayoff->approver)->name ?? '—' }}</strong>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Chi tiết nghỉ phép -->
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-sm radius-10">
                    <div class="card-body">

                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center">
                                <div class="fs-5 fw-semibold text-success me-2"><i class="bx bx-detail"></i></div>
                                <h5 class="mb-0">Chi tiết yêu cầu nghỉ phép</h5>
                            </div>
                            <span
                                class="badge bg-{{ $dayoff->status == 'approved' ? 'success' : ($dayoff->status == 'rejected' ? 'danger' : 'secondary') }} px-3 py-2">
                                {{ ucfirst($dayoff->status_label) }}
                            </span>
                        </div>

                        <!-- Thông tin chính -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="fw-semibold text-muted">Loại nghỉ</div>
                                @switch($dayoff->type)
                                    @case('ke_hoach')
                                        <span class="badge bg-primary mt-1">Kế hoạch</span>
                                    @break

                                    @case('lam_viec_o_nha')
                                        <span class="badge bg-info mt-1">Làm việc ở nhà</span>
                                    @break

                                    @case('ngoai_le')
                                        <span class="badge bg-warning text-dark mt-1">Ngoại lệ</span>
                                    @break

                                    @default
                                        <span>-</span>
                                @endswitch
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="fw-semibold text-muted">Lý do</div>
                                <div class="mt-1">
                                    @switch($dayoff->reason_type)
                                        @case('tac_duong')
                                            Tắc đường
                                        @break

                                        @case('nghi_om')
                                            Nghỉ ốm
                                        @break

                                        @case('viec_khan_cap')
                                            Việc khẩn cấp
                                        @break

                                        @case('khac')
                                            Lý do khác
                                        @break

                                        @default
                                            -
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        <!-- Ngày, buổi, giờ -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="fw-semibold text-muted">Ngày</div>
                                <div class="mt-1">{{ \Carbon\Carbon::parse($dayoff->date)->format('d/m/Y') }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="fw-semibold text-muted">Buổi</div>
                                <div class="mt-1">{{ $dayoff->session }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="fw-semibold text-muted">Giờ</div>
                                <div class="mt-1">{{ $dayoff->time ?? '—' }}</div>
                            </div>
                        </div>

                        @if ($dayoff->type === 'ngoai_le')
                            <div class="mb-4">
                                <div class="fw-semibold text-muted">Hình thức ngoại lệ</div>
                                <div class="mt-1">
                                    @switch($dayoff->mode)
                                        @case('den_muon')
                                            Đến muộn
                                        @break

                                        @case('ve_som')
                                            Về sớm
                                        @break

                                        @case('ra_ngoai')
                                            Ra ngoài
                                        @break

                                        @default
                                            -
                                    @endswitch
                                </div>
                            </div>
                        @endif

                        <!-- Ghi chú -->
                        <div class="mb-4">
                            <div class="fw-semibold text-muted">Ghi chú</div>
                            <div class="border rounded bg-light p-3 mt-1">
                                {{ $dayoff->note ?? 'Không có ghi chú' }}
                            </div>
                        </div>

                        <!-- Nút hành động -->
                        <div class="text-end">
                            <a href="{{ route('dayoff.index') }}" class="btn btn-warning me-2">
                                <i class="bx bx-arrow-back"></i> Quay lại
                            </a>

                            @if ($dayoff->status == 'pending')
                                @can(PermissionEnum::DAY_OFF_UPDATE)
                                    <a href="{{ route('dayoff.edit', $dayoff->id) }}" class="btn btn-secondary me-2">
                                        <i class="bx bx-edit me-1"></i> 
                                    </a>
                                @endcan
                                @can(PermissionEnum::DAY_OFF_APPROVE)
                                    <form action="{{ route('dayoff.approve', $dayoff->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success me-1">
                                            <i class="bx bx-check"></i> 
                                        </button>
                                    </form>

                                    <form action="{{ route('dayoff.reject', $dayoff->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </form>
                                @endcan
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
