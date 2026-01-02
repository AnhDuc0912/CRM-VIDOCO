@extends('core::layouts.app')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\Customer\Enums\CustomerTypeEnum')

@section('title', 'Quản lý khách hàng')

@section('content')
    <style>
        #customers-table {
            table-layout: fixed;
            width: 100%;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }


        #customers-table th:nth-child(3),
        #customers-table td:nth-child(3) {
            width: 150px;
        }

        #customers-table th:nth-child(1),
        #customers-table td:nth-child(1) {
            width: 60px;
            text-align: center;
        }
    </style>



    <div class="card">
        <div class="card-body">


            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý Khách Hàng</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh Sách Khách Hàng</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        @can(PermissionEnum::CUSTOMER_CREATE)
                            <a href="{{ route('customers.create') }}" class="btn btn-secondary"><i
                                    class="bx bx-cloud-upload me-1"></i>Thêm
                                khách hàng</a>
                        @endcan

                    </div>
                </div>
            </div>
            <hr />
            <div class="table-responsive">
                <table id="customers-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Danh sách dịch vụ</th>
                            <th>Mã khách hàng</th>
                            <th>Loại khách hàng</th>
                            <th>Tên chủ thể</th>
                            <th>Email/Điện thoại</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>
                                    @if ($customer->services->count() > 0)
                                        <a href="{{ route('orders.active', ['customer_id' => $customer->id]) }}"
                                            class="text-info">
                                            {{ $customer->services->count() }}
                                            DỊCH VỤ</a>
                                    @else
                                        <span class="text-info">Không còn dịch
                                            vụ</span>
                                    @endif
                                </td>
                                <td>{{ $customer->code ?? '' }}</td>
                                <td>
                                    @if ($customer->customer_type == CustomerTypeEnum::PERSONAL)
                                        Cá Nhân
                                    @else
                                        Công Ty
                                    @endif
                                </td>
                                <td>
                                    @if ($customer->customer_type == CustomerTypeEnum::PERSONAL)
                                        {{ $customer->last_name ?? '' }}
                                        {{ $customer->first_name ?? '' }}
                                    @else
                                        {{ $customer->company_name ?? '' }}
                                    @endif
                                </td>
                                <td>{{ $customer->email ?? '' }} <br>
                                    {{ $customer->phone ?? '' }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @can(PermissionEnum::CUSTOMER_SHOW)
                                            <a href="{{ route('customers.show', $customer->id) }}" title="Xem hồ sơ"
                                                type="button" class="btn btn-info m-1"><i class="bx bx-info-square"></i>
                                            </a>
                                        @endcan
                                        @can(PermissionEnum::CUSTOMER_UPDATE)
                                            <a href="{{ route('customers.edit', $customer->id) }}" title="Cập nhật"
                                                type="button" class="btn btn-secondary m-1"><i class="bx bx-edit me-1"></i></a>
                                        @endcan
                                        @can(PermissionEnum::CUSTOMER_DELETE)
                                            <button title="Xóa" type="button" class="btn btn-danger m-1"
                                                onclick="confirmDelete('{{ route('customers.destroy', $customer->id) }}', 'Bạn có chắc chắn muốn xóa khách hàng này không?')">
                                                <i class="bx bx-trash me-1"></i>
                                            </button>
                                        @endcan
                                    </div>
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
            var table = $('#customers-table').DataTable({
                lengthChange: false,
                searching: true,
                buttons: ['excel', 'pdf'],
                columnDefs: [{
                    targets: [4],
                    orderable: false
                }],
                language: {
                    "sProcessing": "Đang xử lý...",
                    "sLengthMenu": "Xem _MENU_ mục",
                    "sZeroRecords": "Không tìm thấy dòng nào phù hợp",
                    "sInfo": "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    "sInfoEmpty": "Đang xem 0 đến 0 trong tổng số 0 mục",
                    "sInfoFiltered": "(được lọc từ _MAX_ mục)",
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
                '#customers-table_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
