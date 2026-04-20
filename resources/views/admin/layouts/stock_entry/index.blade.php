@extends('admin.layout')

@section('title', 'Nhập hàng vào kho - Gia dụng Khoa Quyên')

@section('content')
<div class="stock-entry-container py-4">
    <div class="mb-4">
        <h4 class="fw-bold font-luxury text-primary">Nhập hàng mới</h4>
        <p class="text-muted small">Cập nhật số lượng sản phẩm vào kho hệ thống</p>
    </div>

    <div class="row g-4">
        <!-- Form Nhập Kho -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 bg-white p-4">
                <h6 class="fw-bold mb-4 text-dark d-flex align-items-center">
                    <span class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3">
                        <i class="fa-solid fa-file-invoice fs-5"></i>
                    </span>
                    Thông tin phiếu nhập kho
                </h6>

                <form method="POST" action="{{ route('admin.stock.store') }}" id="stockEntryForm">
                    @csrf
                    <div class="row g-3">
                        <!-- Chọn Sản Phẩm -->
                        <div class="col-12">
                            <label class="form-label-custom fw-bold small text-uppercase">Sản phẩm cần nhập</label>
                            <div class="input-group shadow-sm border rounded-pill overflow-hidden bg-light-subtle">
                                <span class="input-group-text bg-transparent border-0 ps-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <select id="importProductSelect" class="form-select border-0 bg-transparent py-2">
                                    <option value="" selected disabled>Tìm hoặc chọn sản phẩm...</option>
                                    @foreach ($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach ($products->where('category_id', $category->id) as $p)
                                                <option value="{{ $p->id }}" data-stock="{{ $p->stock }}">
                                                    {{ $p->name }} — (Hiện có: {{ $p->stock }} cái)
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Số lượng -->
                        <div class="col-md-6">
                            <label class="form-label-custom fw-bold small text-uppercase">Số lượng nhập</label>
                            <div class="input-group shadow-sm border rounded-pill overflow-hidden bg-light-subtle">
                                <span class="input-group-text bg-transparent border-0 ps-3 text-muted"><i class="fa-solid fa-layer-group"></i></span>
                                <input type="number" id="importQuantityInput" class="form-control border-0 bg-transparent py-2" placeholder="Ví dụ: 10" min="1">
                            </div>
                        </div>

                        <!-- Đơn vị -->
                        <div class="col-md-6">
                            <label class="form-label-custom fw-bold small text-uppercase">Đơn vị tính</label>
                            <input type="text" class="form-control rounded-pill border-light bg-light py-2" value="Cái" readonly>
                        </div>

                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary w-100 rounded-pill fw-bold" id="btnAddImportItem">
                                <i class="fa-solid fa-plus me-2"></i>Thêm vào danh sách nhập
                            </button>
                        </div>

                        <!-- Danh sách mặt hàng trong phiếu -->
                        <div class="col-12">
                            <div class="table-responsive border rounded-4 overflow-hidden mt-3">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="extra-small text-uppercase fw-bold text-muted">
                                            <th class="ps-3">Sản phẩm</th>
                                            <th style="width: 140px;">SL nhập</th>
                                            <th class="text-end pe-3" style="width: 100px;">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody id="importItemsTbody">
                                        <tr id="importEmptyRow">
                                            <td colspan="3" class="text-center text-muted small py-4">
                                                Chưa có sản phẩm nào được chọn.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="col-12">
                            <label class="form-label-custom fw-bold small text-uppercase">Ghi chú (Tùy chọn)</label>
                            <textarea name="note" class="form-control rounded-4 border-light shadow-sm" rows="2" placeholder="Ghi chú về nguồn hàng, lô hàng..."></textarea>
                        </div>

                        <!-- Nút xác nhận -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow" id="btnSubmitImport">
                                <i class="fa-solid fa-cloud-arrow-up me-2"></i> HOÀN TẤT NHẬP KHO
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lịch sử nhập kho gần đây -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">Lịch sử nhập gần đây</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="entryHistoryList">
                        @forelse ($imports as $imp)
                            <div class="list-group-item p-3 border-light">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 me-3">
                                            <i class="fas fa-arrow-down fs-6"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small text-dark">{{ $imp->product?->name ?? '—' }}</div>
                                            <div class="extra-small text-muted">
                                                {{ $imp->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">+{{ $imp->quantity }} cái</span>
                                        <div class="extra-small text-muted mt-1">Tồn k: {{ $imp->product?->stock ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center text-muted small">Chưa có lịch sử nhập kho.</div>
                        @endforelse
                    </div>
                    
                    @if($imports->hasPages())
                        <div class="p-3 bg-light bg-opacity-50">
                            {{ $imports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var productSelect = document.getElementById('importProductSelect');
            var qtyInput = document.getElementById('importQuantityInput');
            var btnAdd = document.getElementById('btnAddImportItem');
            var tbody = document.getElementById('importItemsTbody');
            var emptyRow = document.getElementById('importEmptyRow');
            var form = document.getElementById('stockEntryForm');

            var items = []; 

            function renderItems() {
                tbody.innerHTML = '';
                if (!items.length) {
                    tbody.appendChild(emptyRow);
                    return;
                }

                items.forEach(function(it, idx) {
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td class="ps-3">' +
                        '<div class="fw-bold text-dark small">' + it.name + '</div>' +
                        '<div class="extra-small text-muted">Hiện tồn: ' + it.stock + '</div>' +
                        '</td>' +
                        '<td>' +
                        '<input type="number" class="form-control form-control-sm rounded-pill px-3 border-light shadow-sm" min="1" value="' + it.quantity + '" data-idx="' + idx + '">' +
                        '</td>' +
                        '<td class="text-end pe-3">' +
                        '<button type="button" class="btn btn-sm text-danger" data-remove="' + idx + '"><i class="fas fa-times"></i></button>' +
                        '</td>';
                    tbody.appendChild(tr);
                });
            }

            function syncHiddenInputs() {
                var oldHidden = form.querySelectorAll('input[name^="items["]');
                oldHidden.forEach(el => el.remove());

                items.forEach(function(it, idx) {
                    var pid = document.createElement('input');
                    pid.type = 'hidden';
                    pid.name = 'items[' + idx + '][product_id]';
                    pid.value = it.productId;

                    var qty = document.createElement('input');
                    qty.type = 'hidden';
                    qty.name = 'items[' + idx + '][quantity]';
                    qty.value = it.quantity;

                    form.appendChild(pid);
                    form.appendChild(qty);
                });
            }

            btnAdd.addEventListener('click', function() {
                var productId = productSelect.value;
                var quantity = Number(qtyInput.value || 0);

                if (!productId) { showToast('Vui lòng chọn sản phẩm', 'error'); return; }
                if (quantity < 1) { showToast('Số lượng phải từ 1 trở lên', 'error'); return; }

                var opt = productSelect.options[productSelect.selectedIndex];
                var nameText = opt.textContent || opt.innerText;
                var name = nameText.split('—')[0].trim();
                var stock = opt.getAttribute('data-stock') || '0';

                var existing = items.find(i => String(i.productId) === String(productId));
                if (existing) {
                    existing.quantity += quantity;
                } else {
                    items.push({ productId: productId, name: name, stock: stock, quantity: quantity });
                }

                qtyInput.value = '';
                renderItems();
                syncHiddenInputs();
            });

            tbody.addEventListener('input', function(e) {
                var input = e.target;
                if (!input || input.getAttribute('data-idx') === null) return;
                var idx = Number(input.getAttribute('data-idx'));
                var v = Number(input.value || 0);
                if (idx >= 0 && idx < items.length) {
                    items[idx].quantity = v > 0 ? v : 1;
                    syncHiddenInputs();
                }
            });

            tbody.addEventListener('click', function(e) {
                var btn = e.target.closest('button');
                if (!btn) return;
                var idxAttr = btn.getAttribute('data-remove');
                if (idxAttr === null) return;
                var idx = Number(idxAttr);
                if (idx >= 0 && idx < items.length) {
                    items.splice(idx, 1);
                    renderItems();
                    syncHiddenInputs();
                }
            });

            form.addEventListener('submit', function(e) {
                if (!items.length) {
                    var productId = productSelect.value;
                    var quantity = Number(qtyInput.value || 0);
                    if (productId && quantity > 0) {
                        var opt = productSelect.options[productSelect.selectedIndex];
                        var name = opt.textContent.split('—')[0].trim();
                        var stock = opt.getAttribute('data-stock') || '0';
                        items.push({ productId: productId, name: name, stock: stock, quantity: quantity });
                        syncHiddenInputs();
                    } else {
                        e.preventDefault();
                        showToast('Bạn chưa thêm sản phẩm nào vào phiếu nhập', 'error');
                    }
                }
            });

            renderItems();
        });
    </script>
@endpush
@endsection

<style>
.form-label-custom { font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem; display: block; }
.extra-small { font-size: 0.72rem; }
.bg-light-subtle { background-color: #f8fafc !important; }
#entryHistoryList .list-group-item { transition: background 0.2s; }
#entryHistoryList .list-group-item:hover { background-color: #f8fafc; }
</style>
