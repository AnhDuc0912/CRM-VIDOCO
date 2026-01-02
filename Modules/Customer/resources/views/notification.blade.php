@extends('core::layouts.app')
@use('Modules\Core\Enums\PermissionEnum')

@section('title', 'Lịch sử gửi thông báo')

@section('content')
    <style>
        #notifications-table {
            table-layout: fixed;
            width: 100%;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        #notifications-table th:nth-child(1),
        #notifications-table td:nth-child(1) {
            width: 60px;
            text-align: center;
        }

        #notifications-table th:nth-child(2),
        #notifications-table td:nth-child(2) {
            width: 100px;
            text-align: center;
        }

        #notifications-table th:nth-child(5),
        #notifications-table td:nth-child(5) {
            width: 120px;
            text-align: center;
        }

        #notifications-table th:nth-child(6),
        #notifications-table td:nth-child(6) {
            width: 160px;
            text-align: center;
        }
    </style>

    <div class="card">
        <div class="card-body">

            {{-- Breadcrumb --}}
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Khách hàng</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('customers.index') }}">
                                    <i class="bx bx-home-alt"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Lịch sử gửi thông báo
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <hr />

            {{-- Table --}}
            <div class="table-responsive">
                <table id="notifications-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kênh</th>
                            <th>Người nhận</th>
                            <th>Template</th>
                            <th>Trạng thái</th>
                            <th>Thời gian</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ strtoupper($log->channel) }}
                                    </span>
                                </td>

                                <td>{{ $log->to }}</td>

                                <td>{{ $log->template_id }}</td>

                                <td>
                                    @if ($log->status === 'success')
                                        <span class="badge bg-success">Thành công</span>
                                    @elseif ($log->status === 'failed')
                                        <span class="badge bg-danger">Thất bại</span>
                                    @else
                                        <span class="badge bg-warning">Đang gửi</span>
                                    @endif
                                </td>

                                <td>{{ $log->sent_at }}</td>

                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-info"
                                        onclick="showResponse({{ $log->id }})"
                                    >
                                        <i class="bx bx-show"></i>
                                    </button>

                                    <script>
                                        function showResponse(id) {
                                            const data = @json($logs->keyBy('id'));
                                            alert(JSON.stringify(data[id].response, null, 2));
                                        }
                                    </script>
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
        $(document).ready(function () {
            var table = $('#notifications-table').DataTable({
                lengthChange: false,
                searching: true,
                ordering: true,
                buttons: ['excel', 'pdf'],
                language: {
                    "sProcessing": "Đang xử lý...",
                    "sLengthMenu": "Xem _MENU_ mục",
                    "sZeroRecords": "Không tìm thấy dữ liệu",
                    "sInfo": "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    "sInfoEmpty": "Đang xem 0 đến 0 trong tổng số 0 mục",
                    "sInfoFiltered": "(lọc từ _MAX_ mục)",
                    "sSearch": "Tìm:",
                    "oPaginate": {
                        "sFirst": "Đầu",
                        "sPrevious": "Trước",
                        "sNext": "Tiếp",
                        "sLast": "Cuối"
                    }
                }
            });

            table.buttons().container().appendTo(
                '#notifications-table_wrapper .col-md-6:eq(0)'
            );
        });
    </script>
@endpush
