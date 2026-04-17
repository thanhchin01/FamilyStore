<!-- Toast Notifications -->
@if(session('success') || session('error') || $errors->any())
<div class="premium-toast-container position-fixed top-0 end-0 p-4" style="z-index: 9999; margin-top: 20px;">
    @if(session('success'))
    <div class="premium-toast success">
        <div class="toast-icon"><i class="fas fa-check-circle"></i></div>
        <div class="toast-body">
            <h6 class="mb-0 fw-bold">Thành công</h6>
            <p class="mb-0 small opacity-75">{{ session('success') }}</p>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="premium-toast error">
        <div class="toast-icon"><i class="fas fa-exclamation-circle"></i></div>
        <div class="toast-body">
            <h6 class="mb-0 fw-bold">Lỗi</h6>
            <p class="mb-0 small opacity-75">{{ session('error') }}</p>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    @endif

    @if($errors->any() && !request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('admin.login'))
    <div class="premium-toast error">
        <div class="toast-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="toast-body">
            <h6 class="mb-0 fw-bold">Thông tin không hợp lệ</h6>
            <ul class="mb-0 small opacity-75 list-unstyled">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    @endif
</div>
@endif
