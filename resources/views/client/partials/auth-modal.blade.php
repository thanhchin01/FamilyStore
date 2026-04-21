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
                                    <a href="{{ route('password.request') }}" class="extra-small text-decoration-none">Quên mật khẩu?</a>
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
                        <div class="mb-3">
                            <h4 class="fw-800 mb-1">Tạo tài khoản mới</h4>
                            <p class="text-muted small">Khám phá thiên đường sắm đồ gia dụng</p>
                        </div>
                        
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 active" id="btnRegEmail" onclick="setRegType('email')">Dùng Email</button>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btnRegPhone" onclick="setRegType('phone')">Dùng Số điện thoại</button>
                        </div>

                        <form id="ajaxRegisterForm" action="{{ route('register') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" id="regType" value="email">
                            
                            <!-- Step 1: Information -->
                            <div id="regStep1">
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold text-uppercase">Họ và tên</label>
                                    <div class="input-group border rounded-3 overflow-hidden bg-light">
                                        <span class="input-group-text bg-transparent border-0"><i class="far fa-user text-muted"></i></span>
                                        <input type="text" name="name" id="regName" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="Nguyễn Văn A" required>
                                    </div>
                                </div>

                                <div id="emailField">
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase">Email</label>
                                        <div class="input-group border rounded-3 overflow-hidden bg-light">
                                            <span class="input-group-text bg-transparent border-0"><i class="far fa-envelope text-muted"></i></span>
                                            <input type="email" name="email" id="regEmail" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="example@mail.com">
                                        </div>
                                    </div>
                                </div>

                                <div id="phoneField" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-uppercase">Số điện thoại</label>
                                        <div class="input-group border rounded-3 overflow-hidden bg-light">
                                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-phone-alt text-muted"></i></span>
                                            <input type="text" name="phone" id="regPhone" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="09xx xxx xxx">
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mb-4">
                                    <div class="col-6">
                                        <label class="form-label extra-small fw-bold text-uppercase">Mật khẩu</label>
                                        <div class="input-group border rounded-3 overflow-hidden bg-light">
                                            <input type="password" name="password" id="regPassword" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="********" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label extra-small fw-bold text-uppercase">Xác nhận</label>
                                        <div class="input-group border rounded-3 overflow-hidden bg-light">
                                            <input type="password" name="password_confirmation" id="regPasswordConfirm" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="********" required>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold mb-4 shadow-sm" onclick="goToStep2()">TIẾP TỤC</button>
                            </div>

                            @include('client.partials.modals.otp-step')

                            <p class="text-center extra-small mb-0">Đã có tài khoản? <a href="javascript:void(0)" class="fw-bold text-primary-color text-decoration-none" onclick="toggleAuthForm('login')">Đăng nhập ngay</a></p>
                        </form>
                    </div>

                    <script>
                        function setRegType(type) {
                            document.getElementById('regType').value = type;
                            if(type === 'email') {
                                document.getElementById('emailField').style.display = 'block';
                                document.getElementById('phoneField').style.display = 'none';
                                document.getElementById('btnRegEmail').classList.add('active');
                                document.getElementById('btnRegPhone').classList.remove('active');
                            } else {
                                document.getElementById('emailField').style.display = 'none';
                                document.getElementById('phoneField').style.display = 'block';
                                document.getElementById('btnRegEmail').classList.remove('active');
                                document.getElementById('btnRegPhone').classList.add('active');
                            }
                        }

                        function goToStep2() {
                            const name = document.getElementById('regName').value;
                            const type = document.getElementById('regType').value;
                            const identifier = type === 'email' ? document.getElementById('regEmail').value : document.getElementById('regPhone').value;
                            const pass = document.getElementById('regPassword').value;
                            const passConfirm = document.getElementById('regPasswordConfirm').value;

                            if(!name || !identifier || !pass || !passConfirm) {
                                showToast('Vui lòng điền đầy đủ các thông tin bắt buộc.', 'warning');
                                return;
                            }
                            if(pass !== passConfirm) {
                                showToast('Mật khẩu xác nhận không khớp.', 'warning');
                                return;
                            }

                            // Gửi yêu cầu OTP
                            fetch('{{ route('otp.request') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ identifier, type })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    let msg = data.message;
                                    if(data.otp_preview) {
                                        msg += ' (Mã test: ' + data.otp_preview + ')';
                                    }
                                    showToast(msg, 'success');
                                    document.getElementById('displayIdentifier').innerText = identifier;
                                    document.getElementById('regStep1').style.display = 'none';
                                    document.getElementById('regStep2').style.display = 'block';
                                    startOtpTimer();
                                } else {
                                    showToast(data.message, 'error');
                                }
                            })
                            .catch(error => {
                                showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
                            });
                        }

                        function backToStep1() {
                            document.getElementById('regStep1').style.display = 'block';
                            document.getElementById('regStep2').style.display = 'none';
                        }

                        function startOtpTimer() {
                            let count = 60;
                            const timerDiv = document.getElementById('otpTimer');
                            const countSpan = document.getElementById('timerCount');
                            const resendBtn = document.getElementById('btnResendOtp');
                            
                            timerDiv.style.display = 'block';
                            resendBtn.style.display = 'none';

                            const timer = setInterval(() => {
                                count--;
                                countSpan.innerText = count;
                                if(count <= 0) {
                                    clearInterval(timer);
                                    timerDiv.style.display = 'none';
                                    resendBtn.style.display = 'block';
                                }
                            }, 1000);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
