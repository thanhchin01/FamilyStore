<!-- Modal xem chi tiết hoá đơn -->
<div class="modal fade" id="saleDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold" id="detail_invoice_code">Hóa đơn #...</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-6">
                        <label class="form-label-custom fw-bold small text-uppercase mb-1">Ngày bán</label>
                        <div id="detail_date" class="fw-medium">...</div>
                    </div>
                    <div class="col-6 text-end">
                        <label class="form-label-custom fw-bold small text-uppercase mb-1">Trạng thái</label>
                        <div id="detail_status_badge"></div>
                    </div>

                    <div class="col-12 border-top pt-3">
                        <label class="form-label-custom fw-bold small text-uppercase mb-2">Chi tiết sản phẩm</label>
                        <div id="detail_items_container" class="border rounded-3 overflow-hidden">
                            <!-- Items go here -->
                        </div>
                    </div>

                    <div class="col-12 border-top pt-3">
                        <label class="form-label-custom fw-bold small text-uppercase mb-2">Thông tin khách hàng</label>
                        <div class="p-3 border rounded-3">
                            <div id="detail_customer_name" class="fw-bold text-dark">...</div>
                            <div id="detail_customer_phone_div" class="small text-primary fw-medium mt-1"></div>
                            <div id="detail_customer_address" class="extra-small text-muted mt-1"></div>
                        </div>
                    </div>

                    <div class="col-12 border-top pt-3">
                        <label class="form-label-custom fw-bold small text-uppercase mb-2">Thanh toán</label>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tổng cộng giá trị:</span>
                            <span id="detail_total" class="fw-bold text-dark text-decoration-underline">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Đã thanh toán:</span>
                            <span id="detail_paid" class="text-success fw-bold">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Còn nợ lại:</span>
                            <span id="detail_debt" class="text-danger fw-bold fs-5">0đ</span>
                        </div>
                    </div>

                    <!-- Timeline trả nợ -->
                    <div id="detail_timeline_section" class="col-12 border-top pt-3" style="display: none;">
                        <label class="form-label-custom fw-bold small text-uppercase mb-3">Lịch sử trả bớt nợ</label>
                        <div class="payment-timeline ms-2 border-start ps-4 position-relative" id="detail_timeline_container">
                            <!-- Timeline items go here -->
                        </div>
                    </div>

                    <!-- Phần trả nợ nếu có -->
                    <div id="detail_pay_debt_section" class="col-12 border-top pt-4" style="display: none;">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-hand-holding-dollar me-2 text-success"></i>Ghi nhận trả nợ</h6>
                        <form action="{{ route('admin.debt.pay') }}" method="POST" class="bg-light p-3 rounded-3 border">
                            @csrf
                            <input type="hidden" name="customer_id" id="detail_pay_customer_id">
                            <input type="hidden" name="sale_id" id="detail_pay_sale_id">
                            <div class="row g-2 align-items-end">
                                <div class="col-8">
                                    <label class="small text-muted mb-1">Số tiền khách trả đợt này (đ)</label>
                                    <input type="text" name="amount" id="detail_pay_amount_input" class="form-control money-input" required>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-success w-100 fw-bold">Trả nợ</button>
                                </div>
                            </div>
                            <div class="mt-2 extra-small text-muted d-flex justify-content-between">
                                <span>Tối đa có thể trả cho đơn này: <span id="detail_max_pay" class="fw-bold text-primary">0</span>đ</span>
                                <span>Nợ tổng: <span id="detail_customer_total_debt" class="fw-bold text-danger">0</span>đ</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-timeline { border-width: 2px !important; border-color: #e2e8f0 !important; }
    .timeline-item { position: relative; padding-bottom: 1.5rem; }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -31px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #009688;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #00968820;
    }
    .timeline-item:last-child { padding-bottom: 0; }
    .last-child-border-0:last-child { border-bottom: none !important; }
</style>

