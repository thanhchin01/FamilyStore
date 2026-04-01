document.addEventListener('DOMContentLoaded', function() {
    // Chúng ta định nghĩa biến baseUrl và products ở thẻ <script> trên view file.
    // DOM Elements - Form input
    const categorySelect = document.getElementById('categorySelect');
    const productSelect = document.getElementById('productSelect');
    const quantityInput = document.getElementById('quantityInput');
    const priceInput = document.getElementById('priceInput');
    const systemPriceText = document.getElementById('systemPriceText');
    
    // DOM Elements - Buttons & Tables
    const btnAddSaleItem = document.getElementById('btnAddSaleItem');
    const saleItemsTbody = document.getElementById('saleItemsTbody');
    const saleEmptyRow = document.getElementById('saleEmptyRow');
    const saleForm = document.getElementById('saleForm');
    
    // DOM Elements - Customer
    const customerTypeGuest = document.getElementById('customerTypeGuest');
    const customerTypeCustomer = document.getElementById('customerTypeCustomer');
    const oldCustomerSection = document.getElementById('oldCustomerSection');
    const customerPhone = document.getElementById('customerPhone');
    const customerName = document.getElementById('customerName');
    const customerAddress = document.getElementById('customerAddress');
    const customerRelativeName = document.getElementById('customerRelativeName');
    const btnFindCustomer = document.getElementById('btnFindCustomer');
    const currentDebtAlert = document.getElementById('currentDebtAlert');
    const currentDebtAmount = document.getElementById('currentDebtAmount');
    const afterDebtAmount = document.getElementById('afterDebtAmount');
    
    // DOM Elements - Totals
    const subTotalText = document.getElementById('subTotalText');
    const totalText = document.getElementById('totalText');
    const paidAmountInput = document.getElementById('paidAmountInput');
    const debtAmountInput = document.getElementById('debtAmountInput');

    // State (Trạng thái)
    let saleItems = [];
    let currentCustomerDebt = 0;

    /* --------------------------------------------------------------------------
     * 1. HÀM TIỆN ÍCH (UTILITIES)
     * -------------------------------------------------------------------------- */
    function formatCurrency(value) {
        return (Number(value) || 0).toLocaleString('vi-VN') + 'đ';
    }

    /* --------------------------------------------------------------------------
     * 2. TÍNH TOÁN & CẬP NHẬT GIAO DIỆN
     * -------------------------------------------------------------------------- */
    function updatePriceFromProduct() {
        const selectedId = productSelect.value;
        const product = window.saleProducts.find(p => String(p.id) === String(selectedId));
        if (product) {
            priceInput.value = product.price;
            systemPriceText.textContent = formatCurrency(product.price);
        } else {
            priceInput.value = '';
            systemPriceText.textContent = '0đ';
        }
    }

    function updateTotals() {
        const total = saleItems.reduce((sum, item) => sum + (Number(item.price) * Number(item.quantity)), 0);
        
        // Nếu là khách vãng lai (Guest) -> Ép kiểu trả đủ tiền (Không cho nợ)
        if (customerTypeGuest.checked) {
            paidAmountInput.value = total;
        }

        const paid = Number(paidAmountInput.value) || 0;
        const debt = Math.max(total - paid, 0);

        subTotalText.textContent = formatCurrency(total);
        totalText.textContent = formatCurrency(total);
        debtAmountInput.value = debt;

        if (afterDebtAmount) {
            afterDebtAmount.textContent = (currentCustomerDebt + debt).toLocaleString('vi-VN');
        }
    }

    /* --------------------------------------------------------------------------
     * 3. RENDER DANH SÁCH SẢN PHẨM & ẨN INPUT CHO BACKEND
     * -------------------------------------------------------------------------- */
    function renderSaleItems() {
        saleItemsTbody.innerHTML = '';
        
        if (saleItems.length === 0) {
            saleItemsTbody.innerHTML = `
                <tr id="saleEmptyRow">
                    <td colspan="5" class="text-center text-muted small py-3">
                        Chưa có sản phẩm nào trong hóa đơn.
                    </td>
                </tr>`;
            return;
        }

        saleItems.forEach((item, index) => {
            const lineTotal = Number(item.price) * Number(item.quantity);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><div class="fw-semibold">${item.name}</div></td>
                <td>
                    <input type="number" class="form-control form-control-sm item-qty-input" 
                           min="1" value="${item.quantity}" data-index="${index}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-price-input" 
                           min="0" value="${item.price}" data-index="${index}">
                </td>
                <td class="fw-semibold text-primary">${formatCurrency(lineTotal)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item" data-index="${index}">
                        <i class="fa-solid fa-trash-can"></i> Xóa
                    </button>
                </td>
            `;
            saleItemsTbody.appendChild(tr);
        });
    }

    function syncSaleHiddenInputs() {
        // Xóa các input ẩn cũ
        saleForm.querySelectorAll('input.sync-item').forEach(el => el.remove());
        
        // Tạo lại input ẩn theo mảng saleItems
        saleItems.forEach((item, index) => {
            const html = `
                <input type="hidden" name="items[${index}][product_id]" value="${item.productId}" class="sync-item">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}" class="sync-item">
                <input type="hidden" name="items[${index}][price]" value="${item.price}" class="sync-item">
            `;
            saleForm.insertAdjacentHTML('beforeend', html);
        });
    }

    /* --------------------------------------------------------------------------
     * 4. SỰ KIỆN TƯƠNG TÁC (EVENTS)
     * -------------------------------------------------------------------------- */
    // Lọc sản phẩm theo danh mục
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            Array.from(productSelect.options).forEach(option => {
                if(option.value === "") return; // Bỏ qua option mặc định
                const optCat = option.getAttribute('data-category');
                option.hidden = categoryId ? (optCat !== categoryId) : false;
            });
            productSelect.value = "";
            updatePriceFromProduct();
        });
    }

    // Chọn sản phẩm -> Cập nhật giá mẫu
    if (productSelect) {
        productSelect.addEventListener('change', updatePriceFromProduct);
    }

    // Thay đổi tiền khách trả -> Cập nhật lại nợ
    if (paidAmountInput) {
        paidAmountInput.addEventListener('input', updateTotals);
    }

    // Chuyển đổi loại khách hàng (Guest <-> Customer)
    function toggleCustomerSection() {
        if (customerTypeCustomer.checked) {
            oldCustomerSection.style.display = 'block';
        } else {
            oldCustomerSection.style.display = 'none';
        }
        updateTotals();
    }
    if (customerTypeGuest) customerTypeGuest.addEventListener('change', toggleCustomerSection);
    if (customerTypeCustomer) customerTypeCustomer.addEventListener('change', toggleCustomerSection);

    // Tìm khách hàng qua SĐT
    if (btnFindCustomer) {
        btnFindCustomer.addEventListener('click', function() {
            const phone = (customerPhone.value || '').trim();
            if (!phone) {
                alert('Vui lòng nhập số điện thoại để tìm!');
                return;
            }
            // Thêm class loading (tuỳ chọn)
            btnFindCustomer.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            
            fetch(window.baseUrl + '/admin/customers/find-by-phone?phone=' + encodeURIComponent(phone), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                btnFindCustomer.innerHTML = 'Tìm';
                if (!data.found) {
                    customerName.value = '';
                    customerAddress.value = '';
                    customerRelativeName.value = '';
                    currentCustomerDebt = 0;
                    currentDebtAlert.style.display = 'none';
                    alert('Không tìm thấy khách hàng nào với SĐT này. Vui lòng nhập thông tin mới.');
                    updateTotals();
                    return;
                }

                const c = data.customer;
                customerName.value = c.name || '';
                customerAddress.value = c.address || '';
                customerRelativeName.value = c.relative_name || '';
                
                currentCustomerDebt = Number(data.total_debt) || 0;
                if (currentCustomerDebt > 0) {
                    currentDebtAmount.textContent = currentCustomerDebt.toLocaleString('vi-VN');
                    currentDebtAlert.style.display = 'block';
                } else {
                    currentDebtAlert.style.display = 'none';
                }
                updateTotals();
            })
            .catch(err => {
                btnFindCustomer.innerHTML = 'Tìm';
                alert('Có lỗi xảy ra khi tìm khách hàng!');
            });
        });
    }

    // XỬ LÝ NÚT [THÊM SẢN PHẨM VÀO HÓA ĐƠN]
    if (btnAddSaleItem) {
        btnAddSaleItem.addEventListener('click', function() {
            console.log("Btn Add Item Clicked!"); // Debug log
            
            const productId = productSelect.value;
            const quantity = Number(quantityInput.value);
            const price = Number(priceInput.value);

            if (!productId) {
                alert('Vui lòng chọn 1 sản phẩm trước khi thêm!');
                return;
            }
            if (quantity < 1) {
                alert('Số lượng tối thiểu là 1!');
                return;
            }

            // Lấy tên sản phẩm (Cắt bỏ đoạn "(...đ)" ở cuối nếu có)
            const optionText = productSelect.options[productSelect.selectedIndex].text;
            let productName = optionText.trim();
            if (productName.includes('(')) {
                productName = productName.substring(0, productName.lastIndexOf('(')).trim();
            }

            // Kiểm tra xem SP này đã có trong table chưa?
            const existingItemIndex = saleItems.findIndex(item => String(item.productId) === String(productId));
            
            if (existingItemIndex > -1) {
                // Cập nhật số lượng mới và giá mới
                saleItems[existingItemIndex].quantity += quantity;
                saleItems[existingItemIndex].price = price;
            } else {
                // Thêm SP mới
                saleItems.push({
                    productId: productId,
                    name: productName,
                    quantity: quantity,
                    price: price
                });
            }

            // Refresh giao diện
            renderSaleItems();
            syncSaleHiddenInputs();
            updateTotals();

            // Dọn form sau khi thêm thành công để sẵn sàng nhập món tiếp theo
            productSelect.value = '';
            quantityInput.value = 1;
            priceInput.value = '';
            systemPriceText.textContent = '0đ';
        });
    }

    // Lắng nghe sự kiện Sửa số lượng / Sửa giá / Xóa trực tiếp trên Table Hóa đơn
    if (saleItemsTbody) {
        saleItemsTbody.addEventListener('input', function(e) {
            if (e.target.classList.contains('item-qty-input')) {
                const index = e.target.getAttribute('data-index');
                saleItems[index].quantity = Math.max(Number(e.target.value) || 1, 1);
                renderSaleItems();
                syncSaleHiddenInputs();
                updateTotals();
            }
            
            if (e.target.classList.contains('item-price-input')) {
                const index = e.target.getAttribute('data-index');
                saleItems[index].price = Math.max(Number(e.target.value) || 0, 0);
                renderSaleItems();
                syncSaleHiddenInputs();
                updateTotals();
            }
        });

        saleItemsTbody.addEventListener('click', function(e) {
            // Nếu click trúng Button hoặc Icon bên trong Button
            const btn = e.target.closest('.btn-remove-item');
            if (btn) {
                const index = btn.getAttribute('data-index');
                saleItems.splice(index, 1);
                renderSaleItems();
                syncSaleHiddenInputs();
                updateTotals();
            }
        });
    }

    // Kiểm tra lại trước khi gửi Form (Submit) lên Server
    if (saleForm) {
        saleForm.addEventListener('submit', function(e) {
            // Nếu người dùng chọn sản phẩm nhưng Quyên bấm nút "Thêm vào hóa đơn", tự động gộp vào luôn
            const productId = productSelect.value;
            const quantity = Number(quantityInput.value);
            const price = Number(priceInput.value);

            if (productId && quantity > 0 && saleItems.length === 0) {
                btnAddSaleItem.click(); // Mượn sự kiện click
            }

            if (saleItems.length === 0) {
                e.preventDefault(); // Ngăn form tự gửi
                alert('Vui lòng thêm ít nhất 1 sản phẩm vào hóa đơn!');
            }
        });
    }

    // Boot (Khởi chạy lần đầu)
    if(saleItemsTbody) {
        renderSaleItems();
        updateTotals();
        toggleCustomerSection();
    }
});
