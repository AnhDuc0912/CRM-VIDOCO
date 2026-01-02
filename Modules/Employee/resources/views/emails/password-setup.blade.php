<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiết lập mật khẩu tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Thiết lập mật khẩu tài khoản</h1>
    </div>

    <div class="content">
        <p>Xin chào <strong>{{ $employee->full_name }}</strong>,</p>

        <p>Bạn đã được tạo tài khoản trong hệ thống <strong>{{ config('app.name') }}</strong>.</p>

        <p>Để hoàn tất việc thiết lập tài khoản, vui lòng nhấn vào nút bên dưới để tạo mật khẩu:</p>

        <div style="text-align: center;">
            <a href="{{ $setupUrl }}" class="btn" style="color: white;">Thiết lập mật khẩu</a>
        </div>

        <div class="warning">
            <strong>Lưu ý:</strong> Link này có hiệu lực trong 24 giờ. Vui lòng thiết lập mật khẩu trong thời gian này.
        </div>

        <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>

        <p>Trân trọng,<br>
            <strong>{{ config('app.name') }}</strong>
        </p>
    </div>

    <div class="footer">
        <p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
        <p>Nếu bạn có thắc mắc, vui lòng liên hệ với quản trị viên hệ thống.</p>
    </div>
</body>

</html>
