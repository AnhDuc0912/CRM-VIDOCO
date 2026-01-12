@extends('core::layouts.app')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\SellOrder\Enums\SellOrderStatusEnum')
@use('App\Helpers\FileHelper')

@section('title', 'Chi tiết đơn hàng')

@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Quản lý đơn hàng</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi Tiết Đơn Hàng</li>
                </ol>
            </nav>
        </div>
    </div>


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
                            <label class="form-label">Mã đơn hàng</label>
                            <select class="single-select2 form-control" name="proposal_id" id="proposal_select" disabled>
                                <option value="">-- Chọn Mã Báo Giá --</option>
                                @foreach ($proposals as $proposal)
                                    <option value="{{ $proposal->id }}"
                                        {{ old('proposal_id', !empty($sellOrder) ? $sellOrder->proposal_id : '') == $proposal->id ? 'selected' : '' }}>
                                        {{ $proposal->code }} |
                                        {{ $proposal->customer ? ($proposal->customer->customer_type == CustomerTypeEnum::PERSONAL ? $proposal->customer->first_name . ' ' . $proposal->customer->last_name : $proposal->customer->company_name ?? '') : 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Khách Hàng</label>
                            <select class="single-select1 form-control" name="customer_id" id="customer_select" disabled>
                                <option value="">-- Chọn Khách Hàng--</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->customer_type == CustomerTypeEnum::PERSONAL ? $customer->first_name . ' ' . $customer->last_name : $customer->company_name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Người Phụ Trách</label>
                            <input type="text" name="employee_id" id="employee_id" value="" class="form-control"
                                disabled disabled>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Hạn Hợp Đồng</label>
                            <input type="date" name="expired_at" class="form-control" id="expired_at"
                                value="{{ old('expired_at', !empty($sellOrder) ? $sellOrder->expired_at : date('Y-m-d')) }}"
                                disabled>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Trạng thái</label>
                            <select class="single-select form-control" name="status" id="status_select" disabled>
                                @foreach (SellOrderStatusEnum::getStatusOptions() as $status => $label)
                                    <option value="{{ $status }}"
                                        {{ old('status', SellOrderStatusEnum::CREATED) == $status ? 'selected' : '' }}
                                        disabled>
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
                            <input type="email" name="email" class="form-control" value="" id="email"
                                disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Chủ thể</label>
                            <input readonly type="text" name="name" id="customer_name" value=""
                                class="form-control" disabled>
                        </div>
                        <div class="col-12">
                            <label for="validationServer02" class="form-label">Điện
                                thoại</label>
                            <input readonly type="text" name="phone" class="form-control" id="phone" value=""
                                disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <input readonly type="text" name="address" id="address" value="" class="form-control"
                                disabled>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control" disabled name="note" id="inputAddress" placeholder="Ghi chú..." rows="3"
                        disabled>{{ old('note', !empty($sellOrder) ? $sellOrder->note : '') }}</textarea>
                </div>
            </div>

            <hr>

            <h5 class="mt-4 mb-3">Dịch vụ</h5>
            <div id="services-container">
                @if (!empty($sellOrder) && $sellOrder->services?->count() > 0)
                    @foreach ($sellOrder->services as $key => $service)
                        <div class="service-row row g-3 mb-3">
                            <div class="col-2">
                                <label class="form-label">Danh mục</label>
                                <select name="services[{{ $key }}][category_id]" class="form-control" disabled>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Dịch vụ</label>
                                <select name="services[{{ $key }}][service_id]" class="form-control" disabled>
                                    <option value="">-- Chọn dịch vụ --</option>
                                    @if ($service->category_id)
                                        @php
                                            $category = $categories->find($service->category_id);
                                        @endphp
                                        @if ($category && $category->services)
                                            @foreach ($category->services as $categoryService)
                                                <option value="{{ $categoryService->id }}"
                                                    {{ $service->service_id == $categoryService->id ? 'selected' : '' }}>
                                                    {{ $categoryService->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Gói</label>
                                <select name="services[{{ $key }}][product_id]" class="form-control" disabled>
                                    <option value="">-- Chọn gói --</option>
                                    @if ($service->service_id)
                                        @php
                                            $selectedService = null;
                                            foreach ($categories as $category) {
                                                if ($category->services) {
                                                    foreach ($category->services as $categoryService) {
                                                        if ($categoryService->id == $service->service_id) {
                                                            $selectedService = $categoryService;
                                                            break 2;
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if ($selectedService && $selectedService->products)
                                            @foreach ($selectedService->products as $product)
                                                @php
                                                    $payment_period = $product->payment_period == 1 ? 'Năm' : 'Tháng';
                                                    $productText =
                                                        $product->payment_period .
                                                        ' ' .
                                                        $payment_period .
                                                        ' - ' .
                                                        number_format($product->price, 0, ',', '.') .
                                                        ' VND';
                                                @endphp
                                                <option value="{{ $product->id }}"
                                                    {{ $service->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $productText }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                            </div>
                            <div class="col-1">
                                <label class="form-label">Số lượng</label>
                                <input type="number" name="services[{{ $key }}][quantity]"
                                    class="form-control service-quantity" value="{{ $service->quantity ?? '' }}"
                                    disabled>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Đơn giá</label>
                                <input type="text" name="services[{{ $key }}][price]"
                                    class="form-control service-price"
                                    value="{{ number_format($service->price ?? 0, 0, ',', '.') }}" disabled>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Thành tiền</label>
                                <input type="text" name="services[{{ $key }}][total]"
                                    class="form-control service-total"
                                    value="{{ number_format($service->total ?? 0, 0, ',', '.') }}" disabled>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="service-row row g-3 mb-3">
                        <div class="col-2">
                            <label class="form-label">Danh mục</label>
                            <select name="services[0][category_id]" class="form-control" disabled>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Dịch vụ</label>
                            <select name="services[0][service_id]" class="form-control" disabled>
                                <option value="">-- Chọn dịch vụ --</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Gói</label>
                            <select name="services[0][product_id]" class="form-control" disabled>
                                <option value="">-- Chọn gói --</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <label class="form-label">Số lượng</label>
                            <input type="number" name="services[0][quantity]" class="form-control service-quantity"
                                value="{{ old('services.0.quantity') }}" disabled>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Đơn giá</label>
                            <input type="text" name="services[0][price]" class="form-control service-price"
                                value="{{ old('services.0.price') }}" disabled>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Thành tiền</label>
                            <input type="text" name="services[0][total]" class="form-control service-total"
                                value="{{ old('services.0.total') }}" disabled>
                        </div>
                    </div>
                @endif
            </div>
            <hr>

            <h5 class="mt-4 mb-3">File báo Giá Đính Kèm</h5>

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="file-preview" id="filePreview">
                        @if (!empty($sellOrder) && $sellOrder->files?->count() > 0)
                            @foreach ($sellOrder->files as $file)
                                @if ($file->extension == 'jpeg' || $file->extension == 'png' || $file->extension == 'jpg')
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('modules/sellorder/js/validation/sell-order-validation.js') }}"></script>
    <script>
        // Khởi tạo service index với giá trị từ form component
        let serviceIndex = {{ !empty($sellOrder) ? $sellOrder->services?->count() : 1 }};
        let flagInitAjax = false;
        $(document).ready(function() {
            setTimeout(function() {
                $('#proposal_select').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    language: {
                        noResults: function() {
                            return "Không tìm thấy kết quả";
                        }
                    }
                });

                // Trigger change event nếu proposal đã được chọn khi load trang
                var selectedProposal = $('#proposal_select').val();
                console.log(selectedProposal);

                if (selectedProposal && selectedProposal !== '') {
                    $('#proposal_select').trigger('change');
                }

                $('#customer_select, #status_select')
                    .each(function() {
                        if ($(this).hasClass(
                                'select2-hidden-accessible')) {
                            $(this).select2('destroy');
                        }
                    });

                var selectConfig = {
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Chọn khách hàng',
                    language: {
                        noResults: function() {
                            return "Không tìm thấy kết quả";
                        }
                    }
                };

                try {
                    $('#customer_select').select2(selectConfig);
                } catch (e) {
                    console.error('✗ Customer select error:', e);
                }

                try {
                    $('#status_select').select2(selectConfig);
                } catch (e) {
                    console.error('✗ Status select error:', e);
                }
            }, 200);

            $('#proposal_select').on('change', function() {
                var proposalId = $(this).val();

                if (proposalId != '' && proposalId != null) {
                    $.ajax({
                        url: '{{ route('proposals.ajax.show', ['id' => ':id']) }}'
                            .replace(':id', proposalId),
                        type: 'GET',
                        beforeSend: function() {
                            $('#submit-btn').prop('disabled', true);
                        },
                        success: function(response) {
                            // Cập nhật customer
                            $('#customer_select').val(response.customer_id).trigger('change');

                            // Cập nhật expired_at
                            if (response.expired_at) {
                                $('#expired_at').val(response.expired_at);
                            }
                            // Load services
                            if (response.services && response.services.length > 0) {
                                if (flagInitAjax) {
                                    loadServicesFromResponse(response.services);
                                }
                            } else {
                                if (flagInitAjax) {
                                    resetServices();
                                }
                            }
                            flagInitAjax = true;

                            $('#submit-btn').prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading proposal:', error);
                            $('#submit-btn').prop('disabled', false);
                        }
                    });
                } else {
                    resetForm();
                }
            });

            $('#customer_select').on('change', function() {
                var customerId = $(this).val();
                if (customerId != '') {
                    $.ajax({
                        url: '{{ route('customers.ajax.show', ['id' => ':id']) }}'
                            .replace(':id', customerId),
                        type: 'GET',
                        beforeSend: function() {
                            $('#submit-btn').prop(
                                'disabled', true);
                        },
                        success: function(response) {
                            const type = response
                                .customer_type;
                            let customerName = '';

                            if (type == {{ CustomerTypeEnum::COMPANY }}) {
                                customerName = response.company_name ?? '';
                            } else {
                                customerName =
                                    `${response.first_name ?? ''} ${response.last_name ?? ''}`;
                            }

                            $('#email').val(response.email ?? '');
                            $('#phone').val(response.phone ?? '');
                            $('#address').val(response.address ?? '');
                            $('#employee_id').val(response.person_in_charge?.full_name ?? '');
                            $('#customer_name').val(customerName ?? '');
                            $('#submit-btn').prop('disabled', false);
                        }
                    });
                }
            });

            function resetForm() {
                $('#customer_select').val('').trigger('change');
                $('#status_select').val({{ SellOrderStatusEnum::CREATED }}).trigger('change');
                $('#email').val('');
                $('#phone').val('');
                $('#address').val('');
                $('#employee_id').val('');
                $('#customer_name').val('');
                $('#expired_at').val('');

                // Reset services
                resetServices();
            }

            // Function để load services từ response
            function loadServicesFromResponse(services) {
                // Xóa tất cả service rows hiện tại
                $('#services-container').empty();

                // Tạo lại container
                $('#services-container').html('');

                // Cập nhật service index dựa trên số lượng services
                serviceIndex = Math.max(services.length, 1);

                // Load từng service
                services.forEach(function(service, index) {

                    const newRow = `
                    <div class="product-row row g-3 mb-3">
                        <div class="col-5">
                            <label class="form-label required">Tên dịch vụ</label>
                            <input type="text" name="services[${index}][name]" class="form-control service-name" value="${service.name || ''}">
                        </div>
                        <div class="col-2">
                            <label class="form-label required">Số lượng</label>
                            <input type="number" name="services[${index}][quantity]" class="form-control service-quantity" value="${service.quantity || ''}">
                        </div>
                        <div class="col-3">
                            <label class="form-label required">Đơn giá</label>
                            <input type="text" name="services[${index}][price]" class="form-control service-price" value="${formatCurrency(service.price || 0)}">
                        </div>
                        <div class="col-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-service">
                                <i class="bx bx-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    `;

                    $('#services-container').append(newRow);
                });

                // Cập nhật serviceIndex sau khi load xong
                serviceIndex = Math.max($('.service-row, .product-row').length, 1);

                // Cập nhật validation cho các service mới
                setTimeout(function() {
                    if (typeof addServiceValidation === 'function') {
                        addServiceValidation();
                    }
                }, 100);
            }

            // Function để reset services
            function resetServices() {
                // Xóa tất cả service rows hiện tại
                $('#services-container').empty();

                // Tạo lại container với 1 row trống
                $('#services-container').html(`
                    <div class="product-row row g-3 mb-3">
                        <div class="col-5">
                            <label class="form-label required">Tên dịch vụ</label>
                            <input type="text" name="services[0][name]" class="form-control service-name" value="">
                        </div>
                        <div class="col-2">
                            <label class="form-label required">Số lượng</label>
                            <input type="number" name="services[0][quantity]" class="form-control service-quantity" value="">
                        </div>
                        <div class="col-3">
                            <label class="form-label required">Đơn giá</label>
                            <input type="text" name="services[0][price]" class="form-control service-price" value="">
                        </div>
                        <div class="col-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-service">
                                <i class="bx bx-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `);

                serviceIndex = 1; // Reset về 1 khi không có services
            }

            // Function để format currency
            function formatCurrency(amount) {
                if (!amount) return '';
                return parseInt(amount).toLocaleString('vi-VN');
            }

            // Function để cập nhật serviceIndex
            function updateServiceIndex() {
                serviceIndex = Math.max($('.service-row, .product-row').length, 1);
            }
        });
    </script>
@endpush
