@extends('core::layouts.app')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\SellOrder\Enums\SellOrderStatusEnum')

@section('title', 'Danh sách đơn hàng')

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý đơn hàng</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh Sách Đơn Hàng</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    @can(PermissionEnum::SELL_ORDER_CREATE)
                        <a href="{{ route('sell-orders.create') }}" class="btn btn-secondary"><i
                                class="bx bx-cloud-upload me-1"></i>Thêm
                            đơn hàng</a>
                    @endcan
                </div>
            </div>
            <hr />
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Người phụ trách</th>
                            <th>Giá Trị Đơn hàng</th>
                            <th>Hạn thanh toán</th>
                            <th>File Đính Kèm</th>
                            <th>Trạng thái</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellOrders as $sellOrder)
                            <tr>
                                <td>{{ $sellOrder->code ?? '' }}</td>
                                <td>{{ $sellOrder->customer?->customer_type == CustomerTypeEnum::PERSONAL ? $sellOrder->customer?->first_name . ' ' . $sellOrder->customer?->last_name : $sellOrder->customer?->company_name ?? '' }}
                                </td>
                                <td>{{ $sellOrder->customer?->personInCharge?->full_name ?? '' }}
                                </td>
                                <td>{{ format_money($sellOrder->amount ?? 0) }}</td>
                                <td>{{ $sellOrder->expired_at ?? '' }}</td>
                                <td>
                                    @if ($sellOrder->files->count() > 0)
                                        <a title="Tải file"
                                            href="{{ route('sell-orders.download-files', $sellOrder->id) }}">
                                            <button type="button" class="btn btn-success  m-1">
                                                <i class="bx bx-cloud-download me-1"></i>
                                                Tải File
                                            </button>
                                        </a>
                                    @else
                                        <span class="text-danger">Không có
                                            file</span>
                                    @endif
                                </td>
                                <td>
                                    {{ SellOrderStatusEnum::getStatusName($sellOrder->status ?? SellOrderStatusEnum::CREATED) }}
                                </td>
                                <td>
                                    <a title="In đơn hàng" href="">
                                        <button type="button" class="btn btn-primary  m-1">
                                            <i class="bx bx bx-printer"></i>
                                        </button>
                                    </a>
                                    @can(PermissionEnum::SELL_ORDER_SHOW)
                                        <a title="Xem chi tiết" href="{{ route('sell-orders.show', $sellOrder->id) }}">
                                            <button type="button" class="btn btn-info m-1">
                                                <i class="bx bx-info-square"></i>
                                            </button>
                                        </a>
                                    @endcan
                                    @can(PermissionEnum::SELL_ORDER_UPDATE)
                                        <a title="Cập nhật hồ sơ" href="{{ route('sell-orders.edit', $sellOrder->id) }}">
                                            <button type="button" class="btn btn-secondary m-1">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                        </a>
                                    @endcan
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
            //Default data table
            $('#example').DataTable();
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            });
            table.buttons().container().appendTo(
                '#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
