@extends('core::layouts.app')

@section('title', 'Chỉnh sửa Cấp Bậc')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cấp Bậc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa Cấp Bậc</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Cập Nhật Thông Tin Cấp Bậc</h5>
                <form action="{{ route('level.update', $level->id) }}" method="POST" class="row g-3 needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label">Tên Cấp Bậc</label>
                        <input type="text" class="form-control" name="name" value="{{ $level->name }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Mô tả</label>
                        <textarea id="mytextarea" name="description">{{ $level->description }}</textarea>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $('#positions').select2({
            tags: false,
            placeholder: "Chọn vị trí"
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

