    @extends('core::layouts.app')

    @section('title', 'Chi tiết dự án')

    @section('content')

        @use('Modules\Employee\Enums\EmployeeFileTypeEnum')
        @use('App\Helpers\FileHelper')

        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Dự Án</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $project->project_name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">

                        <!-- Dropdown Trạng Thái -->
                        <div class="dropdown m-1">
                            <button class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bx bx-refresh me-1"></i>Trạng Thái
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item update-status" data-status="1">Đang chờ</a></li>
                                <li><a class="dropdown-item update-status" data-status="2">Đang thực hiện</a></li>
                                <li><a class="dropdown-item update-status" data-status="3">Hoàn thành</a></li>
                                <li><a class="dropdown-item update-status" data-status="4">Chờ nghiệm thu</a></li>
                                <li><a class="dropdown-item update-status" data-status="5">Đã nghiệm thu</a></li>
                                <li><a class="dropdown-item update-status" data-status="6">Đã bàn giao</a></li>
                                <li><a class="dropdown-item update-status" data-status="7">Hủy</a></li>
                            </ul>
                        </div>

                        <a href="{{ route('work.create', ['project_id' => $project->id]) }}" class="btn btn-info m-1">
                            <i class="bx  me-1"></i>Thêm Công Việc
                        </a>


                        <div class="btn-group">

                            <!-- Modal Tham Gia -->
                            <button class="btn btn-info m-1" data-bs-toggle="modal" data-bs-target="#thamGiaModal">
                                <i class="bx bx-user-plus me-1"></i>Tham Gia
                            </button>

                        </div>


                        <a href="{{ route('project.edit', $project->id) }}" class="btn btn-warning m-1">
                            <i class="bx  me-1"></i>Hang Mục
                        </a>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="user-profile-page">
                <div class="card radius-15">
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
                        <ul class="nav nav-pills">
                            <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab"
                                    href="#thongtinchung"><span class="p-tab-name">Tổng Quan</span><i
                                        class='bx bx-donate-blood font-24 d-sm-none'></i></a>
                            </li>
                            <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#congviec"><span
                                        class="p-tab-name">Danh Sách Công Việc</span><i
                                        class='bx bx-message-edit font-24 d-sm-none'></i></a>
                            </li>
                            <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#thaoluan"><span
                                        class="p-tab-name">Thảo Luận Dự Án</span><i
                                        class='bx bx-message-edit font-24 d-sm-none'></i></a>
                            </li>
                            <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#dinhkem"><span
                                        class="p-tab-name">Đính Kèm Tài Liệu</span><i
                                        class='bx bx-message-edit font-24 d-sm-none'></i></a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3">

                            <div class="tab-pane fade show active" id="thongtinchung">
                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="card shadow-none border radius-15">
                                            <div class="card-body">
                                                <h5 class="mb-3">Tổng Quan</h5>
                                                <div class="row g-3 mb-4">
                                                    <div class="col-12">
                                                        <label class="form-label">Tên Dự Án</label>
                                                        <input readonly type="text" class="form-control"
                                                            value="{{ $project->project_name }}">
                                                    </div>

                                                    <div class="col-3">
                                                        <label class="form-label">Trạng Thái</label>
                                                        <select class="form-select" name="status" required disabled>
                                                            <option value="1"
                                                                {{ old('status', $project->status) == 1 ? 'selected' : '' }}>
                                                                Đang
                                                                chờ</option>
                                                            <option value="2"
                                                                {{ old('status', $project->status) == 2 ? 'selected' : '' }}>
                                                                Đang
                                                                thực hiện</option>
                                                            <option value="3"
                                                                {{ old('status', $project->status) == 3 ? 'selected' : '' }}>
                                                                Hoàn
                                                                thành</option>
                                                            <option value="4"
                                                                {{ old('status', $project->status) == 4 ? 'selected' : '' }}>
                                                                Chờ
                                                                nghiệm thu</option>
                                                            <option value="5"
                                                                {{ old('status', $project->status) == 5 ? 'selected' : '' }}>
                                                                Đã
                                                                nghiệm thu</option>
                                                            <option value="6"
                                                                {{ old('status', $project->status) == 6 ? 'selected' : '' }}>
                                                                Đã bàn
                                                                giao</option>
                                                            <option value="0"
                                                                {{ old('status', $project->status) == 0 ? 'selected' : '' }}>
                                                                Hủy
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-4">
                                                        <label class="form-label">Mức độ</label>
                                                        <select class="form-select" name="level" required disabled>
                                                            <option value="1"
                                                                {{ old('level', $project->level) == 1 ? 'selected' : '' }}>
                                                                Bình
                                                                thường</option>
                                                            <option value="2"
                                                                {{ old('level', $project->level) == 2 ? 'selected' : '' }}>
                                                                Khẩn cấp
                                                            </option>
                                                            <option value="3"
                                                                {{ old('level', $project->level) == 3 ? 'selected' : '' }}>
                                                                Quan
                                                                trọng</option>
                                                            <option value="4"
                                                                {{ old('level', $project->level) == 4 ? 'selected' : '' }}>
                                                                Ưu tiên
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-4">
                                                        <label class="form-label">Thuộc Nhóm</label>
                                                        <select disabled class="form-select" name="group">
                                                            @foreach ($groups as $group)
                                                                <option value="{{ $group->id }}"
                                                                    {{ old('group', $project->group) == $group->id ? 'selected' : '' }}>
                                                                    {{ $group->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-5">
                                                        <label class="form-label">Quản lý</label>
                                                        <input readonly type="text" class="form-control"
                                                            value="{{ $project->manager->full_name ?? 'Chưa có' }}">
                                                    </div>

                                                    <div class="col-7">
                                                        <label class="form-label">Cách tính tiến độ dự án</label>
                                                        <input readonly type="text" class="form-control"
                                                            value="{{ $project->progress_calculate == 1 ? 'Theo % nhân viên cập nhật' : 'Khác' }}">
                                                    </div>

                                                    <div class="col-6">
                                                        <label class="form-label">Người tham gia</label>
                                                        <div>
                                                            @foreach ($project->assignee_employees as $employee)
                                                                <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                                    class="rounded-circle shadow" width="30"
                                                                    height="30" alt="{{ $employee->name }}">
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        <label class="form-label">Người theo dõi</label>
                                                        <div>
                                                            @foreach ($project->follow_employees as $employee)
                                                                <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                                    class="rounded-circle shadow" width="30"
                                                                    height="30" alt="{{ $employee->name }}">
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <label class="form-label">Ngày Bắt Đầu</label>
                                                        <input readonly type="date" class="form-control"
                                                            name="start_date"
                                                            value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label">Ngày Kết thúc</label>
                                                        <input readonly type="date" class="form-control"
                                                            name="end_date"
                                                            value="{{ old('end_date', $project->end_date->format('Y-m-d')) }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label">Ngày thực tế hoàn thành</label>
                                                        @if ($project->complete_date)
                                                            <input readonly type="date" class="form-control"
                                                                name="complete_date"
                                                                value="{{ old('complete_date', $project->complete_date->format('Y-m-d')) }}">
                                                        @else
                                                            <input readonly type="text" class="form-control"
                                                                value="N/A">
                                                        @endif
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">Link Thảo Luận Nhóm Zalo</label>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $project->zalo_group ?? '0' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $today = now();

                                        $onTime = $project->works
                                            ->filter(function ($w) {
                                                return $w->status >= 4 && $w->complete_date <= $w->end_date;
                                            })
                                            ->count();

                                        $late = $project->works
                                            ->filter(function ($w) use ($today) {
                                                return ($w->status >= 4 && $w->complete_date > $w->end_date) ||
                                                    ($w->status < 4 && $w->end_date < $today);
                                            })
                                            ->count();
                                    @endphp

                                    <div class="col-6">
                                        <div class="card shadow-none border  radius-15">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="card radius-15 bg-voilet">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h2 class="mb-0 text-white">
                                                                            {{ $project->works->count() }}</h2>
                                                                    </div>
                                                                    <div class="ms-auto font-30 text-white"><i
                                                                            class="bx bx-list-ol"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <p class="mb-0 text-white">Công Việc Cần Làm</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="card radius-15 bg-secondary">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h2 class="mb-0 text-white">{{ $onTime }}
                                                                        </h2>
                                                                    </div>
                                                                    <div class="ms-auto font-30 text-white"><i
                                                                            class="bx bx-calendar-star"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <p class="mb-0 text-white">Việc Đúng Hạn</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="card radius-15 bg-danger">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h2 class="mb-0 text-white">{{ $late }}
                                                                        </h2>
                                                                    </div>
                                                                    <div class="ms-auto font-30 text-white"><i
                                                                            class="bx bx-list-ol"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <p class="mb-0 text-white">Việc Trễ hạn</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="card radius-15 bg-info ">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h2 class="mb-0 text-white">0</h2>
                                                                    </div>
                                                                    <div class="ms-auto font-30 text-white"><i
                                                                            class="bx bx-bell"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <p class="mb-0 text-white">Báo Cáo Gửi lên</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="card radius-15 bg-primary">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h2 class="mb-0 text-white">0</h2>
                                                                    </div>
                                                                    <div class="ms-auto font-30 text-white"><i
                                                                            class="bx bx-file"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <p class="mb-0 text-white">Tài liệu</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="card radius-15 bg-success">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h2 class="mb-0 text-white">
                                                                            {{ $project->progress ?? 0 }}%</h2>
                                                                    </div>
                                                                    <div class="ms-auto font-30 text-white"><i
                                                                            class="bx bx-line-chart-down"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <p class="mb-0 text-white">Tiến độ</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            @foreach ($project->categories as $category)
                                                <div class="col-6">
                                                    <div class="card shadow-none border radius-15">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="font-30 text-primary">
                                                                    <i class="bx bxs-folder"></i>
                                                                </div>

                                                                <div class="user-groups ms-auto">
                                                                    <img src="{{ FileHelper::getFileUrl($category->manager?->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                                        width="35" height="35"
                                                                        class="rounded-circle" alt="">
                                                                </div>
                                                            </div>

                                                            <h6 class="mb-0 text-primary">{{ $category->name }}</h6>
                                                            <small>
                                                                <a href="{{ route('work.index', ['category_id' => $category->id]) }}"
                                                                    class="text-decoration-none text-muted">
                                                                    {{ $category->works->count() ?? 0 }} Công Việc
                                                                </a>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>




                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <div class="card shadow-none border radius-15">
                                            <div class="card-body">
                                                <h5 class="mb-3">Mô tả chi tiết</h5>
                                                <div class="border p-3 radius-15" style="min-height:100px">
                                                    {!! $project->description ?? '0' !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <div class="card shadow-none border radius-15">
                                            <div class="card-body">
                                                <h5 class="mb-3">Tài Liệu Đính Kèm</h5>
                                                <div class="row g-3 mb-4">
                                                    <div class="col-12">
                                                        <div class="table-responsive mt-3">
                                                            <table class="table table-striped table-hover table-sm mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Tên File <i
                                                                                class='bx bx-up-arrow-alt ms-2'></i></th>
                                                                        <th>Người Tải Lên</th>
                                                                        <th>Ngày Tải Lên</th>
                                                                        <th>Chức năng</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse ($project->files as $file)
                                                                        @php
                                                                            $extension = strtolower(
                                                                                pathinfo(
                                                                                    $file->file_path,
                                                                                    PATHINFO_EXTENSION,
                                                                                ),
                                                                            );
                                                                            $icon = 'bxs-file';
                                                                            $color = 'primary';

                                                                            switch ($extension) {
                                                                                case 'pdf':
                                                                                    $icon = 'bxs-file-pdf';
                                                                                    $color = 'danger';
                                                                                    break;
                                                                                case 'doc':
                                                                                case 'docx':
                                                                                    $icon = 'bxs-file-doc';
                                                                                    $color = 'success';
                                                                                    break;
                                                                                case 'xls':
                                                                                case 'xlsx':
                                                                                    $icon = 'bxs-file-xls';
                                                                                    $color = 'success';
                                                                                    break;
                                                                                case 'ppt':
                                                                                case 'pptx':
                                                                                    $icon = 'bxs-file-ppt';
                                                                                    $color = 'warning';
                                                                                    break;
                                                                                default:
                                                                                    $icon = 'bxs-file';
                                                                                    $color = 'primary';
                                                                            }
                                                                        @endphp
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center">
                                                                                    <div><i
                                                                                            class='bx {{ $icon }} me-2 font-24 text-{{ $color }}'></i>
                                                                                    </div>
                                                                                    <div
                                                                                        class="fw-bold text-{{ $color }}">
                                                                                        {{ basename($file->file_path) }}
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>{{ $file->uploader->full_name ?? 'Không rõ' }}
                                                                            </td>
                                                                            <td>{{ \Carbon\Carbon::parse($file->created_at)->format('M d, Y') }}
                                                                            </td>
                                                                            <td>
                                                                                <a title="Xem chi tiết"
                                                                                    href="{{ asset('storage/' . $file->file_path) }}"
                                                                                    target="_blank">
                                                                                    <button type="button"
                                                                                        class="btn btn-info m-1">
                                                                                        <i class="bx bx-info-square"></i>
                                                                                    </button>
                                                                                </a>
                                                                                <a title="Tải về"
                                                                                    href="{{ asset('storage/' . $file->file_path) }}"
                                                                                    download>
                                                                                    <button type="button"
                                                                                        class="btn btn-primary m-1">
                                                                                        <i class="bx bx-printer"></i>
                                                                                    </button>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="4"
                                                                                class="text-center text-muted">Chưa có tài
                                                                                liệu đính kèm</td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <div class="card shadow-none border radius-15">
                                            <div class="card-body">
                                                <h5 class="mb-3">Nhật ký hoạt động</h5>
                                                <div class="row g-3 mb-4">
                                                    <div class="col-12">
                                                        @forelse ($project->logs as $log)
                                                            <div class="alert alert-secondary fade show" role="alert">
                                                                <strong>{{ $log->user->full_name ?? 'Hệ thống' }}</strong>
                                                                {{ $log->description ?? $log->action }}
                                                                <span class="text-muted float-end small">
                                                                    {{ $log->created_at->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                        @empty
                                                            <div class="alert alert-light">Chưa có hoạt động nào.</div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="congviec">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="example2" class="table table-striped table-bordered"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Tên Công Việc</th>
                                                        <th>Thực Hiện</th>
                                                        <th>Theo dõi</th>
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
                                                                    <button
                                                                        class="btn btn-sm btn-outline-primary toggle-children">
                                                                        <i class="bx bx-plus"></i> </button>
                                                                @endif
                                                            </td>
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
                                                                        class="rounded-circle shadow" width="30"
                                                                        height="30" alt="{{ $employee->name }}">
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @foreach ($work->follow_employees as $employee)
                                                                    <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                                        class="rounded-circle shadow" width="25"
                                                                        height="25" alt="{{ $employee->name }}">
                                                                @endforeach
                                                            </td>


                                                            <td> S:
                                                                {{ $work->start_date ? \Carbon\Carbon::parse($work->start_date)->format('d/m/Y') : '-' }}<br>
                                                                E:
                                                                {{ $work->end_date ? \Carbon\Carbon::parse($work->end_date)->format('d/m/Y') : '-' }}
                                                            </td>
                                                            <td> @switch($work->priority)
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
                                                                    <span class="badge bg-success">Đã hoàn thành tiến
                                                                        độ</span>
                                                                @else
                                                                    <div class="d-flex align-items-center"> <input
                                                                            type="range" min="1" max="100"
                                                                            value="{{ $work->progress }}"
                                                                            class="form-range progress-slider me-2"
                                                                            data-id="{{ $work->id }}">
                                                                        <span
                                                                            class="progress-label me-2">{{ $work->progress }}%</span>
                                                                        <button class="btn btn-sm btn-info update-progress"
                                                                            data-id="{{ $work->id }}">Cập
                                                                            nhật</button>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td> <a title="Xem chi tiết"
                                                                    href="{{ route('work.show', $work->id) }}"
                                                                    class="btn btn-info m-1">
                                                                    <i class="bx bx-info-square"></i> </a> <a
                                                                    title="Cập nhật"
                                                                    href="{{ route('work.edit', $work->id) }}"
                                                                    class="btn btn-secondary m-1">
                                                                    <i class="bx bx-edit"></i> </a>
                                                                <a href="{{ route('work.report.index', $work->id) }}"
                                                                    class="btn btn-primary">
                                                                    <i class="bx bx-printer"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @if ($work->children && $work->children->count() > 0)
                                                            @foreach ($work->children as $child)
                                                                <tr class="child-row d-none parent-{{ $work->id }}">
                                                                    <td></td>
                                                                    <td>{{ $child->work_name }}
                                                                        @php
                                                                            $status = $statuses[$child->status] ?? [
                                                                                'label' => '',
                                                                                'color' => '',
                                                                            ];
                                                                        @endphp

                                                                        <span
                                                                            class="badge bg-{{ $child->status_badge['color'] }}">
                                                                            {{ $child->status_badge['label'] }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        @foreach ($child->user_employees as $employee)
                                                                            <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                                                class="rounded-circle shadow"
                                                                                width="25" height="25"
                                                                                alt="{{ $employee->name }}">
                                                                        @endforeach
                                                                    </td>
                                                                    <td>
                                                                        @foreach ($child->follow_employees as $employee)
                                                                            <img src="{{ FileHelper::getFileUrl($employee->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '') ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                                                class="rounded-circle shadow"
                                                                                width="25" height="25"
                                                                                alt="{{ $employee->name }}">
                                                                        @endforeach
                                                                    </td>
                                                                    <td> S:
                                                                        {{ $child->start_date ? \Carbon\Carbon::parse($child->start_date)->format('d/m/Y') : '-' }}<br>
                                                                        E:
                                                                        {{ $child->end_date ? \Carbon\Carbon::parse($child->end_date)->format('d/m/Y') : '-' }}
                                                                    </td>
                                                                    <td> @switch($child->priority)
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
                                                                            <span class="badge bg-success">Đã hoàn thành
                                                                                tiến độ</span>
                                                                        @else
                                                                            <div class="d-flex align-items-center"> <input
                                                                                    type="range" min="1"
                                                                                    max="100"
                                                                                    value="{{ $child->progress }}"
                                                                                    class="form-range progress-slider me-2"
                                                                                    data-id="{{ $child->id }}"> <span
                                                                                    class="progress-label me-2">{{ $child->progress }}%</span>
                                                                                <button
                                                                                    class="btn btn-sm btn-info update-progress"
                                                                                    data-id="{{ $child->id }}">Cập
                                                                                    nhật</button>
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Bình luận -->
                            <div class="tab-pane fade" id="thaoluan">
                                <div class="card shadow-none border mb-0 radius-15">
                                    <div class="card-body">
                                        <h5 class="mb-3">Thảo Luận</h5>

                                        <!-- Form nhập bình luận -->
                                        <form action="{{ route('comment.store') }}" method="POST" class="d-flex mb-3">
                                            @csrf
                                            <input type="hidden" name="commentable_id" value="{{ $project->id }}">
                                            <input type="hidden" name="commentable_type" value="project">
                                            <input type="hidden" name="parent_id" value="">

                                            <img src="{{ auth()->user()?->employee?->avatar ? FileHelper::getFileUrl(auth()->user()?->employee?->avatar) : asset('assets/images/avatars/avatar-1.png') }}"
                                                class="rounded-circle me-2" width="40" height="40">

                                            <div class="comment-editor form-control" contenteditable="true"
                                                data-placeholder="Viết bình luận..." style="min-height: 42px"></div>

                                            <input type="hidden" name="content" id="comment-content">

                                            <button class="btn btn-success ms-2">Gửi</button>
                                        </form>

                                        <ul class="list-unstyled">
                                            @foreach ($project->comments()->whereNull('parent_id')->latest()->get() as $comment)
                                                @include('comment::components.comments', [
                                                    'comment' => $comment,
                                                ])
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="dinhkem">
                                <div class="card shadow-none border mb-0 radius-15">
                                    <div class="card-body">
                                        <h5 class="mb-3">Upload tài liệu</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-12">
                                                <input type="file" class="filepond" name="files[]" multiple
                                                    data-max-files="10">
                                                @if ($project && $project->files && $project->files->count() > 0)
                                                    <strong class="text-danger">* File đã thêm trước đây:</strong>
                                                    <div class="d-flex flex-wrap gap-2">

                                                        @foreach ($project->files as $file)
                                                            <div class="old-file-item position-relative border rounded p-2"
                                                                id="file-{{ $file->id }}"
                                                                style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">

                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-old-file"
                                                                    data-id="{{ $file->id }}">×</button>

                                                                @if (in_array($file->extension, ['png', 'jpg', 'jpeg', 'gif', 'webp']))
                                                                    <img src="{{ asset('storage/' . $file->file_path) }}"
                                                                        alt="file"
                                                                        style="max-width: 100%; max-height: 100%;">
                                                                @else
                                                                    <div class="text-center">
                                                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                                                            target="_blank" class="text-decoration-none">
                                                                            <strong>{{ strtoupper($file->extension) }}</strong>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="thamGiaModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quản lý thành viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <p>Người đang tham gia:</p>
                        <div class="mb-3">
                            @foreach ($project->assignee_employees as $employee)
                                <span class="badge bg-primary p-2">{{ $employee->full_name }}</span>
                            @endforeach
                        </div>

                        <hr>

                        <label>Thêm người mới</label>
                        <select class="form-select" id="new-member">
                            @foreach ($users as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-info" id="add-member">Thêm</button>
                    </div>
                </div>
            </div>
        </div>


    @endsection

    @push('styles')
        <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
            rel="stylesheet">
        <style>
            .filepond--root {
                border: 2px dashed #3b82f6 !important;
                border-radius: 12px;
                background: #f8fafc;
            }

            .filepond--panel-root {
                background-color: transparent !important;
            }

            .filepond--drop-label {
                color: #1e293b !important;
                font-weight: 600;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js">
        </script>
        <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/at.js/1.5.4/css/jquery.atwho.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Caret.js/0.3.1/jquery.caret.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/at.js/1.5.4/js/jquery.atwho.min.js"></script>

        <script>
            FilePond.create(document.querySelector('.filepond'), {
                acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ],
                allowMultiple: true,
                maxFiles: 10,
                labelIdle: 'Chọn file',

                storeAsFile: true
            });
        </script>
        <script>
            $(document).on('click', '.remove-old-file', function() {
                let fileId = $(this).data('id');

                $('<input>').attr({
                    type: 'hidden',
                    name: 'delete_files[]',
                    value: fileId
                }).appendTo('form');

                $('#file-' + fileId).fadeOut();
            });
        </script>
        <script>
            $('.single-select').select2({
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });
            $('.single-select2').select2({
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });
            $('.multiple-select').select2({
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });
        </script>

        <script>
            $('#fancy-file-upload').FancyFileUpload({
                url: "{{ route('project.upload-file') }}",
                params: {
                    _token: '{{ csrf_token() }}'
                },
                maxfilesize: 10000000,
                edit: false,
                added: function(e, data) {
                    return true;
                },
                uploadcompleted: function(e, data) {
                    let uploaded = $('#uploaded_files').val();
                    let files = uploaded ? JSON.parse(uploaded) : [];

                    files.push({
                        path: data.result.path,
                        name: data.result.name,
                        extension: data.result.extension,
                        size: data.result.size
                    });

                    $('#uploaded_files').val(JSON.stringify(files));
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#image-uploadify').imageuploadify();
            })
        </script>

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
                // Update tiến độ
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

                // Hiển thị giá trị % khi kéo slider
                $(document).on('input', '.progress-slider', function() {
                    var slider = $(this);
                    var value = slider.val();
                    slider.closest('td').find('.progress-label').text(value + '%');
                });
            });
        </script>

        <script>
            $(document).on('click', '.reply-btn', function() {
                let id = $(this).data('id');
                $('#reply-form-' + id).toggleClass('d-none');
            });
        </script>

        <script>
            $('.update-status').click(function() {
                let status = $(this).data('status');
                $.post("{{ route('project.updateStatus', $project->id) }}", {
                    status: status,
                    _token: "{{ csrf_token() }}"
                }, function(res) {
                    location.reload();
                });
            });

            $('#add-member').click(function() {
                let empId = $('#new-member').val();
                $.post("{{ route('project.addMember', $project->id) }}", {
                    employee_id: empId,
                    _token: "{{ csrf_token() }}"
                }, function(res) {
                    location.reload();
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

    @push('scripts')
        <!-- CKEditor 5 Classic -->
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const editorElement = document.querySelector('#mytextarea');

                if (editorElement) {
                    ClassicEditor
                        .create(editorElement, {
                            toolbar: [
                                'undo', 'redo', '|',
                                'heading', '|',
                                'bold', 'italic', 'underline', '|',
                                'bulletedList', 'numberedList', '|',
                                'link', 'insertTable', '|',
                                'blockQuote', 'codeBlock'
                            ],
                            heading: {
                                options: [{
                                        model: 'paragraph',
                                        title: 'Đoạn văn',
                                        class: 'ck-heading_paragraph'
                                    },
                                    {
                                        model: 'heading1',
                                        view: 'h1',
                                        title: 'Tiêu đề 1',
                                        class: 'ck-heading_heading1'
                                    },
                                    {
                                        model: 'heading2',
                                        view: 'h2',
                                        title: 'Tiêu đề 2',
                                        class: 'ck-heading_heading2'
                                    }
                                ]
                            },
                            language: 'vi'
                        })
                        .then(editor => {
                            console.log('CKEditor đã sẵn sàng', editor);
                        })
                        .catch(error => {
                            console.error('Lỗi khi khởi tạo CKEditor:', error);
                        });
                }
            });

            $(function() {

                $('.comment-editor').atwho({
                    at: "@",
                    delay: 200,
                    displayTpl: "<li>${name}</li>",
                    insertTpl: '<span class="mention" contenteditable="false" ' +
                        'data-user-id="${id}">@${name}</span>&nbsp;',
                    searchKey: "name",
                    limit: 10,

                    callbacks: {
                        remoteFilter: function(query, render) {
                            $.get("{{ route('comment.search_employee') }}", {
                                q: query
                            }, function(data) {
                                render(data);
                            });
                        }
                    }
                });

                $('form').on('submit', function() {
                    let html = $('.comment-editor').html();
                    $('#comment-content').val(html);
                });



            });
        </script>

        <style>
            .ck-editor__editable_inline {
                min-height: 250px;
                border-radius: 10px;
            }

            .mention {
                background: #e0f2fe;
                color: #0369a1;
                padding: 2px 6px;
                border-radius: 6px;
                display: inline-block;
                font-weight: 600;
            }

            .mention::after {
                content: '\00a0';
            }
        </style>
    @endpush
