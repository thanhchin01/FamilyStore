@extends('admin.layout')

@section('title', 'Lập hóa đơn bán hàng')

@section('content')
    <div class="sales-container py-4">
        <div class="mb-4">
            <h4 class="fw-bold">Bán hàng tại quầy</h4>
            <p class="text-muted small">Tạo đơn hàng nhanh cho khách gia dụng</p>
        </div>
        <form action="{{ route('admin.sale.store') }}" method="POST">
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
                                <select name="product_id" id="productSelect"
                                    class="form-select border-light-subtle shadow-sm" required>
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
                                <input type="number" id="quantityInput" name="quantity" class="form-control" value="1"
                                    min="1" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">Giá bán thực tế (đ)</label>
                                {{-- <input type="text" class="form-control fw-bold text-primary" value="1,200,000">
                                --}}
                                <input type="number" name="price" id="priceInput"
                                    class="form-control fw-bold text-primary" required>
                                <div class="form-text">Mặc định từ hệ thống: <span id="systemPriceText">0đ</span></div>
                            </div>
                        </div>

                        <hr class="my-4 text-light-subtle">

                        <!-- Thông tin khách hàng -->
                        <h6 class="fw-bold mb-4"><i class="fa-solid fa-user-tag me-2 text-primary"></i>Thông tin khách hàng
                        </h6>

                        <div class="mb-3">
                            {{-- <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="customerType" id="newCustomer" checked>
                                <label class="form-check-label" for="newCustomer">Khách vãng lai</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="customerType" id="oldCustomer">
                                <label class="form-check-label" for="oldCustomer">Khách quen (Ghi nợ/SĐT)</label>
                            </div> --}}
                            <div id="oldCustomerSection" class="mt-3" style="display:none;"
                                class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="customer_type" value="guest" checked>
                                <label class="form-check-label">Khách vãng lai</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="customer_type" value="customer">
                                <label class="form-check-label">Khách quen (Ghi nợ/SĐT)</label>
                            </div>
                        </div>

                        <!-- Section hiển thị khi là khách quen -->
                        <div id="oldCustomerSection" class="mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Số điện thoại khách</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fa-solid fa-phone"></i></span>
                                        <input name="phone" id="customerPhone" type="text" class="form-control"
                                            placeholder="09xx xxx xxx">
                                        <button class="btn btn-outline-secondary" id="btnFindCustomer">Tìm</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Tên khách hàng</label>
                                    <input name="customer_name" id="customerName" type="text" class="form-control"
                                        placeholder="Nhập tên nếu là khách mới (Bắt buộc)">
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
                                        <input name="paid_amount" id="paidAmountInput" type="text"
                                            class="form-control form-control-sm" value="0">
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
                            {{-- <span class="fw-bold fs-5 text-primary">1.200.000đ</span> --}}
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
        </form>
    </div>
    </div>
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
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                <td>{{ $sale->product->name }}</td>
                                {{-- <td>
                                    <span class="fw-semibold">Nguyễn Văn A</span><br>
                                    <small class="text-muted">0909xxx999</small>
                                </td> --}}
                                <td>
                                    <strong>{{ $sale->customer?->name ?? 'Khách lẻ' }}</strong><br>
                                    <small>{{ $sale->customer?->phone }}</small>
                                </td>
                                <td>{{ $sale->quantity }}</td>
                                <td class="fw-bold">
                                    {{ number_format($sale->total) }}đ
                                </td>
                                <td>
                                    <span class="badge bg-success">Đã trả đủ</span>
                                </td>
                                {{-- <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </td> --}}
                                <td>
                                    @if ($sale->debt > 0)
                                        <span class="badge bg-warning text-dark">Còn nợ</span>
                                    @else
                                        <span class="badge bg-success">Đã trả đủ</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
