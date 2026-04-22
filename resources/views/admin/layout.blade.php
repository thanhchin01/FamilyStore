<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Khoa Quyen Store</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/scss/admin/app.scss', 'resources/js/admin/app.js'])
</head>

<body class="admin-shell">
    <div class="admin-app" data-admin-app>
        @include('admin.partials.sidebar')

        <div class="admin-main">
            @include('admin.partials.nav')

            <main class="admin-page">
                @yield('content')
            </main>

            @include('admin.partials.footer')
        </div>
    </div>

    @include('client.partials.toasts')
    @include('admin.components.confirm-delete-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hideToast = (toast) => {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100px)';
                    toast.style.transition = 'all 0.5s ease';
                    setTimeout(() => toast.remove(), 500);
                }, 5000);
            };

            document.querySelectorAll('.premium-toast').forEach(hideToast);

            window.showToast = function(message, type = 'success') {
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
                hideToast(toast);
            };

            function updateAdminNotifications() {
                fetch('{{ route('admin.notifications.counts') }}')
                    .then(res => res.json())
                    .then(res => {
                        if (!res.success) {
                            return;
                        }

                        const orderBadge = document.getElementById('orderBadge');
                        const messageBadge = document.getElementById('messageBadge');

                        if (orderBadge) {
                            orderBadge.innerText = res.orders;
                            orderBadge.classList.toggle('d-none', !(res.orders > 0));
                        }

                        if (messageBadge) {
                            messageBadge.innerText = res.messages;
                            messageBadge.classList.toggle('d-none', !(res.messages > 0));
                        }
                    })
                    .catch(err => console.error('Notification error:', err));
            }

            updateAdminNotifications();
            setInterval(updateAdminNotifications, 7000);

            window.formatCurrency = function(value) {
                if (!value) return '';
                value = value.toString().replace(/\D/g, '');
                return new Intl.NumberFormat('vi-VN').format(value);
            };

            window.unformatCurrency = function(value) {
                return value.toString().replace(/\D/g, '');
            };

            document.body.addEventListener('input', function(e) {
                if (!e.target.classList.contains('money-input')) {
                    return;
                }

                let cursorPosition = e.target.selectionStart;
                let oldLength = e.target.value.length;

                let formatted = formatCurrency(e.target.value);
                e.target.value = formatted;

                let newLength = e.target.value.length;
                cursorPosition = cursorPosition + (newLength - oldLength);
                e.target.setSelectionRange(cursorPosition, cursorPosition);
            });

            document.body.addEventListener('submit', function(e) {
                const moneyInputs = e.target.querySelectorAll('.money-input');
                moneyInputs.forEach(input => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = unformatCurrency(input.value);

                    input.dataset.originalName = input.name;
                    input.removeAttribute('name');
                    e.target.appendChild(hiddenInput);
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
