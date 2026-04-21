@extends('admin.layout')

@section('title', 'Lập hóa đơn bán hàng')

@section('content')
    <div class="sales-container py-4">
        <div class="mb-4">
            <h4 class="fw-bold">Bán hàng tại quầy</h4>
            <p class="text-muted small">Tạo đơn hàng nhanh cho khách gia dụng</p>
        </div>
        
        <form action="{{ route('admin.sale.store') }}" method="POST" id="saleForm">
            @csrf
            <div class="row g-4">
                <!-- Cấu hình sản phẩm -->
                <div class="col-lg-8">
                    <div class="card product-select-card p-4 shadow-sm border-0 rounded-4 bg-white">
                        <h6 class="fw-bold mb-4 text-primary font-luxury"><i class="fa-solid fa-cart-plus me-2"></i>Thông tin sản phẩm</h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom fw-bold small text-uppercase">Loại sản phẩm</label>
                                <select id="categorySelect" class="form-select border-light-subtle shadow-sm rounded-3">
                                    <option value="" selected>-- Chọn loại hàng --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom fw-bold small text-uppercase">Sản phẩm (Hãng)</label>
                                <select name="product_id" id="productSelect" class="form-select border-light-subtle shadow-sm rounded-3">
                                    <option value="">-- Chọn sản phẩm --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-category="{{ $product->category_id }}" data-price="{{ $product->price }}">
                                            {{ $product->name }} ({{ number_format($product->price) }}đ)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom fw-bold small text-uppercase">Số lượng</label>
                                <input type="number" id="quantityInput" name="quantity" class="form-control rounded-3" value="1" min="1">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom fw-bold small text-uppercase">Giá bán thực tế (đ)</label>
                                <input type="text" id="priceInput" name="price" class="form-control fw-bold text-primary rounded-3 money-input">
                                <div class="form-text small">Mặc định từ hệ thống: <span id="systemPriceText" class="fw-bold">0đ</span></div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-outline-primary fw-bold w-100 rounded-pill py-2" id="btnAddSaleItem">
                                <i class="fa-solid fa-plus me-2"></i>Thêm vào hóa đơn
                            </button>
                        </div>

                        <div class="mt-4">
                            <div class="table-responsive border rounded-4 overflow-hidden">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="small text-uppercase">
                                            <th class="ps-3 py-3">Sản phẩm</th>
                                            <th style="width: 100px;">SL</th>
                                            <th>Đơn giá</th>
                                            <th>Thành tiền</th>
                                            <th class="text-end pe-3">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody id="saleItemsTbody">
                                        <tr id="saleEmptyRow">
                                            <td colspan="5" class="text-center text-muted small py-4">Chưa có sản phẩm nào.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr class="my-5 text-light-subtle">

                        <!-- Thông tin khách hàng -->
                        <h6 class="fw-bold mb-4 text-primary font-luxury"><i class="fa-solid fa-user-tag me-2"></i>Thông tin khách hàng</h6>

                        <div class="mb-4">
                            <div class="form-check form-check-inline me-4">
                                <input class="form-check-input" type="radio" name="customer_type" value="guest" id="customerTypeGuest" checked>
                                <label class="form-check-label fw-medium" for="customerTypeGuest">Khách vãng lai</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="customer_type" value="customer" id="customerTypeCustomer">
                                <label class="form-check-label fw-medium" for="customerTypeCustomer">Khách nợ cũ / Khách quen</label>
                            </div>
                        </div>

                        <div id="oldCustomerSection" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom fw-bold small text-uppercase">Tìm khách hàng</label>
                                    <div class="input-group shadow-sm rounded-3">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                                        <input name="phone" id="customerPhone" type="text" class="form-control border-start-0 border-end-0 ps-0" placeholder="Số điện thoại...">
                                        <button type="button" class="btn btn-primary px-3" id="btnFindCustomer" title="Tìm nhanh bằng SĐT">
                                            Tìm
                                        </button>
                                        <button type="button" class="btn btn-light border-start px-3" data-bs-toggle="modal" data-bs-target="#modalSearchDebtor" title="Mở danh sách khách nợ">
                                            <i class="fa-solid fa-address-book text-primary"></i>
                                        </button>

                                    </div>
                                    <div class="form-text extra-small text-primary fst-italic">Bấm nút danh bạ để tìm nhanh theo Tên hoặc SĐT.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom fw-bold small text-uppercase">Tên khách hàng</label>
                                    <input name="customer_name" id="customerName" type="text" class="form-control rounded-3" placeholder="Tên khách hàng...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom fw-bold small text-uppercase">Địa chỉ</label>
                                    <input name="customer_address" id="customerAddress" type="text" class="form-control rounded-3" placeholder="Địa chỉ...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom fw-bold small text-uppercase">Người thân liên hệ</label>
                                    <input name="customer_relative_name" id="customerRelativeName" type="text" class="form-control rounded-3" placeholder="Tên người thân...">
                                </div>
                            </div>

                            <div id="currentDebtAlert" class="alert alert-warning mt-4 border-0 rounded-4 shadow-sm" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-triangle-exclamation fa-2x me-3 opacity-75"></i>
                                    <div>
                                        <div class="fw-bold">Khách hàng đang còn nợ: <span id="currentDebtAmount" class="text-danger fs-5">0</span>đ</div>
                                        <div class="small">Hóa đơn này sẽ được cộng dồn vào dư nợ cũ nếu không trả hết.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="customer-debt-info mt-4 p-3 bg-light rounded-4 border">
                                <label class="form-label-custom fw-bold small text-uppercase mb-3">Thanh toán & Ghi nợ hóa đơn này</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1 fw-bold">Khách trả trước (VND)</div>
                                        <input name="paid_amount" id="paidAmountInput" type="text" class="form-control form-control-lg fw-bold text-success rounded-3 border-0 shadow-sm money-input" value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1 fw-bold">Số tiền ghi nợ mới (VND)</div>
                                        <input type="text" id="debtAmountInput" class="form-control form-control-lg text-danger fw-bold rounded-3 border-0 bg-white" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <div class="col-lg-4">
                    <div class="checkout-summary card p-4 shadow-lg border-0 rounded-4 bg-white sticky-top" style="top: 100px; z-index: 10;">
                        <h5 class="fw-bold mb-4 text-dark font-luxury">Hóa đơn bán hàng</h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tổng tiền hàng:</span>
                            <span id="subTotalText" class="fw-bold">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-success">
                            <span class="small">Khuyến mãi/Chiết khấu:</span>
                            <span class="fw-bold">-0đ</span>
                        </div>
                        <hr class="my-4">
                        <div class="mb-5 text-center">
                            <span class="text-muted d-block small mb-1">TỔNG CỘNG THANH TOÁN</span>
                            <span id="totalText" class="fw-bold fs-2 text-primary">0đ</span>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-sm">
                                <i class="fa-solid fa-print me-2"></i>HOÀN TẤT & LƯU TRỮ
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light rounded-pill py-2 text-muted">Hủy hóa đơn</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lịch sử giao dịch gần đây -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
                        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Giao dịch vừa thực hiện</h6>
                            <a href="{{ route('admin.history') }}" class="btn btn-sm btn-link text-decoration-none extra-small fw-bold">Xem tất cả lịch sử</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="bg-light">
                                    <tr class="extra-small text-uppercase fw-bold text-muted">
                                        <th class="ps-4 py-3">Mã đơn</th>
                                        <th>Sản phẩm</th>
                                        <th>Khách hàng</th>
                                        <th>Tổng cộng</th>
                                        <th>Trạng thái</th>
                                        <th class="text-end pe-4">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sales->take(10) as $sale)
                                        @php 
                                            // Chuẩn bị dữ liệu cho Modal (giống trang lịch sử)
                                            $saleData = $sale->toArray();
                                            $saleData['formatted_date'] = $sale->sold_at->format('d/m/Y H:i');
                                            $saleData['items'] = $sale->items->load('product');
                                            $saleData['customer'] = $sale->customer ? $sale->customer->load('debtBalance') : null;
                                            $saleData['total'] = $sale->grand_total;
                                            $saleData['debt'] = $sale->debt_amount;
                                            // Load thêm lịch sử trả nợ cho hoá đơn này (qua relation đã định nghĩa ở model)
                                            $saleData['transactions'] = $sale->debtTransactions()->latest()->get()->toArray();
                                        @endphp
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">#{{ $sale->invoice_code }}</td>
                                            <td>
                                                @if($sale->items->count() === 1)
                                                    <div class="fw-medium">{{ $sale->items->first()->product->name }}</div>
                                                    <div class="extra-small text-muted">SL: {{ $sale->items->first()->quantity }}</div>
                                                @else
                                                    <div class="fw-medium">{{ $sale->items->count() }} mặt hàng</div>
                                                    <div class="extra-small text-muted">
                                                        {{ $sale->items->take(2)->map(function($i){ return $i->product->name; })->join(', ') }}...
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold small">{{ $sale->customer?->name ?? 'Khách lẻ' }}</div>
                                                @if($sale->customer?->phone) <div class="extra-small text-muted">{{ $sale->customer->phone }}</div> @endif
                                            </td>
                                            <td class="fw-bold">{{ number_format($sale->grand_total) }}đ</td>
                                            <td>
                                                @if($sale->debt_amount > 0)
                                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2">Nợ: {{ number_format($sale->debt_amount) }}đ</span>
                                                @else
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Đã trả đủ</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" 
                                                    onclick="openSaleDetailModal({{ json_encode($saleData) }})">
                                                    Chi tiết
                                                </button>
                                            </td>
                                        </tr>
                                    @empty

                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted small">Chưa có giao dịch nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('admin.components.sale-history-detail-modal')

    @push('scripts')
    <script>
        window.saleProducts = @json($products);
        window.baseUrl = '{{ url('/') }}';
    </script>
    @vite('resources/js/sale.js')
    @endpush

    @include('admin.components.sale-debtor-search-modal')
@endsection

<style>
.sales-container { padding-top: 60px; }
.font-luxury { font-family: 'Playfair Display', serif; letter-spacing: 0.5px; }
.extra-small { font-size: 0.7rem; }
.form-label-custom { font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem; display: block; }
</style>