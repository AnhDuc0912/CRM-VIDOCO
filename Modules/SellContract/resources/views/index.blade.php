@extends('core::layouts.app')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\SellContract\Enums\SellContractStatusEnum')

@section('title', 'Danh sách báo giá')

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý hợp đồng bán hàng</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh Sách Báo Giá</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    @can(PermissionEnum::SELL_CONTRACT_CREATE)
                        <a href="{{ route('sell-contracts.create') }}" class="btn btn-secondary"><i
                                class="bx bx-cloud-upload me-1"></i>Thêm
                            hợp đồng bán hàng</a>
                    @endcan
                </div>
            </div>

            <hr />
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Mã hợp đồng</th>
                            <th>Khách hàng</th>
                            <th>Người phụ trách</th>
                            <th>Giá Trị Đơn hàng</th>
                            <th>Hạn Báo Giá</th>
                            <th>File Đính Kèm</th>
                            <th>Trạng thái</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellContracts as $sellContract)
                            <tr>
                                <td>{{ $sellContract->code ?? '' }}</td>
                                <td>{{ $sellContract->customer?->customer_type == CustomerTypeEnum::PERSONAL ? $sellContract->customer?->first_name . ' ' . $sellContract->customer?->last_name : $sellContract->customer?->company_name ?? '' }}
                                </td>
                                <td>{{ $sellContract->customer?->personInCharge?->full_name ?? '' }}
                                </td>
                                <td>{{ format_money($sellContract->amount ?? 0) }}</td>
                                <td>{{ $sellContract->expired_at ?? '' }}</td>
                                <td>
                                    @if ($sellContract->files->count() > 0)
                                        <a title="Tải file"
                                            href="{{ route('sell-contracts.download-files', $sellContract->id) }}">
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
                                    {{ SellContractStatusEnum::getStatusName($sellContract->status ?? SellContractStatusEnum::NEW) }}
                                </td>
                                <td>
                                    @can(PermissionEnum::SELL_CONTRACT_CONVERT_TO_ORDER)
                                        @if (
                                            $sellContract->status != SellContractStatusEnum::CONVER_TO_ORDER &&
                                                $sellContract->status != SellContractStatusEnum::REJECTED)
                                            <a onclick="confirmAction('{{ route('sell-contracts.convert-to-order', $sellContract->id) }}', 'PUT', 'Bạn có chắc chắn muốn chuyển hợp đồng này thành đơn hàng không?')"
                                                title="Chuyển thành đơn hàng">
                                                <button type="button" class="btn btn-danger  m-1">
                                                    <i class="bx bx bx-repeat"></i>
                                                </button>
                                            </a>
                                        @endif
                                    @endcan
                                    <a title="In Hợp đồng" href="">
                                        <button type="button" class="btn btn-primary  m-1">
                                            <i class="bx bx bx-printer"></i>
                                        </button>
                                    </a>
                                    @can(PermissionEnum::SELL_CONTRACT_SHOW)
                                        <a title="Xem chi tiết" href="{{ route('sell-contracts.show', $sellContract->id) }}">
                                            <button type="button" class="btn btn-info m-1">
                                                <i class="bx bx-info-square"></i>
                                            </button>
                                        </a>
                                    @endcan
                                    @can(PermissionEnum::SELL_CONTRACT_UPDATE)
                                        <a title="Cập nhật hồ sơ" href="{{ route('sell-contracts.edit', $sellContract->id) }}">
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
