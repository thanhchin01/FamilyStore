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
            <!-- Desktop Actions (Visible on XL+) -->
            <div class="d-none d-xl-flex align-items-center gap-2">
                <button type="button" class="navbar-tech__search-btn" data-bs-toggle="collapse"
                    data-bs-target="#searchCollapse">
                    <i class="fas fa-search"></i>
                </button>

                <a href="{{ Auth::check() ? route('client.wishlist.index') : 'javascript:void(0)' }}"
                    class="navbar-tech__cart me-2" {{ !Auth::check() ? 'data-require-auth' : '' }}
                    data-auth-msg="Vui lòng đăng nhập để xem danh sách yêu thích của bạn." title="Yêu thích">
                    <i class="fas fa-heart text-danger"></i>
                </a>

                <a href="{{ Auth::check() ? route('client.cart') : 'javascript:void(0)' }}" class="navbar-tech__cart"
                    {{ !Auth::check() ? 'data-require-auth' : '' }}
                    data-auth-msg="Vui lòng đăng nhập để xem giỏ hàng và tiến hành mua sắm.">
                    <i class="fas fa-bag-shopping"></i>
                    <span>{{ count(session()->get('cart', [])) }}</span>
                </a>

                @if (Auth::check())
                    <div class="dropdown">
                        <button class="navbar-tech__account dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4">
                            <li><a class="dropdown-item py-2 px-3 rounded-3" href="{{ route('client.profile') }}">Hồ
                                    sơ</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 px-3 rounded-3 text-danger">Đăng
                                        xuất</button>
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
            </div>

            <!-- Mobile/Tablet Toggle -->
            <button class="navbar-toggler border-0 shadow-none ms-2" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Mở menu">
                <div class="toggler-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>

        <div class="collapse navbar-collapse order-xl-2" id="navbarNav">
            <!-- Mobile Actions (Visible only on < XL) -->
            <div class="d-xl-none py-3 border-bottom mb-3">
                <div class="row g-2">
                    <div class="col-4">
                        <a href="{{ route('client.products.index') }}" class="mobile-action-card">
                            <i class="fas fa-search"></i>
                            <span>Tìm kiếm</span>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ Auth::check() ? route('client.cart') : 'javascript:void(0)' }}"
                            class="mobile-action-card position-relative"
                            {{ !Auth::check() ? 'data-require-auth' : '' }}
                            data-auth-msg="Vui lòng đăng nhập để xem giỏ hàng.">
                            <i class="fas fa-bag-shopping"></i>
                            <span
                                class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle"
                                style="font-size: 0.65rem;">
                                {{ count(session()->get('cart', [])) }}
                            </span>
                            <span>Giỏ hàng</span>
                        </a>
                    </div>
                    <div class="col-4">
                        @if (Auth::check())
                            <a href="{{ route('client.profile') }}" class="mobile-action-card">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
                            </a>
                        @else
                            <button type="button" class="mobile-action-card w-100 border-0 bg-transparent"
                                data-bs-toggle="modal" data-bs-target="#authModal">
                                <i class="fas fa-user"></i>
                                <span>Đăng nhập</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

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
