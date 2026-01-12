@extends('core::layouts.app')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\SellOrder\Enums\SellOrderStatusEnum')

@section('title', 'Cập nhật đơn bán hàng')

@section('content')
   <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý đơn hàng</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chỉnh Sửa Đơn Hàng</li>
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
            <form id="sell-order-form" method="POST" action="{{ route('sell-orders.update', $sellOrder->id) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                @include('sellorder::components.form')

                <div class="row g-3 mb-4 text-center">
                    <div class="col-12">
                        <button class="btn btn-info" type="submit" id="submit-btn">Cập nhật</button>
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
        let existingServices = @json($sellOrder->services ?? []);
        let hasExistingServices = existingServices && existingServices.length > 0;
        let sellOrderData = @json($sellOrder ?? null);

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

                // Load existing services data for edit mode
                if (hasExistingServices) {
                    setTimeout(function() {
                        loadExistingServicesData();
                    }, 500);
                }

                // Chỉ trigger change event nếu proposal đã được chọn và không có services hiện tại
                var selectedProposal = $('#proposal_select').val();
                if (selectedProposal && selectedProposal !== '' && !hasExistingServices) {
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

                    // Set the customer value if it exists in sell order
                    if (sellOrderData && sellOrderData.customer_id) {
                        $('#customer_select').val(sellOrderData.customer_id).trigger('change.select2');
                    }
                } catch (e) {
                    console.error('✗ Customer select error:', e);
                }

                try {
                    $('#status_select').select2(selectConfig);
                } catch (e) {
                    console.error('✗ Status select error:', e);
                }



                // Load customer data when customer is selected
                $('#customer_select').on('change', function() {
                    const customerId = $(this).val();
                    if (customerId) {
                        // Load customer data via AJAX
                        $.ajax({
                            url: `{{ route('customers.ajax.show', ['id' => ':id']) }}`
                                .replace(':id', customerId),
                            method: 'GET',
                            success: function(customer) {
                                console.log(customer);
                                $('#email').val(customer.email || '');
                                $('#phone').val(customer.phone || '');
                                $('#address').val(customer.address || '');
                                $('#employee_id').val(customer.person_in_charge
                                    ?.full_name || '');

                                // Set customer name based on type
                                const customerName = customer.customer_type ===
                                    'personal' ?
                                    `${customer.first_name} ${customer.last_name}`
                                    .trim() :
                                    customer.company_name || '';
                                $('#customer_name').val(customerName);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error loading customer data:', error);
                            }
                        });
                    } else {
                        // Clear customer fields
                        $('#email').val('');
                        $('#phone').val('');
                        $('#address').val('');
                        $('#employee_id').val('');
                        $('#customer_name').val('');
                    }
                });

                // Load customer data on page load - use existing sell order data first
                if (sellOrderData && sellOrderData.customer) {
                    const customer = sellOrderData.customer;
                    $('#email').val(customer.email || '');
                    $('#phone').val(customer.phone || '');
                    $('#address').val(customer.address || '');
                    $('#employee_id').val(customer.person_in_charge
                        ?.full_name || '');

                    // Set customer name based on type
                    const customerName = customer.customer_type === 'personal' ?
                        `${customer.first_name || ''} ${customer.last_name || ''}`.trim() :
                        customer.company_name || '';
                    $('#customer_name').val(customerName);
                } else {
                    // Fallback to AJAX if customer data not available
                    const selectedCustomer = $('#customer_select').val();
                    if (selectedCustomer) {
                        $('#customer_select').trigger('change');
                    }
                }
            }, 200);

            // Function để load existing services data for edit mode
            function loadExistingServicesData() {
                // Use the existing services data from the sell order
                existingServices.forEach(function(service, index) {
                    const categorySelect = $(`.select2-category[data-index="${index}"]`);
                    const serviceSelect = $(`.select2-service[data-index="${index}"]`);
                    const productSelect = $(`.select2-product[data-index="${index}"]`);

                    // If selects don't exist with data-index, try finding by name attribute
                    if (categorySelect.length === 0) {
                        const categorySelectByName = $(`select[name="services[${index}][category_id]"]`);
                        const serviceSelectByName = $(`select[name="services[${index}][service_id]"]`);
                        const productSelectByName = $(`select[name="services[${index}][product_id]"]`);

                        if (categorySelectByName.length > 0) {
                            loadServicesForCategoryByName(service.category_id, index, service.service_id);
                            setTimeout(function() {
                                loadProductsForServiceByName(service.service_id, index, service
                                    .product_id);
                            }, 100);
                        }
                    } else {
                        // Load services for selected category
                        if (service.category_id) {
                            loadServicesForCategory(service.category_id, index, service.service_id);

                            // Load products for selected service
                            if (service.service_id) {
                                setTimeout(function() {
                                    loadProductsForService(service.service_id, index, service
                                        .product_id);
                                }, 100);
                            }
                        }
                    }
                });
            }

            // Helper function to load services by name attribute
            function loadServicesForCategoryByName(categoryId, index, selectedServiceId = null) {
                const serviceSelect = $(`select[name="services[${index}][service_id]"]`);

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
            }

            // Helper function to load products by name attribute
            function loadProductsForServiceByName(serviceId, index, selectedProductId = null) {
                const productSelect = $(`select[name="services[${index}][product_id]"]`);

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

                // For edit mode, set the actual saved price, quantity and total
                if (selectedProductId) {
                    const existingService = existingServices[index];
                    if (existingService) {
                        // Set price
                        if (existingService.price) {
                            const priceInput = $(`input[name="services[${index}][price]"]`);
                            const formattedPrice = parseInt(existingService.price).toLocaleString('vi-VN');
                            priceInput.val(formattedPrice);
                        }

                        // Set quantity
                        if (existingService.quantity) {
                            const quantityInput = $(`input[name="services[${index}][quantity]"]`);
                            quantityInput.val(existingService.quantity);
                        }

                        // Set total
                        if (existingService.total) {
                            const totalInput = $(`input[name="services[${index}][total]"]`);
                            const formattedTotal = parseInt(existingService.total).toLocaleString('vi-VN');
                            totalInput.val(formattedTotal);
                        }
                    }
                }
            }

            // Helper function to load services for a category
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

                // Update select2 display
                serviceSelect.trigger('change.select2');
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

                // Update select2 display and set price, quantity, total for edit mode
                if (selectedProductId) {
                    productSelect.val(selectedProductId).trigger('change.select2');

                    // For edit mode, use the actual saved values instead of product default values
                    const existingService = existingServices[index];
                    if (existingService) {
                        // Set price
                        if (existingService.price) {
                            const priceInput = $(`.service-price[name*="[${index}]"]`);
                            const formattedPrice = parseInt(existingService.price).toLocaleString('vi-VN');
                            priceInput.val(formattedPrice);
                        } else {
                            // Fallback to product default price if no saved price
                            const selectedOption = productSelect.find('option:selected');
                            const price = selectedOption.data('price');
                            if (price) {
                                const priceInput = $(`.service-price[name*="[${index}]"]`);
                                const formattedPrice = parseInt(price).toLocaleString('vi-VN');
                                priceInput.val(formattedPrice);
                            }
                        }

                        // Set quantity
                        if (existingService.quantity) {
                            const quantityInput = $(`.service-quantity[name*="[${index}]"]`);
                            quantityInput.val(existingService.quantity);
                        }

                        // Set total
                        if (existingService.total) {
                            const totalInput = $(`.service-total[name*="[${index}]"]`);
                            const formattedTotal = parseInt(existingService.total).toLocaleString('vi-VN');
                            totalInput.val(formattedTotal);
                        }
                    }
                }
            }

            $('#proposal_select').on('change', function() {
                var proposalId = $(this).val();
                if (proposalId != '') {
                    $.ajax({
                        url: '{{ route('proposals.ajax.show', ['id' => ':id']) }}'
                            .replace(':id', proposalId),
                        type: 'GET',
                        beforeSend: function() {
                            $('#submit-btn').prop('disabled', true);
                        },
                        success: function(response) {
                            $('#customer_select').val(response.customer_id).trigger('change');
                            $('#expired_at').val(response.expired_at);

                            if (response.services && response.services.length > 0) {
                                // Reset services first
                                $('#services-container .service-row').remove();

                                // Create service rows for each service in the proposal
                                response.services.forEach(function(service, index) {
                                    // Add new service row
                                    $('#add-service').click();

                                    // Set category and service values
                                    setTimeout(function() {
                                        const categorySelect = $(
                                            `.select2-category[data-index="${index}"]`
                                        );
                                        const serviceSelect = $(
                                            `.select2-service[data-index="${index}"]`
                                        );

                                        // Set category value
                                        categorySelect.val(service.category_id)
                                            .trigger('change');

                                        // Load services for the category and set service value
                                        setTimeout(function() {
                                            // Trigger category change to load services
                                            categorySelect.val(service
                                                    .category_id)
                                                .trigger('change');

                                            // Set service value after services are loaded
                                            setTimeout(function() {
                                                serviceSelect
                                                    .val(service
                                                        .service_id
                                                    )
                                                    .trigger(
                                                        'change'
                                                    );

                                                // Load products for the service and set product value
                                                setTimeout
                                                    (function() {
                                                        // Set product value after products are loaded
                                                        const
                                                            productSelect =
                                                            $(
                                                                `.select2-product[data-index="${index}"]`
                                                            );
                                                        productSelect
                                                            .val(
                                                                service
                                                                .product_id
                                                            )
                                                            .trigger(
                                                                'change'
                                                            );

                                                        // Set quantity and price after product is set
                                                        setTimeout
                                                            (function() {
                                                                    const
                                                                        quantityInput =
                                                                        $(
                                                                            `.service-quantity[name*="[${index}]"]`
                                                                        );
                                                                    const
                                                                        priceInput =
                                                                        $(
                                                                            `.service-price[name*="[${index}]"]`
                                                                        );

                                                                    quantityInput
                                                                        .val(
                                                                            service
                                                                            .quantity ||
                                                                            1
                                                                        );

                                                                    // Price will be set automatically by product change event
                                                                    // But we can override if needed
                                                                    if (service
                                                                        .price
                                                                    ) {
                                                                        priceInput
                                                                            .val(
                                                                                parseInt(
                                                                                    service
                                                                                    .price
                                                                                )
                                                                                .toLocaleString(
                                                                                    'vi-VN'
                                                                                )
                                                                            );
                                                                    }

                                                                    // Trigger calculation
                                                                    quantityInput
                                                                        .trigger(
                                                                            'input'
                                                                        );
                                                                },
                                                                100
                                                            );
                                                    }, 200);
                                            }, 200);
                                        }, 100);
                                    });
                                });
                            } else {
                                resetServices();
                            }

                            $('#submit-btn').prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            $('#submit-btn').prop('disabled', false);
                        }
                    });
                } else {
                    resetForm();
                }
            });

            function resetServices() {
                $('#services-container .service-row').remove();
                serviceIndex = 1;

                // Add one default service row
                $('#add-service').click();
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
        });
    </script>
@endpush
