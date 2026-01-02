    @extends('core::layouts.app')

    @section('title', 'Văn bản')

    @section('content')
        <div class="row">
            <div class="col-2 border-end bg-light" style="min-height:100vh">
                <div class="p-3">

                    <a href="{{ route('document.create') }}" class="btn btn-secondary w-100 fw-bold mb-3">
                        <i class="bx bx-cloud-upload me-1"></i> Tạo văn bản
                    </a>


                    <div class="accordion accordion-flush mb-2" id="categoryAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button px-0 py-2 fw-bold text-muted small" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#categoryCollapse">
                                    Danh mục
                                </button>
                            </h2>

                            <div id="categoryCollapse" class="accordion-collapse collapse show">
                                <div class="accordion-body px-0 pt-1">
                                    <ul class="nav flex-column gap-1">

                                        <li class="nav-item">
                                            <a class="nav-link px-2 {{ request('type') == '' ? 'active' : '' }}"
                                                href="{{ route('document.index') }}">
                                                Tất cả văn bản
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-2 {{ request('type') == 1 ? 'active' : '' }}"
                                                href="{{ route('document.index', ['type' => 1]) }}">
                                                Văn bản đi
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-2 {{ request('type') == 2 ? 'active' : '' }}"
                                                href="{{ route('document.index', ['type' => 2]) }}">
                                                Văn bản đến
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-2 {{ request('type') == 3 ? 'active' : '' }}"
                                                href="{{ route('document.index', ['type' => 3]) }}">
                                                Văn bản nội bộ
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>



                    <hr>
                    <div class="accordion accordion-flush" id="sidebarAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button
                                    class="accordion-button px-0 py-2 fw-bold text-muted small
                {{ request('storage_id') ? '' : 'collapsed' }}"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#storageCollapse">
                                    Kho lưu trữ
                                </button>
                            </h2>

                            <div id="storageCollapse"
                                class="accordion-collapse collapse {{ request('storage_id') ? 'show' : '' }}"
                                data-bs-parent="#sidebarAccordion">

                                <div class="accordion-body px-0 pt-1">
                                    <ul class="nav flex-column gap-1" style="max-height:200px; overflow-y:auto">
                                        @foreach ($storages as $item)
                                            <li class="nav-item">
                                                <a class="nav-link px-2 {{ request('storage_id') == $item->id ? 'active' : '' }}"
                                                    href="{{ request()->fullUrlWithQuery(['storage_id' => $item->id]) }}">
                                                    {{ $item->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                            </div>
                        </div>


                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button
                                    class="accordion-button px-0 py-2 fw-bold text-muted small
                {{ request('group_id') ? '' : 'collapsed' }}"
                                    data-bs-toggle="collapse" data-bs-target="#groupCollapse">
                                    Nhóm nội dung
                                </button>
                            </h2>

                            <div id="groupCollapse"
                                class="accordion-collapse collapse {{ request('group_id') ? 'show' : '' }}"
                                data-bs-parent="#sidebarAccordion">

                                <div class="accordion-body px-0 pt-1">
                                    <ul class="nav flex-column gap-1" style="max-height:200px; overflow-y:auto">
                                        @foreach ($groups as $item)
                                            <li class="nav-item">
                                                <a class="nav-link px-2 {{ request('group_id') == $item->id ? 'active' : '' }}"
                                                    href="{{ request()->fullUrlWithQuery(['group_id' => $item->id]) }}">
                                                    {{ $item->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button
                                    class="accordion-button px-0 py-2 fw-bold text-muted small
                {{ request('folder_id') ? '' : 'collapsed' }}"
                                    data-bs-toggle="collapse" data-bs-target="#folderCollapse">
                                    Thư mục
                                </button>
                            </h2>

                            <div id="folderCollapse"
                                class="accordion-collapse collapse {{ request('folder_id') ? 'show' : '' }}"
                                data-bs-parent="#sidebarAccordion">

                                <div class="accordion-body px-0 pt-1">
                                    <ul class="nav flex-column gap-1" style="max-height:200px; overflow-y:auto">
                                        @foreach ($folders as $item)
                                            <li class="nav-item">
                                                <a class="nav-link px-2 {{ request('folder_id') == $item->id ? 'active' : '' }}"
                                                    href="{{ request()->fullUrlWithQuery(['folder_id' => $item->id]) }}">
                                                    {{ $item->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button
                                    class="accordion-button px-0 py-2 fw-bold text-muted small
                {{ request('book_id') ? '' : 'collapsed' }}"
                                    data-bs-toggle="collapse" data-bs-target="#bookCollapse">
                                    Sổ văn bản
                                </button>
                            </h2>

                            <div id="bookCollapse"
                                class="accordion-collapse collapse {{ request('book_id') ? 'show' : '' }}"
                                data-bs-parent="#sidebarAccordion">

                                <div class="accordion-body px-0 pt-1">
                                    <ul class="nav flex-column gap-1" style="max-height:200px; overflow-y:auto">
                                        @foreach ($books as $item)
                                            <li class="nav-item">
                                                <a class="nav-link px-2 {{ request('book_id') == $item->id ? 'active' : '' }}"
                                                    href="{{ request()->fullUrlWithQuery(['book_id' => $item->id]) }}">
                                                    {{ $item->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('document.index') }}" class="btn btn-sm btn-outline-waning w-100">
                        <i class="bx bx-reset"></i>
                    </a>

                </div>
            </div>

            <div class="col-10 page-content">
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Quản lý Văn Bản</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Danh Sách Văn Bản</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <div class="btn-group">
                            <form method="GET" class="d-flex gap-2">
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                                    style="max-width:280px" placeholder="Tìm mã hoặc tiêu đề">
                                <button class="btn btn-outline-warning">Tìm</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th style="width:40px">
                                        ID
                                    </th>
                                    <th>Văn Bản</th>
                                    <th style="width:140px">Loại</th>
                                    <th style="width:220px">Người Tạo</th>
                                    <th style="width:220px">Người Duyệt</th>
                                    <th style="width:140px">Trạng Thái Văn Bản</th>
                                    <th style="width:140px">Ngày Ban Hành</th>
                                    <th style="width:120px">Chức Năng</th>
                                </tr>
                            </thead>
                            <tbody style="border-top:none!important;">
                                @forelse($documents as $doc)
                                    <tr>
                                        <td>
                                            {{ $doc->id }}
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                @if ($doc->code)
                                                    <span class="text-muted">[{{ $doc->code }}]</span>
                                                @endif
                                                {{ $doc->title }}
                                            </div>
                                            <small class="text-muted">
                                                Tạo lúc {{ $doc->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>

                                        <td>
                                            @switch($doc->type_id)
                                                @case(1)
                                                    <span class="badge bg-primary">Văn bản đi</span>
                                                @break

                                                @case(2)
                                                    <span class="badge bg-warning">Văn bản đến</span>
                                                @break

                                                @case(3)
                                                    <span class="badge bg-success">Thông Báo</span>
                                                @break

                                                @default
                                                    —
                                            @endswitch
                                        </td>

                                        <td>
                                            {{ $doc->fromEmployee->full_name ?? '—' }}
                                        </td>

                                        <td>
                                            {!! $doc->approver->full_name ?? '<span class="badge bg-danger">VĂN BẢN CHƯA ĐƯỢC DUYỆT</span>' !!}
                                        </td>
                                        <td>
                                            @php
                                                $today = \Carbon\Carbon::today();
                                            @endphp
                                            @if ($doc->approver?->full_name)
                                                @if ($doc->expiration_date && $doc->expiration_date->lt($today))
                                                    <span class="badge bg-danger">Hết hiệu lực</span>
                                                @elseif($doc->effective_date && $doc->effective_date->lte($today))
                                                    <span class="badge bg-success">Có hiệu lực</span>
                                                @elseif($doc->issue_date && $doc->issue_date->lte($today))
                                                    <span class="badge bg-warning text-dark">Đã ban hành</span>
                                                @else
                                                    —
                                                @endif
                                            @else
                                                {!! '<span class="badge bg-danger">VĂN BẢN CHƯA ĐƯỢC DUYỆT</span>' !!}
                                            @endif
                                        </td>


                                        <td>
                                            {{ $doc->issue_date?->format('d/m/Y') ?? '—' }}
                                        </td>

                                        <td>

                                            <a title="Xem chi tiết" href="{{ route('document.show', $doc) }}">
                                                <button type="button" class="btn btn-info m-1">
                                                    <i class="bx bx-info-square"></i>
                                                </button>
                                            </a>

                                            {{-- <a title="Chỉnh sửa văn bản" href="{{ route('document.edit', $doc) }}">
                                                <button type="button" class="btn btn-secondary m-1">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                            </a> --}}


                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Không có văn bản
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        {{ $documents->links() }}
                    </div>

                </div>
            </div>
        @endsection

        @push('scripts')
            <style>
                #sidebarAccordion .accordion-item {
                    background: transparent;
                    border: 0;
                    border-bottom: 1px solid #e5e7eb;
                }

                #sidebarAccordion .accordion-header {
                    margin-bottom: 0;
                }

                #sidebarAccordion .accordion-button {
                    background: transparent;
                    box-shadow: none;
                    font-size: 13px;
                    font-weight: 600;
                    color: #6b7280;
                    padding: 6px 0;
                }

                #sidebarAccordion .accordion-button:not(.collapsed) {
                    color: #111827;
                }

                #sidebarAccordion .accordion-button::after {
                    width: 14px;
                    height: 14px;
                    background-size: 14px;
                }

                #sidebarAccordion .accordion-body {
                    padding: 4px 0 8px 0;
                }

                #sidebarAccordion .nav-link {
                    font-size: 13px;
                    padding: 4px 8px;
                    border-radius: 6px;
                    color: #374151;
                }

                #sidebarAccordion .nav-link:hover {
                    background: #f3f4f6;
                    color: #111827;
                }

                #sidebarAccordion .nav-link.active {
                    background: #e5e7eb;
                    color: #111827;
                    font-weight: 600;
                }

                #sidebarAccordion ul {
                    scrollbar-width: thin;
                }

                #sidebarAccordion ul::-webkit-scrollbar {
                    width: 6px;
                }

                #sidebarAccordion ul::-webkit-scrollbar-thumb {
                    background: #d1d5db;
                    border-radius: 6px;
                }

                #sidebarAccordion,
                #categoryAccordion {
                    font-size: 13px;
                }

                #categoryAccordion .accordion-item {
                    background: transparent;
                    border: 0;
                    border-bottom: 1px solid #e5e7eb;
                }

                #categoryAccordion .accordion-button {
                    background: transparent;
                    box-shadow: none;
                    font-size: 13px;
                    font-weight: 600;
                    color: #6b7280;
                    padding: 6px 0;
                }

                #categoryAccordion .accordion-button:not(.collapsed) {
                    color: #111827;
                }

                #categoryAccordion .nav-link {
                    font-size: 13px;
                    padding: 4px 8px;
                    border-radius: 6px;
                    color: #374151;
                }

                #categoryAccordion .nav-link.active {
                    background: #e5e7eb;
                    color: #111827;
                    font-weight: 600;
                }
            </style>
        @endpush
