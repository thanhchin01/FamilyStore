// import './bootstrap';
import 'bootstrap';
// import {
//     runCounter
// } from './anime';

// View bán hàng
document.addEventListener('DOMContentLoaded', () => {
    //

    // runCounter();

    //
    const categorySelect = document.getElementById('categorySelect');
    const productSelect = document.getElementById('productSelect');
    const priceInput = document.getElementById('priceInput');
    const quantityInput = document.getElementById('quantityInput');

    const systemPriceText = document.getElementById('systemPriceText');
    const subTotalText = document.getElementById('subTotalText');
    const totalText = document.getElementById('totalText');

    const paidAmountInput = document.getElementById('paidAmountInput');
    const debtAmountInput = document.getElementById('debtAmountInput');

    const customerRadios = document.querySelectorAll('input[name="customer_type"]');
    const oldCustomerSection = document.getElementById('oldCustomerSection');

    const form = document.querySelector('form');

    let unitPrice = 0;
    let total = 0;

    /* ===== HELPERS ===== */
    const formatMoney = value =>
        new Intl.NumberFormat('vi-VN').format(value) + 'đ';

    const parseMoney = value =>
        parseInt(value.replace(/\D/g, '')) || 0;

    /* ===== TÍNH TIỀN ===== */
    function updateTotal() {
        const qty = parseInt(quantityInput.value) || 1;
        total = unitPrice * qty;

        subTotalText.innerText = formatMoney(total);
        totalText.innerText = formatMoney(total);

        updateDebt();
    }

    function updateDebt() {
        const paid = parseMoney(paidAmountInput.value || '0');
        let debt = total - paid;
        if (debt < 0) debt = 0;

        debtAmountInput.value = formatMoney(debt);
    }

    /* ===== LỌC SẢN PHẨM THEO LOẠI ===== */
    categorySelect.addEventListener('change', () => {
        const categoryId = categorySelect.value;

        Array.from(productSelect.options).forEach(option => {
            if (!option.value) return;
            option.style.display =
                option.dataset.category === categoryId ? 'block' : 'none';
        });

        productSelect.value = '';
        priceInput.value = '';
        systemPriceText.innerText = '0đ';
        unitPrice = 0;
        updateTotal();
    });

    /* ===== CHỌN SẢN PHẨM ===== */
    productSelect.addEventListener('change', () => {
        const selected = productSelect.selectedOptions[0];
        if (!selected) return;

        unitPrice = parseInt(selected.dataset.price);
        priceInput.value = unitPrice;
        systemPriceText.innerText = formatMoney(unitPrice);

        updateTotal();
    });

    /* ===== SỐ LƯỢNG ===== */
    quantityInput.addEventListener('input', updateTotal);

    /* ===== KHÁCH TRẢ TRƯỚC ===== */
    paidAmountInput.addEventListener('input', updateDebt);

    /* ===== KHÁCH QUEN / VÃNG LAI ===== */
    customerRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            oldCustomerSection.style.display =
                radio.value === 'customer' ? 'block' : 'none';
        });
    });

    /* ===== VALIDATE TRƯỚC KHI SUBMIT ===== */
    form.addEventListener('submit', e => {
        if (!productSelect.value || unitPrice <= 0) {
            e.preventDefault();
            alert('Vui lòng chọn sản phẩm hợp lệ');
            return;
        }

        if (quantityInput.value <= 0) {
            e.preventDefault();
            alert('Số lượng không hợp lệ');
            return;
        }
    });
});



