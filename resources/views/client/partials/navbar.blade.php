<!-- Premium Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('client.home') }}">
            <i class="fas fa-shopping-bag me-2"></i> K-Q <span>Store</span>
        </a>
        <div class="d-flex align-items-center order-lg-3 ms-auto">
            <form action="{{ route('client.products.index') }}" method="GET" class="search-box me-3 d-none d-xl-block">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-transparent border-0"><i
                            class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-0 bg-transparent shadow-none"
                        placeholder="Tìm tên hàng..." value="{{ request('search') }}">
                </div>
            </form>
            <a href="{{ route('client.cart') }}" class="nav-link position-relative p-2 mx-1 mx-md-2">
                <i class="fas fa-shopping-cart fa-lg text-primary-color"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-counter"
                    style="font-size: 0.6rem;">
                    {{ count(session()->get('cart', [])) }}
                </span>
            </a>
            @if (Auth::check())

                <div class="dropdown ms-2 ms-md-3">
                    <button class="btn btn-primary-custom dropdown-toggle px-3 px-md-4 py-2" type="button"
                        id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle d-none d-sm-inline me-1"></i> <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3"
                        aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item py-2 px-3 rounded-2" href="{{ route('client.profile') }}"><i
                                    class="fas fa-id-card me-2"></i> Hồ sơ</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 px-3 rounded-2 text-danger"><i
                                        class="fas fa-sign-out-alt me-2"></i> Thoát</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <button type="button" class="btn btn-primary-custom ms-2 ms-md-3 px-3 px-md-4 py-2" data-bs-toggle="modal"
                    data-bs-target="#authModal">
                    <i class="fas fa-user d-inline d-md-none"></i>
                    <span class="d-none d-md-inline">ĐĂNG NHẬP</span>
                </button>
            @endif
            
            <button class="navbar-toggler border-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="width: 1.25em; height: 1.25em;"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse order-lg-2" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-600">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('client.home') ? 'active' : '' }}"
                        href="{{ route('client.home') }}">TRANG CHỦ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('client.products.*') ? 'active' : '' }}"
                        href="{{ route('client.products.index') }}">SẢN PHẨM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">CHÍNH SÁCH</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">LIÊN HỆ</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
