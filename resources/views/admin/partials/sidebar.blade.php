@php
    $navItems = [
        ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-house'],
        ['route' => 'admin.orders.index', 'active' => 'admin.orders.*', 'label' => 'Đơn hàng', 'icon' => 'fa-bag-shopping'],
        ['route' => 'admin.sale', 'active' => 'admin.sale*', 'label' => 'Bán hàng', 'icon' => 'fa-cash-register'],
        ['route' => 'admin.inventory', 'active' => 'admin.inventory*', 'label' => 'Kho hàng', 'icon' => 'fa-boxes-stacked'],
        ['route' => 'admin.products', 'active' => 'admin.products*', 'label' => 'Sản phẩm', 'icon' => 'fa-box-open'],
        ['route' => 'admin.debt', 'active' => 'admin.debt*', 'label' => 'Công nợ', 'icon' => 'fa-wallet'],
        ['route' => 'admin.statistics', 'active' => 'admin.statistics*', 'label' => 'Thống kê', 'icon' => 'fa-chart-simple'],
        ['route' => 'admin.chat.index', 'active' => 'admin.chat.*', 'label' => 'Tin nhắn', 'icon' => 'fa-comments'],
        ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'Tài khoản', 'icon' => 'fa-users-gear'],
    ];
@endphp

<div class="admin-sidebar-backdrop" data-sidebar-close></div>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar__header">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            <span class="admin-brand__mark">KQ</span>
            <span class="admin-brand__text">
                <strong>Khoa Quyen</strong>
                <small>Admin Control</small>
            </span>
        </a>

        <button type="button" class="admin-sidebar__close d-lg-none" data-sidebar-close aria-label="Đóng menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="admin-sidebar__section">
        <p class="admin-sidebar__label">Điều hướng</p>

        <nav class="admin-sidebar__nav">
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                    class="admin-sidebar__link {{ request()->routeIs($item['active']) ? 'is-active' : '' }}">
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <div class="admin-sidebar__footer">
        <div class="admin-user-card">
            <div class="admin-user-card__avatar">
                {{ strtoupper(mb_substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="admin-user-card__meta">
                <strong>{{ auth('admin')->user()->name ?? 'Admin' }}</strong>
                <span>Quản trị hệ thống</span>
            </div>

            <a href="#"
                onclick="return confirm('Bạn có chắc muốn đăng xuất?') && (event.preventDefault(), document.getElementById('logout-form').submit());"
                class="admin-user-card__logout"
                aria-label="Đăng xuất">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>

        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
            @csrf
        </form>

    </div>
</aside>
