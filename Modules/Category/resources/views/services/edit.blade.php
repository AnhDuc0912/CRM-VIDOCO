@extends('core::layouts.app')
@use('Modules\Category\Enums\PaymentPeriodEnum')
@use('Modules\Category\Enums\ServiceStatusEnum')
@use('Modules\Category\Enums\VATServiceEnum')

@section('title', 'Cập nhật dịch vụ')

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
                    <li class="breadcrumb-item active" aria-current="page">Sửa Dịch Vụ</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="user-profile-page">
        <div class="card radius-15">
            <div class="card-body">
                <form action="{{ route('services.update', $service->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-lg-6">
                            <div class="card shadow-none border mb-0 radius-15">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Mã dịch vụ</label>
                                            <input type="text" class="form-control" value="{{ $service->code }}"
                                                readonly>
                                        </div>
                                        <div class="col-12">
                                            <label for="category_id" class="form-label">Danh mục</label>
                                            <select name="category_id" class="single-select form-select">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="name" class="form-label">Tên dịch vụ</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $service->name }}">
                                        </div>
                                        <div class="col-6">
                                            <label for="payment_type" class="form-label">Kỳ Thanh Toán</label>
                                            <select name="payment_type" id="payment_type" class="form-select">
                                                <option value="">-- Chọn kỳ thanh toán --</option>
                                                @foreach (\Modules\Category\Enums\PaymentTypeEnum::getValues() as $type)
                                                    <option value="{{ $type }}"
                                                        {{ $service->payment_type == $type ? 'selected' : '' }}>
                                                        {{ \Modules\Category\Enums\PaymentTypeEnum::getLabel($type) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label for="vat" class="form-label">Thuế VAT</label>
                                            <select name="vat" class="form-select">
                                                @foreach (VATServiceEnum::getValues() as $vat)
                                                    <option value="{{ $vat }}"
                                                        {{ $service->vat == $vat ? 'selected' : '' }}>
                                                        {{ VATServiceEnum::getLabel($vat) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label for="status" class="form-label">Trạng Thái</label>
                                            <select name="status" class="form-select">
                                                @foreach (ServiceStatusEnum::getValues() as $status)
                                                    <option value="{{ $status }}"
                                                        {{ $service->status == $status ? 'selected' : '' }}>
                                                        {{ $status == ServiceStatusEnum::ACTIVE ? 'Hiệu lực' : ServiceStatusEnum::getLabel($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="card radius-15">
                                                <div class="card-body">
                                                    <div class="card-title">
                                                        <h4 class="mb-0">Mô Tả</h4>
                                                    </div>
                                                    <textarea id="mytextarea" name="description">{{ $service->description }}</textarea>
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
                                            @foreach ($service->products as $i => $product)
                                                <div class="product-row row g-3 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label">Gói lựa chọn</label>
                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <select
                                                                    name="products[{{ $i }}][payment_period]"
                                                                    class="form-select">
                                                                    <option value="">-- Chọn kỳ hạn --</option>

                                                                    <option value="{{ $product->payment_period }}"
                                                                        selected>
                                                                        {{ PaymentPeriodEnum::getLabel($product->payment_period) }}
                                                                    </option>

                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="text"
                                                                    name="products[{{ $i }}][package_period]"
                                                                    class="form-control package-period-input"
                                                                    value="{{ $product->package_period }}"
                                                                    placeholder="Nhập thời hạn" min="0"
                                                                    step="1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label">Đơn giá</label>
                                                        <input type="text" name="products[{{ $i }}][price]"
                                                            class="form-control product-price"
                                                            value="{{ format_money($product->price ?? 0) }}">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-danger remove-product">
                                                            <i class="bx bx-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
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
                            <a href="{{ route('services.index') }}" class="btn btn-warning">Quay lại</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
                    const currentValue = $(this).data("value");
                    $(this).empty().append(`<option value="">-- Chọn kỳ hạn --</option>`);

                    options.forEach(opt => {
                        $(this).append(`<option value="${opt.value}">${opt.label}</option>`);
                    });

                    if (currentValue) {
                        $(this).val(currentValue);
                    }
                });
            }



            $('#payment_type').on('change', function() {
                updatePaymentPeriodSelects($(this).val());
            });



            let productIndex = {{ count($service->products) }};

            $('#add-product').click(function() {
                const newRow = `
        <div class="product-row row g-3 mb-3">
            <div class="col-6">
                <label class="form-label">Gói lựa chọn</label>
                <div class="row g-2">
                    <div class="col-6">
                        <select name="products[${productIndex}][payment_period]" class="form-select" data-value="">
                            <option value="">-- Chọn kỳ hạn --</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <input type="text" name="products[${productIndex}][package_period]" class="form-control package-period-input" placeholder="Nhập thời hạn">
                    </div>
                </div>
            </div>
            <div class="col-4">
                <label class="form-label">Đơn giá</label>
                <input type="text" name="products[${productIndex}][price]" class="form-control product-price">
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


            $(document).on('click', '.remove-product', function() {
                $(this).closest('.product-row').remove();
            });
            $('.single-select').select2({
                theme: 'bootstrap4',
                width: '100%',
                allowClear: true,
                placeholder: 'Chọn danh mục',
            });
            /*
                        $(document).on('input', '.package-period-input', function() {
                            let value = $(this).val();
                            value = value.replace(/[^0-9]/g, '');
                            if (value === '' || parseInt(value) < 1) {
                                value = '1';
                            }

                            $(this).val(value);
                        });

                        $(document).on('keypress', '.package-period-input', function(e) {
                            if (e.which < 48 || e.which > 57) {
                                e.preventDefault();
                            }
                        });

                       $(document).on('paste', '.package-period-input', function(e) {
                            e.preventDefault();
                            let pastedText = (e.originalEvent || e).clipboardData.getData('text/plain');
                            let numericValue = pastedText.replace(/[^0-9]/g, '');

                            if (numericValue) {
                                let value = parseInt(numericValue);
                                if (value < 1) value = 1;
                                $(this).val(value);
                            }
                        }); */
        });
    </script>
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
