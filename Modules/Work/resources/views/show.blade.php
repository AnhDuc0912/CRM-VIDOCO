    @extends('core::layouts.app')

    @section('title', 'Chi Tiết Công Việc')

    @section('content')
        <div class="page-content">
            <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Công Việc</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $work->work_name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Thông Tin Công Việc</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên Công Việc</label>
                            <input type="text" class="form-control" value="{{ $work->work_name }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Thuộc Dự Án</label>
                            <select class="single-select2" disabled>
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
                            <select class="single-select2" disabled>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ $work->group_id == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Ngày Bắt Đầu</label>
                            <input type="date" class="form-control" value="{{ $work->start_date->format('Y-m-d') }}"
                                readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Ngày Kết Thúc</label>
                            <input type="date" class="form-control" value="{{ $work->end_date->format('Y-m-d') }}"
                                readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Trạng Thái</label>
                            <select class="form-select" disabled>
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
                            <select class="form-select" disabled>
                                <option value="1" {{ $work->priority == 1 ? 'selected' : '' }}>Bình thường</option>
                                <option value="2" {{ $work->priority == 2 ? 'selected' : '' }}>Quan trọng</option>
                                <option value="3" {{ $work->priority == 3 ? 'selected' : '' }}>Khẩn cấp</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Người Thực Hiện</label>
                            <select class="single-select2" multiple disabled>
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
                            <select class="single-select2" multiple disabled>
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
                            <div class="ckeditor-content">
                                {!! $work->description !!}
                            </div>

                        </div>

                        <div class="col-12">
                            <label class="form-label"><b>Danh Sách Công Việc Con</b></label>
                            <div id="children-wrapper">
                                @foreach ($work->children as $child)
                                    <div class="child-row mb-3 p-3 border rounded bg-light">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-2">
                                                <input type="text" class="form-control form-control-sm"
                                                    value="{{ $child->work_name }}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="date" class="form-control form-control-sm"
                                                    value="{{ $child->start_date->format('Y-m-d') }}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="date" class="form-control form-control-sm"
                                                    value="{{ $child->end_date->format('Y-m-d') }}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-select form-select-sm" disabled>
                                                    <option value="1" {{ $child->status == 1 ? 'selected' : '' }}>Đang
                                                        chờ
                                                    </option>
                                                    <option value="2" {{ $child->status == 2 ? 'selected' : '' }}>Đang
                                                        thực hiện</option>
                                                    <option value="3" {{ $child->status == 3 ? 'selected' : '' }}>Hoàn
                                                        thành</option>
                                                    <option value="4" {{ $child->status == 4 ? 'selected' : '' }}>Bàn
                                                        giao
                                                    </option>
                                                    <option value="5" {{ $child->status == 5 ? 'selected' : '' }}>Tạm
                                                        dừng
                                                    </option>
                                                    <option value="6" {{ $child->status == 6 ? 'selected' : '' }}>Đã
                                                        hủy
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-select form-select-sm" disabled>
                                                    <option value="1" {{ $child->priority == 1 ? 'selected' : '' }}>
                                                        Bình
                                                        thường</option>
                                                    <option value="2" {{ $child->priority == 2 ? 'selected' : '' }}>
                                                        Quan
                                                        trọng</option>
                                                    <option value="3" {{ $child->priority == 3 ? 'selected' : '' }}>
                                                        Khẩn
                                                        cấp</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control form-control-sm single-select2" multiple
                                                    disabled>
                                                    @foreach ($users as $employee)
                                                        <option value="{{ $employee->id }}"
                                                            {{ in_array($employee->id, json_decode($child->to_user) ?? []) ? 'selected' : '' }}>
                                                            {{ $employee->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control form-control-sm single-select2" multiple
                                                    disabled>
                                                    @foreach ($users as $employee)
                                                        <option value="{{ $employee->id }}"
                                                            {{ in_array($employee->id, json_decode($child->follow_id) ?? []) ? 'selected' : '' }}>
                                                            {{ $employee->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    value="{{ $child->description }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row g-3 mb-4 text-center">
                            <div class="col-12">
                                <a href="{{ route('work.edit', $work->id) }}" class="btn btn-secondary">
                                    <i class="bx bx-edit"></i> Sửa Công Việc
                                </a>
                            </div>
                        </div>
                    </div>

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
                    let tpl = $('#child-template').html().replace(/__INDEX__/g, childIndex);
                    $('#children-wrapper').append(tpl);
                    childIndex++;

                    $('.single-select2').select2({
                        theme: 'bootstrap4',
                        width: '100%'
                    });
                });

                $(document).on('click', '.remove-child', function() {
                    $(this).closest('.child-row').remove();
                });
            });
        </script>
    @endpush

    @push('scripts')
        <style>
            .ckeditor-content {
                border: 1px solid #ddd;
                padding: 12px;
                border-radius: 10px;
                background: #fff;
                min-height: 200px;
            }
        </style>
    @endpush
