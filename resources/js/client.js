import 'swiper/css/bundle';
import { animate, createTimeline, stagger } from 'animejs';

import { formatCurrency, formatDate, showToast, initConfirmModals, ensureAuthenticated } from './helpers/ui-utils';


window.formatCurrency = formatCurrency;
window.formatDate = formatDate;
window.showToast = showToast;

initConfirmModals();


const initClientScripts = () => {
    const navbar = document.querySelector('.navbar-tech');
    const searchToggle = document.querySelector('[data-search-toggle]');
    const searchPanel = document.querySelector('[data-search-panel]');

    if (navbar) {
        const updateNavbarState = () => {
            navbar.classList.toggle('is-scrolled', window.scrollY > 12);
        };

        updateNavbarState();
        window.addEventListener('scroll', updateNavbarState);
    }

    if (searchToggle && searchPanel) {
        searchToggle.addEventListener('click', () => {
            searchPanel.classList.toggle('is-open');
        });
    }

    if (document.querySelector('.tech-hero')) {
        createTimeline({ defaults: { ease: 'outExpo' } })
            .add({
                targets: '.tech-hero h1',
                y: [50, 0], opacity: [0, 1],
                duration: 1200, delay: 200
            })
            .add({
                targets: '.tech-hero p, .tech-hero .tech-badge, .tech-hero__metrics > div',
                y: [20, 0], opacity: [0, 1],
                duration: 900,
                delay: stagger(80)
            }, '-=800')
            .add({
                targets: '.tech-hero .btn-tech-primary, .tech-hero .btn-tech-secondary, .tech-float-card',
                scale: [0.9, 1], opacity: [0, 1],
                duration: 800, delay: stagger(100)
            }, '-=600');
    }

    if (document.querySelector('.product-card--tech')) {
        animate('.product-card--tech', {
            y: [60, 0], opacity: [0, 1],
            delay: stagger(60, { start: 250 }),
            ease: 'outQuad', duration: 800
        });
    }

    // Global Auth Interceptor
    document.addEventListener('click', (e) => {
        const authBtn = e.target.closest('[data-require-auth]');
        if (authBtn && !window.isAuthenticated) {
            e.preventDefault();
            e.stopPropagation();
            
            const msg = authBtn.dataset.authMsg || null;
            ensureAuthenticated(() => {
                // If it was just a link click, we might want to navigate after login
                // But for now, we just show the modal
            }, msg);
        }
    }, true);

    // AJAX Add to Cart
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.add-to-cart-btn');
        if (!btn || !window.isAuthenticated) return;

        e.preventDefault();
        const productId = btn.dataset.id;
        const quantity = btn.dataset.qty || 1;
        
        try {
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId, quantity: quantity })
            });

            const data = await response.json();

            if (data.success) {
                // Update Desktop Badge
                const desktopBadge = document.querySelector('.navbar-tech__cart span');
                if (desktopBadge) desktopBadge.innerText = data.cart_count;

                // Update Mobile Badge
                const mobileBadge = document.querySelector('.mobile-action-card .badge');
                if (mobileBadge) mobileBadge.innerText = data.cart_count;

                showToast(data.message, 'success');
                
                // Animation for badge
                animate('.navbar-tech__cart, .mobile-action-card [href*="cart"]', {
                    scale: [1, 1.2, 1],
                    duration: 400,
                    easing: 'easeOutElastic(1, .5)'
                });
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Cart Error:', error);
            showToast('Không thể kết nối đến máy chủ', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus"></i>'; // Default icon, can be dynamic
        }
    });


};
    // AJAX Buy Now
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.buy-now-btn');
        if (!btn || !window.isAuthenticated) return;

        e.preventDefault();
        const productId = btn.dataset.id;
        const quantity = btn.dataset.qty || 1;

        try {
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang xử lý...';

            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    product_id: productId, 
                    quantity: quantity,
                    buy_now: true
                })
            });

            const data = await response.json();

            if (data.success) {
                // Redirect directly to checkout
                window.location.href = '/checkout';
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
                btn.disabled = false;
                btn.innerHTML = 'Mua ngay';
            }
        } catch (error) {
            console.error('Buy Now Error:', error);
            showToast('Không thể kết nối đến máy chủ', 'error');
            btn.disabled = false;
        }
    });

    // AJAX Wishlist Toggle
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.wishlist-btn');
        if (!btn || !window.isAuthenticated) return;

        e.preventDefault();
        const productId = btn.dataset.id;
        const icon = btn.querySelector('i');

        try {
            btn.classList.add('is-loading');
            const response = await fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId })
            });

            const data = await response.json();

            if (data.success) {
                // Toggle Icon
                if (data.action === 'added') {
                    icon.classList.replace('far', 'fas');
                    icon.classList.add('text-danger', 'animate__heartBeat');
                } else {
                    icon.classList.replace('fas', 'far');
                    icon.classList.remove('text-danger', 'animate__heartBeat');
                }

                showToast(data.message, 'success');

                // Dispatch event for specialized pages (like wishlist page) to react
                document.dispatchEvent(new CustomEvent('wishlist-updated', {
                    detail: { productId, action: data.action }
                }));
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Wishlist Error:', error);
            showToast('Không thể kết nối đến máy chủ', 'error');
        } finally {
            btn.classList.remove('is-loading');
        }
    });


// Handle Preloader Dismissal
window.addEventListener('load', () => {
    const preloader = document.getElementById('preloader');
    if (preloader) {
        preloader.classList.add('fade-out');
        // Ensure it is removed from DOM after animation
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 600);
    }
});

// Application Launch
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initClientScripts);
} else {
    initClientScripts();
}
