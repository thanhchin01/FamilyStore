<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Khoa Quyen Store - Chào mừng bạn!')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/store.scss'])
    <style>
        :root {
            --primary: #5c62ec;
            --primary-dark: #4b52d1;
            --accent: #f83a26;
            --bg-glass: rgba(255, 255, 255, 0.85);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }

        .navbar-custom {
            background-color: var(--bg-glass);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--primary) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
            color: #555 !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .cart-icon {
            position: relative;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: var(--accent);
            color: #fff;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 50%;
        }

        .store-footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 40px 0;
            margin-top: 60px;
        }

        .store-footer a {
            color: #bdc3c7;
            text-decoration: none;
            transition: 0.3s;
        }

        .store-footer a:hover {
            color: #fff;
        }

        /* Hover animations for product cards */
        .product-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .product-img-wrapper {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1/1;
        }

        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img-wrapper img {
            transform: scale(1.1);
        }

        .btn-primary-custom {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
            transform: scale(1.02);
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('store.home') }}">
                <i class="fas fa-shopping-bag"></i> K-Q Store
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('store.home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Chính sách</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="{{ route('store.cart') }}" class="nav-link cart-icon mx-3">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="cart-badge">3</span>
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Đăng nhập</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="store-footer">
        <div class="container text-center text-md-start">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Khoa Quyên Store</h5>
                    <p>Địa chỉ uy tín cung cấp sỉ & lẻ các mặt hàng gia dụng, điện máy chính hãng. Mang chất lượng đến mọi nhà.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('store.home') }}">Trang chủ</a></li>
                        <li><a href="#">Sản phẩm</a></li>
                        <li><a href="#">Giỏ hàng</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liên hệ</h5>
                    <p><i class="fas fa-phone-alt me-2"></i> 090xxxxxxx</p>
                    <p><i class="fas fa-envelope me-2"></i> info@khoaquyen.com</p>
                    <div class="mt-3">
                        <a href="#" class="me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-4 border-secondary">
            <div class="text-center mt-3">
                <p class="mb-0 text-secondary">&copy; {{ date('Y') }} Khoa Quyên Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
