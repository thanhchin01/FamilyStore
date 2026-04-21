<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Khoa Quyên Store</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles (Biên dịch từ app.scss) -->
    @vite(['resources/scss/admin/app.scss', 'resources/js/admin/app.js'])

    <style>
        :root {
            --font-main: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            font-family: var(--font-main) !important;
            background-color: #f8fafc;
            color: #1e293b;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-luxury {
            font-family: var(--font-main) !important;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .btn,
        .form-control,
        .form-select,
        .badge {
            font-family: var(--font-main) !important;
        }
    </style>
</head>

<body>

    <!-- Include Navbar -->
    @include('admin.partials.nav')

    <!-- Include Sidebar (Offcanvas) -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <main class="py-2 container">
        <div class="container-fluid px-4">
            @yield('content')
        </div>
    </main>

    <!-- Include Footer -->
    @include('admin.partials.footer')

    {{-- Premium Toasts --}}
    @include('client.partials.toasts')

    @include('admin.components.confirm-delete-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto hide toasts
            const hideToast = (toast) => {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100px)';
                    toast.style.transition = 'all 0.5s ease';
                    setTimeout(() => toast.remove(), 500);
                }, 5000);
            };

            document.querySelectorAll('.premium-toast').forEach(hideToast);

            // Global Show Toast Function
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

            // Notification Polling
            function updateAdminNotifications() {
                fetch('{{ route('admin.notifications.counts') }}')
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            const orderBadge = document.getElementById('orderBadge');
                            const messageBadge = document.getElementById('messageBadge');

                            if (res.orders > 0 && orderBadge) {
                                orderBadge.innerText = res.orders;
                                orderBadge.classList.remove('d-none');
                            } else if (orderBadge) {
                                orderBadge.classList.add('d-none');
                            }

                            if (res.messages > 0 && messageBadge) {
                                messageBadge.innerText = res.messages;
                                messageBadge.classList.remove('d-none');
                            } else if (messageBadge) {
                                messageBadge.classList.add('d-none');
                            }
                        }
                    })
                    .catch(err => console.error('Notification error:', err));
            }

            updateAdminNotifications();
            setInterval(updateAdminNotifications, 7000); // Polling every 7s

            // --- CURRENCY FORMATTING UTILITY ---
            window.formatCurrency = function(value) {
                if (!value) return '';
                value = value.toString().replace(/\D/g, '');
                return new Intl.NumberFormat('vi-VN').format(value);
            };

            window.unformatCurrency = function(value) {
                return value.toString().replace(/\D/g, '');
            };

            // Auto-apply to all inputs with class 'money-input'
            document.body.addEventListener('input', function(e) {
                if (e.target.classList.contains('money-input')) {
                    let cursorPosition = e.target.selectionStart;
                    let oldLength = e.target.value.length;

                    let formatted = formatCurrency(e.target.value);
                    e.target.value = formatted;

                    // Adjust cursor position
                    let newLength = e.target.value.length;
                    cursorPosition = cursorPosition + (newLength - oldLength);
                    e.target.setSelectionRange(cursorPosition, cursorPosition);
                }
            });

            // Handle form submission to clean money inputs (send only numbers to server)
            document.body.addEventListener('submit', function(e) {
                const moneyInputs = e.target.querySelectorAll('.money-input');
                moneyInputs.forEach(input => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = unformatCurrency(input.value);

                    // Disable the original input temporarily or remove its name to avoid double submission
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
