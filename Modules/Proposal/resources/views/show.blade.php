@extends('core::layouts.app')
@use('Modules\Proposal\Enums\ProposalStatusEnum')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('App\Helpers\FileHelper')

@section('title', 'Danh sách báo giá')

@section('content')
    <div class="card shadow-none border mb-0 radius-15">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-3">Thông tin chung</h4>
            </div>
            <hr />
            <div class="row g-3 mb-4">
                <div class="col-12 col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Khách Hàng</label>
                            <select class="single-select1 form-control" name="customer_id" id="customer_select" disabled>
                                <option value="{{ $proposal->customer?->id }}">
                                    {{ $proposal->customer?->customer_type == CustomerTypeEnum::PERSONAL ? $proposal->customer?->first_name . ' ' . $proposal->customer?->last_name : $proposal->customer?->company_name ?? '' }}
                                </option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Người Phụ Trách</label>
                            <input type="text" name="employee_id" id="employee_id"
                                value="{{ $proposal->customer?->personInCharge?->full_name ?? '' }}" class="form-control"
                                disabled>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Hạn Báo Giá</label>
                            <input type="date" name="expired_at" class="form-control"
                                value="{{ $proposal->expired_at ?? '' }}" disabled>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Trạng thái</label>
                            <select class="single-select form-control" name="status" id="status_select" disabled>
                                @foreach (ProposalStatusEnum::getStatusOptions() as $status => $label)
                                    <option value="{{ $status }}"
                                        {{ $proposal->status == $status ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="validationServer02" class="form-label">Email
                                chính</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ $proposal->customer?->email ?? '' }}" id="email" disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Chủ thể</label>
                            <input readonly type="text" name="name" id="customer_name"
                                value="{{ $proposal->customer?->customer_type == CustomerTypeEnum::PERSONAL ? $proposal->customer?->first_name . ' ' . $proposal->customer?->last_name : $proposal->customer?->company_name ?? '' }}"
                                class="form-control" disabled>
                        </div>
                        <div class="col-12">
                            <label for="validationServer02" class="form-label">Điện
                                thoại</label>
                            <input readonly type="text" name="phone" class="form-control" id="phone"
                                value="{{ $proposal->customer?->phone ?? '' }}" disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <input readonly type="text" name="address" id="address"
                                value="{{ $proposal->customer?->address ?? '' }}" class="form-control" disabled>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control" name="note" id="inputAddress" placeholder="Ghi chú..." rows="3" disabled>{{ $proposal->note ?? '' }}</textarea>
                </div>
            </div>

            <hr>

            <h5 class="mt-4 mb-3">Dịch vụ</h5>

            @if ($proposal->services->count() > 0)
                @foreach ($proposal->services as $service)
                    <div class="service-row row g-3 mb-3">
                        <div class="col-2">
                            <label class="form-label">Danh mục</label>
                            <input type="text" class="form-control"
                                value="{{ $service->category_id ? \Modules\Category\Models\Category::find($service->category_id)?->name : 'N/A' }}"
                                disabled>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Dịch vụ</label>
                            <input type="text" class="form-control"
                                value="{{ $service->service_id ? \Modules\Category\Models\CategoryService::find($service->service_id)?->name : $service->name ?? 'N/A' }}"
                                disabled>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Gói</label>
                            <input type="text" class="form-control"
                                value="{{ $service->product_id ? \Modules\Category\Models\CategoryServiceProduct::find($service->product_id)?->payment_period . ' tháng' : 'N/A' }}"
                                disabled>
                        </div>
                        <div class="col-1">
                            <label class="form-label">Số lượng</label>
                            <input type="number" class="form-control" value="{{ $service->quantity ?? '' }}" disabled>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Đơn giá</label>
                            <input type="text" class="form-control"
                                value="{{ !empty($service->price) ? number_format($service->price, 0, ',', '.') : '' }}"
                                disabled>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Thành tiền</label>
                            <input type="text" class="form-control"
                                value="{{ !empty($service->total) ? number_format($service->total, 0, ',', '.') : (!empty($service->price) && !empty($service->quantity) ? number_format($service->price * $service->quantity, 0, ',', '.') : '') }}"
                                disabled>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="">Không có dịch vụ</div>
            @endif

            <hr>

            <h5 class="mt-4 mb-3">File báo Giá Đính Kèm</h5>

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="file-preview" id="filePreview">
                        @foreach ($proposal->files as $file)
                            @if ($file->extension == 'jpeg' || $file->extension == 'png')
                                <div class="file-item">
                                    <div class="file-image">
                                        <img src="{{ FileHelper::getFileUrl($file->path) }}" alt="Preview">
                                    </div>
                                </div>
                            @else
                                <div class="file-item">
                                    <div class="file-image d-flex align-items-center justify-content-center">
                                        <div class="file-icon text-primary">
                                            {{ $file->extension ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
