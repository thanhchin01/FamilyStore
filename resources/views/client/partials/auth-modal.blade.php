<!-- Auth Modal (Login / Register) -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-5 shadow-lg overflow-hidden">
            <div class="row g-0">
                <!-- Left Side: Branding -->
                <div class="col-md-5 d-none d-md-flex authentication-sidebar flex-column justify-content-center p-5 text-white">
                    <div class="mb-4">
                        <i class="fas fa-shopping-bag fa-3x mb-3"></i>
                        <h3 class="fw-bold">Khoa Quyên Store</h3>
                        <div class="header-line"></div>
                    </div>
                    <p class="opacity-75 lead small mb-5">Đăng nhập để tận hưởng tối ưu trải nghiệm mua sắm và theo dõi đơn hàng dễ dàng.</p>
                    <div class="auth-features">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span class="small">Tích điểm đổi quà hấp dẫn</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle me-3"></i>
                            <span class="small">Nhận ưu đãi độc quyền</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-3"></i>
                            <span class="small">Bảo mật thông tin tuyệt đối</span>
                        </div>
                    </div>
                </div>
                <!-- Right Side: Form -->
                <div class="col-md-7 p-4 p-md-5 bg-white position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                    <!-- Login Form -->
                    <div id="loginFormSection">
                        <div class="mb-4">
                            <h4 class="fw-800 mb-1">Mừng bạn trở lại</h4>
                            <p class="text-muted small">Vui lòng đăng nhập với tài khoản của bạn</p>
                        </div>
                        <form id="ajaxLoginForm" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label extra-small fw-bold text-uppercase">Email</label>
                                <div class="input-group border rounded-3 overflow-hidden bg-light">
                                    <span class="input-group-text bg-transparent border-0"><i class="far fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="example@mail.com" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <label class="form-label extra-small fw-bold text-uppercase">Mật khẩu</label>
                                    <a href="#" class="extra-small text-decoration-none">Quên mật khẩu?</a>
                                </div>
                                <div class="input-group border rounded-3 overflow-hidden bg-light">
                                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="••••••••" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold mb-4 shadow-sm">ĐĂNG NHẬP NGAY</button>
                            <p class="text-center small mb-0">Chưa có tài khoản? <a href="javascript:void(0)" class="fw-bold text-primary-color text-decoration-none" onclick="toggleAuthForm('register')">Đăng ký mới</a></p>
                        </form>
                    </div>

                    <!-- Register Form (Hidden by default) -->
                    <div id="registerFormSection" style="display: none;">
                        <div class="mb-4">
                            <h4 class="fw-800 mb-1">Tạo tài khoản mới</h4>
                            <p class="text-muted small">Khám phá thiên đường sắm đồ gia dụng</p>
                        </div>
                        <form id="ajaxRegisterForm" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label extra-small fw-bold text-uppercase">Họ và tên</label>
                                <div class="input-group border rounded-3 overflow-hidden bg-light">
                                    <span class="input-group-text bg-transparent border-0"><i class="far fa-user text-muted"></i></span>
                                    <input type="text" name="name" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="Nguyễn Văn A" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label extra-small fw-bold text-uppercase">Email</label>
                                <div class="input-group border rounded-3 overflow-hidden bg-light">
                                    <span class="input-group-text bg-transparent border-0"><i class="far fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="example@mail.com" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label extra-small fw-bold text-uppercase">Mật khẩu</label>
                                <div class="input-group border rounded-3 overflow-hidden bg-light">
                                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="Tối thiểu 8 ký tự" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label extra-small fw-bold text-uppercase">Xác nhận mật khẩu</label>
                                <div class="input-group border rounded-3 overflow-hidden bg-light">
                                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-shield-alt text-muted"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="Nhập lại mật khẩu" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold mb-4 shadow-sm">TẠO TÀI KHOẢN</button>
                            <p class="text-center small mb-0">Đã có tài khoản? <a href="javascript:void(0)" class="fw-bold text-primary-color text-decoration-none" onclick="toggleAuthForm('login')">Đăng nhập</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
