@extends('core::layouts.app')

@section('title', 'Cập Nhật Công Việc')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Công Việc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cập Nhật Công Việc</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Thông Tin Công Việc</h5>
                <form action="{{ route('work.update', $work->id) }}" method="POST" enctype="multipart/form-data"
                    class="row g-3 needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label">Tên Công Việc</label>
                        <input type="text" class="form-control" name="work_name"
                            value="{{ old('work_name', $work->work_name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Thuộc Dự Án</label>
                        <select class="single-select2" name="project_id" id="project-select" required>
                            <option value="">Việc không thuộc dự án</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ $work->project_id == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Hạng mục</label>
                        <select class="single-select2" name="group_id" id="group-select" required>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" {{ $work->group_id == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Ngày Bắt Đầu</label>
                        <input type="date" class="form-control" name="start_date"
                            value="{{ $work->start_date->format('Y-m-d') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Ngày Kết Thúc</label>
                        <input type="date" class="form-control" name="end_date"
                            value="{{ $work->end_date->format('Y-m-d') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Trạng Thái</label>
                        <select class="form-select" name="status" required>
                            <option value="1" {{ $work->status == 1 ? 'selected' : '' }}>Đang chờ</option>
                            <option value="2" {{ $work->status == 2 ? 'selected' : '' }}>Đang thực hiện</option>
                            <option value="3" {{ $work->status == 3 ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="4" {{ $work->status == 4 ? 'selected' : '' }}>Chờ nghiệm thu</option>
                            <option value="5" {{ $work->status == 5 ? 'selected' : '' }}>Đã nghiệm thu</option>
                            <option value="6" {{ $work->status == 6 ? 'selected' : '' }}>Đã bàn giao</option>
                            <option value="0" {{ $work->status == 0 ? 'selected' : '' }}>Hủy</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Ưu Tiên</label>
                        <select class="form-select" name="priority" required>
                            <option value="1" {{ $work->priority == 1 ? 'selected' : '' }}>Bình thường</option>
                            <option value="2" {{ $work->priority == 2 ? 'selected' : '' }}>Quan trọng</option>
                            <option value="3" {{ $work->priority == 3 ? 'selected' : '' }}>Khẩn cấp</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Người Thực Hiện</label>
                        <select class="single-select2" name="to_user_id[]" multiple required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ in_array($user->id, json_decode($work->to_user) ?? []) ? 'selected' : '' }}>
                                    {{ $user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Người Theo Dõi</label>
                        <select class="single-select2" name="follow_id[]" multiple required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ in_array($user->id, json_decode($work->follow_id) ?? []) ? 'selected' : '' }}>
                                    {{ $user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Mô tả công việc</label>
                        <textarea id="mytextarea" name="description">{{ old('description', $work->description) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Danh sách Công việc con</label>
                        <div id="children-wrapper">
                            @foreach ($work->children as $index => $child)
                                <div class="child-row mb-3 p-3 border rounded bg-light position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold text-primary mb-0">Công việc con #{{ $index + 1 }}</h6>
                                        <button type="button" class="btn btn-sm btn-danger remove-child">X</button>
                                    </div>

                                    <div class="row g-2">
                                        <input type="hidden" name="children[{{ $index }}][id]"
                                            value="{{ $child->id }}">

                                        <div class="col-md-2">
                                            <label class="form-label">Tên CV</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="children[{{ $index }}][work_name]"
                                                value="{{ $child->work_name }}" required>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Bắt đầu</label>
                                            <input type="date" class="form-control form-control-sm"
                                                name="children[{{ $index }}][start_date]"
                                                value="{{ $child->start_date->format('Y-m-d') }}" required>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Kết thúc</label>
                                            <input type="date" class="form-control form-control-sm"
                                                name="children[{{ $index }}][end_date]"
                                                value="{{ $child->end_date->format('Y-m-d') }}" required>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Trạng thái</label>
                                            <select class="form-select form-select-sm"
                                                name="children[{{ $index }}][status]" required>
                                                <option value="1" {{ $child->status == 1 ? 'selected' : '' }}>Đang
                                                    chờ</option>
                                                <option value="2" {{ $child->status == 2 ? 'selected' : '' }}>Đang
                                                    thực hiện</option>
                                                <option value="3" {{ $child->status == 3 ? 'selected' : '' }}>Hoàn
                                                    thành</option>
                                                <option value="4" {{ $child->status == 4 ? 'selected' : '' }}>Bàn
                                                    giao</option>
                                                <option value="5" {{ $child->status == 5 ? 'selected' : '' }}>Tạm
                                                    dừng</option>
                                                <option value="6" {{ $child->status == 6 ? 'selected' : '' }}>Đã hủy
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Ưu tiên</label>
                                            <select class="form-select form-select-sm"
                                                name="children[{{ $index }}][priority]" required>
                                                <option value="1" {{ $child->priority == 1 ? 'selected' : '' }}>Bình
                                                    thường</option>
                                                <option value="2" {{ $child->priority == 2 ? 'selected' : '' }}>Quan
                                                    trọng</option>
                                                <option value="3" {{ $child->priority == 3 ? 'selected' : '' }}>Khẩn
                                                    cấp</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Người thực hiện</label>
                                            <select class="form-control form-control-sm single-select2"
                                                name="children[{{ $index }}][to_user_id][]" multiple required>
                                                @foreach ($users as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        {{ in_array($employee->id, json_decode($child->to_user) ?? []) ? 'selected' : '' }}>
                                                        {{ $employee->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Người theo dõi</label>
                                            <select class="form-control form-control-sm single-select2"
                                                name="children[{{ $index }}][follow_id][]" multiple required>
                                                @foreach ($users as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        {{ in_array($employee->id, json_decode($child->follow_id) ?? []) ? 'selected' : '' }}>
                                                        {{ $employee->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Mô tả</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="children[{{ $index }}][description]"
                                                value="{{ $child->description }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-info mt-2" id="add-child">+ Thêm Công Việc
                            Con</button>
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
@endsection

@push('styles')
    <link href="{{ asset('modules/project/js/fancy-file-uploader/fancy_fileupload.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ asset('modules/project/js/fancy-file-uploader/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('modules/project/js/fancy-file-uploader/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('modules/project/js/fancy-file-uploader/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('modules/project/js/fancy-file-uploader/jquery.fancy-fileupload.js') }}"></script>

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
        $(document).ready(function() {
            $('#project-select').on('change', function() {
                var projectId = $(this).val();
                var groupSelect = $('#group-select');

                groupSelect.empty().append('<option value="">Đang tải...</option>');

                if (projectId) {
                    $.ajax({
                        url: '/work/get-groups/' + projectId,
                        type: 'GET',
                        success: function(data) {
                            groupSelect.empty().append(
                                '<option value="">Chọn hạng mục</option>');
                            $.each(data, function(key, group) {
                                groupSelect.append('<option value="' + group.id + '">' +
                                    group.name + '</option>');
                            });
                            groupSelect.trigger('change.select2');
                        },
                        error: function() {
                            groupSelect.empty().append(
                                '<option value="">Lỗi tải dữ liệu</option>');
                        }
                    });

                } else {
                    groupSelect.empty().append('<option value="">Chọn hạng mục</option>');
                }
            });
        });
        $(document).ready(function() {
            let childIndex = {{ $work->children->count() }};

            function refreshChildLabels() {
                $('#children-wrapper .child-row').each(function(i) {
                    $(this).find('h6').text('Công việc con #' + (i + 1));
                });
            }

            $('#add-child').click(function() {
                let tpl = $('#child-template').html().replace(/__INDEX__/g, childIndex);
                $('#children-wrapper').append(tpl);
                childIndex++;
                $('.single-select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
                refreshChildLabels();
            });

            $(document).on('click', '.remove-child', function() {
                $(this).closest('.child-row').remove();
                refreshChildLabels();
            });

            refreshChildLabels();
        });
    </script>

    <script type="text/template" id="child-template">
    <div class="child-row mb-3 p-3 border rounded bg-light position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-primary mb-0">Công việc con mới</h6>
            <button type="button" class="btn btn-sm btn-danger remove-child">X</button>
        </div>

        <div class="row g-2">
            <div class="col-md-2">
                <label class="form-label">Tên CV</label>
                <input type="text" class="form-control form-control-sm"
                    name="children[__INDEX__][work_name]" placeholder="Tên công việc" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Bắt đầu</label>
                <input type="date" class="form-control form-control-sm"
                    name="children[__INDEX__][start_date]" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Kết thúc</label>
                <input type="date" class="form-control form-control-sm"
                    name="children[__INDEX__][end_date]" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select class="form-select form-select-sm"
                    name="children[__INDEX__][status]" required>
                    <option value="1">Đang chờ</option>
                    <option value="2">Đang thực hiện</option>
                    <option value="3">Hoàn thành</option>
                    <option value="4">Bàn giao</option>
                    <option value="5">Tạm dừng</option>
                    <option value="6">Đã hủy</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Ưu tiên</label>
                <select class="form-select form-select-sm"
                    name="children[__INDEX__][priority]" required>
                    <option value="1">Bình thường</option>
                    <option value="2">Quan trọng</option>
                    <option value="3">Khẩn cấp</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Người thực hiện</label>
                <select class="form-control form-control-sm single-select2"
                    name="children[__INDEX__][to_user_id][]" multiple required>
                    @foreach ($users as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Người theo dõi</label>
                <select class="form-control form-control-sm single-select2"
                    name="children[__INDEX__][follow_id][]" multiple required>
                    @foreach ($users as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Mô tả</label>
                <input type="text" class="form-control form-control-sm"
                    name="children[__INDEX__][description]" placeholder="Mô tả công việc">
            </div>
        </div>
    </div>
</script>
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
