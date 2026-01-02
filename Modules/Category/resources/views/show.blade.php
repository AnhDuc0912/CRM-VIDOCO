@extends('core::layouts.app')
@use('Modules\Category\Enums\CategoryStatusEnum')

@section('title', 'Cập nhật danh mục')

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Quản lý Danh Mục Dịch Vụ</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi Tiết Danh Mục Dịch Vụ</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card radius-15">
        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-12 col-lg-6">
                    <div class="card shadow-none border mb-0 radius-15">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label required">Tên
                                        danh mục</label>
                                    <input name="name" type="text" readonly
                                        value="{{ $category ? $category->name : old('name') }}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label required">Trạng
                                        Thái</label>
                                    <select name="status" class="form-select" disabled>
                                        @foreach (CategoryStatusEnum::getValues() as $status)
                                            <option value="{{ $status }}"
                                                {{ $category && $category->status == $status ? 'selected' : '' }}>
                                                {{ CategoryStatusEnum::getLabel($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card shadow-none border mb-0 radius-15">
                        <div class="card-body">
                            <h5 class="mb-2">Tài liệu USB Sản phẩm</h5>
                            <p class="mb-1">
                                <strong class="text-danger">* Lưu
                                    ý:</strong>
                                Phải hiểu rõ trước khi kinh doanh
                            </p>

                            <div class="row g-3">
                                @if ($category && $category->files && $category->files->count() > 0)
                                    <strong class="text-danger">* File đã
                                        thêm trước đây:</strong>
                                    @foreach ($category->files as $file)
                                        <div class="imageuploadify-container" id="file-{{ $file->id }}"
                                            style="margin-left: 6px;">
                                            <div class="imageuploadify-details" style="opacity: 0;">
                                            </div>
                                            @if ($file->extension == 'png' || $file->extension == 'jpg' || $file->extension == 'jpeg')
                                                <img src="{{ asset('storage/' . $file->file_path) }}">
                                            @else
                                                <div class="imageuploadify-details-preview">
                                                    <span>{{ $file->extension }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @push('styles')
                <style>
                    .imageuploadify-container {
                        width: 100px;
                        height: 100px;
                        position: relative;
                        overflow: hidden;
                        margin-bottom: 1em;
                        float: left;
                        border-radius: 12px;
                        box-shadow: 0 0 4px 0 #888
                    }

                    .imageuploadify-container a.btn-danger {
                        position: absolute;
                        top: 3px;
                        right: 3px;
                        width: 20px;
                        height: 20px;
                        border-radius: 15px;
                        font-size: 10px;
                        line-height: 1.42;
                        padding: 2px 0;
                        text-align: center;
                        z-index: 3
                    }

                    .imageuploadify-container img {
                        height: 100px;
                        left: 50%;
                        position: absolute;
                        top: 50%;
                        transform: translate(-50%, -50%);
                        width: auto
                    }

                    .imageuploadify-container .imageuploadify-details-preview {
                        position: absolute;
                        top: 0;
                        width: 90%;
                        height: 100%;
                        background: rgba(255, 255, 255, 0.5);
                        z-index: 2;
                        transition: opacity 0.3s ease;

                        /* 👇 Thêm các thuộc tính canh giữa */
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        text-align: center;

                        /* Giữ lại nếu bạn muốn xử lý tràn chữ */
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .imageuploadify-container .imageuploadify-details-preview:hover {}

                    .imageuploadify-container .imageuploadify-details span {
                        display: block
                    }
                </style>
            @endpush
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include Category Validation -->
    <script src="{{ asset('modules/category/js/category-validation.js') }}"></script>

    <script>
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this)
                .hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this)
                .hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>

    <script>
        $('#fancy-file-upload').FancyFileUpload({
            params: {
                action: 'fileuploader'
            },
            maxfilesize: 1000000
        });
    </script>
@endpush
