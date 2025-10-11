<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành công</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            font-family: 'Poppins', sans-serif;
            flex-direction: column;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .message {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }
    </style>
    <script>
        setTimeout(() => {
            window.location.href = "/auth/login";
        }, 1500);
    </script>

</head>
<body>
    <div class="loader"></div>
    <div class="message">Đang xử lý đăng ký...</div>
</body>
</html>
