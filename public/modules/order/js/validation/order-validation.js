$(document).ready(function () {
  function validateRequired(value) {
    return value && value.trim() !== '';
  }

  function validateEmail(value) {
    if (value) return true;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(value);
  }

  function validatePhone(value) {
    if (value) return true;
    const phoneRegex = /^(0|\+84)[0-9]{9,10}$/;
    return phoneRegex.test(value);
  }

  function validateDate(value) {
    if (!value) return false;
    const date = new Date(value);
    return date instanceof Date && !isNaN(date);
  }

  function validateFutureDate(value) {
    if (!value) return false;
    const selectedDate = new Date(value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return selectedDate >= today;
  }

  function validateAfterStartDate(value) {
    if (!value) return false;
    const startDate = $('input[name="start_date"]').val();
    if (!startDate) return true;
    return new Date(value) > new Date(startDate);
  }

  function validateField($field) {
    let value = $field.val();
    let fieldName = $field.attr('name');
    let isValid = true;
    let errorMessage = '';

    if (!fieldName || fieldName === 'undefined') {
      return true;
    }

    $field.removeClass('is-invalid is-valid');

    if (!$field.is(':visible') || $field.attr('type') === 'button' || $field.attr('type') === 'submit') {
      return true;
    }

    if ($field.hasClass('select2-hidden-accessible')) {
      return true;
    }

    if (fieldName === 'auto_email') {
      return true;
    }

    if (fieldName === '_token') {
      return true;
    }

    if ($field.hasClass('no-validate')) {
      return true;
    }

    if (fieldName === 'category_id') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn loại dịch vụ';
      }
    } else if (fieldName === 'service_id') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn dịch vụ';
      }
    } else if (fieldName === 'product_id') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn gói - giá tiền';
      }
    } else if (fieldName === 'customer_id') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn khách hàng';
      }
    } else if (fieldName === 'status') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn trạng thái';
      }
    } else if (fieldName === 'start_date') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn ngày bắt đầu';
      } else if (!validateDate(value)) {
        isValid = false;
        errorMessage = 'Ngày bắt đầu không hợp lệ';
      } /*else if (!validateFutureDate(value)) {
        isValid = false;
        errorMessage = 'Ngày bắt đầu phải từ hôm nay trở đi CC';
      }*/
    } else if (fieldName === 'end_date') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn ngày kết thúc';
      } else if (!validateDate(value)) {
        isValid = false;
        errorMessage = 'Ngày kết thúc không hợp lệ';
      } else if (!validateAfterStartDate(value)) {
        isValid = false;
        errorMessage = 'Ngày kết thúc phải sau ngày bắt đầu';
      }
    } else if (fieldName === 'domain') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng nhập tên miền hoặc IP';
      } else if (value.trim().length < 3) {
        isValid = false;
        errorMessage = 'Tên miền hoặc IP phải có ít nhất 3 ký tự';
      }
    } else if (fieldName === 'email') {
      if ($field.hasClass('no-validate')) {
        return true;
      }
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng nhập email chính';
      } else if (!validateEmail(value)) {
        isValid = false;
        errorMessage = 'Email chính không hợp lệ';
      }
    } else if (fieldName === 'sub_email') {
      if (value && !validateEmail(value)) {
        isValid = false;
        errorMessage = 'Email phụ không hợp lệ';
      }
    } else if (fieldName === 'full_name') {
      if ($field.hasClass('no-validate')) {
        return true;
      }
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng nhập tên chủ thể';
      } else if (value.trim().length < 2) {
        isValid = false;
        errorMessage = 'Tên chủ thể phải có ít nhất 2 ký tự';
      }
    } else if (fieldName === 'phone') {
      if ($field.hasClass('no-validate')) {
        return true;
      }
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng nhập số điện thoại';
      } else if (!validatePhone(value)) {
        isValid = false;
        errorMessage = 'Số điện thoại không hợp lệ';
      }
    }

    if (isValid) {
      $field.addClass('is-valid');
      let $errorDiv = $field.siblings('.invalid-feedback');
      if ($errorDiv.length > 0) {
        $errorDiv.removeClass('show').hide();
      }
    } else {
      $field.addClass('is-invalid');

      let $errorDiv = $field.siblings('.invalid-feedback');
      if ($errorDiv.length === 0) {
        $errorDiv = $('<div class="invalid-feedback"></div>');
        $field.after($errorDiv);
      }

      $errorDiv.text(errorMessage).addClass('show').show();
    }

    return isValid;
  }

  function validateForm() {
    let isValid = true;
    let hasErrors = false;

    $('form input, form select').not('.select2-hidden-accessible').each(function() {
      let fieldName = $(this).attr('name');

      if (!fieldName || fieldName === 'undefined' || fieldName === 'auto_email' || fieldName === '_token') {
        return true;
      }

      if (!validateField($(this))) {
        isValid = false;
        hasErrors = true;
      }
    });

    if (hasErrors) {
      $('html, body').animate({
        scrollTop: $('.is-invalid:first').offset().top - 100
      }, 500);
    }

    return isValid;
  }

  $('form').on('submit', function(e) {
    if (!validateForm()) {
      e.preventDefault();
      e.stopPropagation();
      return false;
    }

    showLoading();
  });

  $(document).on('input change blur', 'form input, form select', function() {
    if ($(this).is('button') || $(this).attr('type') === 'button' || $(this).attr('type') === 'submit' || $(this).attr('type') === 'reset') {
      return;
    }

    if ($(this).hasClass('select2-hidden-accessible')) {
      return;
    }

    validateField($(this));
  });

  function showLoading() {
    $('button[type="submit"]')
      .prop('disabled', true)
      .html(
        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...'
      );
  }

  setTimeout(function () {
    $('form')
      .find('input, select, textarea')
      .not('.select2-hidden-accessible')
      .each(function () {
        if ($(this).val()) {
          validateField($(this));
        }
      });
  }, 2000);
});
