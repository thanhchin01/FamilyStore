<!-- resources/views/partials/nav.blade.php -->
<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand text-uppercase" href="{{ route('admin.dashboard') }}">
            <i class="fa-solid fa-tv"></i>
            KQ Store
        </a>

        <!-- Sidebar Toggle Button -->
        <button class="btn-sidebar-toggle ms-auto order-lg-last" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarRight" aria-controls="sidebarRight">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse me-4" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin.products') ? 'active' : '' }}" href="{{ route('admin.products') }}">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Dịch vụ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Liên hệ</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
