<!-- resources/views/partials/sidebar.blade.php -->
<div class="offcanvas offcanvas-end custom-sidebar" tabindex="-1" id="sidebarRight" aria-labelledby="sidebarRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarRightLabel">
            <i class="fa-solid fa-compass me-2"></i> Điều hướng
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0 d-flex flex-column">
        <!-- Menu Links -->
        <ul class="sidebar-menu flex-grow-1">
            <li class="sidebar-item">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.sale') }}" class="sidebar-link {{ request()->routeIs('admin.sale') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Bán hàng
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.inventory')}}" class="sidebar-link {{ request()->routeIs('admin.inventory') ? 'active' : '' }}">
                    <i class="fa-solid fa-box-open"></i> Quản lý kho hàng
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.debt') }}" class="sidebar-link {{ request()->routeIs('admin.debt') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> DS khách hàng nợ
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.stock') }}" class="sidebar-link {{ request()->routeIs('admin.stock') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> Nhập kho
                </a>
            </li>
        </ul>

        <!-- User Profile Section -->
        <div class="sidebar-profile">
            <img src="{{ asset('images/admin/avatar-1.jpg') }}" alt="User" class="profile-img">
            <div class="profile-info">
                <h6>Nguyễn Văn A</h6>
                <span>Admin</span>
            </div>
            <a href="#" class="ms-auto text-light"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>
</div>
