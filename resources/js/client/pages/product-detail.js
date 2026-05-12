document.addEventListener('DOMContentLoaded', function() {
    window.changeQty = function(amount) {
        const input = document.getElementById('productQty');
        if (!input) return;
        let val = parseInt(input.value) + amount;
        if (val < 1) val = 1;
        input.value = val;
    };

    window.updateMainImage = function(src, thumb) {
        const mainImg = document.getElementById('productMainImg');
        if (!mainImg) return;
        mainImg.src = src;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active-thumbnail', 'opacity-50'));
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.add('opacity-50'));
        thumb.classList.remove('opacity-50');
        thumb.classList.add('active-thumbnail');
    };
});
