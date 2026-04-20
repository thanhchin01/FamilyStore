<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang | Gia dụng Khoa Quyên</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #0ea5e9;
            --dark-color: #0f172a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: var(--dark-color);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 40px;
            position: relative;
        }

        .error-code {
            font-size: 10rem;
            font-weight: 800;
            line-height: 1;
            margin: 0;
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            opacity: 0.15;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        .error-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-color);
        }

        p {
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.3);
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(14, 165, 233, 0.4);
            background: #0284c7;
        }

        .search-suggestion {
            margin-top: 40px;
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .search-suggestion a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-icon">
            <i class="fas fa-search-minus"></i>
        </div>
        <h1>Ối! Trang này đã "đi lạc" rồi</h1>
        <p>Có vẻ như đường dẫn bạn đang truy cập không tồn tại hoặc đã được chuyển sang một địa chỉ khác. Đừng lo lắng, hãy để chúng tôi đưa bạn về nhà!</p>
        
        <a href="/" class="btn-home">
            <i class="fas fa-home me-2"></i> Trở về trang chủ
        </a>

        <div class="search-suggestion">
            Bạn cần hỗ trợ? <a href="/contact">Liên hệ ngay với chúng tôi</a>
        </div>
    </div>

</body>
</html>
