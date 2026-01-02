@use('Modules\Category\Enums\CategoryStatusEnum')
@props(['action', 'method', 'category' => null])

<form action="{{ $action }}" method="post" enctype="multipart/form-data"
    id="category-create-form">
    @csrf
    @if ($category)
        @method('put')
    @endif
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-none border mb-0 radius-15">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label required">Tên
                                danh mục</label>
                            <input name="name" type="text"
                                value="{{ $category ? $category->name : old('name') }}"
                                class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Trạng
                                Thái</label>
                            <select name="status" class="form-select">
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
                    <p>
                        <strong class="text-danger">** Chọn nhiều file cùng lúc
                            trong 1 lần.</strong>
                    </p>

                    <div class="row g-3">
                        <input name="files[]" id="image-uploadify"
                            type="file"
                            accept=".xlsx,.xls,image/*,.doc,audio/*,.docx,video/*,.ppt,.pptx,.txt,.pdf"
                            multiple>
                        @if ($category && $category->files && $category->files->count() > 0)
                            <strong class="text-danger">* File đã
                                thêm trước đây:</strong>
                            @foreach ($category->files as $file)
                                <div class="imageuploadify-container"
                                    id="file-{{ $file->id }}"
                                    style="margin-left: 6px;">
                                    <a type="button"
                                        class="btn btn-danger bx bx-x delete-file"
                                        data-id="{{ $file->id }}"></a>
                                    <div class="imageuploadify-details"
                                        style="opacity: 0;">
                                    </div>
                                    @if (
                                        $file->extension == 'png' ||
                                            $file->extension == 'jpg' ||
                                            $file->extension == 'jpeg')
                                        <img
                                            src="{{ asset('storage/' . $file->file_path) }}">
                                    @else
                                        <div
                                            class="imageuploadify-details-preview">
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

    <div class="row g-3 mb-4 text-center">
        <div class="col-12">
            <button class="btn btn-primary" type="submit" id="submit-btn">Lưu
                Dữ
                Liệu</button>
        </div>
    </div>
</form>

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
