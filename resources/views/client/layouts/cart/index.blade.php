@extends('client.layout')

@section('title', 'Giỏ hàng - K-Q Store')

@section('content')

    <div class="container mb-5">
        <h2 class="fw-bold mb-5">Giỏ hàng của bạn</h2>

        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="text-secondary small fw-bold">
                                    <th scope="col">Sản phẩm</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Đơn giá</th>
                                    <th scope="col">Thành tiền</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cartItems as $item)
                                <tr class="align-middle border-bottom border-light">
                                    <td class="py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 border border-light-subtle rounded-3 me-3" style="width: 80px; height: 80px;">
                                                @if($item['image'])
                                                    <img src="{{ Str::startsWith($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}" class="img-fluid rounded-2 h-100 w-100 object-fit-contain" alt="{{ $item['name'] }}">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center h-100 w-100">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $item['name'] }}</h6>
                                                <p class="text-secondary small mb-0">SKU: {{ $item['id'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="d-flex align-items-center gap-2" style="max-width: 120px;">
                                            <button class="btn btn-outline-secondary btn-sm rounded-circle px-2" style="width: 25px; height: 25px; padding: 0;"><i class="fas fa-minus small"></i></button>
                                            <span class="fw-bold mx-2">{{ $item['quantity'] }}</span>
                                            <button class="btn btn-outline-secondary btn-sm rounded-circle px-2" style="width: 25px; height: 25px; padding: 0;"><i class="fas fa-plus small"></i></button>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="fw-medium">{{ number_format($item['price']) }}đ</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="fw-bold text-primary">{{ number_format($item['price'] * $item['quantity']) }}đ</span>
                                    </td>
                                    <td class="py-4 text-end">
                                        <button class="btn btn-link text-danger text-decoration-none p-0">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center text-secondary">
                                        <i class="fas fa-shopping-basket fa-3x mb-3 d-block opacity-25"></i>
                                        Giỏ hàng trống!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h5 class="fw-bold mb-4">Chi tiết đơn hàng</h5>
                    <div class="d-flex justify-content-between mb-3 text-secondary">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($total) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 text-secondary">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success fw-bold">Miễn phí</span>
                    </div>
                    <hr class="mb-4">
                    <div class="d-flex justify-content-between mb-5">
                        <span class="fw-bold h5">Tổng cộng:</span>
                        <span class="fw-bold h5 text-primary">{{ number_format($total) }}đ</span>
                    </div>

                    <div class="d-grid mb-3">
                        <button class="btn btn-primary btn-lg rounded-3 fw-bold py-3 shadow-sm border-0 mb-3" style="background: linear-gradient(135deg, #5c62ec 0%, #4b52d1 100%);">TIẾN HÀNH THANH TOÁN</button>
                        <a href="{{ route('client.home') }}" class="btn btn-outline-secondary btn-lg rounded-3 fw-bold py-3">TIẾP TỤC MUA SẮM</a>
                    </div>

                    <div class="mt-4 p-3 rounded-3 bg-light-subtle d-flex align-items-center">
                        <i class="fas fa-shield-alt text-success me-3 fa-2x"></i>
                        <span class="small text-secondary">Đảm bảo thanh toán an toàn 100% với mã hóa SSL mới nhất.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
