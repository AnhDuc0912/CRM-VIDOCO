    @extends('core::layouts.app')

    @section('title', 'Thêm Công Việc')

    @section('content')
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Công Việc</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm Công Việc</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Thông Tin Công Việc</h5>
                    <form action="{{ route('work.store') }}" method="POST" enctype="multipart/form-data"
                        class="row g-3 needs-validation" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">Tên Công Việc</label>
                            <input type="text" class="form-control" name="work_name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Thuộc Dự Án</label>
                            <select class="single-select2" id="project-select" name="project_id" required
                                {{ isset($selectedProject) ? 'disabled' : '' }}>
                                <option value="">Việc không thuộc dự án</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ isset($selectedProject) && $selectedProject->id == $project->id ? 'selected' : '' }}>
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if (isset($selectedProject))
                                <input type="hidden" name="project_id" value="{{ $selectedProject->id }}">
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hạng mục</label>
                            @if (isset($selectedProject))
                                <select class="single-select2" name="group_id" required>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}"
                                            {{ isset($selectedProject) && $selectedProject->group_id == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <select class="single-select2" id="group-select" name="group_id" required>
                                    <option value="">Chọn hạng mục</option>
                                </select>

                            @endif

                        </div>



                        @php
                            $defaultStart = \Carbon\Carbon::now()->format('Y-m-d');

                            $defaultEnd = \Carbon\Carbon::now()->format('Y-m-d');
                        @endphp

                        <div class="col-md-3">
                            <label class="form-label">Ngày Bắt Đầu</label>
                            <input type="date" class="form-control" name="start_date" value="{{ $defaultStart }}"
                                required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Ngày Kết Thúc</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $defaultEnd }}"
                                required>
                        </div>


                        <div class="col-md-3">
                            <label class="form-label">Trạng Thái</label>
                            <select class="form-select" name="status" required>
                                <option selected value="1">Đang chờ</option>
                                <option value="2">Đang thực hiện</option>
                                <option value="3">Hoàn thành</option>
                                <option value="4">Chờ nghiệm thu</option>
                                <option value="5">Đã nghiệm thu</option>
                                <option value="6">Đã bàn giao</option>
                                <option value="0">Hủy</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Ưu Tiên</label>
                            <select class="form-select" name="priority" required>
                                <option value="1">Bình thường</option>
                                <option value="2">Quan trọng</option>
                                <option value="3">Ưu Tiên</option>
                                <option value="4">Khẩn cấp</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Người Thực Hiện</label>
                            <select class="single-select2" name="to_user_id[]" multiple required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Người Theo Dõi</label>
                            <select class="single-select2" name="follow_id[]" multiple required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mô tả công việc</label>
                            <textarea id="mytextarea" name="description"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><b> Danh Sách Công Việc Con </b> </label>
                            <div id="children-wrapper"></div>
                            <button type="button" class="btn btn-sm btn-primary mt-2" id="add-child">+ Thêm Công Việc
                                Con</button>
                        </div>

                        <template id="child-template">
                            <div class="child-row mb-3 p-3 border rounded bg-light position-relative">
                                <h6 class="fw-bold mb-3 text-primary child-label">Công việc con #__NUMBER__</h6>
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label">Tên CV</label>
                                        <input type="text" class="form-control form-control-sm"
                                            name="children[__INDEX__][work_name]" placeholder="Tên CV" required>
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
                                        <select class="form-select form-select-sm" name="children[__INDEX__][status]"
                                            required>
                                            <option selected value="1">Đang chờ</option>
                                            <option value="2">Đang thực hiện</option>
                                            <option value="3">Chờ nghiệm thu</option>
                                            <option value="4">Đã nghiệm thu</option>
                                            <option value="5">Hoàn thành</option>
                                            <option value="6">Đã bàn giao</option>
                                            <option value="0">Hủy</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Ưu tiên</label>
                                        <select class="form-select form-select-sm" name="children[__INDEX__][priority]"
                                            required>
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
                                    <div class="col-md-2">
                                        <label class="form-label">Người theo dõi</label>
                                        <select class="form-control form-control-sm single-select2"
                                            name="children[__INDEX__][follow_id][]" multiple required>
                                            @foreach ($users as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Mô tả</label>
                                        <input type="text" class="form-control form-control-sm"
                                            name="children[__INDEX__][description]" placeholder="Mô tả">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-danger remove-child">X</button>
                                    </div>
                                </div>
                            </div>
                        </template>



                        <div class="row g-3 mb-4 text-center">
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Lưu Dữ Liệu</button>
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
                let childIndex = 0;

                $('#add-child').click(function() {
                    let tpl = $('#child-template').html()
                        .replace(/__INDEX__/g, childIndex)
                        .replace(/__NUMBER__/g, childIndex + 1);
                    $('#children-wrapper').append(tpl);
                    childIndex++;

                    // re-init select2 cho select mới
                    $('.single-select2').select2({
                        theme: 'bootstrap4',
                        width: '100%'
                    });
                });

                $(document).on('click', '.remove-child', function() {
                    $(this).closest('.child-row').remove();
                    $('.child-row').each(function(i) {
                        $(this).find('.child-label').text('Công việc con #' + (i + 1));
                    });
                });
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
