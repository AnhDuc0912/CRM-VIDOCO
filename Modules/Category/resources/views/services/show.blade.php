@extends('core::layouts.app')
@use('Modules\Category\Enums\PaymentTypeEnum')
@use('Modules\Category\Enums\PaymentPeriodEnum')
@use('Modules\Category\Enums\ServiceStatusEnum')
@use('Modules\Category\Enums\VATServiceEnum')

@section('title', 'Chi tiết dịch vụ')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
@endpush

@section('content')
 <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Quản lý Dịch Vụ</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi Tiết Dịch Vụ</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="user-profile-page">
        <div class="card radius-15">
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-lg-6">
                        <div class="card shadow-none border mb-0 radius-15">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Mã dịch vụ</label>
                                        <input type="text" class="form-control" value="{{ $service->code }}" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Danh mục</label>
                                        <input type="text" class="form-control" value="{{ $service->category?->name }}" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Tên dịch vụ</label>
                                        <input type="text" class="form-control" value="{{ $service->name }}" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Kỳ Thanh Toán</label>
                                        <input type="text" class="form-control" value="{{ PaymentTypeEnum::getLabel($service->payment_type) }}" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Thuế VAT</label>
                                        <input type="text" class="form-control" value="{{ VATServiceEnum::getLabel($service->vat) }}" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Trạng Thái</label>
                                        <input type="text" class="form-control" value="{{ ServiceStatusEnum::getLabel($service->status) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card shadow-none border mb-0 radius-15">
                            <div class="card-body">
                                <h5 class="mt-4 mb-3">Cấu hình Gói Và Giá</h5>
                                <p>{{ $service->products->isEmpty() ? 'Chưa có gói' : 'Giá chưa bao gồm VAT' }}</p>
                                <div class="row g-3">
                                    <div id="products-container">
                                        @foreach($service->products as $product)
                                            <div class="product-row row g-3 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label">Gói lựa chọn</label>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <input type="text" class="form-control" value="{{ PaymentPeriodEnum::getLabel($product->payment_period) }}" readonly>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control" value="{{ $product->package_period }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label">Đơn giá</label>
                                                    <input type="text" class="form-control" value="{{ format_money($product->price) }}" readonly>
                                                </div>
                                                <div class="col-2">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-4 text-center">
                    <div class="col-12">
                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-info">Chỉnh sửa</a>
                        <a href="{{ route('services.index') }}" class="btn btn-warning">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