<script>
    function openSaleDetailModal(data) {
        // 1. Cổng thông tin cơ bản
        document.getElementById('detail_invoice_code').innerText = `Hóa đơn #${data.invoice_code || data.id}`;
        document.getElementById('detail_date').innerText = data.formatted_date;
        
        // 1b. Danh sách sản phẩm
        const itemsContainer = document.getElementById('detail_items_container');
        itemsContainer.innerHTML = '';
        if (data.items && data.items.length > 0) {
            data.items.forEach(item => {
                const row = document.createElement('div');
                row.className = 'd-flex align-items-center p-2 border-bottom last-child-border-0';
                row.innerHTML = `
                    <div class="flex-grow-1">
                        <div class="fw-bold small text-dark">${item.product ? item.product.name : 'Sản phẩm đã xóa'}</div>
                        <div class="extra-small text-muted">${new Intl.NumberFormat('vi-VN').format(item.unit_price)}đ x ${item.quantity}</div>
                    </div>
                    <div class="fw-bold text-primary small">${new Intl.NumberFormat('vi-VN').format(item.subtotal)}đ</div>
                `;
                itemsContainer.appendChild(row);
            });
        }

        document.getElementById('detail_total').innerText = new Intl.NumberFormat('vi-VN').format(data.total) + 'đ';
        document.getElementById('detail_paid').innerText = new Intl.NumberFormat('vi-VN').format(data.paid_amount || 0) + 'đ';
        document.getElementById('detail_debt').innerText = new Intl.NumberFormat('vi-VN').format(data.debt_amount || 0) + 'đ';

        // 2. Trạng thái Badge
        const badgeDiv = document.getElementById('detail_status_badge');
        if (data.debt_amount > 0) {
            badgeDiv.innerHTML = '<span class="badge bg-warning text-dark rounded-pill px-3 shadow-sm">Còn nợ</span>';
        } else {
            badgeDiv.innerHTML = '<span class="badge bg-success rounded-pill px-3 shadow-sm">Đã trả đủ</span>';
        }

        // 3. Khách hàng
        document.getElementById('detail_customer_name').innerText = data.customer ? data.customer.name : 'Khách lẻ';
        const phoneDiv = document.getElementById('detail_customer_phone_div');
        if (data.customer && data.customer.phone) {
            phoneDiv.innerText = `SĐT: ${data.customer.phone}`;
            phoneDiv.style.display = 'block';
        } else {
            phoneDiv.style.display = 'none';
        }
        document.getElementById('detail_customer_address').innerText = (data.customer && data.customer.address) ? `Địa chỉ: ${data.customer.address}` : '';

        // 4. Timeline trả nợ
        const timelineSection = document.getElementById('detail_timeline_section');
        const timelineContainer = document.getElementById('detail_timeline_container');
        timelineContainer.innerHTML = '';
        
        if (data.transactions && data.transactions.length > 0) {
            timelineSection.style.display = 'block';
            data.transactions.forEach(t => {
                const date = new Date(t.occurred_at || t.created_at).toLocaleDateString('vi-VN');
                const item = document.createElement('div');
                item.className = 'timeline-item';
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold small text-dark">${t.type === 'payment' ? 'Trả bớt nợ' : 'Phát sinh'}</div>
                            <div class="extra-small text-muted">${t.description || ''}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold ${t.type === 'payment' ? 'text-success' : 'text-danger'}">${new Intl.NumberFormat('vi-VN').format(t.amount)}đ</div>
                            <div class="extra-small text-muted">${date}</div>
                        </div>
                    </div>
                `;
                timelineContainer.appendChild(item);
            });
        } else {
            timelineSection.style.display = 'none';
        }

        // 5. Xử lý phần trả nợ
        const paySection = document.getElementById('detail_pay_debt_section');
        if (data.customer && data.debt_amount > 0) {
            paySection.style.display = 'block';
            document.getElementById('detail_pay_customer_id').value = data.customer.id;
            document.getElementById('detail_pay_sale_id').value = data.id;
            document.getElementById('detail_pay_amount_input').value = data.debt_amount;
            document.getElementById('detail_max_pay').innerText = new Intl.NumberFormat('vi-VN').format(data.debt_amount);
            document.getElementById('detail_customer_total_debt').innerText = new Intl.NumberFormat('vi-VN').format(data.customer.debtBalance ? data.customer.debtBalance.balance_amount : 0);
        } else {
            paySection.style.display = 'none';
        }

        // 6. Hiện Modal
        new bootstrap.Modal(document.getElementById('saleDetailModal')).show();
    }
</script>

<style>
.form-label-custom { display: block; background: none; }
.extra-small { font-size: 0.75rem; }
</style>
