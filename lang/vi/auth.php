<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Email hoặc mật khẩu không chính xác.',
    'password' => 'Mật khẩu không đúng.',
    'throttle' => 'Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau :seconds giây.',

    // Login messages
    'login' => [
        'title' => 'TẠO KHÁCH HÀNG CHO KHÁCH HÀNG',
        'subtitle' => 'Nhập email và mật khẩu của bạn',
        'email' => 'Email',
        'password' => 'Mật khẩu',
        'remember_me' => 'Ghi nhớ đăng nhập',
        'submit' => 'Đăng nhập',
        'forgot_password' => 'Quên mật khẩu?',
        'no_account' => 'Bạn chưa có tài khoản? Liên hệ quản trị viên để được cấp tài khoản.',
        'success' => 'Đăng nhập thành công!',
        'invalid_credentials' => 'Email hoặc mật khẩu không chính xác.',
    ],

    // Logout messages
    'logout' => [
        'success' => 'Đăng xuất thành công!',
        'confirm' => 'Bạn có chắc chắn muốn đăng xuất?',
    ],

    // Forgot password messages
    'forgot_password' => [
        'title' => 'Quên mật khẩu',
        'subtitle' => 'Nhập email để nhận link khôi phục mật khẩu',
        'email' => 'Email',
        'submit' => 'Gửi link khôi phục',
        'back_to_login' => '← Quay lại đăng nhập',
        'note' => 'Link khôi phục mật khẩu sẽ có hiệu lực trong 5 phút.',
        'success' => 'Link khôi phục mật khẩu đã được gửi đến email của bạn. Link có hiệu lực trong 5 phút.',
        'email_not_found' => 'Email này không tồn tại trong hệ thống.',
    ],

    // Reset password messages
    'reset_password' => [
        'title' => 'Khôi phục mật khẩu',
        'subtitle' => 'Nhập mật khẩu mới cho tài khoản của bạn',
        'email' => 'Email',
        'password' => 'Mật khẩu mới',
        'password_confirmation' => 'Xác nhận mật khẩu',
        'submit' => 'Cập nhật mật khẩu',
        'back_to_login' => '← Quay lại đăng nhập',
        'note' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        'success' => 'Mật khẩu đã được cập nhật thành công!',
        'invalid_token' => 'Token không hợp lệ.',
        'expired_token' => 'Token đã hết hạn.',
        'expired_link' => 'Link khôi phục mật khẩu đã hết hạn hoặc không hợp lệ.',
    ],

    // Email messages
    'email' => [
        'reset_password' => [
            'subject' => 'Khôi phục mật khẩu - :app_name',
            'greeting' => 'Xin chào :name',
            'intro' => 'Chúng tôi đã nhận được yêu cầu khôi phục mật khẩu cho tài khoản của bạn.',
            'action' => 'Khôi phục mật khẩu',
            'note_title' => 'Lưu ý quan trọng:',
            'note_items' => [
                'Link này chỉ có hiệu lực trong 5 phút',
                'Nếu bạn không yêu cầu khôi phục mật khẩu, hãy bỏ qua email này',
                'Không chia sẻ link này với bất kỳ ai',
            ],
            'alternative' => 'Nếu nút không hoạt động, bạn có thể sao chép và dán link sau vào trình duyệt:',
            'support' => 'Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với bộ phận hỗ trợ.',
            'signature' => 'Trân trọng,',
            'footer' => 'Bảo mật và quyền riêng tư.',
        ],
    ],
];
