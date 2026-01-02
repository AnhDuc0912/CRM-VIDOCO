<?php

return [
  /*
    |--------------------------------------------------------------------------
    | User Management Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for user management operations
    | such as CRUD operations, role assignments, etc.
    |
    */

  // General user messages
  'user' => 'Người dùng',
  'users' => 'Người dùng',
  'name' => 'Họ tên',
  'email' => 'Email',
  'password' => 'Mật khẩu',
  'password_confirmation' => 'Xác nhận mật khẩu',
  'role' => 'Vai trò',
  'roles' => 'Vai trò',
  'permissions' => 'Quyền',
  'status' => 'Trạng thái',
  'created_at' => 'Ngày tạo',
  'updated_at' => 'Ngày cập nhật',

  // Actions
  'actions' => [
    'create' => 'Tạo mới',
    'edit' => 'Chỉnh sửa',
    'update' => 'Cập nhật',
    'delete' => 'Xóa',
    'view' => 'Xem',
    'search' => 'Tìm kiếm',
    'filter' => 'Lọc',
    'export' => 'Xuất dữ liệu',
    'import' => 'Nhập dữ liệu',
    'bulk_action' => 'Thao tác hàng loạt',
    'assign_role' => 'Gán vai trò',
    'remove_role' => 'Gỡ vai trò',
    'change_password' => 'Đổi mật khẩu',
    'activate' => 'Kích hoạt',
    'deactivate' => 'Vô hiệu hóa',
  ],

  // Status
  'status_options' => [
    'active' => 'Hoạt động',
    'inactive' => 'Không hoạt động',
    'verified' => 'Đã xác thực',
    'unverified' => 'Chưa xác thực',
    'all' => 'Tất cả',
  ],

  // Messages
  'messages' => [
    'created' => 'Tạo người dùng thành công!',
    'updated' => 'Cập nhật người dùng thành công!',
    'deleted' => 'Xóa người dùng thành công!',
    'not_found' => 'Không tìm thấy người dùng.',
    'cannot_delete_self' => 'Bạn không thể xóa chính mình.',
    'cannot_delete_admin' => 'Không thể xóa tài khoản quản trị viên.',
    'password_changed' => 'Đổi mật khẩu thành công!',
    'role_assigned' => 'Gán vai trò thành công!',
    'role_removed' => 'Gỡ vai trò thành công!',
    'bulk_role_assigned' => 'Gán vai trò cho :count người dùng thành công!',
    'bulk_role_removed' => 'Gỡ vai trò cho :count người dùng thành công!',
    'activated' => 'Kích hoạt người dùng thành công!',
    'deactivated' => 'Vô hiệu hóa người dùng thành công!',
    'no_users_found' => 'Không tìm thấy người dùng nào.',
    'search_results' => 'Tìm thấy :count kết quả cho ":query"',
  ],

  // Validation messages
  'validation' => [
    'name_required' => 'Họ tên là bắt buộc.',
    'name_max' => 'Họ tên không được vượt quá 255 ký tự.',
    'email_required' => 'Email là bắt buộc.',
    'email_invalid' => 'Email không hợp lệ.',
    'email_unique' => 'Email này đã được sử dụng.',
    'password_required' => 'Mật khẩu là bắt buộc.',
    'password_min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
    'password_confirmed' => 'Xác nhận mật khẩu không khớp.',
    'role_required' => 'Vai trò là bắt buộc.',
    'role_exists' => 'Vai trò không tồn tại.',
  ],

  // Forms
  'forms' => [
    'create_user' => [
      'title' => 'Tạo người dùng mới',
      'subtitle' => 'Nhập thông tin để tạo tài khoản mới',
      'submit' => 'Tạo người dùng',
    ],
    'edit_user' => [
      'title' => 'Chỉnh sửa người dùng',
      'subtitle' => 'Cập nhật thông tin người dùng',
      'submit' => 'Cập nhật',
    ],
    'change_password' => [
      'title' => 'Đổi mật khẩu',
      'subtitle' => 'Nhập mật khẩu mới',
      'current_password' => 'Mật khẩu hiện tại',
      'new_password' => 'Mật khẩu mới',
      'confirm_password' => 'Xác nhận mật khẩu mới',
      'submit' => 'Đổi mật khẩu',
    ],
    'search' => [
      'placeholder' => 'Tìm kiếm theo tên hoặc email...',
      'advanced' => 'Tìm kiếm nâng cao',
      'from_date' => 'Từ ngày',
      'to_date' => 'Đến ngày',
      'submit' => 'Tìm kiếm',
      'reset' => 'Đặt lại',
    ],
    'bulk_actions' => [
      'select_action' => 'Chọn thao tác',
      'assign_role' => 'Gán vai trò',
      'remove_role' => 'Gỡ vai trò',
      'activate' => 'Kích hoạt',
      'deactivate' => 'Vô hiệu hóa',
      'delete' => 'Xóa',
      'submit' => 'Thực hiện',
      'confirm' => 'Bạn có chắc chắn muốn thực hiện thao tác này?',
    ],
  ],

  // Statistics
  'statistics' => [
    'title' => 'Thống kê người dùng',
    'total_users' => 'Tổng số người dùng',
    'active_users' => 'Người dùng hoạt động',
    'inactive_users' => 'Người dùng không hoạt động',
    'recent_users' => 'Người dùng mới (7 ngày)',
    'users_by_role' => 'Phân bố theo vai trò',
    'users_by_month' => 'Người dùng theo tháng',
  ],

  // Roles
  'roles' => [
    'super_admin' => 'Quản trị viên tối cao',
    'admin' => 'Quản trị viên',
    'manager' => 'Quản lý',
    'staff' => 'Nhân viên',
    'employee' => 'Nhân viên',
    'user' => 'Người dùng',
  ],

  // Permissions
  'permissions_list' => [
    'view_users' => 'Xem người dùng',
    'create_users' => 'Tạo người dùng',
    'edit_users' => 'Sửa người dùng',
    'delete_users' => 'Xóa người dùng',
    'view_employees' => 'Xem nhân viên',
    'create_employees' => 'Tạo nhân viên',
    'edit_employees' => 'Sửa nhân viên',
    'delete_employees' => 'Xóa nhân viên',
    'view_roles' => 'Xem vai trò',
    'create_roles' => 'Tạo vai trò',
    'edit_roles' => 'Sửa vai trò',
    'delete_roles' => 'Xóa vai trò',
    'view_permissions' => 'Xem quyền',
    'manage_permissions' => 'Quản lý quyền',
    'view_dashboard' => 'Xem trang chủ',
    'view_reports' => 'Xem báo cáo',
    'view_analytics' => 'Xem phân tích',
    'manage_settings' => 'Quản lý cài đặt',
    'manage_system' => 'Quản lý hệ thống',
    'view_logs' => 'Xem nhật ký',
  ],

  // Table headers
  'table' => [
    'id' => 'ID',
    'name' => 'Họ tên',
    'email' => 'Email',
    'role' => 'Vai trò',
    'status' => 'Trạng thái',
    'last_login' => 'Đăng nhập cuối',
    'created_at' => 'Ngày tạo',
    'actions' => 'Thao tác',
    'select_all' => 'Chọn tất cả',
    'no_data' => 'Không có dữ liệu',
    'showing' => 'Hiển thị :from đến :to trong tổng số :total kết quả',
  ],

  // Confirmations
  'confirmations' => [
    'delete' => 'Bạn có chắc chắn muốn xóa người dùng này?',
    'delete_multiple' => 'Bạn có chắc chắn muốn xóa :count người dùng đã chọn?',
    'change_role' => 'Bạn có chắc chắn muốn thay đổi vai trò của người dùng này?',
    'deactivate' => 'Bạn có chắc chắn muốn vô hiệu hóa người dùng này?',
    'activate' => 'Bạn có chắc chắn muốn kích hoạt người dùng này?',
  ],
];
