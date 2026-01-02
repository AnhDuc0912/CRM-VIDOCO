@extends('core::layouts.app')
@use('Modules\Category\Enums\PaymentPeriodEnum')

@section('title', 'Gia hạn dịch vụ')

@push('styles')
    <!-- Custom SmartWizard CSS -->
    <link href="{{ asset('assets/css/renew-order.css') }}" rel="stylesheet" type="text/css" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="card shadow-none border mb-0 radius-15">
        <div class="card-body">
            <!-- SmartWizard html -->
            <div id="smartwizard">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#step-1">
                            <strong>Bước 1</strong><br>Giỏ hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-2">
                            <strong>Bước 2</strong><br>Khách Hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-3">
                            <strong>Bước 3</strong><br>Xác Nhận Dịch Vụ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-4">
                            <strong>Bước 4</strong><br>Hoàn Tất Đơn Hàng
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                        <h5 class="mb-3">Gia hạn dịch vụ</h5>
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label">Dịch vụ</label>
                                <input type="hidden" name="service_id" value="{{ $orderService->service_id }}">
                                <input type="text" class="form-control" name="service_name"
                                    value="{{ $orderService->service->name ?? '' }}" readonly>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Kỳ Gia Hạn</label>
                                <select class="form-select product-select single-select" name="product_id"
                                    data-placeholder="Chọn gói">
                                    <option value="">-- Chọn gói --</option>
                                    @if($orderService->product)
                                        <option value="{{ $orderService->product->id }}" selected>
                                            {{ $orderService->product->package_period }} {{ PaymentPeriodEnum::getLabel($orderService->product->payment_period) }} - {{ format_money($orderService->product->price) }}đ
                                        </option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-8">
                                <label class="form-label">Tên miền</label>
                                <input type="text" class="form-control" name="domain"
                                    value="{{ $orderService->domain ?? '' }}" placeholder="Nhập tên miền">
                            </div>
                            <div class="col-8">
                                <label class="form-label">Ghi chú</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Nhập ghi chú">{{ $orderService->notes ?? '' }}</textarea>
                            </div>

                            <div id="services-container">
                                <!-- Additional services will be added here -->
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-danger radius-30 px-5" id="add-service-btn">
                                    <i class="bx bx-blanket me-1"></i>Thêm Dịch Vụ
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                        <h5 class="mb-3">Thông tin Khách hàng</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Hình thức</label>
                                <input type="text" readonly value="Cá nhân" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email chính</label>
                                <input type="text" readonly value="{{ $order->customer->email }}" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Phụ</label>
                                <input type="text" readonly value="{{ $order->customer->sub_email }}"
                                    class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tên Khách hàng</label>
                                <input type="text" readonly
                                    value="{{ $order->customer->first_name . ' ' . $order->customer->last_name }}"
                                    class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" readonly value="{{ $order->customer->phone }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                        <h5 class="mb-3">Thông tin khách hàng</h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Tên Khách hàng</label>
                                <input type="text" readonly
                                    value="{{ $order->customer->first_name . ' ' . $order->customer->last_name }}"
                                    class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Email chính</label>
                                <input type="text" readonly value="{{ $order->customer->email }}"
                                    class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" readonly value="{{ $order->customer->phone }}"
                                    class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" readonly value="{{ $order->customer->address }}"
                                    class="form-control">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Thông tin dịch vụ</h5>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">Tên dịch vụ</th>
                                        <th class="text-center">Loại đơn hàng</th>
                                        <th class="text-center">Ngày hết hạn</th>
                                        <th class="text-center">VAT</th>
                                        <th class="text-left">Số tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="js-services-table">
                                    <!-- Services will be loaded here dynamically from Step 1 -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2"><b>TỔNG CỘNG</b></td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2"><b>THUẾ VAT</b></td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2"><b>THANH TOÁN</b></td>
                                        <td>0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                        <div id="invoice">
                            <div class="invoice overflow-auto">
                                <div style="min-width: 600px">
                                    <header>
                                        <div class="row">
                                            <div class="col">
                                                <a href="javascript:;">
                                                    <img src="{{ asset('assets/images/logo-img.png') }}" width="220"
                                                        alt="" />
                                                </a>
                                            </div>
                                            <div class="col company-details">
                                                <h2 class="name">
                                                    <a target="_blank" href="javascript:;">VIDOCO AGENCY</a>
                                                </h2>
                                                <div>685 Âu Cơ, Phường Tân Phú, Hồ Chí Minh</div>
                                                <div>(028)73.027.720</div>
                                                <div>cskh@vidoco.vn</div>
                                            </div>
                                        </div>
                                    </header>
                                    <main>
                                        <div class="row contacts">
                                            <div class="col invoice-to">
                                                <div class="text-gray-light">Kính Gửi:</div>
                                                <h2 class="to">
                                                    {{ $order->customer->first_name . ' ' . $order->customer->last_name }}
                                                </h2>
                                                <div class="address">{{ $order->customer->address }}</div>
                                                <div class="email"><a
                                                        href="mailto:{{ $order->customer->email }}">{{ $order->customer->email }}</a>
                                                </div>
                                            </div>
                                            <div class="col invoice-details">
                                                <h1 class="invoice-id">ĐƠN HÀNG {{ $code }}</h1>
                                                <div class="date">Ngày khởi tạo:
                                                    {{ Carbon\Carbon::parse($order->created_at)->format('d/m/Y') ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="text-center">Tên dịch vụ</th>
                                                        <th class="text-center">Loại đơn hàng</th>
                                                        <th class="text-center">Ngày hết hạn</th>
                                                        <th class="text-center">VAT</th>
                                                        <th class="text-right" style="text-align: right;">Số tiền</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="js-services-table">
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <td colspan="2"><b>TỔNG CỘNG</b></td>
                                                        <td>0</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <td colspan="2"><b>THUẾ VAT</b></td>
                                                        <td>0</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <td colspan="2"><b>THANH TOÁN</b></td>
                                                        <td>0</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="thanks">Xin cảm ơn!</div>
                                        <div class="notices mb-3">
                                            <div>Ghi Chú:</div>
                                            <div class="notice" id="step4-notes"></div>
                                        </div>
                                        <div class="notices">
                                            <div>Thông tin thanh toán: Quý khách vui lòng chuyển khoản theo thông tin sau:
                                            </div>
                                            <div class="notice">
                                                <ul>
                                                    <li>Số Tài Khoản: 2314719999</li>
                                                    <li>Tên Tài Khoản: CÔNG TY CỔ PHẦN VIDOCO</li>
                                                    <li>Ngân Hàng: VIETCOMBANK</li>
                                                    <li>Chi Nhánh: Tân Bình</li>
                                                    <li>Nội dung chuyển khoản: Thanh toan don hang so#:20250722/VIDOCO</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </main>
                                    <footer>Đơn hàng được khởi tạo bởi CÔNG TY CỔ PHẦN VIDOCO</footer>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JavaScript -->
    <script src="{{ asset('assets/plugins/smart-wizard/js/jquery.smartWizard.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('modules/category/js/package-period.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize variables
            let serviceCounter = 0;
            let allProducts = {};
            const orderServices = @json($order->orderServices);

            // Initialize all products data
            @foreach ($services as $category)
                @foreach ($category->services as $service)
                    allProducts[{{ $service->id }}] = @json($service->products);
                @endforeach
            @endforeach

            // Initialize Select2 for main product
            $('.product-select[name="product_id"]').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Chọn gói',
                allowClear: true,
            });

            // Load products from orderService->product for the main service
            @if($orderService->product)
                const orderServiceProduct = @json($orderService->product);
                const orderServiceProducts = @json($orderService->product->service->products ?? []);
                if (orderServiceProducts && orderServiceProducts.length > 0) {
                    allProducts[{{ $orderService->service_id }}] = orderServiceProducts;
                }
            @endif

            // Load products by service
            function loadProductsByService(serviceId, serviceCounter) {
                let $productSelect;

                if (serviceCounter === 'main') {
                    $productSelect = $('.product-select[name="product_id"]');
                } else {
                    $productSelect = $(`.service-item[data-service-id="${serviceCounter}"] .product-select`);
                }

                $productSelect.empty().append('<option value="">-- Chọn gói --</option>');

                if (serviceId && allProducts[serviceId]) {
                    allProducts[serviceId].forEach(function(product) {
                        let optionText = product.package_period + ' ' + PaymentPeriodEnum.getLabel(product.payment_period) + ' - ' +
                            Number(product.price).toLocaleString() + 'đ';
                        $productSelect.append('<option value="' + product.id + '">' + optionText +
                            '</option>');
                    });
                    $productSelect.prop('disabled', false);
                } else {
                    $productSelect.prop('disabled', true);
                }

                $productSelect.select2('destroy').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Chọn gói',
                    allowClear: Boolean($productSelect.data('allow-clear')),
                });

                if (serviceCounter === 'main' && serviceId == {{ $orderService->service_id }}) {
                    @if($orderService->product)
                        const currentProductId = {{ $orderService->product->id }};
                        if (currentProductId) {
                            $productSelect.val(currentProductId).trigger('change');
                        }
                    @endif
                } else if (serviceId && allProducts[serviceId] && allProducts[serviceId].length > 0) {
                    let firstProduct = allProducts[serviceId][0];
                    let firstProductId = firstProduct.id;
                    if (firstProductId !== undefined) {
                        $productSelect.val(firstProductId).trigger('change');
                    }
                }
            }

            // Calculate expiration date
            function calculateExpirationDate(packagePeriodValue, paymentPeriodValue) {
                const today = new Date();
                let expirationDate = new Date(today);

                const period = parseInt(packagePeriodValue) || 1;

                if (paymentPeriodValue == PaymentPeriodEnum.MONTH) {
                    expirationDate.setMonth(today.getMonth() + period);
                } else if (paymentPeriodValue == PaymentPeriodEnum.YEAR) {
                    expirationDate.setFullYear(today.getFullYear() + period);
                } else {
                    expirationDate.setMonth(today.getMonth() + 1);
                }

                return expirationDate.toLocaleDateString('vi-VN');
            }

            function loadServices() {
                let currentStep = window.location.hash;
                if (currentStep === '#step-3') {
                    currentStep = 'step-3';
                } else if (currentStep === '#step-4') {
                    currentStep = 'step-4';
                } else {
                    currentStep = $('.tab-pane.active').attr('id');
                    if (!currentStep) {
                        currentStep = $('.sw-btn-active').closest('.sw-btn').data('step');
                    }
                    if (!currentStep) {
                        currentStep = $('.sw-btn-active').attr('id');
                    }
                }

                let $targetTableBody;

                if (currentStep === 'step-3' || currentStep === '3') {
                    $targetTableBody = $('#step-3 .js-services-table');
                } else if (currentStep === 'step-4' || currentStep === '4') {
                    $targetTableBody = $('#step-4 .js-services-table');
                } else {
                    $targetTableBody = $('#step-3 .js-services-table');
                }

                if ($targetTableBody.length === 0) {
                    console.error('Target table body not found!');
                    return;
                }

                $targetTableBody.empty();

                let rowNumber = 1;
                const mainServiceId = $('input[name="service_id"]').val();
                const mainProductId = $('.product-select[name="product_id"]').val();

                if (mainServiceId && mainProductId) {
                    const mainServiceName = $('input[name="service_name"]').val();
                    const product = allProducts[mainServiceId].find(p => p.id == mainProductId);

                    if (product) {
                        const price = product.price || 0;
                        const packagePeriod = product.package_period + ' ' + PaymentPeriodEnum.getLabel(product.payment_period);
                        const expirationDate = calculateExpirationDate(product.package_period, product.payment_period);

                        const mainOrderService = orderServices.find(os => os.service_id == mainServiceId);
                        const vat = mainOrderService && mainOrderService.service ? mainOrderService.service.vat : 5;
                        const categoryName = mainOrderService && mainOrderService.service && mainOrderService
                            .service.category ? mainOrderService.service.category.name : '';

                        const mainDomain = $('input[name="domain"]').val();
                        const mainRow = `
                            <tr>
                                <td class="no">${String(rowNumber).padStart(2, '0')}</td>
                                <td class="text-left">
                                    <h3><a target="_blank" href="javascript:;">${mainDomain}</a></h3>
                                    ${categoryName}
                                </td>
                                <td class="unit">Gia hạn dịch vụ</td>
                                <td class="qty">${expirationDate}</td>
                                <th class="text-center">${vat}%</th>
                                <td class="total">${Number(price).toLocaleString()}</td>
                            </tr>
                        `;
                        $targetTableBody.append(mainRow);
                        rowNumber++;
                    }
                }

                $('.service-item').each(function(index) {
                    const $serviceSelect = $(this).find('.service-select');
                    const $productSelect = $(this).find('.product-select');

                    if ($serviceSelect.val() && $productSelect.val()) {
                        const serviceName = $serviceSelect.find('option:selected').text();
                        const productId = $productSelect.val();
                        const product = allProducts[$serviceSelect.val()].find(p => p.id == productId);

                        if (product) {
                            const price = product.price || 0;
                            const packagePeriod = product.package_period + ' ' + PaymentPeriodEnum.getLabel(product.payment_period);
                            const expirationDate = calculateExpirationDate(product.package_period, product.payment_period);

                            const additionalOrderService = orderServices.find(os => os.service_id ==
                                $serviceSelect.val());
                            const vat = additionalOrderService && additionalOrderService.service ?
                                additionalOrderService.service.vat : 5;
                            const categoryName = additionalOrderService && additionalOrderService.service &&
                                additionalOrderService.service.category ? additionalOrderService.service
                                .category.name : '';

                            const additionalDomain = $(this).find('input[name*="domain"]').val();
                            const additionalRow = `
                                <tr>
                                    <td class="no">${String(rowNumber).padStart(2, '0')}</td>
                                    <td class="text-left">
                                        <h3><a target="_blank" href="javascript:;">${additionalDomain}</a></h3>
                                        ${categoryName}
                                    </td>
                                    <td class="unit">Đăng ký mới</td>
                                    <td class="qty">${expirationDate}</td>
                                    <th class="text-center">${vat}%</th>
                                    <td class="total">${Number(price).toLocaleString()}</td>
                                </tr>
                            `;
                            $targetTableBody.append(additionalRow);
                            rowNumber++;
                        }
                    }
                });

                updateTotals();
                loadNotesToStep4();
            }

            function updateTotals() {
                let currentStep = window.location.hash;
                if (currentStep === '#step-3') {
                    currentStep = 'step-3';
                } else if (currentStep === '#step-4') {
                    currentStep = 'step-4';
                } else {
                    currentStep = $('.tab-pane.active').attr('id');
                    if (!currentStep) {
                        currentStep = $('.sw-btn-active').closest('.sw-btn').data('step');
                    }
                    if (!currentStep) {
                        currentStep = $('.sw-btn-active').attr('id');
                    }
                }

                let $targetTableBody;
                if (currentStep === 'step-3' || currentStep === '3') {
                    $targetTableBody = $('#step-3 .js-services-table');
                } else if (currentStep === 'step-4' || currentStep === '4') {
                    $targetTableBody = $('#step-4 .js-services-table');
                } else {
                    $targetTableBody = $('#step-3 .js-services-table');
                }

                let total = 0;
                let totalVat = 0;

                const rows = $targetTableBody.find('tr');

                rows.each(function() {
                    const price = Number($(this).find('.total').text().replace(/,/g, '') || 0);
                    const vatPercent = Number($(this).find('th.text-center').text().replace('%', '') || 5);
                    const vatAmount = price * (vatPercent / 100);

                    total += price;
                    totalVat += vatAmount;
                });

                const finalTotal = total + totalVat;

                $('#step-3 tfoot tr:first td:last').text(total.toLocaleString()).show();
                $('#step-3 tfoot tr:nth-child(2) td:last').text(totalVat.toLocaleString()).show();
                $('#step-3 tfoot tr:last td:last').text(finalTotal.toLocaleString()).show();

                $('#step-4 tfoot tr:first td:last').text(total.toLocaleString()).show();
                $('#step-4 tfoot tr:nth-child(2) td:last').text(totalVat.toLocaleString()).show();
                $('#step-4 tfoot tr:last td:last').text(finalTotal.toLocaleString()).show();
            }

            function loadNotesToStep4() {
                let allNotes = [];

                const mainNotes = $('textarea[name="notes"]').val();
                const mainServiceName = $('input[name="service_name"]').val();
                const mainDomain = $('input[name="domain"]').val();

                if (mainNotes && mainServiceName) {
                    allNotes.push(`${mainServiceName} - ${mainDomain} - ${mainNotes}`);
                }

                $('.service-item').each(function(index) {
                    const $serviceSelect = $(this).find('.service-select');
                    const $notesTextarea = $(this).find('textarea[name*="notes"]');

                    if ($serviceSelect.val() && $notesTextarea.val()) {
                        const serviceName = $serviceSelect.find('option:selected').text();
                        const notes = $notesTextarea.val();
                        const domain = $(this).find('input[name*="domain"]').val();
                        allNotes.push(`${serviceName} - ${domain} - ${notes}`);
                    }
                });

                $('#step4-notes').html(allNotes.join('<br>'));
            }

            $(document).on('change', '.service-select', function() {
                const serviceId = $(this).val();
                const serviceCounter = $(this).data('service-counter');
                loadProductsByService(serviceId, serviceCounter);
            });

            $(document).on('change', '.service-select, .product-select', function() {
                setTimeout(function() {
                    loadServices();
                }, 100);
            });

            $(document).on('input', 'textarea[name="notes"]', function() {
                loadNotesToStep4();
            });

            setTimeout(function() {
                const selectedServiceId = $('input[name="service_id"]').val();
                if (selectedServiceId) {
                    loadProductsByService(selectedServiceId, 'main');
                }
            }, 100);

            $("#add-service-btn").on("click", function() {
                serviceCounter++;
                const serviceHtml = `
                    <div class="service-item" data-service-id="${serviceCounter}">
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label">Dịch vụ</label>
                                <select class="form-select service-select single-select" name="services[${serviceCounter}][service_id]" data-service-counter="${serviceCounter}" data-placeholder="Chọn dịch vụ">
                                    <option value="">-- Chọn dịch vụ --</option>
                                    @foreach ($services as $category)
                                        @foreach ($category->services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Kỳ Gia Hạn</label>
                                <select class="form-select product-select single-select" name="services[${serviceCounter}][product_id]" data-service-counter="${serviceCounter}" data-placeholder="Chọn gói" disabled style="padding-left: 0px;">
                                    <option value="">-- Chọn kỳ hạn --</option>
                                </select>
                            </div>
                            <div class="col-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger delete-service-btn" data-service-id="${serviceCounter}">
                                    <i class="bx bxs-trash-alt"></i> Xóa
                                </button>
                            </div>
                            <div class="col-8">
                                <label class="form-label">Tên miền</label>
                                <input type="text" class="form-control" name="services[${serviceCounter}][domain]" placeholder="Nhập tên miền">
                            </div>
                            <div class="col-8">
                                <label class="form-label">Ghi chú</label>
                                <textarea class="form-control" name="services[${serviceCounter}][notes]" placeholder="Ghi chú cho dịch vụ này" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                `;
                $("#services-container").append(serviceHtml);

                $(`.service-item[data-service-id="${serviceCounter}"] .single-select`).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: function() {
                        return $(this).data('placeholder');
                    },
                    allowClear: Boolean($(this).data('allow-clear')),
                });

                setTimeout(function() {
                    const servicesHeight = $("#services-container").height();
                    const addButtonHeight = $("#add-service-btn").outerHeight(true);
                    const currentStepHeight = $("#step-1").height();
                    const newServiceHeight = $(`.service-item[data-service-id="${serviceCounter}"]`)
                        .outerHeight(true);
                    const totalNewHeight = Math.max(currentStepHeight, servicesHeight +
                        addButtonHeight + newServiceHeight);

                    $("#step-1").animate({
                        'min-height': totalNewHeight + 'px',
                        'height': 'auto'
                    }, 400, 'swing');

                    $('.tab-content').animate({
                        'min-height': totalNewHeight + 50 + 'px',
                        'height': 'auto'
                    }, 400, 'swing');

                    $('.card-body').animate({
                        'min-height': totalNewHeight + 50 + 'px',
                        'height': 'auto'
                    }, 400, 'swing');

                    $('.smartwizard').animate({
                        'min-height': totalNewHeight + 100 + 'px',
                        'height': 'auto'
                    }, 400, 'swing');

                    const newServiceElement = $(`[data-service-id="${serviceCounter}"]`);
                    if (newServiceElement.length) {
                        const scrollPosition = newServiceElement.offset().top - 100;
                        $('html, body').animate({
                            scrollTop: scrollPosition
                        }, 500);
                    }
                }, 200);
            });

            $(document).on("click", ".delete-service-btn", function() {
                const serviceId = $(this).data('service-id');
                const $serviceElement = $(`.service-item[data-service-id="${serviceId}"]`);

                $serviceElement.fadeOut(300, function() {
                    $(this).remove();

                    $('#add-service-btn').show().css({
                        'display': 'block',
                        'visibility': 'visible',
                        'opacity': '1'
                    });

                    setTimeout(function() {
                        const servicesHeight = $("#services-container").height();
                        const addButtonHeight = $("#add-service-btn").outerHeight(true);
                        const currentStepHeight = $("#step-1").height();
                        const deletedServiceHeight = $(this).closest('.service-item')
                            .outerHeight(true);
                        const totalNewHeight = Math.max(currentStepHeight, servicesHeight -
                            deletedServiceHeight);

                        $("#step-1").css({
                            'min-height': 'auto',
                            'height': 'auto'
                        });

                        $('.tab-content').css({
                            'min-height': 'auto',
                            'height': 'auto'
                        });

                        $('.card-body').css({
                            'min-height': 'auto',
                            'height': 'auto'
                        });

                        $('.smartwizard').css({
                            'min-height': 'auto',
                            'height': 'auto'
                        });

                        setTimeout(function() {
                            $("#step-1").animate({
                                'min-height': totalNewHeight + 'px',
                                'height': 'auto'
                            }, 400, 'swing');

                            $('.tab-content').animate({
                                'min-height': totalNewHeight + 'px',
                                'height': 'auto'
                            }, 400, 'swing');

                            $('.card-body').animate({
                                'min-height': totalNewHeight + 'px',
                                'height': 'auto'
                            }, 400, 'swing');

                            $('.smartwizard').animate({
                                'min-height': totalNewHeight + 'px',
                                'height': 'auto'
                            }, 400, 'swing');
                        }, 50);
                    }, 100);
                });
            });

            const btnFinish = $('<button></button>')
                .text('Hoàn Thành')
                .addClass('btn btn-success btn-finish')
                .on('click', function() {
                    const orderData = {
                        code: '{{ $code }}',
                        customer_id: {{ $order->customer_id }},
                        services: []
                    };

                    const mainServiceId = $('input[name="service_id"]').val();
                    const mainProductId = $('.product-select[name="product_id"]').val();
                    const mainDomain = $('input[name="domain"]').val();
                    const mainNotes = $('textarea[name="notes"]').val();

                    let mainEndDate = '';
                    let mainPrice = 0;
                    if (mainServiceId && mainProductId) {
                        const product = allProducts[mainServiceId].find(p => p.id == mainProductId);
                        if (product) {
                            mainPrice = product.price || 0;
                            const expirationDate = calculateExpirationDate(product.package_period, product.payment_period);
                            const dateParts = expirationDate.split('/');
                            if (dateParts.length === 3) {
                                mainEndDate =
                                    `${dateParts[2]}-${dateParts[1].padStart(2, '0')}-${dateParts[0].padStart(2, '0')}`;
                            }
                        }

                        orderData.services.push({
                            service_id: mainServiceId,
                            product_id: mainProductId,
                            domain: mainDomain,
                            notes: mainNotes,
                            end_date: mainEndDate,
                            price: mainPrice
                        });
                    }

                    $('.service-item').each(function() {
                        const serviceId = $(this).find('.service-select').val();
                        const productId = $(this).find('.product-select').val();
                        const domain = $(this).find('input[name*="domain"]').val();
                        const notes = $(this).find('textarea[name*="notes"]').val();

                        let endDate = '';
                        let price = 0;
                        if (serviceId && productId) {
                            const product = allProducts[serviceId].find(p => p.id == productId);
                            if (product) {
                                price = product.price || 0;
                                const expirationDate = calculateExpirationDate(product.package_period, product.payment_period);
                                const dateParts = expirationDate.split('/');
                                if (dateParts.length === 3) {
                                    endDate =
                                        `${dateParts[2]}-${dateParts[1].padStart(2, '0')}-${dateParts[0].padStart(2, '0')}`;
                                }
                            }

                            orderData.services.push({
                                service_id: serviceId,
                                product_id: productId,
                                domain: domain,
                                notes: notes,
                                end_date: endDate,
                                price: price
                            });
                        }
                    });

                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('orders.renew.update', ['id' => $order->id, 'orderServiceId' => $orderService->id]) }}'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    }));

                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'code',
                        value: orderData.code
                    }));

                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'customer_id',
                        value: orderData.customer_id
                    }));

                    orderData.services.forEach((service, index) => {
                        form.append($('<input>', {
                            type: 'hidden',
                            name: `services[${index}][service_id]`,
                            value: service.service_id
                        }));

                        form.append($('<input>', {
                            type: 'hidden',
                            name: `services[${index}][product_id]`,
                            value: service.product_id
                        }));

                        form.append($('<input>', {
                            type: 'hidden',
                            name: `services[${index}][domain]`,
                            value: service.domain || ''
                        }));

                        form.append($('<input>', {
                            type: 'hidden',
                            name: `services[${index}][notes]`,
                            value: service.notes || ''
                        }));

                        form.append($('<input>', {
                            type: 'hidden',
                            name: `services[${index}][start_date]`,
                            value: service.start_date || ''
                        }));

                        form.append($('<input>', {
                            type: 'hidden',
                            name: `services[${index}][end_date]`,
                            value: service.end_date || ''
                        }));

                        form.append($('<input>', {
                            type: 'hidden',
                            name: `services[${index}][price]`,
                            value: service.price || 0
                        }));
                    });

                    $('body').append(form);
                    form.submit();
                });

            const btnCancel = $('<button></button>')
                .text('Hủy')
                .addClass('btn btn-danger')
                .on('click', function() {
                    $('#smartwizard').smartWizard("reset");
                });

            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
                $(".sw-btn-prev").removeClass('disabled').prop('disabled', false);
                $(".sw-btn-next").removeClass('disabled').prop('disabled', false);
                $(".btn-finish").removeClass('disabled').prop('disabled', false);

                if (stepPosition === 'first') {
                    $(".sw-btn-prev").addClass('disabled').prop('disabled', true);
                    $(".btn-finish").addClass('disabled').prop('disabled', true);
                } else if (stepPosition === 'last') {
                    $(".sw-btn-next").addClass('disabled').prop('disabled', true);
                    $(".btn-finish").removeClass('disabled').prop('disabled', false);
                } else {
                    $(".sw-btn-prev").removeClass('disabled').prop('disabled', false);
                    $(".sw-btn-next").removeClass('disabled').prop('disabled', false);
                    $(".btn-finish").addClass('disabled').prop('disabled', true);
                }

                $('.sw-btn-prev, .sw-btn-next').removeClass('active');
                $('.sw-btn-prev, .sw-btn-next').eq(stepNumber).addClass('active');

                if (stepNumber === 2 || stepNumber === 3) {
                    loadServices();
                }
            });

            $('#smartwizard').smartWizard({
                selected: 0,
                theme: 'arrows',
                transition: {
                    animation: 'slide-horizontal',
                },
                toolbarSettings: {
                    toolbarPosition: 'bottom',
                    toolbarExtraButtons: [btnFinish, btnCancel]
                },
                onStepChanged: function(e, anchorObject, stepIndex, stepDirection) {
                    if (stepIndex === 2 || stepIndex === 3) {
                        setTimeout(function() {
                            loadServices();
                        }, 100);
                    }
                },

            });

            $("#smartwizard").on("stepChanged", function(e, anchorObject, stepIndex, stepDirection) {
                $('.sw-btn-prev, .sw-btn-next').removeClass('active');
                $('.sw-btn-prev, .sw-btn-next').eq(stepIndex).addClass('active');
            });



            setTimeout(function() {
                $('.sw-btn-prev').text('Trở lại');
                $('.sw-btn-next').text('Tiếp theo');
                $('.btn-finish').addClass('disabled').prop('disabled', true);
            }, 100);
        });
    </script>
@endpush
