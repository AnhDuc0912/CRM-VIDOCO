$(document).ready(function () {
  // Custom validation method for formatted numbers
  $.validator.addMethod('formattedNumber', function(value, element) {
    if (!value) return false;
    const numericValue = value.replace(/[^\d]/g, '');
    return numericValue.length > 0;
  }, 'Vui lòng nhập số hợp lệ');

  // Override jQuery Validation's element method to preserve formatting
  const originalElement = $.validator.prototype.element;
  $.validator.prototype.element = function(element) {
    const $element = $(element);

    // Store original value for price fields
    let originalValue = null;
    if ($element.hasClass('service-price') || $element.hasClass('service-total')) {
      originalValue = $element.val();
    }

    // Call original validation
    const result = originalElement.call(this, element);

    // Restore format if it was a price field and value changed
    if (originalValue !== null) {
      const currentValue = $element.val();
      const numericValue = currentValue.replace(/[^\d]/g, '');

      if (numericValue && numericValue.length > 3 && !currentValue.includes('.')) {
        const formatted = parseInt(numericValue).toLocaleString('vi-VN');
        $element.val(formatted);
      }
    }

    return result;
  };

  // Tùy chỉnh tiếng Việt
  $.extend($.validator.messages, {
    required: 'Trường này là bắt buộc',
    email: 'Vui lòng nhập email hợp lệ',
    minlength: $.validator.format('Vui lòng nhập ít nhất {0} ký tự'),
    maxlength: $.validator.format('Vui lòng nhập không quá {0} ký tự'),
    date: 'Vui lòng nhập ngày hợp lệ',
    digits: 'Vui lòng chỉ nhập số',
  });

  // Format số tiền khi nhập amount tổng
  $(document).on('input', 'input[name="amount"]', function () {
    var value = $(this).val().replace(/[^\d]/g, '');
    if (value) {
      var formatted = parseInt(value).toLocaleString('vi-VN');
      $(this).val(formatted);
    }
  });

  // Format số tiền cho đơn giá
  $(document).on('input', '.service-price', function () {
    const value = $(this).val().replace(/[^\d]/g, '');
    if (value) {
      const formatted = parseInt(value).toLocaleString('vi-VN');
      $(this).val(formatted);
    }
  });

  // Format số tiền cho thành tiền
  $(document).on('input', '.service-total', function () {
    const value = $(this).val().replace(/[^\d]/g, '');
    if (value) {
      const formatted = parseInt(value).toLocaleString('vi-VN');
      $(this).val(formatted);
    }
  });

  // Bỏ format khi submit
  $('#sell-contract-form').on('submit', function (e) {
    // Bỏ format cho amount
    $('input[name="amount"]').val(function (_, val) {
      return val.replace(/[^\d]/g, '');
    });

    // Bỏ format cho tất cả input price của dịch vụ
    $('.service-price').val(function (_, val) {
      return val.replace(/[^\d]/g, '');
    });

    // Bỏ format cho tất cả input total của dịch vụ
    $('.service-total').val(function (_, val) {
      return val.replace(/[^\d]/g, '');
    });
  });

  // Validation
  $('#sell-contract-form').validate({
    ignore: ':disabled',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      if (element.hasClass('select2-hidden-accessible')) {
        error.insertAfter(element.next('.select2-container'));
        // Add invalid class to select2 container
        element.next('.select2-container').addClass('is-invalid');
      } else {
        error.insertAfter(element);
      }
    },
    highlight: function (element) {
      $(element).addClass('is-invalid');
      if ($(element).hasClass('select2-hidden-accessible')) {
        $(element).next('.select2-container').addClass('is-invalid');
        $(element)
          .next('.select2-container')
          .find('.select2-selection')
          .addClass('is-invalid');
      }
    },
    unhighlight: function (element) {
      $(element).removeClass('is-invalid');
      if ($(element).hasClass('select2-hidden-accessible')) {
        $(element).next('.select2-container').removeClass('is-invalid');
        $(element)
          .next('.select2-container')
          .find('.select2-selection')
          .removeClass('is-invalid');
      }
    },
    rules: {
      customer_id: { required: true },
      expired_at: { required: true },
      status: { required: true },
      note: { maxlength: 500 },
      // Initial service validation
      'services[0][category_id]': { required: true },
      'services[0][service_id]': { required: true },
      'services[0][product_id]': { required: true },
      'services[0][quantity]': { required: true, min: 1 },
      'services[0][price]': { required: true, formattedNumber: true },
    },
    messages: {
      customer_id: { required: 'Vui lòng chọn khách hàng' },
      expired_at: { required: 'Vui lòng nhập hạn hợp đồng' },
      status: { required: 'Vui lòng chọn trạng thái' },
      note: { maxlength: 'Ghi chú không vượt quá 500 ký tự' },
      // Initial service messages
      'services[0][category_id]': { required: 'Vui lòng chọn danh mục' },
      'services[0][service_id]': { required: 'Vui lòng chọn dịch vụ' },
      'services[0][product_id]': { required: 'Vui lòng chọn gói' },
      'services[0][quantity]': {
        required: 'Vui lòng nhập số lượng',
        min: 'Số lượng phải lớn hơn 0',
      },
      'services[0][price]': {
        required: 'Vui lòng nhập đơn giá',
        formattedNumber: 'Vui lòng nhập đơn giá hợp lệ'
      },
    },
    submitHandler: function (form) {
      form.submit();
    },
  });

  // Dynamic validation for services
  function addServiceValidation(index) {
    const validator = $('#sell-contract-form').validate();

    const rules = {};
    const messages = {};

    // Category validation
    rules[`services[${index}][category_id]`] = { required: true };
    messages[`services[${index}][category_id]`] = {
      required: 'Vui lòng chọn danh mục',
    };

    // Service validation
    rules[`services[${index}][service_id]`] = { required: true };
    messages[`services[${index}][service_id]`] = {
      required: 'Vui lòng chọn dịch vụ',
    };

    // Product validation
    rules[`services[${index}][product_id]`] = { required: true };
    messages[`services[${index}][product_id]`] = {
      required: 'Vui lòng chọn gói',
    };

    // Quantity validation
    rules[`services[${index}][quantity]`] = { required: true, min: 1 };
    messages[`services[${index}][quantity]`] = {
      required: 'Vui lòng nhập số lượng',
      min: 'Số lượng phải lớn hơn 0',
    };

    // Price validation
    rules[`services[${index}][price]`] = { required: true, formattedNumber: true };
    messages[`services[${index}][price]`] = {
      required: 'Vui lòng chọn gói để có đơn giá',
      formattedNumber: 'Vui lòng nhập đơn giá hợp lệ'
    };

    $.extend(validator.settings.rules, rules);
    $.extend(validator.settings.messages, messages);
  }

  // Add validation for initial service
  addServiceValidation(0);

  // Add validation for existing service rows on page load
  $(document).ready(function() {
    $('.service-row').each(function(index) {
      if (index > 0) { // Skip the first one as it's already validated
        addServiceValidation(index);
      }
    });
  });

  // Real-time validation for select2 fields
  $(document).on(
    'change',
    '.select2-category, .select2-service, .select2-product',
    function () {
      // Validate current element
      $('#sell-contract-form').validate().element($(this));

      // For product change, preserve price validation state and format
      if ($(this).hasClass('select2-product')) {
        const index = $(this).data('index');
        const priceInput = $(`.service-price[name*="[${index}]"]`);

        // Re-validate price field after product change to maintain validation state
        setTimeout(function() {
          $('#sell-contract-form').validate().element(priceInput);

          // Always ensure format is applied after validation
          setTimeout(function() {
            const currentValue = priceInput.val();
            const numericValue = currentValue.replace(/[^\d]/g, '');

            if (numericValue && numericValue.length > 3 && !currentValue.includes('.')) {
              const formatted = parseInt(numericValue).toLocaleString('vi-VN');
              priceInput.val(formatted);
            }
          }, 10);
        }, 100);
      }
    }
  );

  // Real-time validation for quantity and price
  $(document).on(
    'input change',
    '.service-quantity, .service-price',
    function () {
      // For price fields, preserve format during validation
      if ($(this).hasClass('service-price')) {
        const element = $(this);
        const currentValue = element.val();

        // Store the original formatted value
        const originalValue = currentValue;

        // Validate the element
        $('#sell-contract-form').validate().element(element);

        // Check if validation changed the value and restore format if needed
        setTimeout(function() {
          const newValue = element.val();
          const numericValue = newValue.replace(/[^\d]/g, '');

          // If we have a numeric value and it's not already formatted, format it
          if (numericValue && numericValue.length > 3 && !newValue.includes('.')) {
            const formatted = parseInt(numericValue).toLocaleString('vi-VN');
            element.val(formatted);
          }
        }, 10);
      } else {
        $('#sell-contract-form').validate().element($(this));
      }
    }
  );

  // Handle service removal - revalidate form
  $(document).on('click', '.remove-service', function() {
    setTimeout(function() {
      $('#sell-contract-form').validate().resetForm();
      // Re-add validation for remaining services
      $('.service-row').each(function(index) {
        addServiceValidation(index);
      });
    }, 100);
  });

  // Handle form reset - revalidate form
  $(document).on('reset', '#sell-contract-form', function() {
    setTimeout(function() {
      $('#sell-contract-form').validate().resetForm();
      // Re-add validation for remaining services
      $('.service-row').each(function(index) {
        addServiceValidation(index);
      });
    }, 100);
  });

  // Add validation triggers for main fields
  $(document).on('change', '#customer_select, #status_select, #expired_at', function() {
    $('#sell-contract-form').validate().element($(this));
  });

  // Function to restore format for all price and total fields
  function restoreNumberFormat() {
    $('.service-price, .service-total').each(function() {
      const currentValue = $(this).val();
      const numericValue = currentValue.replace(/[^\d]/g, '');

      // Format if we have a numeric value with more than 3 digits and it's not already formatted
      if (numericValue && numericValue.length > 3 && !currentValue.includes('.')) {
        const formatted = parseInt(numericValue).toLocaleString('vi-VN');
        $(this).val(formatted);
      }
    });
  }

  // Restore format after any validation that might have removed it
  $(document).on('focusout', '.service-price, .service-total', function() {
    setTimeout(restoreNumberFormat, 50);
  });

  // Also restore format after form validation events
  $(document).on('invalid-form.validate', '#sell-contract-form', function() {
    setTimeout(restoreNumberFormat, 100);
  });

  $(document).on('valid-form.validate', '#sell-contract-form', function() {
    setTimeout(restoreNumberFormat, 100);
  });

  // Expose function globally for form to use
  window.addServiceValidation = addServiceValidation;
  window.restoreNumberFormat = restoreNumberFormat;
});
