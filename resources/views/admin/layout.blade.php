<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ') - MyProject</title>

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="[https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css](https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css)">

    <!-- Google Fonts -->
    <link
        href="[https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap](https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap)"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles (Biên dịch từ app.scss) -->
    @vite(['resources/scss/admin/app.scss', 'resources/js/admin/app.js'])
</head>

<body>

    <!-- Include Navbar -->
    @include('admin.partials.nav')

    <!-- Include Sidebar (Offcanvas) -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Include Footer -->
    @include('admin.partials.footer')


    <!-- Cách 2: Dùng Vite (Chuẩn Laravel) - Đảm bảo đã chạy npm install bootstrap -->
    <!-- @vite(['resources/js/admin/app.js']) -->
    <!-- SweetAlert2 cho Toasts thay vì f.render() -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: @json(session('success'))
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: @json(session('error'))
                });
            @endif

            @if (session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: @json(session('warning'))
                });
            @endif

            @if (session('info'))
                Toast.fire({
                    icon: 'info',
                    title: @json(session('info'))
                });
            @endif

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: @json($errors->first())
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>
