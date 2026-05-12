document.addEventListener('DOMContentLoaded', function() {
    // Logic to update UI when heart is toggled on the wishlist page
    document.addEventListener('wishlist-updated', function(e) {
        const { productId, action } = e.detail;
        
        if (action === 'removed' && window.location.pathname.includes('/wishlist')) {
            const container = document.querySelector(`.wishlist-item-container[data-product-id="${productId}"]`);
            if (container) {
                container.classList.add('removing');
                setTimeout(() => {
                    container.remove();
                    
                    // Update count
                    const countEl = document.querySelector('.wishlist-count');
                    if (countEl) {
                        const currentCount = parseInt(countEl.innerText);
                        countEl.innerText = currentCount - 1;
                        
                        if (currentCount - 1 === 0) {
                            window.location.reload(); // Reload to show empty state
                        }
                    }
                }, 400);
            }
        }
    });
});
