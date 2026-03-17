@extends('admin.layout')

@section('title', 'Lập hóa đơn bán hàng')

@section('content')
    <div class="sales-container py-4">
        <div class="mb-4">
            <h4 class="fw-bold">Bán hàng tại quầy</h4>
            <p class="text-muted small">Tạo đơn hàng nhanh cho khách gia dụng</p>
        </div>
        {{-- ✅ Updated: bán nhiều sản phẩm trong 1 hóa đơn (items[]) --}}
        <form action="{{ route('admin.sale.store') }}" method="POST" id="saleForm">
            @csrf
            <div class="row g-4">
                <!-- Cấu hình sản phẩm -->
                <div class="col-lg-8">
                    <div class="card product-select-card p-4">
                        <h6 class="fw-bold mb-4"><i class="fa-solid fa-cart-plus me-2 text-primary"></i>Thông tin sản phẩm
                        </h6>

                        <div class="row g-3">
                            <!-- Chọn loại sản phẩm -->
                            <div class="col-md-6">
                                <label class="form-label-custom">Loại sản phẩm</label>
                                <select id="categorySelect" class="form-select border-light-subtle shadow-sm">
                                    <option selected>-- Chọn loại hàng --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Chọn sản phẩm cụ thể -->
                            <div class="col-md-6">
                                <label class="form-label-custom">Sản phẩm (Hãng)</label>
                                {{-- <select name="product_id" id="productSelect" class="form-select border-light-subtle shadow-sm" required>
                                    <option selected>-- Chọn sản phẩm --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                            {{ $product->name }} ({{ number_format($product->price) }}đ)
                                        </option>
                                    @endforeach
                                </select> --}}
                                {{-- ✅ Updated: vẫn giữ name để backend fallback được khi chỉ bán 1 sản phẩm --}}
                                <select name="product_id" id="productSelect"
                                    class="form-select border-light-subtle shadow-sm">
                                    <option value="">-- Chọn sản phẩm --</option>

                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-category="{{ $product->category_id }}"
                                            data-price="{{ $product->price }}">
                                            {{ $product->name }} ({{ number_format($product->price) }}đ)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Số lượng và Giá bán -->
                            <div class="col-md-6">
                                <label class="form-label-custom">Số lượng</label>
                                {{-- <input type="number" class="form-control" value="1" min="1"> --}}
                                {{-- ✅ Updated: vẫn giữ name để backend fallback được khi chỉ bán 1 sản phẩm --}}
                                <input type="number" id="quantityInput" name="quantity" class="form-control" value="1"
                                    min="1">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">Giá bán thực tế (đ)</label>
                                {{-- <input type="text" class="form-control fw-bold text-primary" value="1,200,000">
                                --}}
                                {{-- ✅ Updated: vẫn giữ name để backend fallback được khi chỉ bán 1 sản phẩm --}}
                                <input type="number" id="priceInput" name="price"
                                    class="form-control fw-bold text-primary">
                                <div class="form-text">Mặc định từ hệ thống: <span id="systemPriceText">0đ</span></div>
                            </div>
                        </div>

                        {{-- ✅ Added: nút thêm sản phẩm + danh sách nhiều dòng trong hóa đơn --}}
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary fw-bold w-100" id="btnAddSaleItem">
                                <i class="fa-solid fa-plus me-2"></i>Thêm sản phẩm vào hóa đơn
                            </button>
                        </div>

                        <div class="mt-3">
                            <div class="table-responsive border rounded-3">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th style="width: 110px;">SL</th>
                                            <th style="width: 160px;">Đơn giá</th>
                                            <th style="width: 160px;">Thành tiền</th>
                                            <th class="text-end" style="width: 120px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="saleItemsTbody">
                                        <tr id="saleEmptyRow">
                                            <td colspan="5" class="text-center text-muted small py-3">
                                                Chưa có sản phẩm nào trong hóa đơn.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr class="my-4 text-light-subtle">

                        <!-- Thông tin khách hàng -->
                        <h6 class="fw-bold mb-4"><i class="fa-solid fa-user-tag me-2 text-primary"></i>Thông tin khách hàng
                        </h6>

                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="customer_type" value="guest"
                                    id="customerTypeGuest" checked>
                                <label class="form-check-label" for="customerTypeGuest">Khách vãng lai</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="customer_type" value="customer"
                                    id="customerTypeCustomer">
                                <label class="form-check-label" for="customerTypeCustomer">Khách quen (Ghi nợ/SĐT)</label>
                            </div>
                        </div>

                        <!-- Section hiển thị khi là khách quen -->
                        <div id="oldCustomerSection" class="mt-3" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Số điện thoại khách</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fa-solid fa-phone"></i></span>
                                        <input name="phone" id="customerPhone" type="text" class="form-control"
                                            placeholder="09xx xxx xxx">
                                        <button type="button" class="btn btn-outline-secondary"
                                            id="btnFindCustomer">Tìm</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Tên khách hàng</label>
                                    <input name="customer_name" id="customerName" type="text" class="form-control"
                                        placeholder="Nhập tên nếu là khách mới (Bắt buộc)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Địa chỉ</label>
                                    <input name="customer_address" id="customerAddress" type="text"
                                        class="form-control" placeholder="Địa chỉ (tùy chọn)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Tên người thân</label>
                                    <input name="customer_relative_name" id="customerRelativeName" type="text"
                                        class="form-control" placeholder="Tên người thân (tùy chọn)">
                                </div>
                            </div>

                            {{-- ✅ Hiển thị khi tìm thấy khách có nợ: tổng nợ hiện tại + cộng dồn --}}
                            <div id="currentDebtAlert" class="alert alert-warning mt-3 mb-0 py-2 small"
                                style="display: none;">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                <strong>Tổng nợ hiện tại:</strong> <span id="currentDebtAmount">0</span>đ.
                                Nợ mới (nếu có) sẽ <strong>cộng dồn</strong> vào.
                                <div class="mt-1">
                                    <strong>Nợ sau hóa đơn này (ước tính):</strong> <span id="afterDebtAmount">0</span>đ
                                </div>
                            </div>

                            <div class="customer-debt-info mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label-custom mb-0">Thanh toán & Ghi nợ</label>
                                    <span class="badge bg-warning text-dark">Áp dụng cho khách quen</span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Khách trả trước (đ)</div>
                                        <input name="paid_amount" id="paidAmountInput" type="number"
                                            class="form-control form-control-sm" value="0" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Còn nợ lại (đ)</div>
                                        <input type="text" id="debtAmountInput"
                                            class="form-control form-control-sm text-danger fw-bold" value="0"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tóm tắt đơn hàng -->
                <div class="col-lg-4">
                    <div class="checkout-summary">
                        <h5 class="fw-bold mb-4">Tổng cộng</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            {{-- <span>1.200.000đ</span> --}}
                            <span id="subTotalText">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Giảm giá:</span>
                            <span class="text-success">-0đ</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Thành tiền:</span>
                            <span id="totalText" class="fw-bold fs-5 text-primary">0đ</span>

                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-3 fw-bold rounded-3">
                                <i class="fa-solid fa-check-double me-2"></i> HOÀN TẤT BÁN HÀNG
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light py-2 text-muted">
                                Hủy đơn
                            </a>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded-3">
                            <div class="small fw-bold mb-1"><i class="fa-solid fa-circle-info me-1"></i> Lưu ý:</div>
                            <div class="small text-muted">Hóa đơn sẽ tự động lưu vào báo cáo doanh thu ngày hôm nay.</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            // Truyền biến từ PHP/Blade sang Javascript để dùng bên trong file js rời
            window.saleProducts = @json($products);
            window.baseUrl = '{{ url('/') }}';
        </script>
        {{-- Load logic xử lý bán hàng từ file js bên ngoài --}}
        @vite('resources/js/sale.js')
    @endpush
    <div class="sales-history-container py-4">

        <div class="mb-4">
            <h4 class="fw-bold">Lịch sử bán hàng</h4>
            <p class="text-muted small">Theo dõi các hóa đơn đã bán</p>
        </div>

        <!-- Bộ lọc -->
        <div class="card filter-card mb-4 p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label-custom">Từ ngày</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label-custom">Đến ngày</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label-custom">Trạng thái</label>
                    <select class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="paid">Đã trả đủ</option>
                        <option value="debt">Còn nợ</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label-custom">Loại khách</label>
                    <select class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="guest">Khách lẻ</option>
                        <option value="customer">Khách quen</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Bảng lịch sử -->
        <div class="card history-table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Ngày bán</th>
                            <th>Sản phẩm</th>
                            <th>Khách hàng</th>
                            <th>SL</th>
                            <th>Thành tiền</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ✅ Updated: hiển thị theo hóa đơn (gom invoice_code) --}}
                        @php
                            $invoiceGroups = $sales->groupBy(fn($s) => $s->invoice_code ?? 'single-' . $s->id);
                        @endphp

                        @foreach ($invoiceGroups as $code => $rows)
                            @php
                                $first = $rows->first();
                                $invoiceTotal = $rows->sum(fn($r) => (int) $r->price * (int) $r->quantity);
                                $paid = (int) ($first->paid_amount ?? 0);
                                $debt = max($invoiceTotal - $paid, 0);
                            @endphp
                            <tr>
                                <td>{{ $first->id }}</td>
                                <td>{{ $first->created_at->format('d/m/Y') }}</td>
                                <td>{{ $rows->count() }} SP</td>
                                {{-- <td>
                                    <span class="fw-semibold">Nguyễn Văn A</span><br>
                                    <small class="text-muted">0909xxx999</small>
                                </td> --}}
                                <td>
                                    <strong>{{ $first->customer?->name ?? 'Khách lẻ' }}</strong><br>
                                    <small>{{ $first->customer?->phone }}</small>
                                </td>
                                <td>{{ $rows->sum('quantity') }}</td>
                                <td class="fw-bold">
                                    {{ number_format($invoiceTotal) }}đ
                                </td>
                                <td>
                                    @if ($debt > 0)
                                        <span class="badge bg-warning text-dark">Còn nợ</span>
                                    @else
                                        <span class="badge bg-success">Đã trả đủ</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#saleDetailModal-{{ $first->id }}">
                                        Xem chi tiết
                                    </button>
                                </td>
                            </tr>
                            <!-- Modal xem chi tiết hoá đơn -->
                            <div class="modal fade" id="saleDetailModal-{{ $first->id }}" tabindex="-1"
                                aria-labelledby="saleDetailModalLabel-{{ $first->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold"
                                                id="saleDetailModalLabel-{{ $first->id }}">
                                                Hóa đơn #{{ $first->id }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <div class="small text-muted">Ngày bán</div>
                                                <div>{{ $first->created_at->format('d/m/Y H:i') }}</div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="small text-muted">Sản phẩm</div>
                                                {{-- ✅ Updated: liệt kê nhiều dòng sản phẩm trong hóa đơn --}}
                                                <ul class="mb-0">
                                                    @foreach ($rows as $r)
                                                        <li class="small">
                                                            {{ $r->product?->name ?? '—' }} —
                                                            SL: {{ $r->quantity }} —
                                                            Đơn giá: {{ number_format($r->price) }}đ
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <div class="mb-3">
                                                <div class="small text-muted">Thông tin khách hàng</div>
                                                <div>
                                                    <strong>{{ $first->customer?->name ?? 'Khách lẻ' }}</strong><br>
                                                    @if ($first->customer?->phone)
                                                        <span class="text-primary fw-semibold">SĐT:
                                                            {{ $first->customer->phone }}</span><br>
                                                    @endif
                                                    @if ($first->customer?->address)
                                                        <small class="text-muted">Địa chỉ:
                                                            {{ $first->customer->address }}</small><br>
                                                    @endif
                                                    @if ($first->customer?->relative_name)
                                                        <small class="text-muted">Người thân:
                                                            {{ $first->customer->relative_name }}</small><br>
                                                    @endif
                                                    @if ($first->customer?->debt && $first->customer->debt->total_debt > 0)
                                                        <small class="text-danger fw-semibold">Tổng nợ hiện tại:
                                                            {{ number_format($first->customer->debt->total_debt) }}đ</small>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="small text-muted">Thanh toán</div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Tổng tiền hóa đơn:</span>
                                                    <span class="fw-semibold">{{ number_format($invoiceTotal) }}đ</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Khách đã trả:</span>
                                                    <span>{{ number_format($paid) }}đ</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Còn nợ lại:</span>
                                                    <span
                                                        class="{{ $debt > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                                        {{ number_format($debt) }}đ
                                                    </span>
                                                </div>
                                                <div class="mt-2">
                                                    @if ($debt > 0)
                                                        <span class="badge bg-warning text-dark">Đang còn nợ</span>
                                                    @else
                                                        <span class="badge bg-success">Đã thanh toán đủ</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($first->customer && $first->customer->debt && $first->customer->debt->total_debt > 0)
                                                <hr>
                                                <div class="mb-0">
                                                    <div class="small fw-bold text-muted mb-2">Trả nợ</div>
                                                    <form action="{{ route('admin.debt.pay') }}" method="POST"
                                                        class="d-flex gap-2 align-items-end flex-wrap">
                                                        @csrf
                                                        <input type="hidden" name="customer_id"
                                                            value="{{ $first->customer->id }}">
                                                        <div class="flex-grow-1">
                                                            <label class="form-label small mb-0">Số tiền trả (đ)</label>
                                                            <input type="number" name="amount"
                                                                class="form-control form-control-sm" min="1"
                                                                max="{{ $first->customer->debt->total_debt }}"
                                                                placeholder="Tối đa {{ number_format($first->customer->debt->total_debt) }}đ"
                                                                required>
                                                        </div>
                                                        <div>
                                                            <label class="form-label small mb-0">&nbsp;</label>
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fa-solid fa-money-bill-transfer me-1"></i> Ghi
                                                                nhận trả nợ
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light border"
                                                data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
