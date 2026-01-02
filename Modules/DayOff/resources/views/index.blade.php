@extends('core::layouts.app')

@section('title', 'Lịch nghỉ phép')

@section('content')
    @use('Modules\Core\Enums\RoleEnum')
    @use('Modules\Core\Enums\PermissionEnum')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Đề Xuất Nghỉ Phép</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh Sách Đề Xuất Nghỉ Phép</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    @can(PermissionEnum::DAY_OFF_CREATE)
                        <a href="{{ route('dayoff.create') }}" class="btn btn-dark m-1"> <i class="bx bx-cloud-upload me-1"></i> Tạo yêu cầu</a>
                    @endcan

                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Người gửi</th>
                            <th>Ngày</th>
                            <th>Loại</th>
                            <th>Hình thức</th>
                            <th>Lý do</th>
                            <th>Ghi chú</th>
                            <th>Trạng thái</th>
                            <th class="text-center" width="200">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dayoffs as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->user->employee->full_name ?? ($item->user->name ?? 'N/A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                <td>{{ $item->type_label }}</td>
                                <td>{{ $item->mode_label }}</td>
                                <td>{{ $item->reason_label }}</td>
                                <td>{{ $item->note ?? '-' }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $item->status == 'approved' ? 'success' : ($item->status == 'rejected' ? 'danger' : 'secondary') }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td class="text-center">

                                    @can(PermissionEnum::DAY_OFF_SHOW)
                                        <a href="{{ route('dayoff.show', $item->id) }}"
                                            class="btn btn-info"><i class="bx bx-info-square"></i></a>
                                    @endcan

                                    @can(PermissionEnum::DAY_OFF_UPDATE)
                                        @if ($item->status == 'pending')
                                            <a href="{{ route('dayoff.edit', $item->id) }}"
                                                class="btn btn-secondary"><i class="bx bx-edit me-1"></i></a>
                                        @endif
                                    @endcan

                                    @can(PermissionEnum::DAY_OFF_APPROVE)
                                        @if ($item->status == 'pending')
                                            <form action="{{ route('dayoff.approve', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success"><i class="bx bx-check"></i></button>
                                            </form>

                                            <form action="{{ route('dayoff.reject', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger"><i class="bx bx-x"></i></button>
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-3">
                                    Không có yêu cầu nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $dayoffs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
