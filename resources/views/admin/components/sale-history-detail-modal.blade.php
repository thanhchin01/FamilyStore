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
                        <label class="form-label-custom fw-bold small text-uppercase mb-2">Sản phẩm</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded-3">
                            <i class="fas fa-box text-primary me-3 fs-3"></i>
                            <div>
                                <div id="detail_product_name" class="fw-bold text-dark">...</div>
                                <div class="small text-muted">
                                    Số lượng: <span id="detail_quantity" class="fw-bold">0</span> |
                                    Đơn giá: <span id="detail_price" class="fw-bold">0</span>đ
                                </div>
                            </div>
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
                            <span class="text-muted">Tổng cộng hóa đơn:</span>
                            <span id="detail_total" class="fw-bold text-dark text-decoration-underline">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Khách đã trả:</span>
                            <span id="detail_paid" class="text-success fw-bold">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Còn nợ lại:</span>
                            <span id="detail_debt" class="text-danger fw-bold fs-5">0đ</span>
                        </div>
                    </div>

                    <!-- Phần trả nợ nếu có -->
                    <div id="detail_pay_debt_section" class="col-12 border-top pt-4" style="display: none;">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-hand-holding-dollar me-2 text-success"></i>Ghi nhận trả nợ</h6>
                        <form action="{{ route('admin.debt.pay') }}" method="POST" class="bg-light p-3 rounded-3 border">
                            @csrf
                            <input type="hidden" name="customer_id" id="detail_pay_customer_id">
                            <div class="row g-2 align-items-end">
                                <div class="col-8">
                                    <label class="small text-muted mb-1">Số tiền khách trả đợt này (đ)</label>
                                    <input type="text" name="amount" id="detail_pay_amount_input" class="form-control money-input" required>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-success w-100">Trả nợ</button>
                                </div>
                            </div>
                            <div class="mt-2 extra-small text-muted">Số nợ tổng hiện tại: <span id="detail_customer_total_debt" class="fw-bold text-danger">0</span>đ</div>
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

<script>
    function openSaleDetailModal(data) {
        // 1. Cổng thông tin cơ bản
        document.getElementById('detail_invoice_code').innerText = `Hóa đơn #${data.invoice_code || data.id}`;
        document.getElementById('detail_date').innerText = data.formatted_date;
        document.getElementById('detail_product_name').innerText = data.product.name;
        document.getElementById('detail_quantity').innerText = data.quantity;
        document.getElementById('detail_price').innerText = new Intl.NumberFormat('vi-VN').format(data.price);
        document.getElementById('detail_total').innerText = new Intl.NumberFormat('vi-VN').format(data.total) + 'đ';
        document.getElementById('detail_paid').innerText = new Intl.NumberFormat('vi-VN').format(data.paid_amount || 0) + 'đ';
        document.getElementById('detail_debt').innerText = new Intl.NumberFormat('vi-VN').format(data.debt || 0) + 'đ';

        // 2. Trạng thái Badge
        const badgeDiv = document.getElementById('detail_status_badge');
        if (data.debt > 0) {
            badgeDiv.innerHTML = '<span class="badge bg-warning text-dark rounded-pill px-3">Còn nợ</span>';
        } else {
            badgeDiv.innerHTML = '<span class="badge bg-success rounded-pill px-3">Đã trả đủ</span>';
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

        // 4. Xử lý trả nợ
        const paySection = document.getElementById('detail_pay_debt_section');
        if (data.customer && data.customer.debt && data.customer.debt.total_debt > 0) {
            paySection.style.display = 'block';
            document.getElementById('detail_pay_customer_id').value = data.customer.id;
            document.getElementById('detail_pay_amount_input').max = data.customer.debt.total_debt;
            document.getElementById('detail_pay_amount_input').placeholder = `Tối đa ${new Intl.NumberFormat('vi-VN').format(data.customer.debt.total_debt)}`;
            document.getElementById('detail_customer_total_debt').innerText = new Intl.NumberFormat('vi-VN').format(data.customer.debt.total_debt);
        } else {
            paySection.style.display = 'none';
        }

        // 5. Hiện Modal
        new bootstrap.Modal(document.getElementById('saleDetailModal')).show();
    }
</script>

<style>
.form-label-custom { display: block; background: none; }
.extra-small { font-size: 0.75rem; }
</style>
