<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Khoa Quyên Store - Chào mừng bạn!')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Design System & Custom Style -->
    @vite(['resources/css/client.scss', 'resources/js/client.js'])

    @stack('styles')
</head>

<body class="client-layout">

    <!-- Premium Preloader -->
    <div id="preloader">
        <div class="loader-content">
            <div class="loader-circle"></div>
            <div class="loader-logo">K-Q <span>Store</span></div>
        </div>
    </div>

    {{-- 1. Navigation --}}
    @include('client.partials.navbar')

    {{-- 2. Authentication Modal (Luôn nạp nếu chưa đăng nhập quyền Client) --}}
    @if (!Auth::check())

        @include('client.partials.auth-modal')
    @endif

    {{-- 3. Main Content --}}
    <main class="page-content min-vh-100">
        @yield('content')
    </main>

    {{-- 4. Footer --}}
    @include('client.partials.footer')

    {{-- 5. System Notifications --}}
    @include('client.partials.toasts')

    {{-- 6. Floating Contact Widgets --}}
    @include('client.partials.contact-widget')

    {{-- 6. Essential Scripts --}}
    @include('client.partials.scripts')

    {{-- 7. Global Confirmation Modal --}}
    @include('client.partials.confirm-modal')

    @stack('scripts')

</body>

</html>
