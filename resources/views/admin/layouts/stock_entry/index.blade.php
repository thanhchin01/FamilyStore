@extends('admin.layout')

@section('title', 'Nhập hàng vào kho')

@section('content')
<div class="stock-entry-container py-4">
    <div class="mb-4">
        <h4 class="fw-bold">Nhập hàng mới</h4>
        <p class="text-muted small">Cập nhật số lượng sản phẩm vào kho hệ thống</p>
    </div>

    <div class="row g-4">
        <!-- Form Nhập Kho -->
        <div class="col-lg-7">
            <div class="card entry-card p-4">
                <h6 class="fw-bold mb-4"><i class="fa-solid fa-box-open me-2 text-primary"></i>Thông tin phiếu nhập</h6>

                {{-- ✅ Updated: submit POST để nhập kho + hỗ trợ nhiều sản phẩm trong 1 phiếu --}}
                <form method="POST" action="{{ route('admin.stock.store') }}" id="stockEntryForm">
                    @csrf
                    <div class="row g-3">
                        <!-- Chọn Sản Phẩm -->
                        <div class="col-12">
                            <label class="form-label fw-600 small text-uppercase text-muted">Sản phẩm cần nhập</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                {{-- ✅ Có name để backend có thể xử lý trường hợp chỉ nhập 1 dòng (fallback) --}}
                                <select id="importProductSelect" name="product_id" class="form-select form-control-custom">
                                    <option value="" selected disabled>Tìm hoặc chọn sản phẩm...</option>
                                    @foreach ($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach ($products->where('category_id', $category->id) as $p)
                                                <option value="{{ $p->id }}" data-stock="{{ $p->stock }}">
                                                    {{ $p->name }} — (Hiện có: {{ $p->stock }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Số lượng -->
                        <div class="col-md-6">
                            <label class="form-label fw-600 small text-uppercase text-muted">Số lượng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-icon"><i class="fa-solid fa-layer-group"></i></span>
                                {{-- ✅ Có name để backend có thể xử lý trường hợp chỉ nhập 1 dòng (fallback) --}}
                                <input type="number" id="importQuantityInput" name="quantity" class="form-control form-control-custom" placeholder="Ví dụ: 10" min="1">
                            </div>
                        </div>

                        <!-- Đơn vị (tự động theo sản phẩm) -->
                        <div class="col-md-6">
                            <label class="form-label fw-600 small text-uppercase text-muted">Đơn vị tính</label>
                            <input type="text" class="form-control bg-light" value="Cái" readonly>
                        </div>

                        {{-- ✅ Added: nút thêm dòng vào phiếu nhập --}}
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary w-100 fw-bold" id="btnAddImportItem">
                                <i class="fa-solid fa-plus me-2"></i>Thêm vào phiếu nhập
                            </button>
                        </div>

                        {{-- ✅ Added: danh sách dòng nhập (nhiều sản phẩm / 1 phiếu) --}}
                        <div class="col-12">
                            <div class="table-responsive border rounded-3">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th style="width: 140px;">SL nhập</th>
                                            <th class="text-end" style="width: 120px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="importItemsTbody">
                                        <tr id="importEmptyRow">
                                            <td colspan="3" class="text-center text-muted small py-3">
                                                Chưa có sản phẩm nào trong phiếu nhập.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="col-12">
                            <label class="form-label fw-600 small text-uppercase text-muted">Ghi chú (Tùy chọn)</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Ví dụ: Nhập hàng từ nhà cung cấp A, lô hàng tháng 5..."></textarea>
                        </div>

                        <!-- Nút hành động -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3" id="btnSubmitImport">
                                <i class="fa-solid fa-download me-2"></i> XÁC NHẬN NHẬP KHO
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lịch sử nhập kho gần đây -->
        <div class="col-lg-5">
            <div class="card entry-card overflow-hidden">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h6 class="fw-bold mb-0">Phiếu nhập gần đây</h6>
                </div>
                <div class="card-body p-0">
                    <div class="recent-entry-list">
                        @forelse ($imports as $imp)
                            <div class="entry-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $imp->product?->name ?? '—' }}</div>
                                        <div class="text-muted extra-small">
                                            {{-- ✅ Note: imported_at là date trong DB, hiển thị thêm created_at để thấy giờ --}}
                                            Ngày: {{ optional($imp->imported_at)->format('d/m/Y') ?? $imp->created_at->format('d/m/Y') }}
                                            - {{ $imp->created_at->format('H:i') }}
                                        </div>
                                        @if ($imp->note)
                                            <div class="text-muted small">{{ $imp->note }}</div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success bg-opacity-10 text-success">+{{ $imp->quantity }} cái</span>
                                        <div class="text-muted small mt-1">
                                            Tồn hiện tại: {{ $imp->product?->stock ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted small">Chưa có lịch sử nhập kho.</div>
                        @endforelse
                    </div>
                    <div class="p-3 text-center border-top">
                        <a href="#" class="text-primary small text-decoration-none fw-bold">Xem tất cả lịch sử nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ JS này điều khiển việc thêm nhiều sản phẩm vào 1 phiếu nhập
            var productSelect = document.getElementById('importProductSelect');
            var qtyInput = document.getElementById('importQuantityInput');
            var btnAdd = document.getElementById('btnAddImportItem');
            var tbody = document.getElementById('importItemsTbody');
            var emptyRow = document.getElementById('importEmptyRow');
            var form = document.getElementById('stockEntryForm');

            if (!productSelect || !qtyInput || !btnAdd || !tbody || !emptyRow || !form) {
                return; // nếu thiếu phần tử nào thì bỏ qua để tránh lỗi JS
            }

            var items = []; // mảng các dòng nhập hiện tại

            function renderItems() {
                tbody.innerHTML = '';
                if (!items.length) {
                    tbody.appendChild(emptyRow);
                    return;
                }

                items.forEach(function(it, idx) {
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td>' +
                        '<div class="fw-semibold">' + it.name + '</div>' +
                        '<div class="text-muted small">Tồn hiện tại: ' + it.stock + '</div>' +
                        '</td>' +
                        '<td>' +
                        '<input type="number" class="form-control form-control-sm" min="1" value="' + it.quantity + '" data-idx="' + idx + '">' +
                        '</td>' +
                        '<td class="text-end">' +
                        '<button type="button" class="btn btn-sm btn-outline-danger" data-remove="' + idx + '">Xóa</button>' +
                        '</td>';
                    tbody.appendChild(tr);
                });
            }

            function syncHiddenInputs() {
                // xóa các input ẩn cũ
                var oldHidden = form.querySelectorAll('input[name^="items["]');
                for (var i = 0; i < oldHidden.length; i++) {
                    oldHidden[i].parentNode.removeChild(oldHidden[i]);
                }

                // tạo lại input ẩn theo mảng items
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

                if (!productId) {
                    alert('Vui lòng chọn sản phẩm.');
                    return;
                }
                if (quantity < 1) {
                    alert('Số lượng nhập phải >= 1.');
                    return;
                }

                var opt = productSelect.options[productSelect.selectedIndex];
                var name = opt.textContent || opt.innerText;
                var stock = opt.getAttribute('data-stock') || '0';

                // nếu đã có sản phẩm này trong phiếu thì cộng dồn số lượng
                var existing = null;
                for (var i = 0; i < items.length; i++) {
                    if (String(items[i].productId) === String(productId)) {
                        existing = items[i];
                        break;
                    }
                }
                if (existing) {
                    existing.quantity += quantity;
                } else {
                    items.push({
                        productId: productId,
                        name: name,
                        stock: stock,
                        quantity: quantity
                    });
                }

                qtyInput.value = '';
                renderItems();
                syncHiddenInputs();
            });

            tbody.addEventListener('input', function(e) {
                var input = e.target;
                if (!input || input.getAttribute('data-idx') === null) {
                    return;
                }
                var idx = Number(input.getAttribute('data-idx'));
                var v = Number(input.value || 0);
                if (idx >= 0 && idx < items.length) {
                    items[idx].quantity = v > 0 ? v : 1;
                    syncHiddenInputs();
                }
            });

            tbody.addEventListener('click', function(e) {
                var btn = e.target.closest ? e.target.closest('button') : null;
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
                // ✅ Nếu chưa có dòng nào nhưng người dùng đã chọn 1 sản phẩm + số lượng thì tự thêm giúp
                if (!items.length) {
                    var productId = productSelect.value;
                    var quantity = Number(qtyInput.value || 0);
                    if (productId && quantity > 0) {
                        var opt = productSelect.options[productSelect.selectedIndex];
                        var name = opt.textContent || opt.innerText;
                        var stock = opt.getAttribute('data-stock') || '0';
                        items.push({
                            productId: productId,
                            name: name,
                            stock: stock,
                            quantity: quantity
                        });
                        renderItems();
                        syncHiddenInputs();
                    }
                }

                // ✅ Validate lần cuối: nếu vẫn không có dòng nào thì chặn submit
                if (!items.length) {
                    e.preventDefault();
                    alert('Vui lòng thêm ít nhất 1 sản phẩm vào phiếu nhập.');
                }
            });

            renderItems();
        });
    </script>
@endpush
@endsection
