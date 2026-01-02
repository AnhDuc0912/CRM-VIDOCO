@extends('core::layouts.app')

@section('title', 'Danh sách Vị Trí Công Việc')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Vị Trí Công Việc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh Sách Vị Trí Công Việc</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('position.create') }}"  class="btn btn-dark m-1">
                        <i class="bx bx-cloud-upload me-1"></i>Thêm Vị Trí Công Việc
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="positionsTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Vị Trí Công Việc</th>
                                <th>Mô tả</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($positions as $position)
                                <tr>
                                    <td>{{ $position->id }}</td>
                                    <td>{{ $position->name }}</td>
                                    <td>{!! $position->description !!}</td>
                                    <td>{{ $position->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('position.edit', $position->id) }}" class="btn btn-secondary m-1">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <form action="{{ route('position.destroy', $position->id) }}" method="POST"
                                            style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger m-1"
                                                onclick="return confirm('Bạn có chắc muốn xóa position này?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Tên Vị Trí Công Việc</th>
                                <th>Cấp Bậc</th>
                                <th>Mô tả</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#positionsTable').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            }).buttons().container().appendTo('#positionsTable_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
