<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SCSS compile -->
    @vite(['resources/scss/admin/login.scss'])
</head>

<body class="admin-login">

    <div class="login-wrapper">
        <div class="login-card shadow-lg">
            <!-- Left -->
            <div class="login-left d-none d-md-flex">
                <div class="overlay">
                    <h2>Admin Panel</h2>
                    <p>Quản lý hệ thống một cách chuyên nghiệp</p>
                </div>
            </div>

            <!-- Right -->
            <div class="login-right">
                <h4 class="fw-bold mb-2">Đăng nhập quản trị</h4>
                <p class="text-muted mb-4">Vui lòng đăng nhập để tiếp tục</p>

                <form method="POST" action="#">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-icon">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" class="form-control" placeholder="admin@email.com">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <div class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Ghi nhớ</label>
                        </div>
                        <a href="#" class="text-decoration-none small">Quên mật khẩu?</a>
                    </div>

                    <button class="btn btn-primary w-100 py-2">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng nhập
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
