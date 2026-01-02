@use('Modules\Proposal\Enums\ProposalStatusEnum')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('App\Helpers\FileHelper')
@use('Modules\Category\Enums\PaymentPeriodEnum')

<div class="row g-3 mb-4">
    <div class="col-12 col-lg-6">
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label required">Khách Hàng</label>
                <select class="single-select1 form-control" name="customer_id" id="customer_select">
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
                <input type="text" name="employee_id" id="employee_id" value="" class="form-control" disabled
                    disabled>
            </div>
            <div class="col-6">
                <label class="form-label required">Hạn Báo Giá</label>
                <input type="date" name="expired_at" class="form-control"
                    value="{{ old('expired_at', !empty($proposal) ? $proposal->expired_at : date('Y-m-d')) }}">
            </div>
            <div class="col-6">
                <label class="form-label required">Trạng thái</label>
                <select class="single-select form-control" name="status" id="status_select">
                    @foreach (ProposalStatusEnum::getStatusOptions() as $status => $label)
                        <option value="{{ $status }}"
                            {{ !empty($proposal) ? ($proposal->status == $status ? 'selected' : '') : '' }}>
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
                <input type="email" name="email" class="form-control" value="" id="email" disabled>
            </div>
            <div class="col-12">
                <label class="form-label">Chủ thể</label>
                <input readonly type="text" name="name" id="customer_name" value="" class="form-control"
                    disabled>
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
        <textarea class="form-control" name="note" id="inputAddress" placeholder="Ghi chú..." rows="3">{{ old('note', '') }}</textarea>
    </div>
</div>

<hr>

