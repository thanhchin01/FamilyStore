@extends('client.layout')

@section('title', 'Quên mật khẩu | Khoa Quyên Store')

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="icon-box-reset mb-3">
                            <i class="fas fa-lock-open fa-2x text-primary-color"></i>
                        </div>
                        <h3 class="fw-bold">Quên mật khẩu?</h3>
                        <p class="text-muted small">Đừng lo lắng, hãy nhập thông tin của bạn để lấy lại quyền truy cập.</p>
                    </div>

                    <!-- Step 1: Input Identifier -->
                    <div id="step-identifier">
                        <div class="mb-4">
                            <label class="form-label extra-small fw-bold text-uppercase">Email hoặc Số điện thoại</label>
                            <div class="input-group border rounded-3 overflow-hidden bg-light">
                                <span class="input-group-text bg-transparent border-0"><i class="far fa-user text-muted"></i></span>
                                <input type="text" id="identifier" class="form-control bg-transparent border-0 py-2 shadow-none" placeholder="Nhập thông tin đăng ký">
                            </div>
                        </div>
                        <button class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold shadow-sm" onclick="sendOtp()">GỬI MÃ XÁC MINH</button>
                    </div>

                    <!-- Step 2: Verify OTP -->
                    <div id="step-otp" style="display: none;">
                        <p class="text-center small mb-4">Nhập mã 6 chữ số đã được gửi đến <br><b id="show-id" class="text-dark"></b></p>
                        <div class="otp-container d-flex justify-content-center gap-2 mb-4">
                            <input type="text" class="otp-input-reset" maxlength="1" onkeyup="moveFocus(this, 1)">
                            <input type="text" class="otp-input-reset" maxlength="1" onkeyup="moveFocus(this, 2)">
                            <input type="text" class="otp-input-reset" maxlength="1" onkeyup="moveFocus(this, 3)">
                            <input type="text" class="otp-input-reset" maxlength="1" onkeyup="moveFocus(this, 4)">
                            <input type="text" class="otp-input-reset" maxlength="1" onkeyup="moveFocus(this, 5)">
                            <input type="text" class="otp-input-reset" maxlength="1" onkeyup="moveFocus(this, 6)">
                        </div>
                        <button class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold shadow-sm mb-3" onclick="verifyOtp()">XÁC MINH</button>
                        <div class="text-center">
                            <a href="javascript:void(0)" class="extra-small text-muted text-decoration-none" onclick="location.reload()">Quay lại</a>
                        </div>
                    </div>

                    <!-- Step 3: New Password -->
                    <div id="step-new-pass" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold text-uppercase">Mật khẩu mới</label>
                            <input type="password" id="new_password" class="form-control bg-light border-0 py-2 rounded-3 shadow-none" placeholder="********">
                        </div>
                        <div class="mb-4">
                            <label class="form-label extra-small fw-bold text-uppercase">Xác nhận mật khẩu</label>
                            <input type="password" id="new_password_confirmation" class="form-control bg-light border-0 py-2 rounded-3 shadow-none" placeholder="********">
                        </div>
                        <button class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold shadow-sm" onclick="resetPassword()">ĐỔI MẬT KHẨU</button>
                    </div>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="small mb-0">Quay về <a href="{{ route('client.home') }}" class="fw-bold text-primary-color text-decoration-none">Trang chủ</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-box-reset {
        width: 60px;
        height: 60px;
        background: rgba(var(--primary-color-rgb), 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .otp-input-reset {
        width: 40px;
        height: 50px;
        text-align: center;
        font-size: 1.2rem;
        font-weight: 700;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        background: #f8f9fa;
    }
    .otp-input-reset:focus {
        border-color: var(--primary-color);
        outline: none;
        background: #fff;
    }
</style>

<script>
    let currentIdentifier = '';

    function moveFocus(input, index) {
        if (input.value.length === 1 && index < 6) {
            input.nextElementSibling.focus();
        }
    }

    function sendOtp() {
        currentIdentifier = document.getElementById('identifier').value;
        if(!currentIdentifier) {
            showToast('Vui lòng nhập Email hoặc Số điện thoại', 'warning');
            return;
        }

        fetch('{{ route('password.otp') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ identifier: currentIdentifier })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                let msg = data.message;
                if(data.otp_preview) msg += ' (Mã test: ' + data.otp_preview + ')';
                showToast(msg, 'success');
                document.getElementById('show-id').innerText = currentIdentifier;
                document.getElementById('step-identifier').style.display = 'none';
                document.getElementById('step-otp').style.display = 'block';
            } else {
                showToast(data.message, 'error');
            }
        });
    }

    function verifyOtp() {
        let otp = "";
        document.querySelectorAll('.otp-input-reset').forEach(el => otp += el.value);

        fetch('{{ route('password.verify') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ identifier: currentIdentifier, otp: otp })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showToast(data.message, 'success');
                document.getElementById('step-otp').style.display = 'none';
                document.getElementById('step-new-pass').style.display = 'block';
            } else {
                showToast(data.message, 'error');
            }
        });
    }

    function resetPassword() {
        const password = document.getElementById('new_password').value;
        const confirm = document.getElementById('new_password_confirmation').value;

        if(password !== confirm) {
            showToast('Mật khẩu xác nhận không khớp', 'error');
            return;
        }

        fetch('{{ route('password.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ password: password, password_confirmation: confirm })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.href = '/', 2000);
            } else {
                showToast(data.message, 'error');
            }
        });
    }
</script>
@endsection
