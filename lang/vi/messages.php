<?php

return [
  /*
    |--------------------------------------------------------------------------
    | General Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for general messages throughout
    | the application such as success, error, confirmation messages, etc.
    |
    */

  // General actions
  'save' => 'Lưu',
  'cancel' => 'Hủy',
  'submit' => 'Gửi',
  'create' => 'Tạo mới',
  'edit' => 'Chỉnh sửa',
  'update' => 'Cập nhật',
  'delete' => 'Xóa',
  'view' => 'Xem',
  'back' => 'Quay lại',
  'next' => 'Tiếp theo',
  'previous' => 'Trước',
  'search' => 'Tìm kiếm',
  'filter' => 'Lọc',
  'reset' => 'Đặt lại',
  'refresh' => 'Làm mới',
  'export' => 'Xuất',
  'import' => 'Nhập',
  'download' => 'Tải xuống',
  'upload' => 'Tải lên',
  'select' => 'Chọn',
  'confirm' => 'Xác nhận',
  'close' => 'Đóng',
  'yes' => 'Có',
  'no' => 'Không',
  'ok' => 'OK',

  // Success messages
  'success' => [
    'saved' => 'Đã lưu thành công!',
    'created' => 'Tạo mới thành công!',
    'updated' => 'Cập nhật thành công!',
    'deleted' => 'Xóa thành công!',
    'uploaded' => 'Tải lên thành công!',
    'downloaded' => 'Tải xuống thành công!',
    'exported' => 'Xuất dữ liệu thành công!',
    'imported' => 'Nhập dữ liệu thành công!',
    'sent' => 'Gửi thành công!',
    'processed' => 'Xử lý thành công!',
    'completed' => 'Hoàn thành thành công!',
    'operation_completed' => 'Thao tác đã hoàn thành!',
  ],

  // Error messages
  'error' => [
    'general' => 'Đã xảy ra lỗi. Vui lòng thử lại.',
    'not_found' => 'Không tìm thấy dữ liệu.',
    'unauthorized' => 'Bạn không có quyền thực hiện thao tác này.',
    'forbidden' => 'Truy cập bị từ chối.',
    'server_error' => 'Lỗi máy chủ. Vui lòng liên hệ quản trị viên.',
    'validation_failed' => 'Dữ liệu nhập không hợp lệ.',
    'file_upload_failed' => 'Tải lên tệp thất bại.',
    'file_not_found' => 'Không tìm thấy tệp.',
    'file_too_large' => 'Tệp quá lớn.',
    'invalid_file_type' => 'Loại tệp không hợp lệ.',
    'database_error' => 'Lỗi cơ sở dữ liệu.',
    'network_error' => 'Lỗi kết nối mạng.',
    'timeout' => 'Hết thời gian chờ.',
    'operation_failed' => 'Thao tác thất bại.',
  ],

  // Warning messages
  'warning' => [
    'unsaved_changes' => 'Bạn có thay đổi chưa được lưu. Bạn có muốn rời khỏi trang?',
    'irreversible_action' => 'Thao tác này không thể hoàn tác.',
    'data_will_be_lost' => 'Dữ liệu sẽ bị mất.',
    'confirm_delete' => 'Bạn có chắc chắn muốn xóa?',
    'confirm_action' => 'Bạn có chắc chắn muốn thực hiện thao tác này?',
    'large_dataset' => 'Tập dữ liệu lớn có thể mất thời gian xử lý.',
  ],

  // Info messages
  'info' => [
    'no_data' => 'Không có dữ liệu.',
    'loading' => 'Đang tải...',
    'processing' => 'Đang xử lý...',
    'please_wait' => 'Vui lòng đợi...',
    'data_loading' => 'Đang tải dữ liệu...',
    'saving' => 'Đang lưu...',
    'uploading' => 'Đang tải lên...',
    'downloading' => 'Đang tải xuống...',
    'sending' => 'Đang gửi...',
    'optional' => 'Tùy chọn',
    'required' => 'Bắt buộc',
    'auto_save' => 'Tự động lưu',
  ],

  // Form validation messages
  'validation' => [
    'required' => 'Trường này là bắt buộc.',
    'email' => 'Email không hợp lệ.',
    'min' => 'Phải có ít nhất :min ký tự.',
    'max' => 'Không được vượt quá :max ký tự.',
    'numeric' => 'Phải là số.',
    'integer' => 'Phải là số nguyên.',
    'date' => 'Ngày không hợp lệ.',
    'unique' => 'Giá trị này đã được sử dụng.',
    'confirmed' => 'Xác nhận không khớp.',
    'between' => 'Phải từ :min đến :max ký tự.',
    'in' => 'Giá trị được chọn không hợp lệ.',
    'image' => 'Phải là tệp hình ảnh.',
    'mimes' => 'Phải là tệp có định dạng: :values.',
    'size' => 'Kích thước tệp không được vượt quá :size KB.',
  ],

  // Pagination
  'pagination' => [
    'previous' => '&laquo; Trước',
    'next' => 'Tiếp &raquo;',
    'showing' => 'Hiển thị :from đến :to trong tổng số :total kết quả',
    'results' => ':total kết quả',
    'no_results' => 'Không có kết quả nào.',
    'page' => 'Trang',
    'of' => 'của',
    'per_page' => 'mỗi trang',
    'go_to_page' => 'Đi đến trang',
  ],

  // Table
  'table' => [
    'id' => 'ID',
    'name' => 'Tên',
    'title' => 'Tiêu đề',
    'description' => 'Mô tả',
    'status' => 'Trạng thái',
    'created_at' => 'Ngày tạo',
    'updated_at' => 'Ngày cập nhật',
    'actions' => 'Thao tác',
    'select_all' => 'Chọn tất cả',
    'no_data' => 'Không có dữ liệu',
    'empty_table' => 'Bảng trống',
    'sort_asc' => 'Sắp xếp tăng dần',
    'sort_desc' => 'Sắp xếp giảm dần',
  ],

  // Status
  'status' => [
    'active' => 'Hoạt động',
    'inactive' => 'Không hoạt động',
    'enabled' => 'Bật',
    'disabled' => 'Tắt',
    'published' => 'Đã xuất bản',
    'draft' => 'Bản nháp',
    'pending' => 'Đang chờ',
    'approved' => 'Đã duyệt',
    'rejected' => 'Bị từ chối',
    'completed' => 'Hoàn thành',
    'processing' => 'Đang xử lý',
    'cancelled' => 'Đã hủy',
    'expired' => 'Đã hết hạn',
  ],

  // Date and time
  'datetime' => [
    'today' => 'Hôm nay',
    'yesterday' => 'Hôm qua',
    'tomorrow' => 'Ngày mai',
    'this_week' => 'Tuần này',
    'last_week' => 'Tuần trước',
    'next_week' => 'Tuần sau',
    'this_month' => 'Tháng này',
    'last_month' => 'Tháng trước',
    'next_month' => 'Tháng sau',
    'this_year' => 'Năm nay',
    'last_year' => 'Năm trước',
    'next_year' => 'Năm sau',
    'never' => 'Không bao giờ',
    'just_now' => 'Vừa xong',
    'ago' => ':time trước',
    'in' => 'trong :time',
  ],

  // File operations
  'file' => [
    'choose_file' => 'Chọn tệp',
    'drop_files' => 'Kéo thả tệp vào đây',
    'file_selected' => 'Đã chọn tệp',
    'files_selected' => 'Đã chọn :count tệp',
    'max_file_size' => 'Kích thước tối đa: :size',
    'allowed_types' => 'Loại tệp cho phép: :types',
    'upload_progress' => 'Tiến trình tải lên: :percent%',
    'upload_complete' => 'Tải lên hoàn tất',
    'upload_failed' => 'Tải lên thất bại',
  ],

  // Navigation
  'navigation' => [
    'home' => 'Trang chủ',
    'dashboard' => 'Bảng điều khiển',
    'menu' => 'Menu',
    'settings' => 'Cài đặt',
    'help' => 'Trợ giúp',
    'about' => 'Giới thiệu',
    'contact' => 'Liên hệ',
    'profile' => 'Hồ sơ',
    'logout' => 'Đăng xuất',
    'breadcrumb_home' => 'Trang chủ',
  ],

  // System
  'system' => [
    'maintenance' => 'Hệ thống đang bảo trì',
    'coming_soon' => 'Sắp ra mắt',
    'under_construction' => 'Đang xây dựng',
    'version' => 'Phiên bản',
    'last_updated' => 'Cập nhật lần cuối',
    'powered_by' => 'Được hỗ trợ bởi',
  ],

  // Common labels
  'labels' => [
    'all' => 'Tất cả',
    'none' => 'Không có',
    'other' => 'Khác',
    'custom' => 'Tùy chỉnh',
    'default' => 'Mặc định',
    'optional' => '(Tùy chọn)',
    'required' => '(Bắt buộc)',
    'recommended' => '(Đề xuất)',
    'new' => 'Mới',
    'featured' => 'Nổi bật',
    'popular' => 'Phổ biến',
    'recent' => 'Gần đây',
    'latest' => 'Mới nhất',
    'oldest' => 'Cũ nhất',
  ],
];
