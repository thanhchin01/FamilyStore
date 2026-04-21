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
                                <tr class="align-middle border-bottom border-light cart-row" data-id="{{ $item['id'] }}">
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
                                            <button class="btn btn-outline-secondary btn-sm rounded-circle px-2 btn-update-qty" data-action="minus" style="width: 25px; height: 25px; padding: 0;"><i class="fas fa-minus small"></i></button>
                                            <span class="fw-bold mx-2 item-quantity">{{ $item['quantity'] }}</span>
                                            <button class="btn btn-outline-secondary btn-sm rounded-circle px-2 btn-update-qty" data-action="plus" style="width: 25px; height: 25px; padding: 0;"><i class="fas fa-plus small"></i></button>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="fw-medium">{{ number_format($item['price']) }}đ</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="fw-bold text-primary item-total">{{ number_format($item['price'] * $item['quantity']) }}đ</span>
                                    </td>
                                    <td class="py-4 text-end">
                                        <button class="btn btn-link text-danger text-decoration-none p-0 btn-remove-item">
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
                        <span class="cart-subtotal">{{ number_format($total) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 text-secondary">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success fw-bold">Miễn phí</span>
                    </div>
                    <hr class="mb-4">
                    <div class="d-flex justify-content-between mb-5">
                        <span class="fw-bold h5">Tổng cộng:</span>
                        <span class="fw-bold h5 text-primary cart-grand-total">{{ number_format($total) }}đ</span>
                    </div>

                    <div class="d-grid mb-3">
                        <a href="{{ route('client.checkout') }}" class="btn btn-primary btn-lg rounded-3 fw-bold py-3 shadow-sm border-0 mb-3 text-dark">TIẾN HÀNH THANH TOÁN</a>
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update Quantity
            document.querySelectorAll('.btn-update-qty').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('.cart-row');
                    const productId = row.getAttribute('data-id');
                    const action = this.getAttribute('data-action');
                    const qtyElement = row.querySelector('.item-quantity');
                    let currentQty = parseInt(qtyElement.innerText);
                    
                    let newQty = action === 'plus' ? currentQty + 1 : currentQty - 1;
                    if (newQty < 1) return; // Không cho giảm xuống dưới 1, dùng nút xóa để xóa

                    updateCart(productId, newQty, row);
                });
            });

            // Remove Item
            document.querySelectorAll('.btn-remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('.cart-row');
                    const productId = row.getAttribute('data-id');
                    const productName = row.querySelector('h6').innerText;

                    // Setup Confirm Modal
                    const modalEl = document.getElementById('confirmModal');
                    const modal = new bootstrap.Modal(modalEl);
                    const confirmBtn = document.getElementById('confirmModalActionBtn');
                    
                    document.getElementById('confirmModalTitle').innerText = 'Xóa sản phẩm';
                    document.getElementById('confirmModalMessage').innerHTML = `Bạn có chắc chắn muốn xóa <b>${productName}</b> khỏi giỏ hàng?`;
                    confirmBtn.className = 'btn btn-danger rounded-pill px-4 fw-600';
                    confirmBtn.innerText = 'Xác nhận xóa';

                    // Xử lý khi bấm nút xác nhận trong modal
                    confirmBtn.onclick = async function() {
                        modal.hide();
                        await removeFromCart(productId, row);
                    };

                    modal.show();
                });
            });

            async function updateCart(productId, quantity, row) {
                try {
                    const response = await fetch('{{ route('client.cart.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ product_id: productId, quantity: quantity })
                    });
                    const result = await response.json();
                    if (result.success) {
                        row.querySelector('.item-quantity').innerText = quantity;
                        row.querySelector('.item-total').innerText = result.item_total;
                        updateCartTotals(result.subtotal, result.cart_count);
                    }
                } catch (error) {
                    console.error('Update Error:', error);
                    if (typeof showToast === 'function') {
                        showToast('Không thể cập nhật số lượng.', 'error');
                    }
                }
            }

            async function removeFromCart(productId, row) {
                try {
                    const response = await fetch('{{ route('client.cart.remove') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ product_id: productId })
                    });
                    const result = await response.json();
                    if (result.success) {
                        row.remove();
                        updateCartTotals(result.subtotal, result.cart_count);
                        
                        if (typeof showToast === 'function') {
                            showToast(result.message, 'success');
                        }

                        // Nếu giỏ hàng trống hoàn toàn, ẩn bảng và hiện thông báo trống mà không reload
                        if (result.cart_count === 0) {
                            const cartContainer = document.querySelector('.table-responsive').parentElement;
                            cartContainer.innerHTML = `
                                <div class="py-5 text-center text-secondary animate__animated animate__fadeIn">
                                    <i class="fas fa-shopping-basket fa-3x mb-3 d-block opacity-25"></i>
                                    <h4 class="fw-bold text-dark">Giỏ hàng của bạn đang trống!</h4>
                                    <p class="mb-4">Hãy quay lại cửa hàng để chọn cho mình những sản phẩm ưng ý nhất.</p>
                                    <a href="{{ route('client.products.index') }}" class="btn btn-primary-custom px-5 py-3 rounded-pill fw-bold shadow-sm">TIẾP TỤC MUA SẮM</a>
                                </div>
                            `;
                            
                            // Ẩn luôn phần chi tiết đơn hàng (Order Summary) bên phải nếu cần
                            const summaryCol = document.querySelector('.col-lg-4');
                            if (summaryCol) {
                                summaryCol.style.opacity = '0.3';
                                summaryCol.style.pointerEvents = 'none';
                            }
                        }
                    }
                } catch (error) {
                    console.error('Remove Error:', error);
                    if (typeof showToast === 'function') {
                        showToast('Không thể xóa sản phẩm.', 'error');
                    }
                }
            }

            function updateCartTotals(subtotal, count) {
                document.querySelectorAll('.cart-subtotal').forEach(el => el.innerText = subtotal);
                document.querySelectorAll('.cart-grand-total').forEach(el => el.innerText = subtotal);
                document.querySelectorAll('.cart-counter').forEach(el => el.innerText = count);
            }
        });
    </script>
    @endpush

@endsection
