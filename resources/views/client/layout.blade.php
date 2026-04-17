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
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
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

    {{-- 1. Navigation --}}
    @include('client.partials.navbar')

    {{-- 2. Authentication Modal --}}
    @guest
        @include('client.partials.auth-modal')
    @endguest

    {{-- 3. Main Content --}}
    <main class="page-content min-vh-100">
        @yield('content')
    </main>

    {{-- 4. Footer --}}
    @include('client.partials.footer')

    {{-- 5. System Notifications --}}
    @include('client.partials.toasts')

    {{-- 6. Essential Scripts --}}
    @include('client.partials.scripts')

    @stack('scripts')

</body>

</html>
