@extends('core::layouts.app')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')
@use('Modules\SellContract\Enums\SellContractStatusEnum')
@use('App\Helpers\FileHelper')

@section('title', 'Chi tiết hợp đồng bán hàng')

@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Quản lý hợp đồng bán hàng</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi Tiết Hợp Đồng Bán Hàng</li>
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
                            <label class="form-label">Mã báo giá</label>
                            <select class="single-select2 form-control" name="proposal_id" id="proposal_select" disabled>
                                <option value="">-- Chọn Mã Báo Giá --</option>
                                @foreach ($proposals as $proposal)
                                    <option value="{{ $proposal->id }}"
                                        {{ old('proposal_id', !empty($sellContract) ? $sellContract->proposal_id : '') == $proposal->id ? 'selected' : '' }}>
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
                                value="{{ old('expired_at', !empty($sellContract) ? $sellContract->expired_at : date('Y-m-d')) }}"
                                disabled>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Trạng thái</label>
                            <select class="single-select form-control" name="status" id="status_select" disabled>
                                @foreach (SellContractStatusEnum::getStatusOptions() as $status => $label)
                                    <option value="{{ $status }}"
                                        {{ $sellContract->status == $status ? 'selected' : '' }} disabled>
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
                        disabled>{{ old('note', !empty($sellContract) ? $sellContract->note : '') }}</textarea>
                </div>
            </div>

            <hr>

            <h5 class="mt-4 mb-3">Dịch vụ</h5>
            <div id="services-container">
                @if (!empty($sellContract) && $sellContract->services?->count() > 0)
                    @foreach ($sellContract->services as $key => $service)
                        <div class="product-row row g-3 mb-3">
                            <div class="col-5">
                                <label class="form-label required">Tên dịch vụ</label>
                                <input type="text" name="services[{{ $key }}][name]"
                                    class="form-control service-name" value="{{ $service->name ?? '' }}" disabled>
                            </div>
                            <div class="col-2">
                                <label class="form-label required">Số lượng</label>
                                <input type="number" name="services[{{ $key }}][quantity]"
                                    class="form-control service-quantity" value="{{ $service->quantity ?? '' }}"
                                    disabled>
                            </div>
                            <div class="col-3">
                                <label class="form-label required">Đơn giá</label>
                                <input type="text" name="services[{{ $key }}][price]"
                                    class="form-control service-price" value="{{ $service->price ?? '' }}" disabled>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="product-row row g-3 mb-3">
                        <div class="col-5">
                            <label class="form-label required">Tên dịch vụ</label>
                            <input type="text" name="services[0][name]" class="form-control service-name"
                                value="{{ old('services.0.name') }}" disabled>
                        </div>
                        <div class="col-2">
                            <label class="form-label required">Số lượng</label>
                            <input type="number" name="services[0][quantity]" class="form-control service-quantity"
                                value="{{ old('services.0.quantity') }}" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label required">Đơn giá</label>
                            <input type="text" name="services[0][price]" class="form-control service-price"
                                value="{{ old('services.0.price') }}" disabled>
                        </div>
                    </div>
                @endif
            </div>
            <hr>

            <h5 class="mt-4 mb-3">File báo Giá Đính Kèm</h5>

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="file-preview" id="filePreview">
                        @if (!empty($sellContract) && $sellContract->files?->count() > 0)
                            @foreach ($sellContract->files as $file)
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('modules/sellcontract/js/validation/sell-contract-validation.js') }}"></script>
    <script src="{{ asset('modules/sellcontract/js/format-helper.js') }}"></script>
    <script>
        // Khởi tạo service index với giá trị từ form component
        let serviceIndex = {{ !empty($sellContract) ? $sellContract->services?->count() : 1 }};
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
                                resetServices();
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

                // Preserve validation state before AJAX call
                const wasValid = !$(this).hasClass('is-invalid');

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

                            // Maintain validation state after customer data is loaded
                            if (wasValid && customerId) {
                                $('#customer_select').removeClass('is-invalid');
                                $('#customer_select').next('.select2-container').removeClass(
                                    'is-invalid');
                                $('#customer_select').next('.select2-container').find(
                                    '.select2-selection').removeClass('is-invalid');
                            }
                        }
                    });
                }
            });

            function resetForm() {
                $('#customer_select').val('').trigger('change');
                $('#status_select').val({{ SellContractStatusEnum::NEW }}).trigger('change');
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
