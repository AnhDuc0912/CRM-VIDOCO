@extends('core::layouts.app')

@section('title', 'Chỉnh sửa dự án')

@section('content')

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dự Án</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('project.index') }}"><i class='bx bx-home-alt'></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa dự án</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="user-profile-page">
            <div class="card radius-15">
                <div class="card-body">
                    <form action="{{ route('project.update', $project->id) }}" method="POST" enctype="multipart/form-data"
                        class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#thongtinchung">
                                    <span class="p-tab-name">Thông tin chung</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#upload_File">
                                    <span class="p-tab-name">Cấu hình dự án</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <!-- Tab Thông tin chung -->
                            <div class="tab-pane fade show active" id="thongtinchung">
                                <div class="card shadow-none border mb-0 radius-15">
                                    <div class="card-body">
                                        <h5 class="mb-3">Thông tin chung</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-3">
                                                <label class="form-label">Mã Dự Án</label>
                                                <input name="project_code" type="text" class="form-control"
                                                    value="{{ old('project_code', $project->project_code) }}" required disabled>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Tên Dự Án</label>
                                                <input name="project_name" type="text" class="form-control"
                                                    value="{{ old('project_name', $project->project_name) }}" required>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label">Thuộc Nhóm</label>
                                                <select class="form-select" name="group" required>
                                                    @foreach ($groups as $group)
                                                        <option value="{{ $group->id }}"
                                                            {{ old('group', $project->group) == $group->id ? 'selected' : '' }}>
                                                            {{ $group->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label">Ngày Bắt Đầu</label>
                                                <input type="date" class="form-control" name="start_date"
                                                    value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}"
                                                    required>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label">Ngày Kết Thúc</label>
                                                <input type="date" class="form-control" name="end_date"
                                                    value="{{ old('end_date', $project->end_date->format('Y-m-d')) }}"
                                                    required>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label">Khách hàng</label>
                                                <select class="single-select" name="customer_id" required>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"
                                                            {{ old('customer_id', $project->customer_id) == $customer->id ? 'selected' : '' }}>
                                                            {{ !empty($customer->first_name) ? $customer->last_name . ' ' . $customer->first_name : $customer->company_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label">Người Quản Lý</label>
                                                <select class="single-select2" name="manager_id" required>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ old('manager_id', $project->manager_id) == $user->id ? 'selected' : '' }}>
                                                            {{ $user->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Người Thực hiện</label>
                                                <select class="multiple-select" name="assignees[]" multiple required>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ in_array($user->id, old('assignees', json_decode($project->assignees) ?? [])) ? 'selected' : '' }}>
                                                            {{ $user->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Người Theo Dõi</label>
                                                <select class="multiple-select" name="follow_id[]" multiple required>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ in_array($user->id, old('follow_id', json_decode($project->follow_id) ?? [])) ? 'selected' : '' }}>
                                                            {{ $user->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Cách tính tiến độ</label>
                                                <select class="single-select2" name="progress_calculate" required disabled>
                                                    <option value="1"
                                                        {{ old('progress_calculate', $project->progress_calculate) == 1 ? 'selected' : '' }}>
                                                        Theo % nhân viên</option>
                                                    <option value="2"
                                                        {{ old('progress_calculate', $project->progress_calculate) == 2 ? 'selected' : '' }}>
                                                        Theo khối lượng công việc</option>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label">Mức độ</label>
                                                <select class="form-select" name="level" required>
                                                    <option value="1"
                                                        {{ old('level', $project->level) == 1 ? 'selected' : '' }}>Bình
                                                        thường</option>
                                                    <option value="2"
                                                        {{ old('level', $project->level) == 2 ? 'selected' : '' }}>Khẩn cấp
                                                    </option>
                                                    <option value="3"
                                                        {{ old('level', $project->level) == 3 ? 'selected' : '' }}>Quan
                                                        trọng</option>
                                                    <option value="4"
                                                        {{ old('level', $project->level) == 4 ? 'selected' : '' }}>Ưu tiên
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label">Trạng Thái</label>
                                                <select class="form-select" name="status" required>
                                                    <option value="1"
                                                        {{ old('status', $project->status) == 1 ? 'selected' : '' }}>Đang
                                                        chờ</option>
                                                    <option value="2"
                                                        {{ old('status', $project->status) == 2 ? 'selected' : '' }}>Đang
                                                        thực hiện</option>
                                                    <option value="3"
                                                        {{ old('status', $project->status) == 3 ? 'selected' : '' }}>Hoàn
                                                        thành</option>
                                                    <option value="4"
                                                        {{ old('status', $project->status) == 4 ? 'selected' : '' }}> Chờ
                                                        nghiệm thu</option>
                                                    <option value="5"
                                                        {{ old('status', $project->status) == 5 ? 'selected' : '' }}> Đã
                                                        nghiệm thu</option>
                                                    <option value="6"
                                                        {{ old('status', $project->status) == 6 ? 'selected' : '' }}>Đã bàn
                                                        giao</option>
                                                    <option value="0"
                                                        {{ old('status', $project->status) == 0 ? 'selected' : '' }}>Hủy
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <hr>
                                        <h5 class="mt-4 mb-3">Thông Tin Dự Án</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-12">
                                                <label class="form-label">Mô Tả Chi Tiết</label>
                                                <textarea id="mytextarea" name="description">{{ old('description', $project->description) }}</textarea>
                                            </div>
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

                                                                <!-- Nút xoá -->
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-old-file"
                                                                    data-id="{{ $file->id }}">×</button>

                                                                <!-- Hiển thị thumbnail hoặc extension -->
                                                                @if (in_array($file->extension, ['png', 'jpg', 'jpeg', 'gif', 'webp']))
                                                                    <img src="{{ asset('storage/' . $file->file_path) }}"
                                                                        alt="file"
                                                                        style="max-width: 100%; max-height: 100%;">
                                                                @else
                                                                    <div class="text-center">
                                                                        <strong>{{ strtoupper($file->extension) }}</strong>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                @endif


                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Ngân sách dự án</label>
                                                <input type="text" class="form-control" name="budget_min"
                                                    value="{{ old('budget_min', number_format($project->budget_min)) }}">
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Ngân sách tối đa</label>
                                                <input type="text" class="form-control" name="budget_max"
                                                    value="{{ old('budget_max', number_format($project->budget_max)) }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Nhóm Zalo</label>
                                                <input type="text" class="form-control" name="zalo_group"
                                                    value="{{ old('zalo_group', $project->zalo_group) }}">
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        name="auto_email"
                                                        {{ old('auto_email', $project->auto_email) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Cho phép gửi Email cho khách
                                                        hàng</label>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="upload_File">
                                <div class="card shadow-none border mb-0 radius-15">
                                    <div class="card-body">


                                        <h5 class="mb-3">Quản lý hạng mục</h5>
                                        <div id="categories-wrapper">
                                            @forelse ($project->categories as $index => $category)
                                                <div class="row g-3 category-item mt-2">
                                                    <div class="col-2">
                                                        @if ($index == 0)
                                                            <label class="form-label">Thứ tự</label>
                                                        @endif
                                                        <input type="number"
                                                            name="categories[{{ $index }}][order]"
                                                            class="form-control"
                                                            value="{{ old('categories.' . $index . '.order', $category->order) }}"
                                                            required readonly>
                                                    </div>

                                                    <div class="col-3">
                                                        @if ($index == 0)
                                                            <label class="form-label">Tên Hạng Mục</label>
                                                        @endif
                                                        <input type="text"
                                                            name="categories[{{ $index }}][name]"
                                                            class="form-control"
                                                            value="{{ old('categories.' . $index . '.name', $category->name) }}"
                                                            required>
                                                    </div>

                                                    <div class="col-3">
                                                        @if ($index == 0)
                                                            <label class="form-label">Chịu trách nhiệm chính</label>
                                                        @endif
                                                        <select name="categories[{{ $index }}][manager_id]"
                                                            class="single-select2 form-select" required>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}"
                                                                    {{ $category->manager_id == $user->id ? 'selected' : '' }}>
                                                                    {{ $user->full_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-3">
                                                        @if ($index == 0)
                                                            <label class="form-label">Cách tính tiến độ</label>
                                                        @endif
                                                        <select name="categories[{{ $index }}][progress_calculate]"
                                                            class="form-select" required>
                                                            <option value="1"
                                                                {{ $category->progress_calculate == 1 ? 'selected' : '' }}>
                                                                Theo % nhân viên cập nhật</option>
                                                            <option value="2"
                                                                {{ $category->progress_calculate == 2 ? 'selected' : '' }}>
                                                                Theo tỷ lệ hoàn thành khối lượng công việc</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-1 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger remove-category"><i
                                                                class="bx bx-trash-alt"></i></button>
                                                    </div>
                                                </div>
                                            @empty

                                                <div class="row g-3 category-item">
                                                    <div class="col-2">
                                                        <label class="form-label">Thứ tự</label>
                                                        <input type="number" name="categories[0][order]"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="form-label">Tên Hạng Mục</label>
                                                        <input type="text" name="categories[0][name]"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="form-label">Chịu trách nhiệm chính</label>
                                                        <select name="categories[0][manager_id]"
                                                            class="single-select2 form-select" required>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}">
                                                                    {{ $user->full_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="form-label">Cách tính tiến độ</label>
                                                        <select name="categories[0][progress_calculate]"
                                                            class="form-select" required>
                                                            <option value="1">Theo % nhân viên cập nhật</option>
                                                            <option value="2">Theo tỷ lệ hoàn thành khối lượng công
                                                                việc</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-1 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger remove-category"><i
                                                                class="bx bx-trash-alt"></i></button>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>


                                        <div class="mt-3">
                                            <button type="button" id="add-category" class="btn btn-info">+ Thêm Hạng
                                                Mục</button>
                                        </div>



                                    </div>
                                </div>


                            </div>



                        </div>
                        <div class="row g-3 mb-4 text-center">
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Cập Nhật</button>
                            </div>
                        </div>
                    </form>
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
@endpush

@push('scripts')
    <script>
        let categoryIndex = {{ count($project->categories ?? []) }};

        $('#add-category').on('click', function() {
            const newOrder = $('#categories-wrapper .category-item').length + 1;

            let newItem = `
            <div class="row g-3 category-item mt-2">
                <div class="col-2">
                    <label class="form-label">Thứ tự</label>
                    <input type="number" name="categories[${categoryIndex}][order]" class="form-control" value="${newOrder}" readonly>
                </div>
                <div class="col-3">
                    <label class="form-label">Tên Hạng Mục</label>
                    <input type="text" name="categories[${categoryIndex}][name]" class="form-control" required>
                </div>
                <div class="col-3">
                    <label class="form-label">Chịu trách nhiệm chính</label>
                    <select name="categories[${categoryIndex}][manager_id]" class="single-select2 form-select" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label class="form-label">Cách tính tiến độ</label>
                    <select name="categories[${categoryIndex}][progress_calculate]" class="form-select" required>
                        <option value="1">Theo % nhân viên cập nhật</option>
                        <option value="2">Theo tỷ lệ hoàn thành khối lượng công việc</option>
                        <option value="3">Theo tỷ lệ hoàn thành đầu việc</option>
                        <option value="4">Theo tỷ trọng công việc con</option>
                    </select>
                </div>
                <div class="col-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-category"><i class="bx bx-trash-alt"></i></button>
                </div>
            </div>`;

            $('#categories-wrapper').append(newItem);
            categoryIndex++;

            $('.single-select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });

        $(document).on('click', '.remove-category', function() {
            $(this).closest('.category-item').remove();

            $('#categories-wrapper .category-item').each(function(index) {
                $(this).find('input[name*="[order]"]').val(index + 1);
            });
        });
    </script>

    <script>
        function formatCurrency(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            if (value) {
                input.value = Number(value).toLocaleString('en-US');
            } else {
                input.value = '';
            }
        }

        document.querySelectorAll('input[name="budget_min"], input[name="budget_max"]').forEach(input => {
            input.addEventListener('input', () => formatCurrency(input));
        });

        document.querySelector('form').addEventListener('submit', function() {
            document.querySelectorAll('input[name="budget_min"], input[name="budget_max"]').forEach(input => {
                input.value = input.value.replace(/,/g, '');
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.needs-validation');

            form.addEventListener('submit', function(event) {
                let isValid = true;
                const errors = {};

                const code = form.querySelector('[name="project_code"]');
                if (!code.value.trim()) errors.project_code = 'Vui lòng nhập Mã Dự Án.';

                const name = form.querySelector('[name="project_name"]');
                if (!name.value.trim()) errors.project_name = 'Vui lòng nhập Tên Dự Án.';

                const start = form.querySelector('[name="start_date"]');
                if (!start.value.trim()) errors.start_date = 'Vui lòng chọn Ngày Bắt Đầu.';

                const end = form.querySelector('[name="end_date"]');
                if (!end.value.trim()) errors.end_date = 'Vui lòng chọn Ngày Kết Thúc.';

                const customer = form.querySelector('[name="customer_id"]');
                if (!customer.value.trim()) errors.customer_id = 'Vui lòng chọn Khách Hàng.';

                const manager = form.querySelector('[name="manager_id"]');
                if (!manager.value.trim()) errors.manager_id = 'Vui lòng chọn Người Quản Lý.';

                const assignees = $(form).find('select[name="assignees[]"]').val();
                if (!assignees || assignees.length === 0)
                    errors.assignees = 'Phải chọn ít nhất 1 Người Thực Hiện.';

                const follow_id = $(form).find('select[name="follow_id[]"]').val();
                if (!follow_id || follow_id.length === 0)
                    errors.follow_id = 'Phải chọn ít nhất 1 Người Theo Dõi.';

                // --- Validate Tên Hạng Mục ---
                const categoryNames = form.querySelectorAll('input[name^="categories"][name$="[name]"]');
                categoryNames.forEach((catInput, idx) => {
                    if (!catInput.value.trim()) {
                        errors[`category_${idx}`] = 'Vui lòng nhập Tên Hạng Mục.';
                    }
                });

                // Reset trạng thái cũ
                form.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
                    el.classList.remove('is-invalid', 'is-valid');
                });
                form.querySelectorAll('.invalid-feedback, .valid-feedback').forEach(el => el.remove());

                // Danh sách field chính
                const allFields = [{
                        name: 'project_code',
                        el: code
                    },
                    {
                        name: 'project_name',
                        el: name
                    },
                    {
                        name: 'start_date',
                        el: start
                    },
                    {
                        name: 'end_date',
                        el: end
                    },
                    {
                        name: 'customer_id',
                        el: customer
                    },
                    {
                        name: 'manager_id',
                        el: manager
                    },
                    {
                        name: 'assignees',
                        el: form.querySelector('[name="assignees[]"]')
                    },
                    {
                        name: 'follow_id',
                        el: form.querySelector('[name="follow_id[]"]')
                    },
                ];

                // Field trong danh mục
                categoryNames.forEach((catInput, idx) => {
                    allFields.push({
                        name: `category_${idx}`,
                        el: catInput
                    });
                });

                // Hiển thị feedback
                allFields.forEach(f => {
                    const field = f.el;
                    if (!field) return;

                    const parent = field.parentNode;
                    const feedback = document.createElement('div');

                    if (errors[f.name]) {
                        field.classList.add('is-invalid');
                        feedback.classList.add('invalid-feedback');
                        feedback.textContent = errors[f.name];
                        parent.appendChild(feedback);
                        isValid = false;
                    } else {
                        field.classList.add('is-valid');
                        feedback.classList.add('valid-feedback');
                        feedback.textContent = 'Rất tốt!';
                        parent.appendChild(feedback);
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });

            // realtime validate
            form.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', () => {
                    if (field.value.trim()) {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                        if (!field.parentNode.querySelector('.valid-feedback')) {
                            const fb = document.createElement('div');
                            fb.classList.add('valid-feedback');
                            fb.textContent = 'Rất tốt!';
                            field.parentNode.appendChild(fb);
                        }
                    } else {
                        field.classList.remove('is-valid');
                        field.classList.add('is-invalid');
                    }
                });
            });
        });
    </script>

    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-valid {
            border-color: #198754 !important;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875em;
        }

        .valid-feedback {
            display: block;
            color: #198754;
            font-size: 0.875em;
        }
    </style>
@endpush

@push('scripts')
    <!-- CKEditor 5 Classic -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
                            options: [
                                { model: 'paragraph', title: 'Đoạn văn', class: 'ck-heading_paragraph' },
                                { model: 'heading1', view: 'h1', title: 'Tiêu đề 1', class: 'ck-heading_heading1' },
                                { model: 'heading2', view: 'h2', title: 'Tiêu đề 2', class: 'ck-heading_heading2' }
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
    </script>

    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
            border-radius: 10px;
        }
    </style>
@endpush
