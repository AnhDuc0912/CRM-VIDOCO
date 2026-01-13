@extends('core::layouts.app')
@use('Modules\Proposal\Enums\ProposalStatusEnum')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')

@section('title', 'Danh sách báo giá')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="card-title">
                    <h4 class="mb-0">Danh sách báo giá</h4>
                </div>
                @can(PermissionEnum::PROPOSAL_CREATE)
                    <a href="{{ route('proposals.create') }}" class="btn btn-dark m-1"> <i class="bx bx-cloud-upload me-1"></i>Thêm
                        báo giá</a>
                @endcan
            </div>
            <hr />
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Mã báo giá</th>
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
                        @foreach ($proposals as $proposal)
                            <tr>
                                <td>{{ $proposal->code ?? '' }}</td>
                                <td>{{ $proposal->customer?->customer_type == CustomerTypeEnum::PERSONAL ? $proposal->customer?->first_name . ' ' . $proposal->customer?->last_name : $proposal->customer?->company_name ?? '' }}
                                </td>
                                <td>{{ $proposal->customer?->personInCharge?->full_name ?? '' }}
                                </td>
                                <td>{{ format_money($proposal->amount ?? 0) }}</td>
                                <td>{{ $proposal->expired_at ?? '' }}</td>
                                <td>
                                    @if ($proposal->files->count() > 0)
                                        <a title="Tải file" href="{{ route('proposals.download-files', $proposal->id) }}">
                                            <button type="button" class="btn btn-info  m-1">
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
                                    {{ ProposalStatusEnum::getStatusName($proposal->status ?? ProposalStatusEnum::NEW) }}
                                </td>
                                <td>
                                    @can(PermissionEnum::PROPOSAL_CONVERT_TO_ORDER)
                                        @if ($proposal->status == ProposalStatusEnum::NEW)
                                            <a onclick="confirmAction('{{ route('proposals.reject-redo', $proposal->id) }}', 'PUT', 'Bạn có chắc chắn yêu cầu làm lại không?')"
                                                title="Không duyệt, làm lại">
                                                <button type="button" class="btn bg-voilet m-1">
                                                    <i style="color: #FFF" class='bx bx-x'></i>
                                                </button>
                                            </a>
                                        @endif
                                    @endcan

                                    @can(PermissionEnum::PROPOSAL_CONVERT_TO_ORDER)
                                        @if ($proposal->status != ProposalStatusEnum::CONVER_TO_ORDER && $proposal->status != ProposalStatusEnum::REJECTED && $proposal->status != ProposalStatusEnum::CONVERT_TO_CONTRACT)
                                            <a onclick="approveProposal({{ $proposal->id }})"
                                                title="Duyệt báo giá">
                                                <button type="button" class="btn btn-success  m-1">
                                                    <i class='bx  bx-check'></i>
                                                </button>
                                            </a>
                                        @endif
                                    @endcan
                                    <a title="In Báo Giá" href="">
                                        <button type="button" class="btn btn-primary  m-1">
                                            <i class="bx bx bx-printer"></i>
                                        </button>
                                    </a>
                                    @can(PermissionEnum::PROPOSAL_SHOW)
                                        <a title="Xem chi tiết" href="{{ route('proposals.show', $proposal->id) }}">
                                            <button type="button" class="btn btn-info  m-1">
                                                <i class="bx bx-info-square"></i>
                                            </button>
                                        </a>
                                    @endcan
                                    @can(PermissionEnum::PROPOSAL_UPDATE)
                                        <a title="Cập nhật hồ sơ" href="{{ route('proposals.edit', $proposal->id) }}">
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

        function approveProposal(proposalId) {
            Swal.fire({
                title: 'Duyệt báo giá',
                text: 'Có tạo hợp đồng không?',
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Có, tạo hợp đồng',
                denyButtonText: 'Không, tạo đơn hàng',
                cancelButtonText: 'Huỷ',
                confirmButtonColor: '#3085d6',
                denyButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tạo hợp đồng
                    window.location.href = '{{ route("sell-contracts.create") }}?proposal_id=' + proposalId;
                } else if (result.isDenied) {
                    // Tạo đơn hàng
                    window.location.href = '{{ route("sell-orders.create") }}?proposal_id=' + proposalId;
                }
            });
        }
    </script>
@endpush
