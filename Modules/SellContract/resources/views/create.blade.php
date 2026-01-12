@extends('core::layouts.app')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\SellContract\Enums\SellContractStatusEnum')

@section('title', 'Thêm hợp đồng bán hàng')

@section('content')

     <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý hợp đồng bán hàng</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tạo Hợp Đồng Bán Hàng</li>
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
            <form id="sell-contract-form" method="POST" action="{{ route('sell-contracts.store') }}"
                enctype="multipart/form-data">
                @csrf
                @include('sellcontract::components.form')

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
    <script src="{{ asset('modules/sellcontract/js/validation/sell-contract-validation.js') }}"></script>
    <script src="{{ asset('modules/sellcontract/js/format-helper.js') }}"></script>
    <script>
        let serviceIndex = {{ !empty($sellContract) ? $sellContract->services?->count() : 1 }};
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
                    resetServices();
                }
            });


            // Function để load services từ response
            function loadServicesFromResponse(services) {
                $('.service-row, .product-row').not(':first').remove();

                serviceIndex = Math.max(services.length, 1);

                services.forEach(function(service, index) {
                    console.log(service);
                    if (index === 0) {
                        $('input[name="services[0][category_id]"]').val(service.category_id || '');
                        $('input[name="services[0][service_id]"]').val(service.service_id || '');
                        $('input[name="services[0][product_id]"]').val(service.product_id || '');
                        $('input[name="services[0][quantity]"]').val(service.quantity || '');
                        $('input[name="services[0][price]"]').val(formatCurrency(service.price || 0));
                        $('input[name="services[0][total]"]').val(formatCurrency(service.total || 0));
                    } else {
                        const container = $('#services-container');
                        const currentRows = container.find('.service-row').length;
                        const newIndex = currentRows;
                        let categoryOptionsHtml = '<option value="">-- Chọn danh mục --</option>';
                        categoriesData.forEach(function(category) {
                            categoryOptionsHtml +=
                                `<option value="${category.id}">${category.name}</option>`;
                        });
                        const newRow = `
                                 <div class="service-row row g-3 mb-3">
                                    <div class="col-2">
                                        <label class="form-label required">Danh mục</label>
                                        <select name="services[${newIndex}][category_id]"
                                            class="form-control service-category select2-category"
                                            data-index="${newIndex}">
                                            ${categoryOptionsHtml}
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label required">Dịch vụ</label>
                                        <select name="services[${newIndex}][service_id]"
                                            class="form-control service-service select2-service"
                                            data-index="${newIndex}">
                                            <option value="">-- Chọn dịch vụ --</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label required">Gói</label>
                                        <select name="services[${newIndex}][product_id]"
                                            class="form-control service-product select2-product"
                                            data-index="${newIndex}">
                                            <option value="">-- Chọn gói --</option>
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <label class="form-label required">Số lượng</label>
                                        <input type="number" name="services[${newIndex}][quantity]"
                                            class="form-control service-quantity" value="1" min="1">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label required">Đơn giá</label>
                                        <input type="text" name="services[${newIndex}][price]"
                                            class="form-control service-price">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Thành tiền</label>
                                        <input type="text" name="services[${newIndex}][total]"
                                            class="form-control service-total" readonly>
                                    </div>
                                    <div class="col-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-service">
                                            <i class="bx bx-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                        $('#services-container').append(newRow);
                    }
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

            // Function để reset services
            function resetServices() {
                $('.service-row, .product-row').not(':first').remove();
                $('input[name="services[0][name]"]').val('');
                $('input[name="services[0][quantity]"]').val('');
                $('input[name="services[0][price]"]').val('');
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
