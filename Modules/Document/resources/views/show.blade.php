@extends('core::layouts.app')

@section('title', 'Chi tiết văn bản')

@section('content')
    @use('Modules\Core\Enums\RoleEnum')
    @use('Modules\Core\Enums\PermissionEnum')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3">Văn bản</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('document.index') }}"><i class='bx bx-home-alt'></i></a>
                        </li>
                        <li class="breadcrumb-item active">Chi tiết văn bản</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 ">
                    Chi tiết văn bản
                </h5>

                <div class="d-flex gap-2 align-items-center">
                    @if ($document->approver)
                        <div class="d-flex align-items-center text-success fw-semibold">
                            <i class="bx bx-badge-check fs-4 me-1"></i>
                            <span>Văn bản đã được duyệt bởi {{ $document->approver->full_name }}</span>
                        </div>
                    @else
                        @can(PermissionEnum::DAY_OFF_APPROVE)
                            <form action="{{ route('document.approve', $document->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc chắn muốn duyệt văn bản này?')">
                                @csrf
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bx bx-check-circle me-1"></i> Duyệt văn bản
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label ">Loại văn bản</label>
                        <input class="form-control" readonly
                            value="@switch($document->type_id)
                            @case(1) Văn bản đi @break
                            @case(2) Văn bản đến @break
                            @case(3) Văn bản nội bộ / Thông báo @break
                        @endswitch">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label ">Tiêu đề</label>
                        <input class="form-control" value="{{ $document->title }}" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label ">Kho lưu trữ</label>
                        <input class="form-control" value="{{ optional($document->structure('storage'))->name }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label ">Nhóm nội dung</label>
                        <input class="form-control" value="{{ optional($document->structure('content_group'))->name }}"
                            readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label ">Thư mục</label>
                        <input class="form-control" value="{{ optional($document->structure('folder'))->name }}" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label ">Sổ văn bản</label>
                        <input class="form-control" value="{{ optional($document->structure('book'))->name }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label ">Mã số văn bản</label>
                        <input class="form-control" value="{{ $document->code }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label ">Nhãn</label>
                        <input class="form-control" value="{{ $document->tag ?? '' }}" readonly>
                    </div>
                </div>

                @if ($document->type_id == 1)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label ">Gửi từ</label>
                            <input class="form-control" value="{{ optional($document->fromEmployee)->full_name }}"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label ">Người theo dõi</label>
                            <textarea class="form-control" rows="3" readonly>
@foreach ($document->followers ?? [] as $uid)
{{ optional($users->find($uid))->full_name }}
@endforeach
                        </textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label ">Gửi đến (nội bộ)</label>
                            <textarea class="form-control" rows="3" readonly>
@foreach ($document->to_internals ?? [] as $uid)
{{ optional($users->find($uid))->full_name }}
@endforeach
                        </textarea>
                        </div>
                    </div>

                    <h5 class=" mt-3">Gửi đến (bên ngoài)</h5>
                    @foreach ($document->recipients ?? [] as $r)
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input class="form-control" value="{{ $r['company'] ?? '' }}" readonly
                                    placeholder="Công ty">
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" value="{{ $r['department'] ?? '' }}" readonly
                                    placeholder="Phòng ban">
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" value="{{ $r['email'] ?? '' }}" readonly placeholder="Email">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <textarea class="form-control" readonly placeholder="Ghi chú"> {{ $r['note'] ?? '' }} </textarea>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if (in_array($document->type_id, [2, 3]))
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label ">AA</label>
                            <input class="form-control" value="{{ $document->aa ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label ">Loại hợp đồng</label>
                            <input class="form-control" value="{{ $document->contract_type ?? '' }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label ">Gửi từ</label>
                            <input class="form-control" value="{{ $document->sender['company'] }}" readonly
                                placeholder="">

                        </div>
                        <div class="col-md-6">
                            <label class="form-label ">Người nhận</label>
                            <select class="form-select" multiple disabled>
                                @foreach ($document->receivers ?? [] as $cid)
                                    <option selected>{{ optional($users->find($cid))->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                @endif

                <div class="mb-3">
                    <label class="form-label ">Nội dung</label>
                    <textarea id="content-editor" class="form-control" rows="6">
{!! $document->content !!}
</textarea>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        @if ($document && $document->files && $document->files->count() > 0)
                            <strong class="">File đính kèm:</strong>
                            <div class="d-flex flex-wrap gap-2">

                                @foreach ($document->files as $file)
                                    <div class="old-file-item position-relative border rounded p-2"
                                        id="file-{{ $file->id }}"
                                        style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">

                                        @if (in_array($file->extension, ['png', 'jpg', 'jpeg', 'gif', 'webp']))
                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $file->file_path) }}" alt="file"
                                                    style="max-width: 100%; max-height: 100%; cursor: pointer;">
                                            </a>
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
                </div>

                @if ($document->bonus)
                    <div class="mb-3">
                        <label class="form-label ">Ghi chú thêm</label>
                        <textarea class="form-control" rows="3" readonly>{{ $document->bonus }}</textarea>
                    </div>
                @endif


                <div class="row mb-4 mt-3">
                    <div class="col-md-4">
                        <label class="form-label ">Ngày ban hành</label>
                        <input class="form-control" value="{{ optional($document->issue_date)->format('d/m/Y') }}"
                            readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label ">Ngày hiệu lực</label>
                        <input class="form-control" value="{{ optional($document->effective_date)->format('d/m/Y') }}"
                            readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label ">Ngày hết hạn</label>
                        <input class="form-control" value="{{ optional($document->expiration_date)->format('d/m/Y') }}"
                            readonly>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('document.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>



            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <!-- CKEditor 5 Classic -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editorElement = document.querySelector('#content-editor');

            if (editorElement) {
                ClassicEditor
                    .create(editorElement, {
                        readOnly: true,
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
