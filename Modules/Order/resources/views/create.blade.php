@extends('core::layouts.app')

@section('title', 'Thêm dịch vụ khách hàng')

@section('content')
    <div class="card shadow-none border mb-0 radius-15">
        <div class="card-body">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="row mb-4">
                    <div class="col-12 col-lg-6">
                        <h5 class="mb-3">Thông tin dịch vụ</h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Loại dịch vụ</label>
                                <select name="category_id" class="single-select w-100" id="category-select"
                                    data-placeholder="Chọn danh mục" data-allow-clear="true">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                                            {{ $loop->first && !old('category_id') ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Dịch vụ</label>
                                <select name="service_id" class="multiple-select w-100" id="service-select"
                                    data-placeholder="Chọn dịch vụ" data-allow-clear="true" disabled>
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tên miền - IP</label>
                                <input type="text" name="domain"
                                    class="form-control @error('domain') is-invalid @enderror" value="{{ old('domain') }}"
                                    placeholder="Nhập tên miền hoặc IP">
                                @error('domain')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Gói - Giá Tiền</label>
                                <select name="product_id" class="single-select w-100" id="product-select"
                                    data-placeholder="Chọn gói" data-allow-clear="true" disabled>
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Thông tin dịch vụ</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Ngày đăng ký</label>
                                <input type="date" name="start_date"
                                    class="form-control"
                                    value="{{ old('start_date', date('Y-m-d')) }}"
                                    >
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Ngày hết hạn</label>
                                <input type="date" name="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Auto Email</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="auto_email" id="auto-email"
                                        value="1" checked>
                                    <label class="form-check-label" for="auto-email">Tự động gửi email nhắc gia hạn</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="single-select w-100 @error('status') is-invalid @enderror">
                                    <option value="">-- Lựa chọn --</option>
                                    <option value="1" selected>Đang sử dụng</option>
                                    <option value="0">Hết hạn</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <h5 class="mb-3">Thông tin Khách hàng</h5>
                        <div class="row g-3">
                            <div class="col-7">
                                <label class="form-label">Mã Khách Hàng</label>
                                <select name="customer_id" class="single-select w-100" id="customer-select"
                                    data-allow-clear="true">
                                    <option value="">-- Tìm kiếm khách hàng --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" data-email="{{ $customer->email }}"
                                            data-sub-email="{{ $customer->sub_email }}"
                                            data-first-name="{{ $customer->first_name }}"
                                            data-last-name="{{ $customer->last_name }}"
                                            data-company-name="{{ $customer->company_name }}"
                                            data-phone="{{ $customer->phone }}"
                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->code }} -
                                            {{ !empty($customer->first_name) ? $customer->first_name . ' ' . $customer->last_name : $customer->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-5 d-flex align-items-end">
                                <a href="{{ route('customers.create') }}" class="btn btn-primary">Thêm nhanh khách hàng</a>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email chính</label>
                                <input type="text" name="email" id="customer-email" readonly
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Phụ</label>
                                <input type="text" name="sub_email" id="customer-sub-email" readonly
                                    class="form-control @error('sub_email') is-invalid @enderror"
                                    value="{{ old('sub_email') }}">
                                @error('sub_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tên Chủ thể</label>
                                <input type="text" name="full_name" id="customer-full-name" readonly
                                    class="form-control @error('full_name') is-invalid @enderror"
                                    value="{{ old('full_name') }}">
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" name="phone" id="customer-phone" readonly
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4 text-center">
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Lưu Dữ Liệu</button>
                        <a href="{{ route('orders.active') }}" class="btn btn-danger">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="{{ asset('modules/order/js/validation/order-validation.js') }}"></script>
    <script src="{{ asset('modules/category/js/package-period.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.single-select').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: Boolean($(this).data('allow-clear')),
            });
            $('.multiple-select').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: Boolean($(this).data('allow-clear')),
            });

            let allServices = {};
            let allProducts = {};
            @foreach ($categories as $category)
                allServices[{{ $category->id }}] = [
                    @foreach ($category->services as $service)
                        {
                            id: {{ $service->id }},
                            name: "{{ addslashes($service->name) }}",
                            products: @json($service->products)
                        },
                    @endforeach
                ];
                @foreach ($category->services as $service)
                    allProducts[{{ $service->id }}] = @json($service->products);
                @endforeach
            @endforeach

            function loadServicesByCategory(categoryId) {
                let $serviceSelect = $('#service-select');
                $serviceSelect.empty();
                if (categoryId && allServices[categoryId]) {
                    allServices[categoryId].forEach(function(service) {
                        $serviceSelect.append('<option value="' + service.id + '">' + service.name +
                            '</option>');
                    });
                    $serviceSelect.prop('disabled', false);
                    if (allServices[categoryId].length > 0) {
                        let firstServiceId = allServices[categoryId][0].id;
                        $serviceSelect.val(firstServiceId).trigger('change');
                        loadProductsByService(firstServiceId);
                    }
                } else {
                    $serviceSelect.prop('disabled', true);
                }
                $serviceSelect.select2('destroy').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Chọn dịch vụ',
                    allowClear: Boolean($serviceSelect.data('allow-clear')),
                });
            }

            let initialCategoryId = $('#category-select').val();
            if (initialCategoryId) {
                loadServicesByCategory(initialCategoryId);
            }

            $('#category-select').on('change', function() {
                let categoryId = $(this).val();
                loadServicesByCategory(categoryId);
            });

            function loadProductsByService(serviceId) {
                let $productSelect = $('#product-select');
                $productSelect.empty();

                if (serviceId && allProducts[serviceId]) {
                    allProducts[serviceId].forEach(function(product) {
                        let optionText = product.package_period + ' ' + PaymentPeriodEnum.getLabel(product.payment_period) + ' - ' +
                            Number(product.price).toLocaleString() + 'đ';
                        $productSelect.append('<option value="' + product.id + '">' + optionText +
                            '</option>');
                    });
                    $productSelect.prop('disabled', false);
                    if (allProducts[serviceId].length > 0) {
                        let firstProduct = allProducts[serviceId][0];
                        let firstProductId = firstProduct.id;
                        if (firstProductId !== undefined) {
                            $productSelect.val(firstProductId);
                        }
                    }
                } else {
                    $productSelect.prop('disabled', true);
                }
                $productSelect.select2('destroy').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    allowClear: Boolean($productSelect.data('allow-clear')),
                });
                if (serviceId && allProducts[serviceId] && allProducts[serviceId].length > 0) {
                    let firstProduct = allProducts[serviceId][0];
                    let firstProductId = firstProduct.id;
                    if (firstProductId !== undefined) {
                        $productSelect.val(firstProductId).trigger('change');
                    }
                }
            }

            $('#service-select').on('change', function() {
                let serviceId = $(this).val();
                loadProductsByService(serviceId);
            });

            function calculateExpirationDate(packagePeriodValue, paymentPeriodValue, startDate = null) {
                const today = startDate ? new Date(startDate) : new Date();
                let expirationDate = new Date(today);

                const period = parseInt(packagePeriodValue) || 1;

                if (paymentPeriodValue == PaymentPeriodEnum.MONTH) {
                    expirationDate.setMonth(today.getMonth() + period);
                } else if (paymentPeriodValue == PaymentPeriodEnum.YEAR) {
                    expirationDate.setFullYear(today.getFullYear() + period);
                } else {
                    expirationDate.setMonth(today.getMonth() + 1);
                }

                return expirationDate.toISOString().split('T')[0];
            }

            $('#product-select').on('change', function() {
                let productId = $(this).val();
                let startDate = $('input[name="start_date"]').val();

                if (productId && allProducts) {
                    let selectedProduct = null;
                    for (let serviceId in allProducts) {
                        let product = allProducts[serviceId].find(p => p.id == productId);
                        if (product) {
                            selectedProduct = product;
                            break;
                        }
                    }

                    if (selectedProduct) {
                        let endDate = calculateExpirationDate(selectedProduct.package_period, selectedProduct.payment_period, startDate);
                        $('input[name="end_date"]').val(endDate);
                    }
                }
            });

            $('input[name="start_date"]').on('change', function() {
                let startDate = $(this).val();
                let endDateInput = $('input[name="end_date"]');

                endDateInput.attr('min', startDate);

                if (endDateInput.val() && endDateInput.val() < startDate) {
                    endDateInput.val(startDate);
                }

                let productId = $('#product-select').val();
                if (productId && allProducts) {
                    let selectedProduct = null;
                    for (let serviceId in allProducts) {
                        let product = allProducts[serviceId].find(p => p.id == productId);
                        if (product) {
                            selectedProduct = product;
                            break;
                        }
                    }

                    if (selectedProduct) {
                        let endDate = calculateExpirationDate(selectedProduct.package_period, selectedProduct.payment_period, startDate);
                        endDateInput.val(endDate);
                    }
                }
            });

            let $customerSelect = $('#customer-select');
            $customerSelect.on('change', function() {
                let selectedOption = $(this).find('option:selected');

                if (selectedOption.val()) {
                    $('#customer-email').val(selectedOption.data('email'));
                    $('#customer-sub-email').val(selectedOption.data('sub-email'));
                    if (selectedOption.data('first-name') && selectedOption.data('last-name')) {
                        $('#customer-full-name').val(selectedOption.data('first-name') + ' ' +
                            selectedOption
                            .data('last-name'));
                    } else {
                        $('#customer-full-name').val(selectedOption.data('company-name'));
                    }
                    $('#customer-phone').val(selectedOption.data('phone'));

                    // Disable validation for auto-filled fields and reset validation state
                    $('#customer-email, #customer-full-name, #customer-phone').addClass('no-validate')
                        .removeClass('is-invalid is-valid');
                    $('#customer-sub-email').removeClass('is-invalid is-valid');

                    // Hide error messages for auto-filled fields
                    $('#customer-email, #customer-full-name, #customer-phone').siblings('.invalid-feedback')
                        .hide();
                } else {
                    $('#customer-email').val('');
                    $('#customer-sub-email').val('');
                    $('#customer-full-name').val('');
                    $('#customer-phone').val('');

                    $('#customer-email, #customer-sub-email, #customer-full-name, #customer-phone')
                        .removeClass('no-validate');
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .select2-container {
            display: block !important;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: 38px !important;
            display: flex;
            align-items: center;
        }

        .select2-container--bootstrap4 .select2-selection__rendered {
            line-height: 38px !important;
            padding-left: 0.75rem !important;
        }

        .select2-container--bootstrap4 .select2-selection {
            min-height: 38px !important;
            font-size: 1rem !important;
            border-radius: 0.25rem !important;
        }
    </style>
@endpush
