$(document).ready(function () {
  // Debug: Kiểm tra jQuery và jQuery validation plugin

  if (typeof $.validator === 'undefined') {
    console.error('jQuery validation plugin not loaded!');
    return;
  }

  // Tùy chỉnh thông báo lỗi mặc định cho tiếng Việt
  $.extend($.validator.messages, {
    required: 'Trường này là bắt buộc',
    email: 'Vui lòng nhập email hợp lệ',
    minlength: $.validator.format('Vui lòng nhập ít nhất {0} ký tự'),
    maxlength: $.validator.format('Vui lòng nhập không quá {0} ký tự'),
    date: 'Vui lòng nhập ngày hợp lệ',
    digits: 'Vui lòng chỉ nhập số',
    url: 'Vui lòng nhập URL hợp lệ',
  });

  // Tùy chỉnh method cho tên danh mục (chỉ cho phép chữ cái, số, dấu cách và một số ký tự đặc biệt)
  $.validator.addMethod(
    'category_name',
    function (value, element) {
      if (this.optional(element)) return true;
      return /^[a-zA-ZÀ-ỹ0-9\s\-_\.]+$/.test(value);
    },
    'Tên danh mục chỉ được chứa chữ cái, số, dấu cách và ký tự đặc biệt (-, _, .)'
  );

  // Tùy chỉnh method cho file upload
  $.validator.addMethod(
    'file_extension',
    function (value, element, param) {
      if (this.optional(element)) return true;
      var extension = value.split('.').pop().toLowerCase();
      return param.split(',').indexOf(extension) !== -1;
    },
    'Vui lòng chọn file có định dạng hợp lệ'
  );

  // Tùy chỉnh method cho kích thước file
  $.validator.addMethod(
    'file_size',
    function (value, element, param) {
      if (this.optional(element)) return true;
      var file = element.files[0];
      if (file && file.size > param * 1024 * 1024) {
        return false;
      }
      return true;
    },
    $.validator.format('Kích thước file không được vượt quá {0} MB')
  );

  // Debug: Kiểm tra form có tồn tại không
  var $form = $('#category-create-form');

  if ($form.length === 0) {
    console.error('Form #category-create-form not found!');
    return;
  }

  // Khởi tạo validation cho form
  var validator = $form.validate({
    // Validate tất cả field, kể cả hidden
    ignore: [],

    // Custom validation cho field ẩn
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
      $(element).addClass('is-valid').removeClass('is-invalid');
    },

    rules: {
      // Tên danh mục
      name: {
        required: true,
        minlength: 2,
        maxlength: 100,
        category_name: true,
      },

      // Trạng thái
      status: {
        required: true,
      },

      // File upload (nếu có)
      file: {
        file_extension:
          'xlsx,xls,jpg,jpeg,png,gif,doc,docx,mp3,wav,mp4,avi,mov,ppt,pptx,txt,pdf',
        file_size: 10, // 10MB
      },
    },

    messages: {
      name: {
        required: 'Vui lòng nhập tên danh mục',
        minlength: 'Tên danh mục phải có ít nhất 2 ký tự',
        maxlength: 'Tên danh mục không được quá 100 ký tự',
      },

      status: {
        required: 'Vui lòng chọn trạng thái',
      },

      file: {
        file_extension:
          'Vui lòng chọn file có định dạng hợp lệ (.xlsx, .xls, .jpg, .png, .doc, .pdf, ...)',
        file_size: 'Kích thước file không được vượt quá 10 MB',
      },
    },

    // Validate trước khi submit
    submitHandler: function (form) {
      // Kiểm tra xem có file được chọn không (nếu field file là required)
      var fileInput = $('input[name="file"]');
      if (fileInput.length && fileInput.prop('required')) {
        if (!fileInput[0].files || fileInput[0].files.length === 0) {
          return false;
        }
      }

      // Kiểm tra kích thước tổng của tất cả file
      var totalSize = 0;
      var maxTotalSize = 50 * 1024 * 1024; // 50MB tổng

      if (fileInput[0].files) {
        for (var i = 0; i < fileInput[0].files.length; i++) {
          totalSize += fileInput[0].files[i].size;
        }

        if (totalSize > maxTotalSize) {
          return false;
        }
      }

      showLoading();
      form.submit();
      return false;
    },

    // Debug: Log khi validation fail
    invalidHandler: function (event, validator) {},
  });

  // Debug: Kiểm tra validation đã được khởi tạo

  // QUAN TRỌNG: Thêm event handler trực tiếp cho form submit
  $form.on('submit', function (e) {
    // Kiểm tra validation
    if (!validator.form()) {
      e.preventDefault();
      e.stopPropagation();
      return false;
    }
  });

  // Thêm event handler cho button submit
  $('#submit-btn').on('click', function (e) {
    // Trigger validation
    if (!validator.form()) {
      e.preventDefault();
      e.stopPropagation();
      return false;
    }
  });

  // Trigger validation ngay khi user nhập vào field
  $(document).on(
    'input change blur',
    '#category-create-form input, #category-create-form select, #category-create-form textarea',
    function () {
      validator.element($(this));
    }
  );

  // Xử lý file upload preview (nếu cần)
  $('input[name="file"]').on('change', function () {
    var files = this.files;
    var fileList = '';

    if (files.length > 0) {
      fileList =
        '<div class="mt-2"><strong>Files đã chọn:</strong><ul class="list-unstyled">';
      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var size = (file.size / 1024 / 1024).toFixed(2);
        fileList +=
          '<li><i class="bx bx-file me-1"></i>' +
          file.name +
          ' (' +
          size +
          ' MB)</li>';
      }
      fileList += '</ul></div>';
    }

    // Hiển thị danh sách file (có thể tùy chỉnh vị trí hiển thị)
    var filePreview = $('#file-preview');
    if (filePreview.length) {
      filePreview.html(fileList);
    } else {
      // Tạo element preview nếu chưa có
      $('input[name="file"]').after(
        '<div id="file-preview">' + fileList + '</div>'
      );
    }
  });

  // Hiển thị loading khi submit
  function showLoading() {
    $('#submit-btn')
      .prop('disabled', true)
      .html(
        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...'
      );
  }
  // Thêm button reset nếu cần
  $(document).on('click', '#reset-btn', function (e) {
    e.preventDefault();
    $('#category-create-form')[0].reset();
  });

  // Trigger validation cho tất cả fields khi trang load để hiển thị dấu tích xanh
  setTimeout(function () {
    $('#category-create-form')
      .find('input, select, textarea')
      .each(function () {
        if ($(this).val()) {
          validator.element($(this));
        }
      });
  }, 500);

  // Export functions để có thể sử dụng từ bên ngoài
  window.CategoryValidation = {
    validate: function () {
      return validator.form();
    },
  };

  // Debug: Test validation manually

  // Test validation khi click submit
  $('#submit-btn').on('click', function (e) {
    var isValid = validator.form();

    if (!isValid) {
      e.preventDefault();
      return false;
    }
  });
});
