@extends('core::layouts.app')
@use('Modules\Employee\Enums\EmployeeFileTypeEnum')
@use('App\Helpers\FileHelper')

@section('title', 'Danh Sách Công Việc')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Công Việc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh Sách Công Việc</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('works.create') }}" class="btn btn-info m-1">
                        <i class="bx me-1"></i>Thêm Công Việc
                    </a>
                    <button type="button" class="btn btn-success m-1"><i class="bx bx-cloud-download me-1"></i>Báo
                        Cáo</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Mã CV</th>
                                <th>Tên Công Việc</th>
                                <th>Thuộc Dự Án</th>
                                <th>Người Giao</th>
                                <th>Người Thực Hiện</th>
                                <th>Thời Gian</th>
                                <th>Tiến Độ</th>
                                <th>Trạng Thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($works as $work)
                                <tr>

                                    <td>{{ $work->work_code }}</td>


                                    <td>{{ $work->work_name }}</td>


                                    <td>{{ $work->project?->project_name ?? '-' }}</td>


                                    <td>
                                        <img src="{{ FileHelper::getFileUrl($work->fromUser?->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                            class="rounded-circle shadow" width="40" height="40"
                                            alt="{{ $work->fromUser?->name }}">
                                        {{ $work->fromUser?->name ?? '---' }}
                                    </td>


                                    <td>
                                        <img src="{{ FileHelper::getFileUrl($work->toUser?->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                            class="rounded-circle shadow" width="40" height="40"
                                            alt="{{ $work->toUser?->name }}">
                                        {{ $work->toUser?->name ?? '---' }}
                                    </td>


                                    <td>
                                        BĐ: {{ \Carbon\Carbon::parse($work->created_at)->format('d/m/Y') }} <br>
                                        CN: {{ \Carbon\Carbon::parse($work->updated_at)->format('d/m/Y') }}
                                    </td>


                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $work->progress ?? 0 }}%;"
                                                aria-valuenow="{{ $work->progress ?? 0 }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ $work->progress ?? 0 }}%
                                            </div>
                                        </div>
                                    </td>


                                    <td>
                                        @switch($work->status)
                                            @case(1)
                                                Mới khởi tạo
                                            @break

                                            @case(2)
                                                Đang thực hiện
                                            @break

                                            @case(3)
                                                Hoàn thành
                                            @break

                                            @case(4)
                                                Chờ nghiệm thu
                                            @break

                                            @case(5)
                                                Đã nghiệm thu
                                            @break

                                            @case(6)
                                                Đã bàn giao
                                            @break

                                            @default
                                                Hủy
                                        @endswitch
                                    </td>


                                    <td>
                                        <a title="Xem chi tiết" href="{{ route('works.show', $work->id) }}">
                                            <button type="button" class="btn btn-info m-1">
                                                <i class="bx bx-info-square"></i>
                                            </button>
                                        </a>
                                        <a title="Cập nhật" href="{{ route('works.edit', $work->id) }}">
                                            <button type="button" class="btn btn-secondary m-1">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                        </a>
                                        <a title="Xem báo Cáo" href="">
                                            <button type="button" class="btn btn-success m-1"><i
                                                    class="bx bx-printer"></i></button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Mã CV</th>
                                <th>Tên Công Việc</th>
                                <th>Thuộc Dự Án</th>
                                <th>Người Giao</th>
                                <th>Người Thực Hiện</th>
                                <th>Thời Gian</th>
                                <th>Tiến Độ</th>
                                <th>Trạng Thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            });
            table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
