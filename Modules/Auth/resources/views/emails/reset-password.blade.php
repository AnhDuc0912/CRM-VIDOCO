<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi phục mật khẩu</title>
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
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .button:hover {
            background: #0056b3;
        }

        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Khôi phục mật khẩu</h1>
    </div>

    <div class="content">
        <p>Xin chào <strong>{{ $user->name }}</strong>,</p>

        <p>Chúng tôi đã nhận được yêu cầu khôi phục mật khẩu cho tài khoản của
            bạn.</p>

        <p>Vui lòng nhấp vào nút bên dưới để tiến hành khôi phục mật khẩu:</p>

        <div style="text-align: center; color: #fff;">
            <a href="{{ $resetUrl }}" style="color: #fff;" class="button">Khôi
                phục mật khẩu</a>
        </div>

        <div class="warning">
            <strong>Lưu ý quan trọng:</strong>
            <ul>
                <li>Link này chỉ có hiệu lực trong <strong>5 phút</strong></li>
                <li>Nếu bạn không yêu cầu khôi phục mật khẩu, hãy bỏ qua email
                    này</li>
                <li>Không chia sẻ link này với bất kỳ ai</li>
            </ul>
        </div>

        <p>Nếu nút không hoạt động, bạn có thể sao chép và dán link sau vào
            trình duyệt:</p>
        <p
            style="word-break: break-all; background: #e9ecef; padding: 10px; border-radius: 5px;">
            {{ $resetUrl }}
        </p>

        <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với bộ phận hỗ trợ.
        </p>

        <p>Trân trọng,<br>
            <strong>{{ config('app.name') }}</strong>
        </p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Bảo mật và quyền
            riêng tư.</p>
    </div>
</body>

</html>
