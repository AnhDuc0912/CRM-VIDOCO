@extends('core::layouts.app')

@section('title', 'Tạo Báo Cáo Công Việc')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Công Việc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tạo Báo Cáo Mới</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Báo Cáo Công Việc</h5>
                <form action="{{ route('work.report.store', $work->id) }}" method="POST" enctype="multipart/form-data"
                    class="row g-3 needs-validation" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Công Việc</label>
                        <input type="text" class="form-control" id="name" value="{{ $work->work_name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="project_id" class="form-label">Thuộc Dự Án</label>
                        <select class="form-select" id="project_id" disabled>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ $work->project_id == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="report_date" class="form-label">Ngày Báo Cáo</label>
                        <input type="datetime-local" name="report_date" id="report_date" class="form-control"
                            value="{{ now()->format('Y-m-d\TH:i') }}" readonly>


                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nội Dung Báo Cáo</label>
                        <textarea name="content" id="content" class="form-control editor" rows="5">{{ old('content') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">File Đính Kèm</label>
                        <input type="file" class="filepond" name="files[]" multiple data-max-files="10">
                    </div>

                    <div class="row g-3 mb-4 text-center">
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Tạo Báo Cáo Mới</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
        rel="stylesheet">
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

