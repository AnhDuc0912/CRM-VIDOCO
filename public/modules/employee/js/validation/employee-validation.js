$(document).ready(function () {
  // Tùy chỉnh thông báo lỗi mặc định cho tiếng Việt
  $.extend($.validator.messages, {
    required: 'Trường này là bắt buộc',
    email: 'Vui lòng nhập email hợp lệ',
    minlength: $.validator.format('Vui lòng nhập ít nhất {0} ký tự'),
    maxlength: $.validator.format('Vui lòng nhập không quá {0} ký tự'),
    date: 'Vui lòng nhập ngày hợp lệ',
    digits: 'Vui lòng chỉ nhập số',
  });

  // Tùy chỉnh method cho số điện thoại Việt Nam
  $.validator.addMethod(
    'vnphone',
    function (value, element) {
      if (this.optional(element)) return true;
      return /^(0|\+84)[0-9]{9,10}$/.test(value);
    },
    'Vui lòng nhập số điện thoại hợp lệ (VD: 0987654321 hoặc +84987654321)'
  );

  // Tùy chỉnh method cho số CCCD/Hộ chiếu
  $.validator.addMethod(
    'vncitizen',
    function (value, element) {
      if (this.optional(element)) return true;
      return /^[0-9]{9,12}$/.test(value);
    },
    'Số CCCD/Hộ chiếu phải từ 9-12 chữ số'
  );

  // Tùy chỉnh method cho tài khoản ngân hàng
  $.validator.addMethod(
    'bankaccount',
    function (value, element) {
      if (this.optional(element)) return true;
      return /^[0-9]{6,20}$/.test(value);
    },
    'Số tài khoản ngân hàng phải từ 6-20 chữ số'
  );

  // Tùy chỉnh method cho số tiền có dấu phẩy hoặc dấu chấm
  $.validator.addMethod(
    'money',
    function (value, element) {
      if (this.optional(element)) return true;
      return /^[0-9,\.]+$/.test(value);
    },
    'Vui lòng nhập số tiền hợp lệ'
  );

  // Format số tiền khi người dùng nhập
  $(document).on(
    'input',
    'input[name="salary[basic_salary]"], input[name="salary[insurance_salary]"]',
    function () {
      var value = $(this).val().replace(/[^\d]/g, '');
      if (value) {
        var formatted = parseInt(value).toLocaleString('vi-VN');
        $(this).val(formatted);
      }
    }
  );

  // Xử lý trước khi submit để remove format
  $('#employee-create-form').on('submit', function (e) {
    $(
      'input[name="salary[basic_salary]"], input[name="salary[insurance_salary]"]'
    ).each(function () {
      var value = $(this).val().replace(/[^\d]/g, ''); // Loại bỏ tất cả ký tự không phải số
      $(this).val(value);
    });
  });

  // Khởi tạo validation cho form
  $('#employee-create-form').validate({
    // Validate tất cả field, kể cả hidden trong tab không active
    ignore: [],

    // Custom validation cho field ẩn trong tab
    onfocusout: function (element) {
      this.element(element);
    },

    onkeyup: function (element) {
      this.element(element);
    },

    // Thêm onchange để trigger validation khi user thay đổi giá trị
    onchange: function (element) {
      this.element(element);
    },

    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');

      // Xử lý riêng cho select2 nếu có
      if (element.hasClass('select2-hidden-accessible')) {
        error.insertAfter(element.next('.select2-container'));
      } else {
        error.insertAfter(element);
      }
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid').removeClass('is-valid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid is-valid');
    },
    rules: {
      // Thông tin cá nhân
      'profile[first_name]': {
        required: true,
        minlength: 2,
        maxlength: 50,
      },
      'profile[last_name]': {
        required: true,
        minlength: 2,
        maxlength: 50,
      },
      'profile[birthday]': {
        required: true,
        date: true,
      },
      'profile[gender]': {
        required: true,
      },
      'profile[citizen_id_number]': {
        required: true,
        vncitizen: true,
      },
      'profile[citizen_id_created_date]': {
        required: true,
        date: true,
      },
      'profile[citizen_id_created_place]': {
        required: true,
        maxlength: 100,
      },
      'profile[phone]': {
        required: true,
        vnphone: true,
      },
      'profile[email_personal]': {
        email: true,
        maxlength: 100,
      },
      'profile[email_work]': {
        required: true,
        email: true,
        maxlength: 100,
      },
      'profile[current_address]': {
        required: true,
        maxlength: 255,
      },
      'profile[permanent_address]': {
        required: true,
        maxlength: 255,
      },

      // Tài khoản ngân hàng
      'bank_account[bank_account_number]': {
        required: true,
        bankaccount: true,
      },
      'bank_account[bank_account_name]': {
        required: true,
        minlength: 2,
        maxlength: 100,
      },
      'bank_account[bank_branch]': {
        required: true,
        maxlength: 100,
      },
      'bank_account[bank_name]': {
        required: true,
        maxlength: 100,
      },

      // Thông tin công việc
      'job[department_id]': {
        required: true,
        digits: true,
      },
      'job[level]': {
        required: true,
        digits: true,
      },
      'job[current_position]': {
        required: true,
        digits: true,
      },
      'job[last_position]': {
        digits: true,
      },
      'job[start_date]': {
        required: true,
        date: true,
      },
      'contract[contract_type]': {
        required: true,
      },
      'contract[start_date]': {
        required: true,
        date: true,
      },
      'contract[end_date]': {
        date: true,
        greaterOrEqualStart: true,
      },
      'salary[basic_salary]': {
        required: true,
        money: true,
      },
      'salary[base_salary]': {
        required: true,
        // number: true,
      },
      'salary[insurance_salary]': {
        required: true,
        money: true,
        // min: 1000000,
      },
      'job[manager_id]': {
        required: true,
        digits: true,
      },
    },
    messages: {
      'profile[first_name]': {
        required: 'Vui lòng nhập họ và tên đệm',
        minlength: 'Họ phải có ít nhất 2 ký tự',
        maxlength: 'Họ không được quá 50 ký tự',
      },
      'profile[last_name]': {
        required: 'Vui lòng nhập tên',
        minlength: 'Tên phải có ít nhất 2 ký tự',
        maxlength: 'Tên không được quá 50 ký tự',
      },
      'profile[birthday]': {
        required: 'Vui lòng chọn ngày sinh',
      },
      'profile[gender]': {
        required: 'Vui lòng chọn giới tính',
      },
      'profile[citizen_id_number]': {
        required: 'Vui lòng nhập số CCCD/Hộ chiếu',
      },
      'profile[citizen_id_created_date]': {
        required: 'Vui lòng chọn ngày cấp',
      },
      'profile[citizen_id_created_place]': {
        required: 'Vui lòng nhập nơi cấp',
      },
      'profile[current_address]': {
        required: 'Vui lòng nhập địa chỉ hiện tại',
      },
      'profile[permanent_address]': {
        required: 'Vui lòng nhập địa chỉ thường trú',
      },
      'profile[phone]': {
        required: 'Vui lòng nhập số điện thoại',
      },
      'profile[email_work]': {
        required: 'Vui lòng nhập email công việc',
      },
      'bank_account[bank_account_number]': {
        required: 'Vui lòng nhập số tài khoản ngân hàng',
      },
      'bank_account[bank_account_name]': {
        required: 'Vui lòng nhập tên chủ tài khoản',
        minlength: 'Tên chủ tài khoản phải có ít nhất 2 ký tự',
        maxlength: 'Tên chủ tài khoản không được quá 100 ký tự',
      },
      'bank_account[bank_branch]': {
        required: 'Vui lòng nhập chi nhánh ngân hàng',
      },
      'bank_account[bank_name]': {
        required: 'Vui lòng nhập tên ngân hàng',
      },
      'job[department_id]': {
        required: 'Vui lòng chọn phòng ban',
      },
      'job[level]': {
        required: 'Vui lòng chọn cấp bậc',
      },
      'job[current_position]': {
        required: 'Vui lòng chọn vị trí công việc',
      },
      'job[last_position]': {
        required: 'Vui lòng chọn vị trí trước đây',
      },
      'job[start_date]': {
        required: 'Vui lòng chọn ngày bắt đầu làm việc',
      },
      'contract[contract_type]': {
        required: 'Vui lòng chọn loại hợp đồng',
      },
      'contract[start_date]': {
        required: 'Vui lòng chọn ngày ký hợp đồng',
      },
      'salary[basic_salary]': {
        required: 'Vui lòng nhập lương cơ bản',
        number: 'Lương cơ bản phải là số hợp lệ',
      },
      'salary[base_salary]': {
        required: 'Vui lòng nhập bậc lương hiện tại',
        // number: 'Bậc lương hiện tại phải là số hợp lệ',
      },
      'salary[insurance_salary]': {
        required: 'Vui lòng nhập lương bảo hiểm',
        number: 'Lương bảo hiểm phải là số hợp lệ',
        // min: 'Lương bảo hiểm phải lớn hơn 1.000.000 VNĐ',
      },
      'job[manager_id]': {
        required: 'Vui lòng chọn người quản lý trực tiếp',
      },
    },

    // Validate trước khi submit
    submitHandler: function (form) {
      // Đảm bảo tất cả tab đều visible để validate
      var currentActiveTab = $('.nav-link.active').attr('href');

      // Tạm thời hiển thị tất cả tab để validate
      $('.tab-pane').addClass('show active');

      // Validate cả 2 tab
      var personalValid = validatePersonalInfo();
      var jobValid = validateJobProfile();

      // Khôi phục lại trạng thái tab ban đầu
      $('.tab-pane').removeClass('show active');
      $(currentActiveTab).addClass('show active');

      if (personalValid && jobValid) {
        showLoading();
        form.submit();
      } else {
        var errorMessages = [];
        if (!personalValid) {
          errorMessages.push('Tab "Hồ Sơ Nhân Sự" có thông tin chưa hợp lệ');
          // Chuyển về tab 1 nếu có lỗi
          $('[href="#hosonhansu"]').click();
        }
        if (!jobValid) {
          errorMessages.push('Tab "Hồ sơ công việc" có thông tin chưa hợp lệ');
          // Nếu chỉ có lỗi ở tab 2, chuyển sang tab 2
          if (personalValid) {
            $('[href="#hosocongviec"]').click();
          }
        }
        showError('Vui lòng kiểm tra lại: ' + errorMessages.join(', '));
      }

      return false;
    },
  });

  // Validate thông tin cá nhân
  function validatePersonalInfo() {
    var isValid = true;
    var validator = $('#employee-create-form').validate();

    var personalFields = [
      'profile[first_name]',
      'profile[last_name]',
      'profile[birthday]',
      'profile[gender]',
      'profile[citizen_id_number]',
      'profile[phone]',
      'profile[email_work]',
      'profile[citizen_id_created_date]',
      'profile[citizen_id_created_place]',
      'profile[current_address]',
      'profile[permanent_address]',
      'bank_account[bank_account_number]',
      'bank_account[bank_account_name]',
      'bank_account[bank_name]',
    ];

    personalFields.forEach(function (field) {
      var element = $('[name="' + field + '"]');
      if (element.length && !validator.element(element)) {
        isValid = false;
      }
    });

    // Validate dependent fields nếu có
    $('.dependent-row').each(function () {
      var index = $(this).data('index');
      var dependentFields = [
        'dependent[' + index + '][name]',
        'dependent[' + index + '][birthday]',
        'dependent[' + index + '][phone]',
      ];

      dependentFields.forEach(function (field) {
        var element = $('[name="' + field + '"]');
        if (element.length && element.val() && !validator.element(element)) {
          isValid = false;
        }
      });
    });

    return isValid;
  }

  // Validate thông tin công việc
  function validateJobProfile() {
    var isValid = true;
    var validator = $('#employee-create-form').validate();

    var jobFields = [
      'job[department_id]',
      'job[level]',
      'job[current_position]',
      'job[last_position]',
      'job[start_date]',
      'contract[contract_type]',
      'contract[start_date]',
      'salary[basic_salary]',
    ];

    jobFields.forEach(function (field) {
      var element = $('[name="' + field + '"]');
      if (element.length && !validator.element(element)) {
        isValid = false;
      }
    });

    return isValid;
  }

  // Thêm validation rules cho dependent fields động
  function addDependentValidation(index) {
    $('[name="dependent[' + index + '][name]"]').rules('add', {
      required: true,
      minlength: 2,
      maxlength: 100,
      messages: {
        required: 'Vui lòng nhập họ và tên người phụ thuộc',
        minlength: 'Họ và tên phải có ít nhất 2 ký tự',
        maxlength: 'Họ và tên không được quá 100 ký tự',
      },
    });

    $('[name="dependent[' + index + '][birthday]"]').rules('add', {
      date: true,
      required: true,
      messages: {
        required: 'Vui lòng chọn năm sinh',
        date: 'Năm sinh không hợp lệ',
      },
    });

    $('[name="dependent[' + index + '][phone]"]').rules('add', {
      vnphone: true,
    });
  }

  // Xử lý khi thêm dependent mới
  $(document).on('click', '#add-dependent', function () {
    setTimeout(function () {
      var lastRow = $('.dependent-row:last');
      if (lastRow.length) {
        var index = lastRow.data('index');
        addDependentValidation(index);
      }
    }, 100);
  });

  // Xử lý navigation giữa các tab
  $('.nav-link').on('click', function (e) {
    // Cho phép chuyển tab tự do, không validate
    // Validation chỉ diễn ra khi submit form
  });

  // Trigger validation ngay khi user nhập vào field
  $(document).on(
    'input change blur',
    '#employee-create-form input, #employee-create-form select, #employee-create-form textarea',
    function () {
      // Không validate button
      if (
        $(this).is('button') ||
        $(this).attr('type') === 'button' ||
        $(this).attr('type') === 'submit' ||
        $(this).attr('type') === 'reset'
      ) {
        return;
      }
      var validator = $('#employee-create-form').validate();
      validator.element($(this));
    }
  );

  // Hiển thị loading khi submit
  function showLoading() {
    $('#submit-btn')
      .prop('disabled', true)
      .html(
        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...'
      );
  }

  // Hiển thị thông báo lỗi
  function showError(message) {
    // Sử dụng notification library nếu có, hoặc alert
    if (typeof Lobibox !== 'undefined') {
      Lobibox.notify('error', {
        pauseDelayOnHover: true,
        continueDelayOnInactiveTab: false,
        position: 'top right',
        icon: 'bx bx-x-circle',
        msg: message,
        sound: false,
        delay: 5000,
      });
    } else {
      alert(message);
    }
  }

  // Thêm function để validate toàn bộ form ngay cả khi field bị ẩn
  function validateAllFields() {
    var validator = $('#employee-create-form').validate();
    var isFormValid = true;

    // Chỉ validate input, select, textarea, KHÔNG validate button
    $('#employee-create-form')
      .find('input, select, textarea')
      .not('button, [type=button], [type=submit], [type=reset]')
      .each(function () {
        if (!validator.element($(this))) {
          isFormValid = false;
        }
      });

    return isFormValid;
  }

  // Thêm validation cho dependent fields hiện có (nếu đang edit)
  $('.dependent-row').each(function () {
    var index = $(this).data('index');
    addDependentValidation(index);
  });

  // Trigger validation cho tất cả fields khi trang load để hiển thị dấu tích xanh
  setTimeout(function () {
    var validator = $('#employee-create-form').validate();
    $('#employee-create-form')
      .find('input, select, textarea')
      .each(function () {
        if ($(this).val()) {
          validator.element($(this));
        }
      });
  }, 500);

  // Thêm indicator lỗi cho tab khi có validation error
  function addTabErrorIndicator(tabId) {
    var tabLink = $('[href="#' + tabId + '"]');
    if (!tabLink.hasClass('has-error')) {
      tabLink.addClass('has-error');
    }
  }

  function removeTabErrorIndicator(tabId) {
    var tabLink = $('[href="#' + tabId + '"]');
    tabLink.removeClass('has-error');
  }

  // Backup các function gốc
  var originalValidatePersonalInfo = validatePersonalInfo;
  var originalValidateJobProfile = validateJobProfile;

  // Override để thêm tab indicator
  validatePersonalInfo = function () {
    var isValid = originalValidatePersonalInfo();

    setTimeout(function () {
      if (isValid) {
        removeTabErrorIndicator('hosonhansu');
      } else {
        addTabErrorIndicator('hosonhansu');
      }
    }, 100);

    return isValid;
  };

  validateJobProfile = function () {
    var isValid = originalValidateJobProfile();

    setTimeout(function () {
      if (isValid) {
        removeTabErrorIndicator('hosocongviec');
      } else {
        addTabErrorIndicator('hosocongviec');
      }
    }, 100);

    return isValid;
  };

  // Custom rule: ngày kết thúc >= ngày bắt đầu
  $.validator.addMethod(
    'greaterOrEqualStart',
    function (value, element) {
      var startDate = $('input[name="contract[start_date]"]').val();
      if (!value || !startDate) return true;
      return new Date(value) >= new Date(startDate);
    },
    'Ngày kết thúc phải lớn hơn hoặc bằng ngày ký HĐ'
  );
});
