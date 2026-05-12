/**
 * Khoa Quyen Store - Shared UI Utilities
 * Senior Dev Standard
 */

export const formatCurrency = (value) => {
    if (value === undefined || value === null) return '0đ';
    return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
};

export const parseCurrency = (value) => {
    if (!value) return 0;
    return parseInt(value.toString().replace(/\D/g, '')) || 0;
};

export const formatDate = (dateString, format = 'DD/MM/YYYY') => {
    const date = new Date(dateString);
    if (isNaN(date)) return dateString;

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    if (format === 'DD/MM/YYYY HH:mm') return `${day}/${month}/${year} ${hours}:${minutes}`;
    return `${day}/${month}/${year}`;
};

export const showToast = (message, type = 'success') => {
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
};

// Auto-init confirmation modals
export const initConfirmModals = () => {
    window.confirmAction = (actionUrl, message = null, method = 'DELETE') => {
        const modalElement = document.getElementById('confirmModal');
        if (!modalElement) return;
        
        const bsModal = window.bootstrap ? (window.bootstrap.Modal.getInstance(modalElement) || new window.bootstrap.Modal(modalElement)) : null;
        const messageEl = modalElement.querySelector('#confirmModalMessage');
        const confirmBtn = modalElement.querySelector('#confirmModalActionBtn');

        if (messageEl && message) messageEl.innerHTML = message;
        
        // Default action: If actionUrl is provided, it's a form submission
        if (actionUrl) {
            confirmBtn.onclick = () => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = method;
                
                form.appendChild(csrf);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            };
        }
        
        if (bsModal) bsModal.show();
    };
};

export const ensureAuthenticated = (callback, message = null) => {
    if (window.isAuthenticated) {
        return callback();
    }

    // Open Auth Modal
    const authModal = document.getElementById('authModal');
    if (authModal) {
        const bsModal = window.bootstrap ? new window.bootstrap.Modal(authModal) : null;
        if (bsModal) bsModal.show();
        else {
            authModal.classList.add('show');
            authModal.style.display = 'block';
        }
        
        // Show Toast notification
        const finalMsg = message || 'Vui lòng đăng nhập để thực hiện hành động này';
        showToast(finalMsg, 'info');
    } else {
        window.location.href = '/login';
    }
};
