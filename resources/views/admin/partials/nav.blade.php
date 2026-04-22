<header class="admin-topbar">
    <div class="admin-topbar__left">
        <button class="admin-topbar__toggle" type="button" data-sidebar-toggle aria-label="Mở menu">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <div>
            <p class="admin-topbar__eyebrow">Bảng điều khiển</p>
            <h1 class="admin-topbar__title">@yield('title', 'Admin Dashboard')</h1>
        </div>
    </div>

    <div class="admin-topbar__right">
        <div class="admin-topbar__date">
            <i class="fa-regular fa-calendar"></i>
            <span>{{ now()->format('d/m/Y') }}</span>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="admin-topbar__icon" title="Đơn hàng mới">
            <i class="fa-solid fa-bag-shopping"></i>
            <span id="orderBadge" class="badge rounded-pill bg-danger d-none">0</span>
        </a>

        <a href="{{ route('admin.chat.index') }}" class="admin-topbar__icon" title="Tin nhắn mới">
            <i class="fa-solid fa-envelope"></i>
            <span id="messageBadge" class="badge rounded-pill bg-primary d-none">0</span>
        </a>
    </div>
</header>
