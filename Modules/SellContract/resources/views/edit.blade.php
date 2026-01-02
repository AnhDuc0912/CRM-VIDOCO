@extends('core::layouts.app')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\SellContract\Enums\SellContractStatusEnum')

@section('title', 'Cập nhật hợp đồng bán hàng')

@section('content')

     <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý hợp đồng bán hàng</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chỉnh Sửa Hợp Đồng Bán Hàng</li>
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
            <form id="sell-contract-form" method="POST" action="{{ route('sell-contracts.update', $sellContract->id) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                @include('sellcontract::components.form')

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
    <script src="{{ asset('modules/sellcontract/js/validation/sell-contract-validation.js') }}"></script>
    <script src="{{ asset('modules/sellcontract/js/format-helper.js') }}"></script>
    <script>
        let serviceIndex = {{ !empty($sellContract) ? $sellContract->services?->count() : 1 }};
        let categoriesData = @json($categories);
        let existingServices = @json($sellContract->services ?? []);
        let hasExistingServices = existingServices && existingServices.length > 0;
        let sellContractData = @json($sellContract ?? null);

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

                    // Set the customer value if it exists in sell contract
                    if (sellContractData && sellContractData.customer_id) {
                        $('#customer_select').val(sellContractData.customer_id).trigger('change.select2');
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

                // Load customer data on page load - use existing sell contract data first
                if (sellContractData && sellContractData.customer) {
                    const customer = sellContractData.customer;
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
                // Use the existing services data from the sell contract
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

                // Update select2 display
                productSelect.trigger('change.select2');
            }

            // Function để reset services
            function resetServices() {
                $('.service-row, .product-row').not(':first').remove();
                $('input[name="services[0][category_id]"]').val('');
                $('input[name="services[0][service_id]"]').val('');
                $('input[name="services[0][product_id]"]').val('');
                $('input[name="services[0][quantity]"]').val('');
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
