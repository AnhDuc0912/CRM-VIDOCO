$(document).ready(function () {
  // Format unit price khi nhập (input)
  $(document).on('input', 'input[name*="[price]"]', function () {
    let value = $(this).val().replace(/[^\d]/g, '');
    if (value) {
      let formatted = parseInt(value, 10).toLocaleString('vi-VN');
      $(this).val(formatted);
    } else {
      $(this).val('');
    }
  });

  // Custom validation functions
  function validateRequired(value) {
    return value && value.trim() !== '';
  }

  function validateMinLength(value, min) {
    return value && value.trim().length >= min;
  }

  function validateMaxLength(value, max) {
    return value && value.trim().length <= max;
  }

  function validateMoney(value) {
    if (!value) return false;
    const cleanValue = value.replace(/[^\d]/g, '');
    return /^[0-9]+$/.test(cleanValue) && cleanValue.length > 0;
  }

  // Validate single field
  function validateField($field) {
    let value = $field.val();
    let fieldName = $field.attr('name');
    let isValid = true;
    let errorMessage = '';

    // Reset validation state
    $field.removeClass('is-invalid is-valid');

    // Skip hidden fields and buttons
    if (!$field.is(':visible') || $field.attr('type') === 'button' || $field.attr('type') === 'submit') {
      return true;
    }

    // Validation rules based on field name
    if (fieldName === 'category_id' || fieldName === 'payment_type' || fieldName === 'vat' || fieldName === 'status') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn giá trị';
      }
    } else if (fieldName === 'payment_type') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn loại thanh toán';
      }
    } else if (fieldName === 'name') {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng nhập tên dịch vụ';
      } else if (!validateMinLength(value, 2)) {
        isValid = false;
        errorMessage = 'Tên dịch vụ phải có ít nhất 2 ký tự';
      } else if (!validateMaxLength(value, 255)) {
        isValid = false;
        errorMessage = 'Tên dịch vụ không được quá 255 ký tự';
      }
    } else if (fieldName && fieldName.includes('[payment_period]')) {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng chọn kỳ thanh toán';
      }
    } else if (fieldName && fieldName.includes('[package_period]')) {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng nhập thời hạn';
      }
    } else if (fieldName && fieldName.includes('[price]')) {
      if (!validateRequired(value)) {
        isValid = false;
        errorMessage = 'Vui lòng nhập đơn giá';
      } else if (!validateMoney(value)) {
        isValid = false;
        errorMessage = 'Đơn giá không hợp lệ';
      }
    }

    // Apply validation result
    if (isValid) {
      $field.addClass('is-valid');
      // Ẩn error message của field này
      let $errorDiv = $field.siblings('.invalid-feedback');
      if ($errorDiv.length > 0) {
        $errorDiv.removeClass('show').hide();
      }
    } else {
      $field.addClass('is-invalid');

      // Find or create error message div
      let $errorDiv = $field.siblings('.invalid-feedback');
      if ($errorDiv.length === 0) {
        // Create error div if not exists
        $errorDiv = $('<div class="invalid-feedback"></div>');
        $field.after($errorDiv);
      }

      // Show error message
      $errorDiv.text(errorMessage).addClass('show').show();
    }

    return isValid;
  }

  // Validate all form fields
  function validateForm() {
    let isValid = true;
    let hasErrors = false;

    // Validate all input and select fields
    $('form input, form select').each(function() {
      if (!validateField($(this))) {
        isValid = false;
        hasErrors = true;
      }
    });

    if (hasErrors) {
      // Scroll to first error
      $('html, body').animate({
        scrollTop: $('.is-invalid:first').offset().top - 100
      }, 500);
    }

    return isValid;
  }

  // Form submit handler
  $('form').on('submit', function(e) {
    // Trước khi validate và submit, chuyển value về số nguyên
    $('input[name*="[price]"]').each(function () {
      let value = $(this).val().replace(/[^\d]/g, '');
      $(this).val(value);
    });

    // Validate form
    if (!validateForm()) {
      e.preventDefault();
      e.stopPropagation();
      return false;
    }

    showLoading();
  });

  let hasSubmitted = false;

  $(document).on('input change blur', 'form input, form select', function() {
    if (hasSubmitted) {
      validateField($(this));
    }
  });

  $('form').on('submit', function() {
    hasSubmitted = true;
  });

  // Show loading when submit
  function showLoading() {
    $('button[type="submit"]')
      .prop('disabled', true)
      .html(
        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...'
      );
  }
});
