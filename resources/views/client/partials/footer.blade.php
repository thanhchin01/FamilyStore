@php
    $footerMenus = [
        'Khám phá' => [
            ['label' => 'Trang chủ', 'href' => route('client.home')],
            ['label' => 'Sản phẩm', 'href' => route('client.products.index')],
            ['label' => 'Khuyến mãi', 'href' => '#'],
            ['label' => 'Bộ sưu tập', 'href' => '#'],
        ],
        'Hỗ trợ' => [
            ['label' => 'Đổi trả hàng', 'href' => '#'],
            ['label' => 'Bảo hành', 'href' => '#'],
            ['label' => 'Vận chuyển', 'href' => '#'],
            ['label' => 'Thành viên', 'href' => '#'],
        ],
    ];

    $socialLinks = [
        ['icon' => 'fab fa-facebook', 'href' => '#', 'label' => 'Facebook'],
        ['icon' => 'fab fa-instagram', 'href' => '#', 'label' => 'Instagram'],
        ['icon' => 'fab fa-tiktok', 'href' => '#', 'label' => 'TikTok'],
        ['icon' => 'fab fa-youtube', 'href' => '#', 'label' => 'YouTube'],
    ];

    $contacts = [
        ['icon' => 'fas fa-map-marker-alt', 'text' => 'QL37, Thôn Khoa Quyên, Xã X, Huyện Y, Tỉnh Z'],
        ['icon' => 'fas fa-phone-alt', 'text' => '09xx xxx xxx'],
        ['icon' => 'fas fa-envelope', 'text' => 'info@khoaquyen.vn'],
    ];
@endphp

<footer class="store-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <div class="store-footer__brand">
                    <h4>Khoa Quyên Store</h4>
                    <div class="store-footer__accent"></div>
                </div>
                <p class="store-footer__description">
                    Đơn vị phân phối các sản phẩm điện máy, gia dụng cao cấp chính hãng tại khu vực. Chúng tôi mang
                    niềm tin và chất lượng đến từng căn bếp gia đình Việt.
                </p>
                <div class="store-footer__social">
                    @foreach ($socialLinks as $link)
                        <a href="{{ $link['href'] }}" aria-label="{{ $link['label'] }}">
                            <i class="{{ $link['icon'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            @foreach ($footerMenus as $title => $items)
                <div class="col-lg-2 col-md-6">
                    <h5>{{ $title }}</h5>
                    <ul class="list-unstyled">
                        @foreach ($items as $item)
                            <li class="mb-2"><a href="{{ $item['href'] }}">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="col-lg-4 col-md-6">
                <h5>Liên hệ</h5>
                <div class="store-footer__contact-list">
                    @foreach ($contacts as $contact)
                        <div class="store-footer__contact-item">
                            <i class="{{ $contact['icon'] }}"></i>
                            <p>{{ $contact['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="store-footer__bottom">
            <p>&copy; {{ date('Y') }} Khoa Quyên Store. Thiết kế và phát triển theo hướng dễ mở rộng, dễ bảo trì.</p>
        </div>
    </div>
</footer>
