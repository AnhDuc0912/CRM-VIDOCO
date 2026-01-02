@extends('core::layouts.app')
@use('Modules\Category\Enums\PaymentTypeEnum')
@use('Modules\Category\Enums\PaymentPeriodEnum')
@use('Modules\Category\Enums\ServiceStatusEnum')

@section('title', 'Quản lý dịch vụ')

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý Dịch Vụ</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh Sách Dịch Vụ</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="{{ route('services.create') }}" class="btn btn-secondary m-1">
                            <i class="bx bx-cloud-upload me-1"></i>Thêm dịch vụ
                        </a>
                    </div>
                </div>
            </div>

            <hr />

            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Mã Dịch Vụ</th>
                            <th>Dịch Vụ</th>
                            <th>Danh mục</th>
                            <th>Kỳ Thanh toán</th>
                            <th>Gói-Giá Bán</th>
                            <th>VAT</th>
                            <th>Trạng thái</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr>
                                <td>{{ $service->code ?? '' }}</td>
                                <td>{{ $service->name ?? '' }}</td>
                                <td>
                                    @if ($service->category)
                                        <a
                                            href="{{ route('categories.index', ['category_service' => $service->category->id]) }}">
                                            {{ $service->category->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Chưa phân loại</span>
                                    @endif
                                </td>
                                <td>{{ PaymentTypeEnum::getLabel(intval($service->payment_type)) ?? '' }}</td>
                                <td style="min-width: 250px;">
                                    @if ($service->products && $service->products->count() > 0)
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($service->products as $product)
                                                <li class="mb-1">
                                                    {{ $product->package_period . ' ' . PaymentPeriodEnum::getLabel($product->payment_period) ?? '' }}
                                                    | {{ format_money($product->price ?? 0) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Chưa có gói</span>
                                    @endif
                                </td>
                                <td>{{ $service->vat ?? 0 }}%</td>
                                <td>{{ ServiceStatusEnum::getLabel($service->status) }}</td>
                                <td>
                                    <a href="{{ route('services.show', $service->id) }}" title="Xem Chi tiết"
                                        class="btn btn-info m-1">
                                        <i class="bx bx-info-square"></i>
                                    </a>
                                    <a href="{{ route('services.edit', $service->id) }}" title="Cập nhật"
                                        class="btn btn-secondary m-1">
                                        <i class="bx bx-edit me-1"></i>
                                    </a>
                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger m-1" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
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
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            });
            table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
