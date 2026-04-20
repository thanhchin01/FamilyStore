<!-- resources/views/partials/nav.blade.php -->
<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand text-uppercase" href="{{ route('admin.dashboard') }}">
            {{-- <i class="fa-solid fa-tv"></i>
            KQ Store --}}
            {{-- <img class="navbar-logo" src="{{ asset('images/admin/logo-1.png') }}" alt="KQ Store"> --}}
        </a>

        <!-- Notification Badges -->
        <div class="d-flex align-items-center ms-auto order-lg-last gap-2">
            <!-- Order Notification -->
            <a href="{{ route('admin.orders.index') }}" class="nav-link position-relative p-2" title="Đơn hàng mới">
                <i class="fa-solid fa-cart-shopping fs-5"></i>
                <span id="orderBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" style="font-size: 0.6rem;">
                    0
                </span>
            </a>

            <!-- Message Notification -->
            <a href="{{ route('admin.chat.index') }}" class="nav-link position-relative p-2" title="Tin nhắn mới">
                <i class="fa-solid fa-envelope fs-5"></i>
                <span id="messageBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary-light d-none" style="font-size: 0.6rem; background-color: #009688 !important;">
                    0
                </span>
            </a>

            <!-- Sidebar Toggle Button -->
            <button class="btn-sidebar-toggle ms-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarRight" aria-controls="sidebarRight">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
        </div>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse me-4" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin.products') ? 'active' : '' }}"
                        href="{{ route('admin.products') }}">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin.history') ? 'active' : '' }}"
                        href="{{ route('admin.history') }}">Lịch sử bán hàng</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
