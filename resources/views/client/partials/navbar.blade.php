<nav class="navbar navbar-expand-xl navbar-tech sticky-top">
    <div class="container">
        <a class="navbar-brand tech-brand" href="{{ route('client.home') }}">
            <span class="tech-brand__mark">KQ</span>
            <span class="tech-brand__text">
                <strong>Khoa Quyen Store</strong>
                <small>Home Tech Experience</small>
            </span>
        </a>

        <div class="navbar-tech__actions order-xl-3 ms-auto">
            <button type="button" class="navbar-tech__search-toggle d-xl-none" data-search-toggle aria-label="Mở tìm kiếm">
                <i class="fas fa-search"></i>
            </button>

            <form action="{{ route('client.products.index') }}" method="GET" class="navbar-tech__search" data-search-panel>
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Tìm sản phẩm, thương hiệu, model..."
                    value="{{ request('search') }}">
                <button type="submit">Tìm</button>
            </form>

            <a href="{{ route('client.cart') }}" class="navbar-tech__cart" aria-label="Giỏ hàng">
                <i class="fas fa-bag-shopping"></i>
                <span>{{ count(session()->get('cart', [])) }}</span>
            </a>

            @if (Auth::check())
                <div class="dropdown">
                    <button class="navbar-tech__account dropdown-toggle" type="button" id="profileDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4"
                        aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item py-2 px-3 rounded-3" href="{{ route('client.profile') }}">Hồ sơ</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 px-3 rounded-3 text-danger">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <button type="button" class="navbar-tech__account is-guest" data-bs-toggle="modal"
                    data-bs-target="#authModal">
                    <i class="fas fa-user"></i>
                    <span>Đăng nhập</span>
                </button>
            @endif

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse order-xl-2" id="navbarNav">
            <ul class="navbar-nav mx-auto navbar-tech__menu">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('client.home') ? 'active' : '' }}"
                        href="{{ route('client.home') }}">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('client.products.*') ? 'active' : '' }}"
                        href="{{ route('client.products.index') }}">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('client.products.index', ['sort' => 'newest']) }}">Mới về</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('client.products.index', ['price_range' => '3']) }}">Cao cấp</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
