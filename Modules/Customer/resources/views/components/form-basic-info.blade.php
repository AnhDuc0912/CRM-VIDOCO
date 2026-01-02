@use('Modules\Customer\Enums\CustomerTypeEnum')

<div class="card shadow-none border mb-0 radius-15">
    <div class="card-body">
        <div class="alert alert-primary alert-dismissible fade show">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="customer_type" id="type_personal"
                    value="{{ CustomerTypeEnum::PERSONAL }}"
                    {{ !empty($customer) ? ($customer->customer_type == CustomerTypeEnum::PERSONAL ? 'checked' : '') : 'checked' }}>
                <label class="form-check-label" for="type_personal">Cá
                    nhân</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="customer_type" id="type_company"
                    value="{{ CustomerTypeEnum::COMPANY }}"
                    {{ !empty($customer) ? ($customer->customer_type == CustomerTypeEnum::COMPANY ? 'checked' : '') : '' }}>
                <label class="form-check-label" for="type_company">Công
                    ty</label>
            </div>
        </div>

        <!-- FORM CÁ NHÂN -->
        @include('customer::components.form-basic-info.personal')

        <!-- FORM DOANH NGHIỆP -->
        @include('customer::components.form-basic-info.bussiness')
    </div>
</div>

@push('scripts')
    <script>
        // Khai báo biến global để có thể truy cập từ bên ngoài
        let formLocked = false;
        let lockedFormType = null;
        let customerType =
            '{{ old('customer_type') ?? ($customer->customer_type ?? null) }}';

        $(document).ready(function() {
            function switchForm() {
                // Nếu có customerType từ database, giữ nguyên loại customer nhưng cho phép chuyển đổi form
                if (customerType && customerType !== 'null') {
                    if (customerType ==
                        '{{ CustomerTypeEnum::PERSONAL }}') {
                        // Luôn hiển thị form cá nhân cho customer cá nhân
                        $('#form-individual').show();
                        $('#form-company').hide();
                        return;
                    } else if (customerType ==
                        '{{ CustomerTypeEnum::COMPANY }}') {
                        // Luôn hiển thị form công ty cho customer công ty
                        $('#type_company').prop('checked', true);
                        $('#form-individual').hide();
                        $('#form-company').show();
                        return;
                    }
                }

                if ($('#type_personal').is(':checked')) {
                    $('#form-individual').show();
                    $('#form-company').hide();

                    // Nếu form bị khóa và không phải form cá nhân
                    if (formLocked && lockedFormType !== 'individual') {
                        $('#type_personal').prop('disabled', true);
                        $('#type_company').prop('checked', true);
                        $('#type_personal').prop('checked', false);
                        $('#form-individual').hide();
                        $('#form-company').show();
                        return;
                    }
                } else {
                    $('#form-individual').hide();
                    $('#form-company').show();

                    // Nếu form bị khóa và không phải form công ty
                    if (formLocked && lockedFormType !== 'company') {
                        $('#type_company').prop('disabled', true);
                        $('#type_personal').prop('checked', true);
                        $('#type_company').prop('checked', false);
                        $('#form-individual').show();
                        $('#form-company').hide();
                        return;
                    }
                }
            }

            function checkAndLockForm() {
                // Nếu có customerType từ database, chỉ hiển thị form tương ứng và khóa radio của tab kia
                if (customerType && customerType !== 'null') {
                    if (customerType ===
                        '{{ CustomerTypeEnum::PERSONAL }}') {
                        // Customer là cá nhân - hiển thị form cá nhân và khóa radio công ty
                        $('#form-individual').show();
                        $('#form-company').hide();
                        $('#type_company').prop('disabled', true);
                        $('#type_company').parent().css('opacity', '0.5');
                        $('#type_personal').prop('disabled', false);
                        $('#type_personal').parent().css('opacity', '1');
                        formLocked = true;
                        lockedFormType = 'individual';
                        return;
                    } else if (customerType ===
                        '{{ CustomerTypeEnum::COMPANY }}') {
                        // Customer là công ty - hiển thị form công ty và khóa radio cá nhân
                        $('#form-individual').hide();
                        $('#form-company').show();
                        $('#type_personal').prop('disabled', true);
                        $('#type_personal').parent().css('opacity', '0.5');
                        $('#type_company').prop('disabled', false);
                        $('#type_company').parent().css('opacity', '1');
                        formLocked = true;
                        lockedFormType = 'company';
                        return;
                    }
                }

                // Kiểm tra form cá nhân có data không
                let individualHasData = false;
                $('#form-individual input, #form-individual select, #form-individual textarea')
                    .each(function() {
                        let value = $(this).val();
                        let elementType = $(this).prop('tagName')
                            .toLowerCase();

                        if (elementType === 'select') {
                            // Với select, kiểm tra xem có option nào được chọn không
                            if (value && value !== '' && value !==
                                '0') {
                                individualHasData = true;
                                return false; // break loop
                            }
                        } else {
                            // Với input và textarea, kiểm tra giá trị
                            if (value !== null && value !== undefined &&
                                value.toString().trim() !== '' &&
                                value !== '') {
                                individualHasData = true;
                                return false; // break loop
                            }
                        }
                    });

                // Kiểm tra form công ty có data không
                let companyHasData = false;
                $('#form-company input, #form-company select, #form-company textarea')
                    .each(function() {
                        let value = $(this).val();
                        let elementType = $(this).prop('tagName')
                            .toLowerCase();

                        if (elementType === 'select') {
                            // Với select, kiểm tra xem có option nào được chọn không
                            if (value && value !== '' && value !==
                                '0') {
                                companyHasData = true;
                                return false; // break loop
                            }
                        } else {
                            // Với input và textarea, kiểm tra giá trị
                            if (value !== null && value !== undefined &&
                                value.toString().trim() !== '' &&
                                value !== '') {
                                companyHasData = true;
                                return false; // break loop
                            }
                        }
                    });

                if (individualHasData && !companyHasData) {
                    // Khóa radio công ty
                    $('#type_company').prop('disabled', true);
                    $('#type_company').parent().css('opacity', '0.5');
                    formLocked = true;
                    lockedFormType = 'individual';
                } else if (companyHasData && !individualHasData) {
                    // Khóa radio cá nhân
                    $('#type_personal').prop('disabled', true);
                    $('#type_personal').parent().css('opacity', '0.5');
                    formLocked = true;
                    lockedFormType = 'company';
                } else if (!individualHasData && !companyHasData) {
                    // Mở khóa cả 2 radio
                    $('#type_personal, #type_company').prop('disabled',
                        false);
                    $('#type_personal').parent().css('opacity', '1');
                    $('#type_company').parent().css('opacity', '1');
                    formLocked = false;
                    lockedFormType = null;
                }
            }

            // Event handlers
            $('input[name="customer_type"]').on('change', switchForm);

            // Lắng nghe sự kiện input/change trên tất cả form fields
            $(document).on('input change',
                '#form-individual input, #form-individual select, #form-individual textarea, #form-company input, #form-company select, #form-company textarea',
                checkAndLockForm);

            // Khởi tạo form
            switchForm();

            // Kiểm tra dữ liệu cũ sau khi switch form
            setTimeout(function() {
                checkAndLockForm();
            }, 100);
        });

        // Hàm reset form
        function resetForms() {
            customerType = null;
            // Reset tất cả input, select, textarea
            $('#form-individual input, #form-individual select, #form-individual textarea')
                .val('');
            $('#form-company input, #form-company select, #form-company textarea')
                .val('');

            // Reset các select về option đầu tiên
            $('#form-individual select, #form-company select').prop('selectedIndex',
                0);

            // Mở khóa radio buttons
            $('#type_personal, #type_company').prop('disabled', false);
            $('#type_personal, #type_company').parent().css('opacity', '1');
            $('#type_personal').parent().css('opacity', '1');
            $('#type_company').parent().css('opacity', '1');

            // Nếu có customerType từ database, giữ nguyên loại customer đó và khóa radio của tab kia
            if (customerType && customerType !== 'null') {
                if (customerType === '{{ CustomerTypeEnum::PERSONAL }}') {
                    $('#type_personal').prop('checked', true);
                    $('#type_company').prop('checked', false);
                    $('#form-individual').show();
                    $('#form-company').hide();
                    // Khóa radio công ty
                    $('#type_company').prop('disabled', true);
                    $('#type_company').parent().css('opacity', '0.5');
                    formLocked = true;
                    lockedFormType = 'individual';
                } else if (customerType === '{{ CustomerTypeEnum::COMPANY }}') {
                    $('#type_company').prop('checked', true);
                    $('#type_personal').prop('checked', false);
                    $('#form-individual').hide();
                    $('#form-company').show();
                    // Khóa radio cá nhân
                    $('#type_personal').prop('disabled', true);
                    $('#type_personal').parent().css('opacity', '0.5');
                    formLocked = true;
                    lockedFormType = 'company';
                }
            } else {
                // Nếu tạo mới, chọn mặc định cá nhân
                $('#type_personal').prop('checked', true);
                $('#type_company').prop('checked', false);
                $('#form-individual').show();
                $('#form-company').hide();
                // Reset trạng thái lock
                formLocked = false;
                lockedFormType = null;
            }
        }
    </script>
@endpush
