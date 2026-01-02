@extends('core::layouts.app')
@use('Modules\Category\Enums\PaymentTypeEnum')

@section('title', 'Dịch vụ sắp hết hạn')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="card-title">
                    <h4 class="mb-0">Danh sách dịch vụ sắp hết hạn</h4>
                </div>
                <div class="btn-group">
                    <a href="{{ route('orders.create') }}" class="btn btn-dark m-1">
                        <i class="bx bx-cloud-upload me-1"></i>Thêm Dịch Vụ
                    </a>
                    <button type="button" class="btn btn-primary m-1">
                        <i class="bx bx-cloud-download me-1"></i>Xuất Excel
                    </button>
                </div>
            </div>
            <hr />
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                     <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Miền - IP</th>
                            <th>Thuộc Loại</th>
                            <th>Thông Tin Khách Hàng</th>
                            <th>Bắt Đầu | Kết thúc</th>
                            <th>Auto Email</th>
                            <th>Trạng thái</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            @foreach ($order->orderServices as $orderService)
                                   @php
                                    $endDate = $orderService->end_date
                                        ? \Carbon\Carbon::parse($orderService->end_date)
                                        : null;
                                    $now = \Carbon\Carbon::now();
                                    $thirtyDaysFromNow = $now->copy()->addDays(30);
                                @endphp
                                @if ($endDate && $endDate->isAfter($now) && $endDate->lte($thirtyDaysFromNow) && $orderService->status)
                                    <tr>
                                         <td>{{ $orderService->id }}</td>
                                         <td>{{ $orderService->domain ?? '' }}</td>
                                        <td>{{ $orderService->service->category->name ?? '' }}</td>
                                        <td> {{ $order->customer->company_name ?? '' }} - {{ $order->customer->phone }} </td>
                                        <td>
                                            S:
                                            {{ $orderService->start_date ? \Carbon\Carbon::parse($orderService->start_date)->format('d/m/Y') : '' }}
                                            <br>
                                            E:
                                            {{ $orderService->end_date ? \Carbon\Carbon::parse($orderService->end_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-center form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    {{ $orderService->status ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    {{ $orderService->auto_email ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($orderService->service->payment_type == 1)
                                                <a title="Gia hạn dịch vụ"
                                                    href="{{ route('orders.renew', ['id' => $order->id, 'orderServiceId' => $orderService->id]) }}">
                                                    <button type="button" class="btn btn-success m-1">
                                                        <i class="bx bx-alarm-add"></i>
                                                    </button>
                                                </a>
                                            @endif
                                            <a title="Xem chi tiết"
                                                href="{{ route('orders.show', ['id' => $order->id, 'orderServiceId' => $orderService->id]) }}">
                                                <button type="button" class="btn btn-info m-1">
                                                    <i class="bx bx-info-square"></i>
                                                </button>
                                            </a>
                                            <a title="Gửi Email Khách Hàng" href="#">
                                                <button type="button" class="btn btn-primary m-1">
                                                    <i class="bx bx-envelope"></i>
                                                </button>
                                            </a>
                                            <a title="In Hóa Đơn" href="#">
                                                <button type="button" class="btn btn-danger m-1">
                                                    <i class="bx bx-file"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có dịch vụ sắp hết hạn</td>
                            </tr>
                        @endforelse
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
