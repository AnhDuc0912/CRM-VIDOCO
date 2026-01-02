@extends('core::layouts.app')

@section('title', 'Tạo văn bản')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Văn Bản</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm mới văn bản</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="step-1">
                    <div class="list-group">
                        <label class="list-group-item d-flex gap-3 type-option">
                            <input type="radio" name="type" value="1" class="form-check-input">
                            Văn bản đi
                        </label>
                        <label class="list-group-item d-flex gap-3 type-option">
                            <input type="radio" name="type" value="2" class="form-check-input">
                            Văn bản đến
                        </label>
                        <label class="list-group-item d-flex gap-3 type-option">
                            <input type="radio" name="type" value="3" class="form-check-input">
                            Văn bản nội bộ / Thông báo
                        </label>
                    </div>

                    <button id="btn-step-1" class="btn btn-success w-100 mt-4" disabled>Tiếp tục</button>
                </div>

                <div id="step-2" style="display:none">
                    <form method="POST" action="{{ route('document.store') }}" enctype="multipart/form-data">
                        @csrf

                        <h5 class="fw-bold mb-3">Thông tin văn bản</h5>

                        <div class="mb-3">
                            <label class="form-label">Tiêu đề *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="row mb-3 gx-3">
                            <div class="col-md-4">
                                <label class="form-label">Loại văn bản</label>
                                <input type="text" id="document_type_text" class="form-control" readonly>

                                <input type="hidden" name="type_id" id="type_id">
                            </div>


                            <div class="col-md-4">
                                <label class="form-label">Kho lưu trữ</label>

                                <div class="structure-picker" data-type="storage">
                                    <input type="text" class="form-control structure-input"
                                        placeholder="Chọn kho lưu trữ" readonly>

                                    <input type="hidden" name="storage_id" id="storage_id">

                                    <div class="structure-panel d-none">
                                        <div class="structure-path"></div>
                                        <div class="structure-list list-group"></div>
                                    </div>
                                </div>
                            </div>




                            <div class="col-md-4">
                                <label class="form-label">Nhóm nội dung</label>

                                <div class="structure-picker" data-type="content_group">
                                    <input type="text" class="form-control structure-input"
                                        placeholder="Chọn nhóm nội dung" readonly>

                                    <input type="hidden" name="content_group_id" id="content_group_id">

                                    <div class="structure-panel d-none">
                                        <div class="structure-path"></div>
                                        <div class="structure-list list-group"></div>
                                    </div>
                                </div>
                            </div>



                        </div>

                        <div class="row mb-3 gx-3">
                            <div class="col-md-6">
                                <label class="form-label">Thư mục</label>

                                <div class="structure-picker" data-type="folder">
                                    <input type="text" class="form-control structure-input" placeholder="Chọn thư mục"
                                        readonly>

                                    <input type="hidden" name="folder_id" id="folder_id">

                                    <div class="structure-panel d-none">
                                        <div class="structure-path"></div>
                                        <div class="structure-list list-group"></div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <label class="form-label">Sổ văn bản</label>

                                <div class="structure-picker" data-type="book">
                                    <input type="text" class="form-control structure-input" placeholder="Chọn sổ văn bản"
                                        readonly>

                                    <input type="hidden" name="book_id" id="book_id">

                                    <div class="structure-panel d-none">
                                        <div class="structure-path"></div>
                                        <div class="structure-list list-group"></div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mã số văn bản</label>
                            <input type="text" name="code" id="code" class="form-control" readonly>
                        </div>

                        <div id="dynamic-fields"></div>

                        <div class="mb-3">
                            <label class="form-label">Nhãn</label>
                            <input type="text" name="tag" class="form-control">
                        </div>

                        <h5 class="fw-bold mt-4">Nội dung</h5>
                        <textarea name="content" rows="6" class="form-control"></textarea>

                        <h5 class="fw-bold mt-4">Đính kèm</h5>
                        <div class="card radius-15">
                            <div class="card-body">
                                <div class="card-title">
                                    <h4 class="mb-0">Tài liệu Đính Kèm</h4>
                                </div>
                                <div class="row g-3">
                                    <input type="file" class="filepond" name="files[]" multiple data-max-files="10">

                                </div>
                            </div>
                        </div>

                        <textarea name="bonus" rows="3" class="form-control mb-3" placeholder="Ghi chú thêm"></textarea>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Ngày ban hành</label>
                                <input type="date" name="issue_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ngày hiệu lực</label>
                                <input type="date" name="effective_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ngày hết hạn</label>
                                <input type="date" name="expiration_date" class="form-control">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="backStep1()">Quay
                                lại</button>
                            <button class="btn btn-success">Tạo văn bản</button>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- CKEditor --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        function generateRandom(length = 3) {
            return Math.floor(Math.random() * Math.pow(10, length))
                .toString()
                .padStart(length, '0');
        }

        function todayString() {
            const d = new Date();
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}${month}${year}`;
        }

        function renderDocumentCode(type) {
            const TYPE_PREFIX = {
                1: 'VBD',
                2: 'VBN',
                3: 'NB'
            };

            if (!TYPE_PREFIX[type]) return;

            const code = `${TYPE_PREFIX[type]}/${todayString()}/${generateRandom(3)}`;
            document.getElementById('code').value = code;
        }
    </script>


    <script>
        function initSelect2(context = document) {
            $(context).find('.single-select2').select2({
                placeholder: 'Chọn người',
                width: '100%',
                allowClear: true
            });
        }

        document.addEventListener("DOMContentLoaded", function() {

            initSelect2();

            // CKEditor cho textarea content
            ClassicEditor
                .create(document.querySelector('textarea[name="content"]'))
                .catch(error => {
                    console.error(error);
                });

            document.querySelectorAll('.type-option input').forEach(el => {
                el.addEventListener('change', () => {
                    document.getElementById('btn-step-1').disabled = false;
                });
            });

            document.getElementById('btn-step-1').addEventListener('click', () => {
                const type = document.querySelector('.type-option input:checked').value;

                document.getElementById('type_id').value = type;

                document.getElementById('document_type_text').value =
                    DOCUMENT_TYPE_LABEL[type] ?? '';

                document.getElementById('step-1').style.display = 'none';
                document.getElementById('step-2').style.display = 'block';

                renderDocumentCode(type);
                renderDynamic(type);
            });


        });
    </script>

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
        const DOCUMENT_TYPE_LABEL = {
            1: 'Văn bản đi',
            2: 'Văn bản đến',
            3: 'Văn bản nội bộ / Thông báo'
        };

        function initSelect2(context = document) {
            $(context).find('.single-select2').select2({
                placeholder: 'Chọn người',
                width: '100%',
                allowClear: true
            });
        }


        document.addEventListener("DOMContentLoaded", function() {

            initSelect2();

            document.querySelectorAll('.type-option input').forEach(el => {
                el.addEventListener('change', () => {
                    document.getElementById('btn-step-1').disabled = false;
                });
            });

            document.addEventListener('click', function(e) {
                document.querySelectorAll('.structure-panel').forEach(p => p.classList.add('d-none'));

                const picker = e.target.closest('.structure-picker');
                if (!picker) return;

                picker.querySelector('.structure-panel').classList.remove('d-none');
            });


            document.getElementById('btn-step-1').addEventListener('click', () => {
                const type = document.querySelector('.type-option input:checked').value;

                document.getElementById('type_id').value = type;
                document.getElementById('document_type_text').value =
                    DOCUMENT_TYPE_LABEL[type];

                document.getElementById('step-1').style.display = 'none';
                document.getElementById('step-2').style.display = 'block';

                renderDocumentCode(type);
                renderDynamic(type);
            });


        });

        const usersHtml = `@foreach ($users ?? [] as $u)
