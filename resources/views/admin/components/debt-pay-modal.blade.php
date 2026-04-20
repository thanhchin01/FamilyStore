<!-- Modal Trả bớt nợ -->
<div class="modal fade border-0" id="modalPayDebt" tabindex="-1" aria-labelledby="modalPayDebtLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form action="{{ route('admin.debt.pay') }}" method="POST">
                @csrf
                <input type="hidden" name="customer_id" id="pay_customer_id">
                
                <div class="modal-header bg-success text-white border-0 py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                    <h5 class="modal-title fw-bold" id="modalPayDebtLabel">
                        <i class="fas fa-hand-holding-usd me-2"></i> Ghi nhận trả nợ
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4 bg-light p-3 rounded-4">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Khách hàng</label>
                        <h5 class="fw-bold mb-0 text-dark" id="pay_customer_name">---</h5>
                    </div>

                    <div class="mb-4 text-center">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Tổng nợ hiện tại</label>
                        <div class="fs-2 fw-bold text-danger" id="pay_current_debt">0đ</div>
                    </div>

                    <div class="mb-3">
                        <label for="pay_amount" class="form-label fw-bold">Số tiền khách trả</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden border">
                            <input type="text" name="amount" id="pay_amount" class="form-control border-0 fw-bold text-success money-input" 
                                placeholder="Nhập số tiền..." required>
                            <span class="input-group-text border-0 bg-white fw-bold">VND</span>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="pay_description" class="form-label fw-bold">Ghi chú giao dịch</label>
                        <textarea name="description" id="pay_description" class="form-control rounded-3" rows="2" 
                            placeholder="VD: Trả tiền mặt, chuyển khoản ngân hàng..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                        CẬP NHẬT TRẢ NỢ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalPayDebt = document.getElementById('modalPayDebt');
        if (modalPayDebt) {
            modalPayDebt.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const customerId = button.getAttribute('data-id');
                const customerName = button.getAttribute('data-name');
                const currentDebt = button.getAttribute('data-debt');

                document.getElementById('pay_customer_id').value = customerId;
                document.getElementById('pay_customer_name').textContent = customerName;
                
                const formattedDebt = new Intl.NumberFormat('vi-VN').format(currentDebt) + 'đ';
                document.getElementById('pay_current_debt').textContent = formattedDebt;
                document.getElementById('pay_amount').value = formatCurrency(currentDebt);
                document.getElementById('pay_amount').focus();
            });
        }
    });
</script>