<h5 class="mt-4 mb-3">Dịch vụ</h5>
<div id="services-container">
    @if (!empty($proposal) && $proposal->services?->count() > 0)
        @foreach ($proposal->services as $key => $service)
            <div class="service-row row g-3 mb-3">
                <div class="col-2">
                    <label class="form-label required">Danh mục</label>
                    <select name="services[{{ $key }}][category_id]"
                        class="form-control service-category select2-category @error('services.{{ $key }}.category_id') is-invalid @enderror"
                        data-index="{{ $key }}">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('services.' . $key . '.category_id', $service->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('services.{{ $key }}.category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-2">
                    <label class="form-label required">Dịch vụ</label>
                    <select name="services[{{ $key }}][service_id]"
                        class="form-control service-service select2-service @error('services.{{ $key }}.service_id') is-invalid @enderror"
                        data-index="{{ $key }}">
                        <option value="">-- Chọn dịch vụ --</option>
                    </select>
                    @error('services.{{ $key }}.service_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-2">
                    <label class="form-label required">Gói</label>
                    <select name="services[{{ $key }}][product_id]"
                        class="form-control service-product select2-product @error('services.{{ $key }}.product_id') is-invalid @enderror"
                        data-index="{{ $key }}">
                        <option value="">-- Chọn gói --</option>
                    </select>
                    @error('services.{{ $key }}.product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-1">
                    <label class="form-label required">Số lượng</label>
                    <input type="number" name="services[{{ $key }}][quantity]"
                        class="form-control service-quantity @error('services.{{ $key }}.quantity') is-invalid @enderror"
                        value="{{ old('services.' . $key . '.quantity', $service->quantity ?? 1) }}" min="1">
                    @error('services.{{ $key }}.quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-2">
                    <label class="form-label required">Đơn giá</label>
                    <input type="text" name="services[{{ $key }}][price]"
                        class="form-control service-price @error('services.{{ $key }}.price') is-invalid @enderror"
                        value="{{ old('services.' . $key . '.price', $service->price ?? '') }}">
                    @error('services.{{ $key }}.price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-2">
                    <label class="form-label">Thành tiền</label>
                    <input type="text" name="services[{{ $key }}][total]"
                        class="form-control service-total"
                        value="{{ old('services.' . $key . '.total', !empty($service->total) ? number_format($service->total, 0, ',', '.') : '') }}"
                        readonly>
                </div>
                <div class="col-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-service">
                        <i class="bx bx-trash-alt"></i>
                    </button>
                </div>
            </div>
        @endforeach
    @else
        <div class="service-row row g-3 mb-3">

            <div class="col-2">
                <label class="form-label required">Danh mục</label>
                <select name="services[0][category_id]"
                    class="form-control service-category select2-category @error('services.0.category_id') is-invalid @enderror"
                    data-index="0">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('services.0.category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('services.0.category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-2">
                <label class="form-label required">Dịch vụ</label>
                <select name="services[0][service_id]"
                    class="form-control service-service select2-service @error('services.0.service_id') is-invalid @enderror"
                    data-index="0">
                    <option value="">-- Chọn dịch vụ --</option>
                </select>
                @error('services.0.service_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-2">
                <label class="form-label required">Gói</label>
                <select name="services[0][product_id]"
                    class="form-control service-product select2-product @error('services.0.product_id') is-invalid @enderror"
                    data-index="0">
                    <option value="">-- Chọn gói --</option>
                </select>
                @error('services.0.product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-1">
                <label class="form-label required">Số lượng</label>
                <input type="number" name="services[0][quantity]"
                    class="form-control service-quantity @error('services.0.quantity') is-invalid @enderror"
                    value="{{ old('services.0.quantity', 1) }}" min="1">
                @error('services.0.quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-2">
                <label class="form-label required">Đơn giá</label>
                <input type="text" name="services[0][price]"
                    class="form-control service-price @error('services.0.price') is-invalid @enderror"
                    value="{{ old('services.0.price') }}">
                @error('services.0.price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-2">
                <label class="form-label">Thành tiền</label>
                <input type="text" name="services[0][total]" class="form-control service-total" readonly>
            </div>
            <div class="col-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-service">
                    <i class="bx bx-trash-alt"></i>
                </button>
            </div>
        </div>
    @endif
</div>
<div class="col-12 text-center">
    <button type="button" class="btn btn-info" id="add-service">Thêm dịch vụ</button>
</div>

<hr>

<h5 class="mt-4 mb-3">File báo Giá Đính Kèm</h5>

<div class="row g-3 mb-4">
    <div class="col-12">
        <input id="fancy-file-upload" type="file" name="files[]"
            accept=".jpg, .png, image/jpeg, image/png, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx" multiple>
    </div>
    @if (request()->routeIs('proposals.edit'))
        <div class="file-preview" id="filePreview">
            @foreach ($proposal->files as $file)
                @if ($file->extension == 'jpeg' || $file->extension == 'png' || $file->extension == 'jpg')
                    <div class="file-item">
                        <div class="file-image">
                            <img src="{{ FileHelper::getFileUrl($file->path) }}" alt="Preview">
                        </div>
                        <a class="remove-btn" href="javascript:void(0)"
                            onclick="confirmDelete('{{ route('proposals.remove-file', ['id' => $proposal->id, 'fileId' => $file->id]) }}', 'Bạn có chắc chắn muốn xóa file này không?')">&times;</a>
                    </div>
                @else
                    <div class="file-item">
                        <div class="file-image d-flex align-items-center justify-content-center">
                            <div class="file-icon text-primary">
                                {{ $file->extension ?? '' }}
                            </div>
                        </div>
                        <a class="remove-btn" href="javascript:void(0)"
                            onclick="confirmDelete('{{ route('proposals.remove-file', ['id' => $proposal->id, 'fileId' => $file->id]) }}', 'Bạn có chắc chắn muốn xóa file này không?')">&times;</a>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
    <script>
        let serviceIndex = {{ !empty($proposal) ? $proposal->services?->count() : 1 }};

        $(document).ready(function() {
            // Prepare data from server-side
            const categoriesData = @json($categories);

            function initSelect2() {
                $('.select2-category').select2({
                    theme: 'bootstrap4',
                    placeholder: '-- Chọn danh mục --',
                    allowClear: false,
                    width: '100%'
                });

                $('.select2-service').select2({
                    theme: 'bootstrap4',
                    placeholder: '-- Chọn dịch vụ --',
                    allowClear: false,
                    width: '100%'
                });

                $('.select2-product').select2({
                    theme: 'bootstrap4',
                    placeholder: '-- Chọn gói --',
                    allowClear: false,
                    width: '100%'
                });
            }

            // Initialize select2 on page load
            initSelect2();

            // Load existing data for edit mode
            @if (!empty($proposal) && $proposal->services?->count() > 0)
                setTimeout(function() {
                    @foreach ($proposal->services as $key => $service)
                        @if (!empty($service->category_id))
                            loadServicesForCategory({{ $service->category_id }}, {{ $key }},
                                {{ $service->service_id ?? 'null' }});
                        @endif
                        @if (!empty($service->service_id))
                            setTimeout(function() {
                                loadProductsForService({{ $service->service_id }},
                                    {{ $key }}, {{ $service->product_id ?? 'null' }});
                            }, 100);
                        @endif
                        // Calculate total for existing service if not already set
                        @if (empty($service->total))
                            setTimeout(function() {
                                calculateTotal({{ $key }});
                            }, 200);
                        @endif
                    @endforeach
                }, 300);
            @endif

            // Handle category change - load services
            $(document).on('change', '.select2-category', function() {
                const categoryId = $(this).val();
                const index = $(this).data('index');
                const serviceSelect = $(`.select2-service[data-index="${index}"]`);
                const productSelect = $(`.select2-product[data-index="${index}"]`);

                // Reset dependent selects
                serviceSelect.empty().append('<option value="">-- Chọn dịch vụ --</option>');
                productSelect.empty().append('<option value="">-- Chọn gói --</option>');

                if (categoryId) {
                    // Find category in data
                    const category = categoriesData.find(cat => cat.id == categoryId);
                    if (category && category.services) {
                        category.services.forEach(function(service) {
                            serviceSelect.append(
                                `<option value="${service.id}">${service.name}</option>`);
                        });
                    }
                }

                // Reset price and total
                $(`.service-price[name*="[${index}]"]`).val('');
                calculateTotal(index);
            });

            // Handle service change - load products
            $(document).on('change', '.select2-service', function() {
                const serviceId = $(this).val();
                const index = $(this).data('index');
                const productSelect = $(`.select2-product[data-index="${index}"]`);

                // Reset product select
                productSelect.empty().append('<option value="">-- Chọn gói --</option>');

                if (serviceId) {
                    // Find service in data
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
                            productSelect.append(
                                `<option value="${product.id}" data-price="${product.price}">${productText}</option>`
                            );
                        });
                    }
                }

                // Reset price and total
                $(`.service-price[name*="[${index}]"]`).val('');
                calculateTotal(index);
            });

            // Handle product change - set price
            $(document).on('change', '.select2-product', function() {
                const productId = $(this).val();
                const index = $(this).data('index');
                const priceInput = $(`.service-price[name*="[${index}]"]`);

                if (productId) {
                    // Get price from data-price attribute
                    const selectedOption = $(this).find('option:selected');
                    const price = selectedOption.data('price');

                    if (price) {
                        const formattedPrice = parseInt(price).toLocaleString('vi-VN');
                        priceInput.val(formattedPrice);
                    }
                } else {
                    priceInput.val('');
                }

                calculateTotal(index);
            });

            // Handle quantity change
            $(document).on('input change', '.service-quantity', function() {
                const name = $(this).attr('name');
                const indexMatch = name.match(/\[(\d+)\]/);
                if (indexMatch) {
                    const index = indexMatch[1];
                    calculateTotal(index);
                }
            });

            // Handle price change - recalculate total when user manually edits price
            $(document).on('input change', '.service-price', function() {
                const name = $(this).attr('name');
                const indexMatch = name.match(/\[(\d+)\]/);
                if (indexMatch) {
                    const index = indexMatch[1];
                    calculateTotal(index);
                }
            });

            // Calculate total for a service row
            function calculateTotal(index) {
                const quantity = parseInt($(`.service-quantity[name*="[${index}]"]`).val()) || 0;
                const priceText = $(`.service-price[name*="[${index}]"]`).val() || '0';
                const price = parseInt(priceText.replace(/[^\d]/g, '')) || 0;

                const total = quantity * price;
                const formattedTotal = total.toLocaleString('vi-VN');
                $(`.service-total[name*="[${index}]"]`).val(formattedTotal);

                // Ensure format is maintained after calculation
                setTimeout(function() {
                    if (typeof window.restoreNumberFormat === 'function') {
                        window.restoreNumberFormat();
                    }
                }, 50);
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
                        const productText =
                            `${product.payment_period} tháng - ${parseInt(product.price).toLocaleString('vi-VN')} VND`;
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

            // Add new service row
            $('#add-service').click(function() {
                const container = $('#services-container');
                const currentRows = container.find('.service-row').length;
                const newIndex = currentRows;

                // Build category options from data
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

                container.append(newRow);

                // Initialize select2 for new row
                container.find('.service-row:last .select2-category').select2({
                    theme: 'bootstrap4',
                    placeholder: '-- Chọn danh mục --',
                    allowClear: false,
                    width: '100%'
                });

                container.find('.service-row:last .select2-service').select2({
                    theme: 'bootstrap4',
                    placeholder: '-- Chọn dịch vụ --',
                    allowClear: false,
                    width: '100%'
                });

                container.find('.service-row:last .select2-product').select2({
                    theme: 'bootstrap4',
                    placeholder: '-- Chọn gói --',
                    allowClear: false,
                    width: '100%'
                });

                // Add validation for new service if addServiceValidation function exists
                if (typeof window.addServiceValidation === 'function') {
                    window.addServiceValidation(newIndex);
                }

                // Trigger validation for new row immediately
                setTimeout(function() {
                    container.find('.service-row:last select, .service-row:last input').each(
                        function() {
                            if ($(this).hasClass('select2-hidden-accessible')) {
                                $(this).on('change.validate', function() {
                                    $('#proposal-form').validate().element($(this));
                                });
                            }
                        });
                }, 100);

                serviceIndex++;
            });

            // Remove service row
            $(document).on('click', '.remove-service', function() {
                const rows = $('#services-container .service-row');
                if (rows.length > 1) {
                    $(this).closest('.service-row').remove();
                } else {
                    alert('Phải có ít nhất một dịch vụ');
                }
            });
        });
    </script>
@endpush