<option value="{{ $u->id }}">{{ $u->full_name }}</option>
@endforeach`;

        const customersHtml = `@foreach ($customers ?? [] as $u)
<option value="{{ $u->id }}">{{ $u->company_name }}</option>
@endforeach`;

        function renderDynamic(type) {
            let html = '';
            if (type == 1) {
                html = `
<div class="row mb-3">
    <div class="col-md-12">
        <label class="form-label">Gửi từ</label>
        <select name="from_unit_id" class="form-select">
            <option value="">-- Chọn --</option>
            @foreach ($users ?? [] as $u)
                <option value="{{ $u->id }}">{{ $u->full_name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Thông báo email</label>
        <select name="email_notice" class="form-select">
            <option value="1">Có</option>
            <option value="0">Không</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Người theo dõi</label>
        <select class="single-select2" name="followers[]" multiple>
            ${usersHtml}
        </select>
    </div>
</div>

<h5 class="fw-bold mt-4 mb-2">Gửi đến (bên ngoài)</h5>
<div id="recipients">
    <div class="row mb-2 recipient-item">
        <div class="col-md-4">
            <input name="recipients[0][company]" class="form-control" placeholder="Công ty / Tổ chức">
        </div>
        <div class="col-md-4">
            <input name="recipients[0][department]" class="form-control" placeholder="Phòng ban / Nhóm">
        </div>
        <div class="col-md-4">
            <input name="recipients[0][email]" class="form-control" placeholder="Email">
        </div>
        <div class="col-md-12 mt-2">
            <textarea name="recipients[0][note]" rows="4" class="form-control" placeholder="Thông tin thêm"></textarea>
        </div>
    </div>
</div>

<button type="button" id="addRecipient" class="btn btn-outline-secondary btn-sm mb-3">+ Thêm</button>

<div class="row">
    <div class="col-md-12">
        <label class="form-label">Gửi đến (nội bộ)</label>
        <select class="single-select2" name="to_internal[]" multiple required>
            ${usersHtml}
        </select>
    </div>
</div>`;
            }
            if (type == 2) {
                html = `
                <div class="row mb-3">
                    <!-- AA -->
    <div class="col-md-6 mt-2">
        <input name="sender[aa]" class="form-control" placeholder="AA">
    </div>

    <!-- Loại hợp đồng -->
    <div class="col-md-6 mt-2">
        <select name="sender[contract_type]" class="form-select">
            <option value="">-- Loại hợp đồng --</option>
            <option value="hd_kinh_te">Hợp đồng kinh tế</option>
            <option value="hd_nguyen_tac">Hợp đồng nguyên tắc</option>
            <option value="hd_thi_cong">Hợp đồng thi công</option>
            <option value="khac">Khác</option>
        </select>
    </div>
    </div>
<h5 class="fw-bold mb-3">Được gửi từ</h5>

<div class="row mb-3">
    <div class="col-md-3">
        <input name="sender[company]" class="form-control" placeholder="Công ty / Tổ chức">
    </div>
    <div class="col-md-3">
        <input name="sender[department]" class="form-control" placeholder="Phòng ban / Nhóm">
    </div>
    <div class="col-md-3">
        <input type="date" name="sender[date]" class="form-control">
    </div>
    <div class="col-md-3">
        <input name="sender[phone]" class="form-control" placeholder="Số điện thoại">
    </div>

    <div class="col-md-4 mt-2">
        <input name="sender[email]" class="form-control" placeholder="Email">
    </div>
    <div class="col-md-4 mt-2">
        <input name="sender[address]" class="form-control" placeholder="Địa chỉ">
    </div>
    <div class="col-md-4 mt-2">
        <input name="sender[document_code]" class="form-control" placeholder="Mã văn bản">
    </div>

    <div class="col-md-12 mt-2">
        <textarea name="sender[note]" rows="4" class="form-control" placeholder="Thông tin thêm"></textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-label">Người nhận</label>
            <select class="single-select2" name="receivers[]" multiple required>
              ${usersHtml}
        </select>
    </div>
</div>`;
            }

            if (type == 3) {
                html = `
                <div class="row mb-3">
                    <!-- AA -->
    <div class="col-md-6 mt-2">
        <input name="aa" class="form-control" placeholder="AA">
    </div>

    <!-- Loại hợp đồng -->
    <div class="col-md-6 mt-2">
        <select name="contract_type" class="form-select">
            <option value="">-- Loại hợp đồng --</option>
            <option value="1">Hợp đồng kinh tế</option>
            <option value="2">Hợp đồng nguyên tắc</option>
            <option value="3">Hợp đồng thi công</option>
            <option value="4">Khác</option>
        </select>
    </div>
    </div>
<h5 class="fw-bold mb-3">Gửi từ</h5>

<div class="row mb-3">
    <div class="col-md-12">
        <input name="sender[company]" class="form-control" placeholder="Công ty / Tổ chức">
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-label">Người nhận</label>
      <select class="single-select2" name="receivers[]" multiple required>
            ${usersHtml}
        </select>
    </div>
</div>`;
            }

            const container = document.getElementById('dynamic-fields');
            container.innerHTML = html;
            initSelect2(container);
        }
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'addRecipient') {
                const container = document.getElementById('recipients');
                const idx = container.querySelectorAll('.recipient-item').length;

                container.insertAdjacentHTML('beforeend', `
<div class="row mb-2 recipient-item">
    <div class="col-md-3"><input name="recipients[${idx}][company]" class="form-control" placeholder="Công ty"></div>
    <div class="col-md-4"><input name="recipients[${idx}][department]" class="form-control" placeholder="Phòng ban"></div>
    <div class="col-md-4"><input name="recipients[${idx}][email]" class="form-control" placeholder="Email"></div>
    <div class="col-md-12 mt-2">
        <textarea name="recipients[${idx}][note]" rows="4" class="form-control" placeholder="Thông tin thêm"></textarea>
    </div>
</div>`);
            }
        });

        function updateStructureInput(type) {
            const state = structureState[type];
            const picker = document.querySelector(`.structure-picker[data-type="${type}"]`);

            if (!picker) return;

            const input = picker.querySelector('.structure-input');

            if (!state.path.length) {
                input.value = '';
                return;
            }

            input.value = state.path.map(p => p.name).join(' / ');
        }


        function backStep1() {
            document.getElementById('step-2').style.display = 'none';
            document.getElementById('step-1').style.display = 'block';

            document.getElementById('type_id').value = '';
            document.getElementById('document_type_text').value = '';
            document.getElementById('btn-step-1').disabled = true;

            document.querySelectorAll('.type-option input').forEach(el => el.checked = false);
        }
    </script>

    <script>
        const structureState = {
            storage: {
                path: [],
                type: 'storage'
            },
            content_group: {
                path: [],
                type: 'content_group'
            },
            folder: {
                path: [],
                type: 'folder'
            },
            book: {
                path: [],
                type: 'book'
            },
        };


        async function loadStructure(type, parentId = null) {
            const res = await fetch(
                `{{ route('document.structure.children') }}?type=${type}&parent_id=${parentId ?? ''}`
            );
            const items = await res.json();

            renderPath(type);
            renderList(type, items);
        }


        function renderPath(type) {
            const state = structureState[type];
            const picker = document.querySelector(`.structure-picker[data-type="${type}"]`);
            const pathEl = picker.querySelector('.structure-path');

            if (!state.path.length) {
                pathEl.innerHTML = `<span class="text-muted">Chưa chọn</span>`;
                return;
            }

            pathEl.innerHTML = state.path.map((p, i) => `
        <a href="#" data-index="${i}">${p.name}</a>
    `).join(' / ');

            pathEl.querySelectorAll('a').forEach(a => {
                a.onclick = e => {
                    e.preventDefault();
                    const idx = +a.dataset.index;
                    state.path = state.path.slice(0, idx + 1);
                    document.getElementById(`${type}_id`).value = state.path.at(-1).id;
                    updateStructureInput(type);
                    loadStructure(type, state.path.at(-1).id);
                };
            });
        }



        function renderList(type, items) {
            const picker = document.querySelector(`.structure-picker[data-type="${type}"]`);
            const list = picker.querySelector('.structure-list');
            const state = structureState[type];

            list.innerHTML = '';

            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'list-group-item';
                div.textContent = item.name;

                div.onclick = async () => {
                    state.path.push({
                        id: item.id,
                        name: item.name
                    });
                    document.getElementById(`${type}_id`).value = item.id;
                    updateStructureInput(type);
                    await loadStructure(type, item.id);
                };

                list.appendChild(div);
            });
        }


        document.addEventListener('DOMContentLoaded', () => {
            loadStructure('storage');
            loadStructure('content_group');
            loadStructure('folder');
            loadStructure('book');
        });
    </script>
    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
            border-radius: 10px;
        }

        .structure-picker {
            position: relative;
        }

        .structure-input {
            cursor: pointer;
            background-color: #fff;
        }

        .structure-panel {
            position: absolute;
            z-index: 50;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            margin-top: 4px;
        }

        .structure-path {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .structure-list {
            max-height: 220px;
            overflow-y: auto;
        }

        .structure-list .list-group-item {
            cursor: pointer;
        }
    </style>
@endpush
