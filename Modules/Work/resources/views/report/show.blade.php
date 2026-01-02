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
                        <li class="breadcrumb-item active" aria-current="page">Báo Cáo Công Việc</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Báo Cáo Công Việc</h5>
                <form action="  " method="POST" enctype="multipart/form-data" class="row g-3 needs-validation"
                    novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Công Việc</label>
                        <input type="text" class="form-control" id="name" value="{{ $report->work->work_name }}"
                            disabled>
                    </div>

                    <div class="mb-3">
                        <label for="project_id" class="form-label">Thuộc Dự Án</label>
                        <select class="form-select" id="project_id" disabled>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ $report->project_id == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="report_user" class="form-label">Người Báo Cáo</label>
                        <input type="text" id="report_user" class="form-control"
                            value="{{ $report->user->employee->full_name ?? 'Không rõ' }}" readonly>
                    </div>


                    <div class="mb-3">
                        <label for="report_date" class="form-label">Ngày Báo Cáo</label>
                        <input type="datetime-local" name="report_date" id="report_date" class="form-control"
                            value="{{ $report->report_date ? $report->report_date->format('Y-m-d\TH:i') : '' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <span class="form-control d-flex align-items-center" disabled>
                            {{ \Modules\Work\Models\WorkReport::STATUS_LABELS[$report->receiver_status] ?? 'Không xác định' }}
                        </span>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Nội Dung Báo Cáo</label>
                        <textarea name="content" id="content" class="form-control editor" rows="5">
        {{ old('content', $report->content ?? '') }}
    </textarea>
                    </div>

                    <div class="col-12">
                        @if ($report->files && $report->files->count() > 0)
                            <strong class="text-danger">File đính kèm:</strong>
                            <div class="d-flex flex-wrap gap-2">

                                @foreach ($report->files as $file)
                                    <div class="old-file-item position-relative border rounded p-2"
                                        id="file-{{ $file->id }}"
                                        style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">

                                        @if (in_array($file->extension, ['png', 'jpg', 'jpeg', 'gif', 'webp']))
                                            <img src="{{ asset('storage/' . $file->file_path) }}" alt="file"
                                                style="max-width: 100%; max-height: 100%;">
                                        @else
                                            <div class="text-center">
                                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                    class="text-decoration-none">
                                                    <strong>{{ strtoupper($file->extension) }}</strong>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                            </div>
                        @endif
                    </div>


                    <div class="row g-3 mb-4 text-center">
                        <div class="col-12">

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
@endpush

@push('scripts')
    <!-- CKEditor 5 Classic -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editorElement = document.querySelector('#content');

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
    </script>

    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
            border-radius: 10px;
        }
    </style>
@endpush
