@extends('core::layouts.app')
@use('Modules\Employee\Enums\EmployeeFileTypeEnum')
@use('App\Helpers\FileHelper')
@use('Modules\Core\Enums\RoleEnum')
@use('Modules\Core\Enums\PermissionEnum')

@section('title', 'Danh sách dự án')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Dự Án</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh Sách Dự Án</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('project.create') }}" class="btn btn-secondary m-1">
                        <i class="bx bx-cloud-upload me-1"></i>Thêm Dự Án
                    </a>
                    <button type="button" class="btn btn-primary m-1"><i class="bx bx-cloud-download me-1"></i>Báo
                        Cáo</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                @php
                    $statuses = [
                        1 => ['label' => 'Đang chờ', 'color' => 'secondary'],
                        2 => ['label' => 'Đang thực hiện', 'color' => 'info'],
                        3 => ['label' => 'Hoàn thành', 'color' => 'success'],
                        4 => ['label' => 'Chờ nghiệm thu', 'color' => 'warning'],
                        5 => ['label' => 'Đã nghiệm thu', 'color' => 'primary'],
                        6 => ['label' => 'Đã bàn giao', 'color' => 'dark'],
                        0 => ['label' => 'Hủy', 'color' => 'danger'],
                    ];

                    $currentStatus = request('status');
                @endphp

                <div class="mb-3 d-flex gap-2">
                    @foreach ($statuses as $key => $status)
                        <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}"
                            class="btn btn-{{ $currentStatus == $key ? $status['color'] : $status['color'] }}">
                            {{ $status['label'] }} ({{ $allProjects[$key] ?? 0 }})
                        </a>
                    @endforeach

                </div>


                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Quản Lý</th>
                                <th>Tên Dự Án</th>
                                <th>Thực Hiện</th>
                                <th>Thời hạn</th>
                                <th>Tiến Độ</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>
                                        {{ $project->id }}
                                    </td>
                                    <td>
                                        <img src="{{ FileHelper::getFileUrl($project->manager->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                            class="rounded-circle shadow" width="60" height="60" alt="">
                                    </td>

                                    <td>{{ $project->project_name }}
                                        @php
                                            $status = $statuses[$project->status] ?? [
                                                'label' => '',
                                                'color' => '',
                                            ];
                                        @endphp

                                        <span class="badge bg-{{ $project->status_badge['color'] }}">
                                            {{ $project->status_badge['label'] }}
                                        </span>

                                    </td>

                                    <td>
                                        @foreach ($project->assignee_employees as $employee)
                                            <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                class="rounded-circle shadow" width="30" height="30"
                                                alt="{{ $employee->name }}">
                                        @endforeach
                                    </td>

                                    <td>
                                        S: {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }} <br>
                                        E: {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}
                                    </td>

                                    <td>
                                        @if ($project->progress_calculate == 1)
                                            @if ($project->status >= 3 && $project->progress == 100)
                                                <span class="badge bg-success">Đã hoàn thành tiến độ</span>
                                            @else
                                                <div class="d-flex align-items-center">
                                                    <input type="range" min="1" max="100"
                                                        value="{{ $project->progress ?? 0 }}"
                                                        class="form-range project-progress-slider me-2"
                                                        data-id="{{ $project->id }}">
                                                    <span class="progress-label me-2">{{ $project->progress ?? 0 }}%</span>
                                                    <button class="btn btn-sm btn-info update-project-progress"
                                                        data-id="{{ $project->id }}">Cập nhật</button>
                                                </div>
                                            @endif
                                        @else
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $project->progress ?? 0 }}%;"
                                                    aria-valuenow="{{ $project->progress ?? 0 }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ $project->progress ?? 0 }}%
                                                </div>
                                            </div>
                                        @endif
                                    </td>



                                    <td>
                                        @can(PermissionEnum::PROJECT_SHOW)
                                            <a title="Xem chi tiết" href="{{ route('project.show', $project->id) }}">
                                                <button type="button" class="btn btn-info m-1">
                                                    <i class="bx bx-info-square"></i>
                                                </button>
                                            </a>
                                        @endcan
                                        @can(PermissionEnum::PROJECT_UPDATE)
                                            <a title="Cập nhật" href="{{ route('project.edit', $project->id) }}">
                                                <button type="button" class="btn btn-secondary m-1">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                            </a>
                                        @endcan
                                        @can(PermissionEnum::PROJECT_CREATE)
                                            <a title="Thêm Công Việc"
                                                href="{{ route('work.create', ['project_id' => $project->id]) }}">
                                                <button type="button" class="btn btn-success m-1"><i
                                                        class="bx bx-alarm-add"></i></button>
                                            </a>
                                        @endcan
                                        <a title="Xem Báo Cáo" href="{{ route('project.reports', $project->id) }}">
                                            <button type="button" class="btn btn-primary m-1">
                                                <i class="bx bx-printer"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
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

    <script>
        $(document).ready(function() {
            $(document).on('click', '.update-project-progress', function() {
                var btn = $(this);
                var id = btn.data('id');
                var slider = btn.closest('td').find('.project-progress-slider');
                var value = slider.val();
                var label = btn.closest('td').find('.progress-label');

                $.ajax({
                    url: "{{ route('project.update-progress') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        progress: value
                    },
                    success: function(res) {
                        label.text(value + '%');
                        alert("Cập nhật tiến độ dự án thành công!");
                        location.reload();
                    },
                    error: function() {
                        alert("Cập nhật tiến độ thất bại!");
                    }
                });
            });

            $(document).on('input', '.project-progress-slider', function() {
                var slider = $(this);
                var value = slider.val();
                slider.closest('td').find('.progress-label').text(value + '%');
            });
        });
    </script>
@endpush
