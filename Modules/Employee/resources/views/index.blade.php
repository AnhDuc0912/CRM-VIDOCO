@extends('core::layouts.app')
@use('Modules\Core\Enums\AccountStatusEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\Core\Enums\RoleEnum')
@use('Modules\Employee\Enums\ContractTypeEnum')
@use('Modules\Employee\Enums\JobPositionEnum')

@section('title', 'Quản lý nhân sự')

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="page-breadcrumb d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <div class="breadcrumb-title pe-3">Quản lý Nhân Sự</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Danh Sách Nhân Sự</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <select id="filter-department" class="form-select" style="width: 180px;">
                        <option value="">-- Phòng ban --</option>
                        @foreach ($employees->pluck('department.name')->unique() as $dept)
                            @if ($dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endif
                        @endforeach
                    </select>

                    <a href="{{ route('employees.create') }}" class="btn btn-dark m-1">
                        <i class="bx bx-cloud-upload me-1"></i>Thêm Nhân Sự
                    </a>
                </div>
            </div>




            <hr />
            <div class="table-responsive">

                <table id="employees-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Họ tên</th>
                            <th>Vị trí</th>
                            <th>Phòng ban</th>
                            <th>Bắt đầu làm việc</th>
                            <th>Hạn hợp đồng</th>
                            <th>Trạng thái</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->full_name ?? '' }}</td>
                                <td>{{ $employee->position->name ?? '' }}
                                </td>
                                <td>{{ $employee->department?->name ?? '' }}
                                </td>
                                <td>{{ $employee->contracts?->last()?->start_date ? format_date($employee->contracts?->last()?->start_date, 'd/m/Y') : '' }}
                                </td>
                                <td> {{ $employee->contracts()->latest()?->first() ? ContractTypeEnum::getLabel($employee->contracts()->latest()?->first()?->contract_type) : 'Chưa xác định' }}
                                </td>
                                <td>
                                    @if ($employee->user?->status == AccountStatusEnum::ACTIVE)
                                        <span class="badge bg-success p-2">Đang làm
                                            việc</span>
                                    @elseif ($employee->user?->status == AccountStatusEnum::INACTIVE)
                                        <span class="badge bg-danger p-2">Đã nghỉ
                                            việc</span>
                                    @else
                                        <span class="badge bg-warning p-2">Chưa tạo tài khoản</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @if ($employee->user)
                                            @can(PermissionEnum::EMPLOYEE_SHOW)
                                                <a href="{{ route('employees.info', $employee->id) }}" title="Xem hồ sơ"
                                                    type="button" class="btn btn-info m-1"><i class="bx bx-info-square"></i>
                                                </a>
                                            @endcan
                                        @endif
                                        @can(PermissionEnum::EMPLOYEE_UPDATE)
                                            <a href="{{ route('employees.edit', $employee->id) }}" title="Cập nhật"
                                                type="button" class="btn btn-secondary m-1"><i class="bx bx-edit me-1"></i></a>
                                        @endcan
                                        @can(PermissionEnum::EMPLOYEE_UPDATE_STATUS)
                                            @if ($employee->user)
                                                <form
                                                    action="{{ route('employees.update-status', [
                                                        'employeeId' => $employee->id,
                                                        'status' => AccountStatusEnum::ACTIVE,
                                                    ]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success m-1"
                                                        @if ($employee->user?->status != AccountStatusEnum::INACTIVE) disabled @endif>
                                                        <i class="bx bx-key me-1"></i>
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('employees.update-status', [
                                                        'employeeId' => $employee->id,
                                                        'status' => AccountStatusEnum::INACTIVE,
                                                    ]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger m-1"
                                                        @if ($employee->user?->status != AccountStatusEnum::ACTIVE) disabled @endif>
                                                        <i class="bx bx-block me-1"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        @can(PermissionEnum::EMPLOYEE_CREATE)
                                            @if (!$employee->user)
                                                <button title="Gửi email tạo mật khẩu" class="btn btn-success m-1"
                                                    onclick="return confirmAction('{{ route('employees.send-password-setup', $employee->id) }}', 'POST', 'Bạn có chắc chắn muốn gửi email tạo mật khẩu cho {{ $employee->full_name ?? '' }}?')">
                                                    <i class="bx bx-envelope me-1"></i>
                                                </button>
                                            @endif
                                        @endcan
                                        @can(PermissionEnum::EMPLOYEE_DELETE)
                                            <button title="Xóa" type="button" class="btn btn-danger m-1"
                                                onclick="confirmDelete('{{ route('employees.delete', $employee->id) }}', 'Bạn có chắc chắn muốn xóa nhân sự {{ $employee->full_name ?? '' }}?')">
                                                <i class="bx bx-trash me-1"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#employees-table').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf'],
                columnDefs: [{
                    targets: [6],
                    orderable: false
                }],
                language: {
                    "sProcessing": "Đang xử lý...",
                    "sLengthMenu": "Xem _MENU_ mục",
                    "sZeroRecords": "Không tìm thấy dòng nào phù hợp",
                    "sInfo": "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    "sInfoEmpty": "Đang xem 0 đến 0 trong tổng số 0 mục",
                    "sInfoFiltered": "(được lọc từ _MAX_ mục)",
                    "sSearch": "Tìm:",
                    "oPaginate": {
                        "sFirst": "Đầu",
                        "sPrevious": "Trước",
                        "sNext": "Tiếp",
                        "sLast": "Cuối"
                    }
                }
            });

            table.buttons().container().appendTo('#employees-table_wrapper .col-md-6:eq(0)');

            $('#filter-department').on('change', function() {
                table.column(2).search(this.value).draw();
            });
        });
    </script>
@endpush
