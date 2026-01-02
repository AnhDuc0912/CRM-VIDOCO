@extends('core::layouts.app')

@section('title', 'Danh sách Cấp Bậc')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Cấp Bậc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh Sách Cấp Bậc</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('level.create') }}"  class="btn btn-dark m-1">
                        <i class="bx bx-cloud-upload me-1"></i>Thêm Cấp Bậc
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="levelsTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Cấp Bậc</th>
                                <th>Mô tả</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($levels as $level)
                                <tr>
                                    <td>{{ $level->id }}</td>
                                    <td>{{ $level->name }}</td>
                                    <td>{!! $level->description !!}</td>
                                    <td>{{ $level->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('level.edit', $level->id) }}" class="btn btn-secondary m-1">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <form action="{{ route('level.destroy', $level->id) }}" method="POST"
                                            style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger m-1"
                                                onclick="return confirm('Bạn có chắc muốn xóa cấp bậc này?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Tên Cấp Bậc</th>
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
            $('#levelsTable').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            }).buttons().container().appendTo('#levelsTable_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
