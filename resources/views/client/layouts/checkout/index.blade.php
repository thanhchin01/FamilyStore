@extends('client.layout')

@section('title', 'Thanh toán - K-Q Store')

@section('content')
    <div class="checkout-page py-5 bg-light-subtle">
        <div class="container">
            <form action="{{ route('client.checkout.place') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="row g-5">
                    <!-- Left: Shipping Info -->
                    <div class="col-lg-7">
                        <div class="checkout-section mb-4 animate__animated animate__fadeInLeft">
                            <div class="d-flex align-items-center mb-4">
                                <span class="section-number me-3">1</span>
                                <h4 class="fw-bold mb-0">Thông tin giao hàng</h4>
                            </div>

                            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border-0">
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0 bg-light rounded-3"
                                                id="shipping_name" name="shipping_name" placeholder="Họ tên"
                                                value="{{ auth()->check() ? auth()->user()->name : '' }}" required>
                                            <label for="shipping_name">Họ và tên khách hàng</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control border-0 bg-light rounded-3"
                                                id="shipping_phone" name="shipping_phone" placeholder="Số điện thoại"
                                                value="{{ auth()->check() ? auth()->user()->phone : '' }}" required>
                                            <label for="shipping_phone">Số điện thoại nhận hàng</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <textarea class="form-control border-0 bg-light rounded-3" id="shipping_address" name="shipping_address"
                                                placeholder="Địa chỉ" style="height: 120px" required>{{ auth()->check() ? auth()->user()->address : '' }}</textarea>
                                            <label for="shipping_address">Địa chỉ giao hàng chi tiết</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <textarea class="form-control border-0 bg-light rounded-3" id="note" name="note" placeholder="Ghi chú"
                                                style="height: 100px"></textarea>
                                            <label for="note">Ghi chú đơn hàng (không bắt buộc)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-section animate__animated animate__fadeInLeft" style="animation-delay: 0.1s">
                            <div class="d-flex align-items-center mb-4">
                                <span class="section-number me-3">2</span>
                                <h4 class="fw-bold mb-0">Phương thức thanh toán</h4>
                            </div>

                            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border-0">
                                <div class="payment-options">
                                    <label class="payment-card mb-3 d-flex align-items-center p-4 rounded-4 border active">
                                        <input type="radio" name="payment_method" value="cod" checked class="d-none">
                                        <div class="payment-icon me-4">
                                            <i class="fas fa-truck-fast fa-2x text-primary"></i>
                                        </div>
                                        <div class="payment-info">
                                            <h6 class="fw-bold mb-1">Thanh toán khi nhận hàng (COD)</h6>
                                            <p class="text-secondary small mb-0">Bạn sẽ thanh toán tiền mặt cho shipper khi
                                                nhận được kiện hàng.</p>
                                        </div>
                                        <div class="ms-auto check-mark">
                                            <i class="fas fa-check-circle text-primary fa-xl"></i>
                                        </div>
                                    </label>

                                    <label class="payment-card d-flex align-items-center p-4 rounded-4 border">
                                        <input type="radio" name="payment_method" value="transfer" class="d-none">
                                        <div class="payment-icon me-4">
                                            <i class="fas fa-university fa-2x text-secondary"></i>
                                        </div>
                                        <div class="payment-info">
                                            <h6 class="fw-bold mb-1">Chuyển khoản ngân hàng</h6>
                                            <p class="text-secondary small mb-0">Thanh toán qua mã QR hoặc số tài khoản. Xử
                                                lý nhanh hơn.</p>
                                        </div>
                                        <div class="ms-auto check-mark opacity-0">
                                            <i class="fas fa-check-circle text-primary fa-xl"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Summary -->
                    <div class="col-lg-5">
                        <div class="checkout-sidebar sticky-top" style="top: 100px; z-index: 11;">
                            <div
                                class="bg-white p-4 p-md-5 rounded-4 shadow-lg border-0 animate__animated animate__fadeInRight">
                                <h5 class="fw-bold mb-4 d-flex justify-content-between align-items-center">
                                    Tóm tắt đơn hàng
                                    <span
                                        class="badge bg-primary-subtle text-primary rounded-pill small">{{ count($cartItems) }}
                                        món</span>
                                </h5>

                                <div class="order-items mb-4" style="max-height: 350px; overflow-y: auto;">
                                    @foreach ($cartItems as $item)
                                        <div class="order-item d-flex align-items-center mb-4">
                                            <div class="item-img rounded-3 border p-1 me-3 position-relative">
                                                <img src="{{ $item['image'] ? (Str::startsWith($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image'])) : '' }}"
                                                    alt="{{ $item['name'] }}"
                                                    style="width: 60px; height: 60px; object-fit: contain;">
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary"
                                                    style="font-size: 0.65rem;">
                                                    {{ $item['quantity'] }}
                                                </span>
                                            </div>
                                            <div class="item-info flex-grow-1 pe-3">
                                                <h6 class="small fw-bold mb-0 text-truncate" style="max-width: 200px;">
                                                    {{ $item['name'] }}</h6>
                                                <p class="text-secondary x-small mb-0">Đơn giá:
                                                    {{ number_format($item['price']) }}đ</p>
                                            </div>
                                            <div class="item-price">
                                                <span
                                                    class="fw-bold small">{{ number_format($item['price'] * $item['quantity']) }}đ</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="pricing-details border-top pt-4">
                                    <div class="d-flex justify-content-between mb-3 text-secondary">
                                        <span>Tạm tính</span>
                                        <span class="fw-medium">{{ number_format($total) }}đ</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 text-secondary">
                                        <span>Phí vận chuyển</span>
                                        <span class="text-success fw-bold">Miễn phí</span>
                                    </div>
                                    <div class="d-flex justify-content-between pt-3 border-top">
                                        <span class="h5 fw-bold">Tổng cộng</span>
                                        <span class="h5 fw-800 text-primary">{{ number_format($total) }}đ</span>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="btn btn-primary-gradient w-100 py-4 rounded-4 fw-bold mt-5 shadow-lg border-0 hvr-grow">
                                    <i class="fas fa-lock me-2"></i> HOÀN TẤT ĐẶT HÀNG
                                </button>

                                <div
                                    class="trust-badges mt-4 pt-4 border-top d-flex justify-content-center gap-4 grayscale opacity-50">
                                    <i class="fab fa-cc-visa fa-2x"></i>
                                    <i class="fab fa-cc-mastercard fa-2x"></i>
                                    <i class="fas fa-shield-halved fa-2x"></i>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <a href="{{ route('client.cart') }}"
                                    class="text-secondary text-decoration-none small hvr-backward">
                                    <i class="fas fa-arrow-left me-2"></i> Quay lại giỏ hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .section-number {
            width: 40px;
            height: 40px;
            background: var(--tech-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: 800;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .payment-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent !important;
            background: #f8fafc;
        }

        .payment-card:hover {
            background: #fff;
            border-color: rgba(37, 99, 235, 0.1) !important;
            transform: translateX(5px);
        }

        .payment-card.active {
            background: #fff;
            border-color: var(--tech-primary) !important;
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.08);
        }

        .payment-card.active .check-mark {
            opacity: 1 !important;
        }

        .payment-card.active .payment-icon i {
            color: var(--tech-primary) !important;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
            font-size: 1.1rem;
            letter-spacing: 1px;
        }

        .btn-primary-gradient:hover {
            filter: brightness(1.1);
            color: #fff;
        }

        .form-control:focus {
            background: #fff !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .x-small {
            font-size: 0.75rem;
        }

        .fw-800 {
            font-weight: 800;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentCards = document.querySelectorAll('.payment-card');
            paymentCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Reset all
                    paymentCards.forEach(c => {
                        c.classList.remove('active');
                        c.querySelector('.check-mark').classList.add('opacity-0');
                    });

                    // Set active
                    this.classList.add('active');
                    this.querySelector('.check-mark').classList.remove('opacity-0');
                    this.querySelector('input').checked = true;
                });
            });
        });
    </script>
@endpush
