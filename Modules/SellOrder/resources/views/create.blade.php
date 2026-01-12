@extends('core::layouts.app')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\SellOrder\Enums\SellOrderStatusEnum')

@section('title', 'Thêm đơn hàng')

@section('content')


    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Quản lý đơn hàng</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thêm Đơn Hàng</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a class="btn btn-info" href="{{ route('customers.create') }}"><i class="bx bx-plus me-1"></i>Thêm khách
                hàng</a>
        </div>
    </div>


    <div class="card shadow-none border mb-0 radius-15">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-3">Thông tin chung</h4>


            </div>
            <hr />
            <form id="sell-order-form" method="POST" action="{{ route('sell-orders.store') }}"
                enctype="multipart/form-data">
                @csrf
                @include('sellorder::components.form')

                <div class="row g-3 mb-4 text-center">
                    <div class="col-12">
                        <button class="btn btn-info" type="submit" id="submit-btn">Lưu Dữ
                            Liệu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('modules/sellorder/js/validation/sell-order-validation.js') }}"></script>
    <script src="{{ asset('modules/sellorder/js/format-helper.js') }}"></script>
    <script>
        let serviceIndex = {{ !empty($sellOrder) ? $sellOrder->services?->count() : 1 }};
        let categoriesData = @json($categories);

        $(document).ready(function() {
            setTimeout(function() {
                $('#proposal_select').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    allowClear: false,
                    minimumResultsForSearch: -1,
                    language: {
                        noResults: function() {
                            return "Không tìm thấy kết quả";
                        }
                    }
                });

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
                    allowClear: false,
                    minimumResultsForSearch: -1,
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

            // Tự động chọn proposal nếu có proposalId từ URL
            @if(!empty($proposalId))
                setTimeout(function() {
                    $('#proposal_select').val({{ $proposalId }}).trigger('change');
                }, 300);
            @endif

            // Event handler cho proposal_select change
            $('#proposal_select').on('change', function() {
                var proposalId = $(this).val();
                if (proposalId != '') {
                    $.ajax({
                        url: `{{ route('proposals.ajax.show', ['id' => ':id']) }}`
                            .replace(':id', proposalId),
                        method: 'GET',
                        beforeSend: function() {
                            $('#submit-btn').prop('disabled', true);
                        },
                        success: function(response) {
                            $('#customer_select').val(response.customer_id).trigger('change');
                            $('#expired_at').val(response.expired_at);

                            if (response.services && response.services.length > 0) {
                                loadServicesFromResponse(response.services);
                            } else {
                                resetServices();
                            }

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

            // Event handler cho customer_select change
            $('#customer_select').on('change', function() {
                var customerId = $(this).val();

                // Preserve validation state before AJAX call
                const wasValid = !$(this).hasClass('is-invalid');

                if (customerId != '') {
                    $.ajax({
                        url: '{{ route('customers.ajax.show', ['id' => ':id']) }}'
                            .replace(':id', customerId),
                        type: 'GET',
                        beforeSend: function() {
                            $('#submit-btn').prop('disabled', true);
                        },
                        success: function(response) {
                            const type = response.customer_type;
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

                            // Maintain validation state after customer data is loaded
                            if (wasValid && customerId) {
                                $('#customer_select').removeClass('is-invalid');
                                $('#customer_select').next('.select2-container').removeClass(
                                    'is-invalid');
                                $('#customer_select').next('.select2-container').find(
                                    '.select2-selection').removeClass('is-invalid');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading customer:', error);
                            $('#submit-btn').prop('disabled', false);
                        }
                    });
                } else {
                    // Clear customer fields when no customer selected
                    $('#email').val('');
                    $('#phone').val('');
                    $('#address').val('');
                    $('#employee_id').val('');
                    $('#customer_name').val('');
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
                $('.service-row').not(':first').remove();

                serviceIndex = Math.max(services.length, 1);

                services.forEach(function(service, index) {
                    console.log(service);
                    if (index === 0) {
                        // Set values for first row
                        $('select[name="services[0][category_id]"]').val(service.category_id || '').trigger(
                            'change');

                        // Load services for category and set service value
                        setTimeout(function() {
                            loadServicesForCategory(service.category_id, 0, service.service_id);

                            // Load products for service and set product value
                            setTimeout(function() {
                                loadProductsForService(service.service_id, 0, service
                                    .product_id);

                                // Set quantity, price and total
                                $('input[name="services[0][quantity]"]').val(service
                                    .quantity || 1);
                                $('input[name="services[0][price]"]').val(formatCurrency(
                                    service.price || 0));
                                $('input[name="services[0][total]"]').val(formatCurrency(
                                    service.total || 0));
                            }, 200);
                        }, 100);
                    } else {
                        // Add new service row
                        $('#add-service').click();

                        // Set values for new row
                        setTimeout(function() {
                            const categorySelect = $(`.select2-category[data-index="${index}"]`);
                            const serviceSelect = $(`.select2-service[data-index="${index}"]`);
                            const productSelect = $(`.select2-product[data-index="${index}"]`);

                            // Set category value
                            categorySelect.val(service.category_id).trigger('change');

                            // Load services for the category and set service value
                            setTimeout(function() {
                                loadServicesForCategory(service.category_id, index, service
                                    .service_id);

                                // Load products for the service and set product value
                                setTimeout(function() {
                                    loadProductsForService(service.service_id,
                                        index, service.product_id);

                                    // Set quantity and price after product is set
                                    setTimeout(function() {
                                        const quantityInput = $(
                                            `.service-quantity[name*="[${index}]"]`
                                        );
                                        const priceInput = $(
                                            `.service-price[name*="[${index}]"]`
                                        );

                                        quantityInput.val(service
                                            .quantity || 1);

                                        // Price will be set automatically by product change event
                                        // But we can override if needed
                                        if (service.price) {
                                            priceInput.val(formatCurrency(
                                                service.price));
                                        }

                                        // Trigger calculation
                                        quantityInput.trigger('input');
                                    }, 100);
                                }, 200);
                            }, 100);
                        }, 300 * index); // Stagger the execution for multiple services
                    }
                });

                // Cập nhật serviceIndex sau khi load xong
                serviceIndex = Math.max($('.service-row').length, 1);

                // Cập nhật validation cho các service mới
                setTimeout(function() {
                    if (typeof addServiceValidation === 'function') {
                        addServiceValidation();
                    }
                }, 100);
            }

            function loadServicesForCategory(categoryId, index, selectedServiceId = null) {
                const serviceSelect = $(`.select2-service[data-index="${index}"]`);

                // Reset and populate services
                serviceSelect.empty().append('<option value="">-- Chọn dịch vụ --</option>');

                const category = categoriesData.find(cat => cat.id == categoryId);
                if (category && category.services) {
                    category.services.forEach(function(service) {
                        const selected = selectedServiceId && service.id == selectedServiceId ? 'selected' :
                            '';
                        serviceSelect.append(
                            `<option value="${service.id}" ${selected}>${service.name}</option>`);
                    });
                }

                // Update select2 display without triggering change event for edit mode
                if (selectedServiceId) {
                    serviceSelect.val(selectedServiceId).trigger('change.select2');
                }
            }

            // Helper function to load products for a service
            function loadProductsForService(serviceId, index, selectedProductId = null) {
                const productSelect = $(`.select2-product[data-index="${index}"]`);

                // Reset and populate products
                productSelect.empty().append('<option value="">-- Chọn gói --</option>');

                let foundService = null;
                categoriesData.forEach(category => {
                    if (category.services) {
                        const service = category.services.find(srv => srv.id == serviceId);
                        if (service) {
                            foundService = service;
                        }
                    }
                });

                if (foundService && foundService.products) {
                    foundService.products.forEach(function(product) {
                        const payment_period = product.payment_period == 1 ? 'Năm' : 'Tháng';
                        const productText =
                            `${product.payment_period} ${payment_period} - ${parseInt(product.price).toLocaleString('vi-VN')} VND`;
                        const selected = selectedProductId && product.id == selectedProductId ? 'selected' :
                            '';
                        productSelect.append(
                            `<option value="${product.id}" data-price="${product.price}" ${selected}>${productText}</option>`
                        );
                    });
                }

                // Update select2 display and set price for edit mode
                if (selectedProductId) {
                    productSelect.val(selectedProductId).trigger('change.select2');
                    // Set price from selected product
                    const selectedOption = productSelect.find('option:selected');
                    const price = selectedOption.data('price');
                    if (price) {
                        const priceInput = $(`.service-price[name*="[${index}]"]`);
                        const formattedPrice = parseInt(price).toLocaleString('vi-VN');
                        priceInput.val(formattedPrice);
                    }
                }
            }

            // Function để reset services
            function resetServices() {
                $('.service-row').not(':first').remove();
                $('select[name="services[0][category_id]"]').val('').trigger('change');
                $('select[name="services[0][service_id]"]').empty().append(
                    '<option value="">-- Chọn dịch vụ --</option>');
                $('select[name="services[0][product_id]"]').empty().append(
                    '<option value="">-- Chọn gói --</option>');
                $('input[name="services[0][quantity]"]').val('1');
                $('input[name="services[0][price]"]').val('');
                $('input[name="services[0][total]"]').val('');
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
