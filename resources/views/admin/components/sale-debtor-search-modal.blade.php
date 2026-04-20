<!-- Modal Tìm kiếm khách nợ Thông minh -->
<div class="modal fade" id="modalSearchDebtor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-address-book me-2"></i>Tìm khách từ danh sách nợ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                        <span class="input-group-text bg-white border-0"><i class="fas fa-filter text-primary"></i></span>
                        <input type="text" id="searchDebtorInput" class="form-control border-0 ps-0" placeholder="Nhập Tên hoặc SĐT để lọc nhanh...">
                    </div>
                </div>

                <div class="table-responsive rounded-4 border overflow-hidden">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase extra-small fw-bold">
                            <tr>
                                <th class="ps-3 py-3">Khách hàng</th>
                                <th>Số điện thoại</th>
                                <th>Nợ hiện tại</th>
                                <th class="text-end pe-3">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="debtorListBody">
                            <tr class="text-center py-5">
                                <td colspan="4"><div class="spinner-border spinner-border-sm text-primary"></div> Đang tải...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let allDebtors = [];
        const modal = document.getElementById('modalSearchDebtor');
        const searchInput = document.getElementById('searchDebtorInput');
        const tbody = document.getElementById('debtorListBody');

        if (modal) {
            modal.addEventListener('show.bs.modal', function() {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div> Đang lấy dữ liệu...</td></tr>';
                
                fetch('{{ route('admin.notifications.counts') }}?type=debtors')
                .then(res => res.json())
                .then(res => {
                    allDebtors = res.debtors || [];
                    renderDebtors(allDebtors);
                });
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const filtered = allDebtors.filter(d => 
                    d.name.toLowerCase().includes(query) || 
                    (d.phone && d.phone.includes(query))
                );
                renderDebtors(filtered);
            });
        }

        function renderDebtors(list) {
            tbody.innerHTML = '';
            if (list.length > 0) {
                list.forEach(d => {
                    const row = `
                        <tr>
                            <td class="ps-3">
                                <div class="fw-bold text-dark">${d.name}</div>
                                <div class="extra-small text-muted">${d.address || ''}</div>
                            </td>
                            <td><span class="badge bg-light text-dark fw-normal">${d.phone || '---'}</span></td>
                            <td class="text-danger fw-bold">${new Intl.NumberFormat('vi-VN').format(d.total_debt)}đ</td>
                            <td class="text-end pe-3">
                                <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" 
                                    onclick="selectDebtor('${d.phone}', '${d.name}', '${d.address || ''}', '${d.relative_name || ''}', ${d.total_debt})">
                                    CHỌN
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-5 text-muted">Không tìm thấy khách hàng nào khớp yêu cầu.</td></tr>';
            }
        }
    });

    function selectDebtor(phone, name, address, relative, debt) {
        const customerRadio = document.getElementById('customerTypeCustomer');
        if (customerRadio) {
            customerRadio.checked = true;
            customerRadio.dispatchEvent(new Event('change'));
        }
        
        document.getElementById('customerPhone').value = phone;
        document.getElementById('customerName').value = name;
        document.getElementById('customerAddress').value = address;
        document.getElementById('customerRelativeName').value = relative;
        
        const alertDiv = document.getElementById('currentDebtAlert');
        if (alertDiv) {
            alertDiv.style.display = 'block';
            document.getElementById('currentDebtAmount').innerText = new Intl.NumberFormat('vi-VN').format(debt);
            window.currentCustomerDebt = debt;
        }
        
        bootstrap.Modal.getInstance(document.getElementById('modalSearchDebtor')).hide();
    }
</script>
