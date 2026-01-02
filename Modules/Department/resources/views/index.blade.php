@extends('core::layouts.app')

@section('title', 'Danh sách Phòng Ban')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Phòng Ban</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh Sách Phòng Ban</li>
                    </ol>
                </nav>
            </div>
                    <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('department.create') }}"  class="btn btn-dark m-1">
                        <i class="bx bx-cloud-upload me-1"></i>Thêm Phòng Ban
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="departmentsTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên phòng ban</th>
                                <th>Mô tả</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                     <td>{{ $department->id }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>{!! $department->description !!}</td>
                                    <td>{{ $department->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('department.edit', $department->id) }}"
                                            class="btn btn-secondary m-1">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <form action="{{ route('department.destroy', $department->id) }}" method="POST"
                                            style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger m-1"
                                                onclick="return confirm('Bạn có chắc muốn xóa phòng ban này?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Tên phòng ban</th>
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
            $('#departmentsTable').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            }).buttons().container().appendTo('#departmentsTable_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
