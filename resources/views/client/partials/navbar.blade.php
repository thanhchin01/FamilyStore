<!-- Premium Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('client.home') }}">
            <i class="fas fa-shopping-bag me-2"></i> K-Q <span>Store</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-600">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('client.home') ? 'active' : '' }}" href="{{ route('client.home') }}">TRANG CHỦ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('client.products.*') ? 'active' : '' }}" href="{{ route('client.products.index') }}">SẢN PHẨM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">CHÍNH SÁCH</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">LIÊN HỆ</a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <div class="search-box me-3 d-none d-md-block">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Tìm tên hàng...">
                    </div>
                </div>
                <a href="{{ route('client.cart') }}" class="nav-link position-relative p-2 mx-2">
                    <i class="fas fa-shopping-cart fa-lg text-primary-color"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        3
                    </span>
                </a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-custom ms-3 px-4 py-2">HỆ THỐNG</a>
                    @else
                        <div class="dropdown ms-3">
                            <button class="btn btn-primary-custom dropdown-toggle px-4 py-2" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i> {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item py-2 px-3 rounded-2" href="{{ route('client.profile') }}"><i class="fas fa-id-card me-2"></i> Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item py-2 px-3 rounded-2" href="#"><i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 px-3 rounded-2 text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                @else
                    <button type="button" class="btn btn-primary-custom ms-3 px-4 py-2" data-bs-toggle="modal" data-bs-target="#authModal">
                        ĐĂNG NHẬP
                    </button>
                @endauth
            </div>
        </div>
    </div>
</nav>
