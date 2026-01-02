@use('Modules\Customer\Enums\CustomerTypeEnum')
@extends('core::layouts.app')

@section('title', 'Chi tiết dịch vụ')

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dịch Vụ</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết dịch vụ</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                @if(!$orderService->status || $orderService->end_date < now())
                    <a href="{{ route('orders.renew', ['id' => $order->id, 'orderServiceId' => $orderService->id]) }}"
                       class="btn btn-success m-1">
                        <i class="bx bx-alarm-add me-1"></i>Gia Hạn dịch vụ
                    </a>
                @endif
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card shadow-none border mb-0 radius-15">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-12 col-lg-6">
                    <h5 class="mb-3">Thông tin dịch vụ</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Mã</label>
                            <input type="text" readonly value="{{ $order->code ?? '' }}" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Dịch vụ</label>
                            <input type="text" readonly value="{{ $orderService->service->name ?? '' }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tên miền - IP</label>
                            <input type="text" readonly value="{{ $orderService->domain ?? '' }}" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Thông tin dịch vụ</label>
                            <textarea readonly class="form-control" placeholder="Thông tin chi tiết dịch vụ" rows="3">{{ $orderService->notes ?? '' }}</textarea>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Ngày đăng ký</label>
                            <input type="text" readonly value="{{ $orderService->start_date ? \Carbon\Carbon::parse($orderService->start_date)->format('d/m/Y') : '' }}" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Ngày hết hạn</label>
                            <input type="text" readonly value="{{ $orderService->end_date ? \Carbon\Carbon::parse($orderService->end_date)->format('d/m/Y') : '' }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Kỳ thanh toán</label>
                            <input type="text" readonly value="{{ \Modules\Category\Enums\PaymentPeriodEnum::getLabel($orderService->service->payment_period ?? 1) }}" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Trạng Thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" disabled type="checkbox" id="flexSwitchCheckChecked" {{ $orderService->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexSwitchCheckChecked">Đang sử dụng</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Auto Email</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" disabled type="checkbox" id="flexSwitchCheckChecked2" {{ $orderService->auto_email ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexSwitchCheckChecked2">Tự động gửi email nhắc gia hạn</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <h5 class="mb-3">Thông tin Khách hàng</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Hình thức</label>
                            <input type="text" readonly value="{{ CustomerTypeEnum::getLabel($order->customer->customer_type) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email chính</label>
                            <input type="text" readonly value="{{ $order->customer->email ?? '' }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email Phụ</label>
                            <input type="text" readonly value="{{ $order->customer->sub_email ?? '' }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tên Chủ thể</label>
                            <input type="text" readonly value="{{ $order->customer->company_name ?? '' }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Điện thoại</label>
                            <input type="text" readonly value="{{ $order->customer->phone ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
