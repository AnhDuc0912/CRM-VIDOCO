@extends('core::layouts.app')
@use('Modules\Core\Enums\RoleEnum')
@use('Modules\Core\Enums\PermissionEnum')

@section('title', 'Danh sách Bình luận')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Bình luận</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh Sách Bình luận</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="commentsTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Người Bình Luận</th>
                                <th>Nội dung</th>
                                <th>Đã trả lời</th>
                                <th>Nơi bình luận</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $comment)
                                <tr>
                                    <td>
                                        <img src="{{ $comment->user->avatar_url ?? asset('assets/images/avatars/avatar-1.png') }}"
                                            class="rounded-circle shadow" width="40" height="40"
                                            alt="{{ $comment->user->name }}">
                                        {{ $comment->user->name }}
                                    </td>

                                    <td>{{ $comment->content }}</td>

                                    <td>
                                        {{ $comment->replies->count() }} trả lời
                                    </td>


                                    <td>
                                        {{ $comment->project->project_name }}
                                    </td>

                                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>

                                    <td>
                                        <a title="Xem chi tiết"
                                            href="{{ route('project.show', $comment->commentable_id) }}">
                                            <button type="button" class="btn btn-info m-1">
                                                <i class="bx bx-info-square"></i>
                                            </button>
                                        </a>

                                        <form action="{{ route('comment.destroy', $comment->id) }}" method="POST"
                                            style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger m-1"
                                                onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Người Bình Luận</th>
                                <th>Nội dung</th>
                                <th>Đã trả lời</th>
                                <th>Nơi bình luận</th>
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
            $('#commentsTable').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            }).buttons().container().appendTo('#commentsTable_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
