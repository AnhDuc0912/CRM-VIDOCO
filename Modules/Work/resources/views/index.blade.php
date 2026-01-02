@extends('core::layouts.app')
@use('Modules\Employee\Enums\EmployeeFileTypeEnum')
@use('App\Helpers\FileHelper')
@use('Modules\Core\Enums\RoleEnum')
@use('Modules\Core\Enums\PermissionEnum')
@section('title', 'Danh sách Công Việc')
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
                <div class="btn-group"> <a href="{{ route('work.create') }}" class="btn btn-secondary m-1"> <i
                            class="bx bx-cloud-upload me-1"></i>Thêm Công Việc </a>

                    <a href="{{ request()->fullUrlWithQuery(array_merge(request()->except('page'), ['date_range' => 'today'])) }}"
                        class="btn m-1 {{ request('date_range') == 'today' ? 'btn-outline-primary' : 'btn-primary' }}">
                        Hôm Nay
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(array_merge(request()->except('page'), ['date_range' => 'this_week'])) }}"
                        class="btn m-1 {{ request('date_range') == 'this_week' ? 'btn-outline-primary' : 'btn-primary' }}">
                        Tuần Này
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(array_merge(request()->except('page'), ['date_range' => 'this_month'])) }}"
                        class="btn m-1 {{ request('date_range') == 'this_month' ? 'btn-outline-primary' : 'btn-primary' }}">
                        Tháng Này
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(array_merge(request()->except('page'), ['date_range' => 'last_month'])) }}"
                        class="btn m-1 {{ request('date_range') == 'last_month' ? 'btn-outline-primary' : 'btn-primary' }}">
                        Tháng Trước
                    </a>


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
                        <a href="{{ request()->fullUrlWithQuery(array_merge(request()->except('page'), ['status' => $key])) }}"
                            class="btn btn-{{ $currentStatus == $key ? $status['color'] : $status['color'] }}">
                            {{ $status['label'] }} ({{ $allWorks[$key] ?? 0 }})
                        </a>
                    @endforeach
                </div>

                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Tên Công Việc</th>
                                <th>Thực Hiện</th>
                                <th>Theo Dõi</th>
                                <th>Thuộc Dự Án</th>
                                <th>Thời hạn</th>
                                <th>Độ ưu tiên</th>
                                <th>Tiến Độ</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($works as $work)
                                <tr data-id="{{ $work->id }}">
                                    <td class="text-center">
                                        @if ($work->children && $work->children->count() > 0)
                                            <button class="btn btn-sm btn-outline-primary toggle-children">
                                                <i class="bx bx-plus"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{ $work->id }}</td>
                                    <td>{{ $work->work_name }}
                                        @php
                                            $status = $statuses[$work->status] ?? [
                                                'label' => '',
                                                'color' => '',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $work->status_badge['color'] }}">
                                            {{ $work->status_badge['label'] }}
                                        </span>


                                    </td>
                                    <td>
                                        @foreach ($work->user_employees as $employee)
                                            <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                class="rounded-circle shadow" width="30" height="30"
                                                alt="{{ $employee->name }}">
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach ($work->follow_employees as $employee)
                                            <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                class="rounded-circle shadow" width="30" height="30"
                                                alt="{{ $employee->name }}">
                                        @endforeach
                                    </td>


                                    <td>{{ $work->project->project_name ?? 'Không thuộc dự án' }}</td>


                                    <td>
                                        S:
                                        {{ $work->start_date ? \Carbon\Carbon::parse($work->start_date)->format('d/m/Y') : '-' }}<br>
                                        E:
                                        {{ $work->end_date ? \Carbon\Carbon::parse($work->end_date)->format('d/m/Y') : '-' }}
                                    </td>

                                    <td>
                                        @switch($work->priority)
                                            @case(1)
                                                <span class="badge bg-secondary">Bình thường</span>
                                            @break

                                            @case(2)
                                                <span class="badge bg-primary">Quan Trọng</span>
                                            @break

                                            @case(3)
                                                <span class="badge bg-warning">Ưu Tiên</span>
                                            @break

                                            @default
                                                <span class="badge bg-danger">Khẩn Cấp</span>
                                        @endswitch
                                    </td>

                                    <td>
                                        @if ($work->status >= 3 && $work->progress == 100)
                                            <span class="badge bg-success">Đã hoàn thành tiến độ</span>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <input type="range" min="1" max="100"
                                                    value="{{ $work->progress }}" class="form-range progress-slider me-2"
                                                    data-id="{{ $work->id }}">
                                                <span class="progress-label me-2">{{ $work->progress }}%</span>
                                                <button class="btn btn-sm btn-info update-progress"
                                                    data-id="{{ $work->id }}">Cập nhật</button>
                                            </div>
                                        @endif

                                    </td>

                                    <td>
                                        @can(PermissionEnum::WORK_SHOW)
                                            <a title="Xem chi tiết" href="{{ route('work.show', $work->id) }}"
                                                class="btn btn-info btn-sm m-1">
                                                <i class="bx bx-info-square"></i>
                                            </a>
                                        @endcan
                                        @can(PermissionEnum::WORK_UPDATE)
                                            <a title="Cập nhật" href="{{ route('work.edit', $work->id) }}"
                                                class="btn btn-secondary btn-sm m-1">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endcan
                                        @can(PermissionEnum::WORK_UPDATE)
                                            <a href="{{ route('work.report.index', $work->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bx bx-printer"></i>
                                            </a>
                                        @endcan


                                    </td>
                                </tr>


                                @if ($work->children && $work->children->count() > 0)
                                    @foreach ($work->children as $child)
                                        <tr class="child-row d-none parent-{{ $work->id }}">
                                            <td></td>
                                            <td>{{ $child->id }}</td>
                                            <td>{{ $child->work_name }}

                                                @php
                                                    $status = $statuses[$child->status] ?? [
                                                        'label' => '',
                                                        'color' => '',
                                                    ];
                                                @endphp

                                                <span class="badge bg-{{ $child->status_badge['color'] }}">
                                                    {{ $child->status_badge['label'] }}
                                                </span>

                                            </td>
                                            <td>
                                                @foreach ($child->user_employees as $employee)
                                                    <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                        class="rounded-circle shadow" width="25" height="25"
                                                        alt="{{ $employee->name }}">
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($child->follow_employees as $employee)
                                                    <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                        class="rounded-circle shadow" width="25" height="25"
                                                        alt="{{ $employee->name }}">
                                                @endforeach
                                            </td>


                                            <td>{{ $child->project->project_name ?? 'Không thuộc dự án' }}</td>

                                            <td>
                                                S:
                                                {{ $child->start_date ? \Carbon\Carbon::parse($child->start_date)->format('d/m/Y') : '-' }}<br>
                                                E:
                                                {{ $child->end_date ? \Carbon\Carbon::parse($child->end_date)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                @switch($child->priority)
                                                    @case(1)
                                                        <span class="badge bg-secondary">Bình thường</span>
                                                    @break

                                                    @case(2)
                                                        <span class="badge bg-primary">Quan Trọng</span>
                                                    @break

                                                    @case(3)
                                                        <span class="badge bg-warning">Ưu Tiên</span>
                                                    @break

                                                    @default
                                                        <span class="badge bg-danger">Khẩn Cấp</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if ($child->status >= 3 && $child->progress == 100)
                                                    <span class="badge bg-success">Đã hoàn thành tiến độ</span>
                                                @else
                                                    <div class="d-flex align-items-center">
                                                        <input type="range" min="1" max="100"
                                                            value="{{ $child->progress }}"
                                                            class="form-range progress-slider me-2"
                                                            data-id="{{ $child->id }}">
                                                        <span class="progress-label me-2">{{ $child->progress }}%</span>
                                                        <button class="btn btn-sm btn-info update-progress"
                                                            data-id="{{ $child->id }}">Cập nhật</button>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>

                    </table>
                    <div class="mt-3">
                        {{ $works->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div> @endsection @push('scripts')
    <script>
        $(document).on('click', '.toggle-children', function() {
            var tr = $(this).closest('tr');
            var id = tr.data('id');
            $('.parent-' + id).toggleClass('d-none');
            var icon = $(this).find('i');
            if (icon.hasClass('bx-plus')) {
                icon.removeClass('bx-plus').addClass('bx-minus');
            } else {
                icon.removeClass('bx-minus').addClass('bx-plus');
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.update-progress', function() {
                var btn = $(this);
                var id = btn.data('id');
                var slider = btn.closest('td').find('.progress-slider');
                var value = slider.val();
                var label = btn.closest('td').find('.progress-label');

                $.ajax({
                    url: "{{ route('work.update-progress') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        progress: value
                    },
                    success: function(res) {
                        label.text(value + '%');
                        alert("Cập nhật tiến độ thành công!");
                        location.reload();
                    },
                    error: function() {
                        alert("Cập nhật tiến độ thất bại!");
                        location.reload();
                    }
                });
            });

            $(document).on('input', '.progress-slider', function() {
                var slider = $(this);
                var value = slider.val();
                slider.closest('td').find('.progress-label').text(value + '%');
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .progress-slider {
            width: 100% !important;
            min-width: 120px;
            appearance: none;
            height: 6px;
            border-radius: 5px;
            background: #e9ecef;
            cursor: pointer;
        }

        .progress-slider::-webkit-slider-thumb {
            appearance: none;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #0d6efd;
            cursor: pointer;
        }
    </style>
@endpush
