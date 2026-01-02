@extends('core::layouts.app')
@use('Modules\Category\Enums\CategoryStatusEnum')

@section('title', 'Quản lý danh mục')

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý Danh Mục Dịch Vụ</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh Sách Danh Mục Dịch Vụ</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="{{ route('categories.create') }}" class="btn btn-secondary">
                            <i class="bx bx-cloud-upload me-1"></i>Thêm danh mục
                        </a>
                    </div>
                </div>
            </div>

            <hr />

            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Mã danh mục</th>
                            <th>Danh Mục</th>
                            <th>Dịch Vụ</th>
                            <th>USB Sản Phẩm</th>
                            <th>Người Thêm</th>
                            <th>Ngày Duyệt</th>
                            <th>Trạng thái</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->code ?? '' }}</td>
                                <td>{{ $category->name ?? '' }}</td>
                                <td>
                                    <a href="{{ route('services.index', ['category_id' => $category->id]) }}">
                                        Xem chi tiết {{ $category->services?->count() ?? 0 }} dịch vụ
                                    </a>
                                </td>
                                <td>
                                    @if ($category->files->count() > 0)
                                        <a href="{{ route('categories.download-files', $category->id) }}"
                                            class="btn btn-outline-info px-5 w-100"
                                            title="Download {{ $category->files->count() }} files">
                                            <i class="bx bx-cloud-download me-1"></i>Tải Files ({{ $category->files->count() }})
                                        </a>
                                    @else
                                        <span class="text-muted">Không có files</span>
                                    @endif
                                </td>
                                <td>{{ $category->creator?->full_name ?? '' }}</td>
                                <td>{{ !empty($category->approved_at) ? date('d/m/Y', strtotime($category->approved_at)) : '' }}</td>
                                <td>
                                    @if ($category->status == CategoryStatusEnum::ACTIVE)
                                        <span class="badge bg-success p-2">{{ CategoryStatusEnum::getLabel($category->status) }}</span>
                                    @elseif ($category->status == CategoryStatusEnum::INACTIVE)
                                        <span class="badge bg-danger p-2">{{ CategoryStatusEnum::getLabel($category->status) }}</span>
                                    @elseif ($category->status == CategoryStatusEnum::PENDING)
                                        <span class="badge bg-warning p-2">{{ CategoryStatusEnum::getLabel($category->status) }}</span>
                                    @else
                                        <span class="badge bg-danger p-2">{{ CategoryStatusEnum::getLabel($category->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('categories.show', $category->id) }}" title="Xem Chi tiết"
                                        class="btn btn-info m-1"><i class="bx bx-info-square"></i></a>

                                    <a href="{{ route('categories.edit', $category->id) }}" title="Cập nhật"
                                        class="btn btn-secondary m-1"><i class="bx bx-edit me-1"></i></a>

                                    {{-- Nút xóa --}}
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger m-1" title="Xóa danh mục">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            });
            table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
