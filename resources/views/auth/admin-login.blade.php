<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống Quản trị - Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/scss/admin/app.scss', 'resources/js/admin/app.js'])
    <style>
        .auth-card {
            border-top: 5px solid #009688;
        }
    </style>
</head>
<body class="auth-bg">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark">KHOA QUYÊN Admin</h2>
                    <p class="text-muted">Đăng nhập vào hệ thống quản lý</p>
                </div>
                <div class="auth-card shadow p-4 bg-white rounded-4">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label fw-600">Tên đăng nhập</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                class="form-control @error('username') is-invalid @enderror" required autofocus>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-600">Mật khẩu</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">Ghi nhớ phiên làm việc</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                VÀO HỆ THỐNG
                            </button>
                        </div>
                    </form>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('client.home') }}" class="text-muted text-decoration-none small">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại cửa hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
