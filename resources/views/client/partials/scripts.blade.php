<!-- Global Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Scroll event for navbar
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('.navbar-custom');
        if (nav) {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        }
    });

    // Auto hide toasts
    document.querySelectorAll('.premium-toast').forEach(toast => {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100px)';
            toast.style.transition = 'all 0.5s ease';
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    });

    // Authentication Modal Toggle
    function toggleAuthForm(form) {
        const loginSection = document.getElementById('loginFormSection');
        const registerSection = document.getElementById('registerFormSection');
        if (form === 'register') {
            if (loginSection) loginSection.style.display = 'none';
            if (registerSection) registerSection.style.display = 'block';
        } else {
            if (loginSection) loginSection.style.display = 'block';
            if (registerSection) registerSection.style.display = 'none';
        }
    }

    // Show dynamic toast notification
    function showToast(message, type = 'success') {
        let container = document.querySelector('.premium-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'premium-toast-container position-fixed top-0 end-0 p-4';
            container.style.zIndex = '9999';
            container.style.marginTop = '20px';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `premium-toast ${type}`;
        toast.innerHTML = `
            <div class="toast-icon"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i></div>
            <div class="toast-body">
                <h6 class="mb-0 fw-bold">${type === 'success' ? 'Thành công' : 'Lỗi'}</h6>
                <p class="mb-0 small opacity-75">${message}</p>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
        `;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100px)';
            toast.style.transition = 'all 0.5s ease';
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    }

    // AJAX Authentication Support
    const handleAuthSubmit = async (e, formId) => {
        e.preventDefault();
        const form = document.getElementById(formId);
        if (!form) return;

        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerText;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Đang xử lý...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showToast(result.message, 'success');
                setTimeout(() => window.location.href = result.redirect, 1000);
            } else {
                // Xử lý lỗi từ Laravel (bao gồm cả lỗi validate 422)
                let errorMessage = result.message || 'Đã có lỗi xảy ra. Vui lòng thử lại.';
                
                // Nếu có danh sách lỗi chi tiết (validate)
                if (result.errors) {
                    const firstErrorKey = Object.keys(result.errors)[0];
                    errorMessage = result.errors[firstErrorKey][0];
                }
                
                showToast(errorMessage, 'error');
            }
        } catch (error) {
            showToast('Có lỗi kết nối hệ thống. Vui lòng thử lại.', 'error');
            console.error('Auth Error:', error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
        }
    };

    document.getElementById('ajaxLoginForm')?.addEventListener('submit', (e) => handleAuthSubmit(e, 'ajaxLoginForm'));
    document.getElementById('ajaxRegisterForm')?.addEventListener('submit', (e) => handleAuthSubmit(e,
        'ajaxRegisterForm'));
</script>
