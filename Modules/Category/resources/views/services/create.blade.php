@extends('core::layouts.app')
@use('Modules\Category\Enums\PaymentPeriodEnum')
@use('Modules\Category\Enums\PaymentTypeEnum')
@use('Modules\Category\Enums\ServiceStatusEnum')
@use('Modules\Category\Enums\VATServiceEnum')

@section('title', 'Thêm mới dịch vụ')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
@endpush

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Quản lý Dịch Vụ</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thêm Dịch Vụ</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="user-profile-page">
        <div class="card radius-15">
            <div class="card-body">
                <form action="{{ route('services.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-lg-6">
                            <div class="card shadow-none border mb-0 radius-15">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="category_id" class="form-label">Danh mục</label>
                                            <select name="category_id"
                                                class="single-select form-select @error('category_id') is-invalid @enderror">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="name" class="form-label">Tên dịch vụ</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            <label for="payment_type" class="form-label">Kỳ Thanh Toán</label>
                                            <select name="payment_type" id="payment_type"
                                                class="form-select @error('payment_type') is-invalid @enderror">
                                                 <option value="">-- Chọn kỳ thanh toán --</option>
                                                @foreach (PaymentTypeEnum::getValues() as $period)
                                                    <option value="{{ $period }}"
                                                        {{ old('payment_type') == $period ? 'selected' : '' }}>
                                                        {{ PaymentTypeEnum::getLabel($period) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('payment_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            <label for="vat" class="form-label">Thuế VAT</label>
                                            <select name="vat" class="form-select @error('vat') is-invalid @enderror">
                                                @foreach (VATServiceEnum::getValues() as $vat)
                                                    <option value="{{ $vat }}"
                                                        {{ old('vat') == $vat ? 'selected' : '' }}>
                                                        {{ VATServiceEnum::getLabel($vat) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('vat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            <label for="status" class="form-label">Trạng Thái</label>
                                            <select name="status"
                                                class="form-select @error('status') is-invalid @enderror">
                                                @foreach (ServiceStatusEnum::getValues() as $status)
                                                    @php
                                                        $isSelected =
                                                            old('status') !== null
                                                                ? old('status') == $status
                                                                : $status == ServiceStatusEnum::ACTIVE;
                                                    @endphp
                                                    <option value="{{ $status }}"
                                                        {{ $isSelected ? 'selected' : '' }}>
                                                        {{ $status == ServiceStatusEnum::ACTIVE ? 'Hiệu lực' : ServiceStatusEnum::getLabel($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="card radius-15">
                                                <div class="card-body">
                                                    <div class="card-title">
                                                        <h4 class="mb-0">Mô Tả</h4>
                                                    </div>
                                                    <textarea id="mytextarea" name="description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card shadow-none border mb-0 radius-15">
                                <div class="card-body">
                                    <h5 class="mt-4 mb-3">Cấu hình Gói Và Giá</h5>
                                    <p>Giá chưa bao gồm VAT</p>
                                    <div class="row g-3">
                                        <div id="products-container">
                                            <div class="product-row row g-3 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label">Gói lựa chọn</label>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <select name="products[0][payment_period]"
                                                                class="form-select @error('products.0.payment_period') is-invalid @enderror">
                                                                <option value="">-- Chọn kỳ hạn --</option>
                                                            </select>

                                                            @error('products.0.payment_period')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="text" name="products[0][package_period]"
                                                                class="form-control package-period-input @error('products.0.package_period') is-invalid @enderror"
                                                                value="{{ old('products.0.package_period') }}"
                                                                placeholder="Nhập thời hạn">
                                                            @error('products.0.package_period')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <label class="form-label">Đơn giá</label>
                                                    <input type="text" name="products[0][price]"
                                                        class="form-control @error('products.0.price') is-invalid @enderror"
                                                        value="{{ old('products.0.price') }}">
                                                    @error('products.0.price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-2">
                                                    <label class="form-label">Giá Vốn</label>
                                                    <input type="text" name="products[0][capital_price]"
                                                        class="form-control @error('products.0.capital_price') is-invalid @enderror"
                                                        value="{{ old('products.0.capital_price') }}">
                                                    @error('products.0.capital_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-2">
                                                    <button type="button" class="btn btn-danger remove-product">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-info" id="add-product">Thêm
                                                Trường</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4 text-center">
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Lưu Dữ Liệu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/k85wu35kfr1wvk3csuts7oniwyqxsiavx137i8ls8rw4dbbs/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        const paymentPeriodOptions = {
            1: [{
                    value: 1,
                    label: "Năm"
                },
                {
                    value: 2,
                    label: "Tháng"
                }
            ],
            2: [{
                    value: 3,
                    label: "Gói"
                },
                {
                    value: 4,
                    label: "Ấn phẩm"
                },
                {
                    value: 5,
                    label: "Bài viết"
                }
            ]
        };



        $(document).ready(function() {
            function updatePaymentPeriodSelects(type) {
                const options = paymentPeriodOptions[type] || [];

                $("select[name*='[payment_period]']").each(function() {
                    const currentValue = $(this).val();
                    $(this).empty().append(`<option value="">-- Chọn kỳ hạn --</option>`);

                    options.forEach(opt => {
                        $(this).append(`<option value="${opt.value}">${opt.label}</option>`);
                    });

                    if (options.find(o => o.value == currentValue)) {
                        $(this).val(currentValue);
                    }
                });
            }

            $('#payment_type').on('change', function() {
                const type = $(this).val();
                updatePaymentPeriodSelects(type);
            });




            let productIndex = 1;

            // Add new product row
            $('#add-product').click(function() {
                const newRow = `
                    <div class="product-row row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Gói lựa chọn</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <select name="products[${productIndex}][payment_period]" class="form-select">
                                            <option value="">-- Chọn kỳ hạn --</option>
                                        </select>

                                </div>
                                <div class="col-6">
                                    <input type="text" name="products[${productIndex}][package_period]" class="form-control package-period-input" placeholder="Nhập thời hạn">
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Đơn giá</label>
                            <input type="text" name="products[${productIndex}][price]" class="form-control product-price">
                        </div>
                         <div class="col-2">
                            <label class="form-label">Giá vốn</label>
                            <input type="text" name="products[${productIndex}][capital_price]" class="form-control product-price">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger remove-product">
                                <i class="bx bx-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#products-container').append(newRow);
                updatePaymentPeriodSelects($('#payment_type').val());


                productIndex++;
            });

            // Remove product row
            $(document).on('click', '.remove-product', function() {
                $(this).closest('.product-row').remove();
            });

            // Initialize select2
            $('.single-select').select2({
                theme: 'bootstrap4',
                width: '100%',
                allowClear: true,
                placeholder: 'Chọn danh mục',
            });

            // Xử lý input thời hạn - chỉ cho phép nhập số
            $(document).on('input', '.package-period-input', function() {
                let value = $(this).val();
                value = value.replace(/[^0-9]/g, '');
                if (value === '' || parseInt(value) < 1) {
                    value = '1';
                }
                $(this).val(value);
            });

            // Ngăn chặn nhập ký tự không hợp lệ
            $(document).on('keypress', '.package-period-input', function(e) {
                if (e.which < 48 || e.which > 57) {
                    e.preventDefault();
                }
            });

            // Xử lý khi paste
            $(document).on('paste', '.package-period-input', function(e) {
                e.preventDefault();
                let pastedText = (e.originalEvent || e).clipboardData.getData('text/plain');
                let numericValue = pastedText.replace(/[^0-9]/g, '');

                if (numericValue) {
                    let value = parseInt(numericValue);
                    if (value < 1) value = 1;
                    $(this).val(value);
                }
            });
        });
    </script>

    <!-- Service Validation JS -->
    <script src="{{ asset('modules/category/js/validation/service-validation.js') }}"></script>
@endpush

@push('scripts')
    <!-- CKEditor 5 Classic -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editorElement = document.querySelector('#mytextarea');

            if (editorElement) {
                ClassicEditor
                    .create(editorElement, {
                        toolbar: [
                            'undo', 'redo', '|',
                            'heading', '|',
                            'bold', 'italic', 'underline', '|',
                            'bulletedList', 'numberedList', '|',
                            'link', 'insertTable', '|',
                            'blockQuote', 'codeBlock'
                        ],
                        heading: {
                            options: [{
                                    model: 'paragraph',
                                    title: 'Đoạn văn',
                                    class: 'ck-heading_paragraph'
                                },
                                {
                                    model: 'heading1',
                                    view: 'h1',
                                    title: 'Tiêu đề 1',
                                    class: 'ck-heading_heading1'
                                },
                                {
                                    model: 'heading2',
                                    view: 'h2',
                                    title: 'Tiêu đề 2',
                                    class: 'ck-heading_heading2'
                                }
                            ]
                        },
                        language: 'vi'
                    })
                    .then(editor => {
                        console.log('CKEditor đã sẵn sàng', editor);
                    })
                    .catch(error => {
                        console.error('Lỗi khi khởi tạo CKEditor:', error);
                    });
            }
        });
    </script>

    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
            border-radius: 10px;
        }
    </style>
@endpush
