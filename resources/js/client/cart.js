/**
 * Khoa Quyen Store - Cart Management Logic
 * Dedicated Page Script
 */

import { showToast } from '../helpers/ui-utils';

document.addEventListener('DOMContentLoaded', function() {
    const cartTable = document.querySelector('.table-responsive');
    if (!cartTable) return;

    // Selection Logic
    const selectAll = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            calculateSelectedTotal();
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            calculateSelectedTotal();
            const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
            if (selectAll) selectAll.checked = allChecked;
        });
    });

    // Update Quantity Logic
    document.querySelectorAll('.btn-update-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.cart-row');
            const productId = row.getAttribute('data-id');
            const action = this.getAttribute('data-action');
            const qtyElement = row.querySelector('.item-quantity');
            let currentQty = parseInt(qtyElement.innerText);
            
            let newQty = action === 'plus' ? currentQty + 1 : currentQty - 1;
            if (newQty < 1) return;

            updateCart(productId, newQty, row);
        });
    });

    // Remove Item Logic
    document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.cart-row');
            const productId = row.getAttribute('data-id');
            const productName = row.querySelector('h6').innerText;

            window.confirmAction && window.confirmAction(
                null, 
                `Bạn có chắc chắn muốn xóa <b>${productName}</b> khỏi giỏ hàng?`,
                'DELETE'
            );

            // Override global confirm for custom cart logic
            const confirmBtn = document.getElementById('confirmModalActionBtn');
            if (confirmBtn) {
                confirmBtn.onclick = async function() {
                    const modalElement = document.getElementById('confirmModal');
                    const modal = window.bootstrap ? (window.bootstrap.Modal.getInstance(modalElement) || new window.bootstrap.Modal(modalElement)) : null;
                    if (modal) modal.hide();
                    await removeFromCart(productId, row);
                };
            }
        });
    });

    // Clear All Logic
    const btnClearCart = document.getElementById('btnClearCart');
    if (btnClearCart) {
        btnClearCart.addEventListener('click', function() {
            if (window.confirmAction) {
                window.confirmAction(null, 'Bạn có chắc chắn muốn xóa toàn bộ sản phẩm khỏi giỏ hàng?', 'DELETE');
                const confirmBtn = document.getElementById('confirmModalActionBtn');
                if (confirmBtn) {
                    confirmBtn.onclick = async function() {
                        const modal = window.bootstrap ? window.bootstrap.Modal.getInstance(document.getElementById('confirmModal')) : null;
                        if (modal) modal.hide();
                        try {
                            const response = await fetch('/cart/clear', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            const result = await response.json();
                            if (result.success) {
                                showEmptyCart();
                                updateNavbarBadges(0);
                                showToast(result.message, 'success');
                            }
                        } catch (error) {
                            console.error('Clear Cart Error:', error);
                        }
                    };
                }
            }
        });
    }

    async function updateCart(productId, quantity, row) {
        try {
            const response = await fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId, quantity: quantity })
            });
            const result = await response.json();
            if (result.success) {
                row.querySelector('.item-quantity').innerText = quantity;
                row.querySelector('.item-total').innerText = result.item_total;
                calculateSelectedTotal();
                updateNavbarBadges(result.cart_count);
            }
        } catch (error) {
            console.error('Update Error:', error);
            showToast('Không thể cập nhật số lượng.', 'error');
        }
    }

    async function removeFromCart(productId, row) {
        try {
            const response = await fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId })
            });
            const result = await response.json();
            if (result.success) {
                row.remove();
                calculateSelectedTotal();
                updateNavbarBadges(result.cart_count);
                showToast(result.message, 'success');
                if (result.cart_count === 0) showEmptyCart();
            }
        } catch (error) {
            console.error('Remove Error:', error);
            showToast('Không thể xóa sản phẩm.', 'error');
        }
    }

    function calculateSelectedTotal() {
        let total = 0;
        document.querySelectorAll('.cart-row').forEach(row => {
            const checkbox = row.querySelector('.item-checkbox');
            if (checkbox && checkbox.checked) {
                const price = parseInt(row.dataset.price);
                const quantity = parseInt(row.querySelector('.item-quantity').innerText);
                total += price * quantity;
            }
        });

        const formattedTotal = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
        document.querySelectorAll('.cart-subtotal, .cart-grand-total').forEach(el => el.innerText = formattedTotal);
        
        const checkoutBtn = document.querySelector('a[href*="checkout"]');
        if (checkoutBtn) {
            if (total === 0) {
                checkoutBtn.classList.add('disabled', 'opacity-50');
                checkoutBtn.style.pointerEvents = 'none';
            } else {
                checkoutBtn.classList.remove('disabled', 'opacity-50');
                checkoutBtn.style.pointerEvents = 'auto';
            }
        }
    }

    function showEmptyCart() {
        const container = document.querySelector('.table-responsive').parentElement;
        container.innerHTML = `
            <div class="py-5 text-center text-secondary animate__animated animate__fadeIn">
                <i class="fas fa-shopping-basket fa-3x mb-3 d-block opacity-25"></i>
                <h4 class="fw-bold text-dark">Giỏ hàng của bạn đang trống!</h4>
                <p class="mb-4">Hãy quay lại cửa hàng để chọn cho mình những sản phẩm ưng ý nhất.</p>
                <a href="/products" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">TIẾP TỤC MUA SẮM</a>
            </div>
        `;
        document.querySelector('.col-lg-4').classList.add('opacity-50');
        document.querySelector('.col-lg-4').style.pointerEvents = 'none';
    }

    function updateNavbarBadges(count) {
        const desktopBadge = document.querySelector('.navbar-tech__cart span');
        if (desktopBadge) desktopBadge.innerText = count;
        const mobileBadge = document.querySelector('.mobile-action-card .badge');
        if (mobileBadge) mobileBadge.innerText = count;
    }

    calculateSelectedTotal();
});
