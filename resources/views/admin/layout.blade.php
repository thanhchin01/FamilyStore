<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ') - MyProject</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="[https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css](https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css)">

    <!-- Google Fonts -->
    <link href="[https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap](https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap)" rel="stylesheet">
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
    {{-- <script src="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js](https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js)"></script> --}}
</body>
</html>
