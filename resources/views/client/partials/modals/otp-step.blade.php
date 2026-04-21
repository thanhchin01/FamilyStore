<!-- Step 2: OTP Verification -->
<div id="regStep2" style="display: none;">
    <div class="text-center mb-4">
        <div class="otp-illustration mb-3">
            <div class="icon-circle bg-primary-light mx-auto bounce-in">
                <i class="fas fa-shield-alt text-primary-color"></i>
            </div>
        </div>
        <h4 class="fw-800 mb-2">Xác nhận mã OTP</h4>
        <p class="text-muted small px-3">Mã xác thực gồm 6 chữ số đã được gửi đến <br><b id="displayIdentifier" class="text-dark">...</b></p>
    </div>

    <div class="otp-container d-flex justify-content-center gap-2 mb-4">
        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 1)">
        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 2)">
        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 3)">
        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 4)">
        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 5)">
        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 6)">
    </div>
    <input type="hidden" name="otp" id="finalOtp">

    <div class="text-center mb-4">
        <div id="otpTimer" class="extra-small text-muted">
            <i class="far fa-clock me-1"></i> Gửi lại mã sau <span id="timerCount" class="fw-bold">60</span>s
        </div>
        <button type="button" id="btnResendOtp" class="btn btn-link extra-small fw-bold text-decoration-none p-0" style="display:none" onclick="goToStep2()">Gửi lại mã ngay</button>
    </div>

    <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold shadow-sm mb-3">XÁC NHẬN & HOÀN TẤT</button>
    
    <div class="text-center">
        <a href="javascript:void(0)" class="extra-small text-muted text-decoration-none" onclick="backToStep1()">
            <i class="fas fa-pencil-alt me-1"></i> Thay đổi thông tin liên lạc
        </a>
    </div>
</div>

<style>
    .otp-input {
        width: 45px;
        height: 55px;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 800;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.2s ease;
    }
    .otp-input:focus {
        border-color: var(--primary-color);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
        outline: none;
        transform: translateY(-2px);
    }
    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }
    .bg-primary-light { background-color: rgba(var(--primary-color-rgb), 0.1); }
    .bounce-in { animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
    @keyframes bounceIn {
        0% { transform: scale(0); }
        100% { transform: scale(1); }
    }
</style>

<script>
    function moveOtpFocus(input, index) {
        if (input.value.length === 1 && index < 6) {
            input.nextElementSibling.focus();
        }
        
        // Tập hợp giá trị vào hidden input
        let otp = "";
        document.querySelectorAll('.otp-input').forEach(el => otp += el.value);
        document.getElementById('finalOtp').value = otp;
    }
    
    // Hỗ trợ Backspace
    document.querySelectorAll('.otp-input').forEach((input, index) => {
        input.addEventListener('keydown', (e) => {
            if (e.key === "Backspace" && input.value === "" && index > 0) {
                input.previousElementSibling.focus();
            }
        });
    });
</script>
