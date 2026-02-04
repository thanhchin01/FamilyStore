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

                <form method="GET" action="{{ route('admin.stock') }}">
                    <div class="row g-3">
                        <!-- Chọn Sản Phẩm -->
                        <div class="col-12">
                            <label class="form-label fw-600 small text-uppercase text-muted">Sản phẩm cần nhập</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <select class="form-select form-control-custom">
                                    <option selected disabled>Tìm hoặc chọn sản phẩm...</option>
                                    <optgroup label="Nồi cơm điện">
                                        <option>Nồi cơm Sharp 1.8L - (Hiện có: 12)</option>
                                        <option>Nồi cơm Cuckoo 1.5L - (Hiện có: 5)</option>
                                    </optgroup>
                                    <optgroup label="Ấm siêu tốc">
                                        <option>Ấm Sunhouse Inox 2L - (Hiện có: 2)</option>
                                        <option>Ấm Philips 1.7L - (Hiện có: 0)</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <!-- Số lượng -->
                        <div class="col-md-6">
                            <label class="form-label fw-600 small text-uppercase text-muted">Số lượng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-icon"><i class="fa-solid fa-layer-group"></i></span>
                                <input type="number" class="form-control form-control-custom" placeholder="Ví dụ: 10" min="1">
                            </div>
                        </div>

                        <!-- Đơn vị (tự động theo sản phẩm) -->
                        <div class="col-md-6">
                            <label class="form-label fw-600 small text-uppercase text-muted">Đơn vị tính</label>
                            <input type="text" class="form-control bg-light" value="Cái" readonly>
                        </div>

                        <!-- Ghi chú -->
                        <div class="col-12">
                            <label class="form-label fw-600 small text-uppercase text-muted">Ghi chú (Tùy chọn)</label>
                            <textarea class="form-control" rows="3" placeholder="Ví dụ: Nhập hàng từ nhà cung cấp A, lô hàng tháng 5..."></textarea>
                        </div>

                        <!-- Nút hành động -->
                        <div class="col-12 mt-4">
                            <button type="button" class="btn btn-primary w-100 py-2 fw-bold rounded-3">
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
                        <!-- Item 1 -->
                        <div class="entry-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold text-dark">Nồi cơm Sharp 1.8L</div>
                                    <div class="text-muted extra-small">Ngày: 20/05/2024 - 09:15</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success">+10 cái</span>
                                    <div class="text-muted small mt-1">Kho: 12 → 22</div>
                                </div>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="entry-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold text-dark">Ấm Sunhouse Inox 2L</div>
                                    <div class="text-muted extra-small">Ngày: 19/05/2024 - 15:30</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success">+5 cái</span>
                                    <div class="text-muted small mt-1">Kho: 2 → 7</div>
                                </div>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="entry-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold text-dark">Vợt muỗi Comet</div>
                                    <div class="text-muted extra-small">Ngày: 18/05/2024 - 10:00</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success">+20 cái</span>
                                    <div class="text-muted small mt-1">Kho: 0 → 20</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 text-center border-top">
                        <a href="#" class="text-primary small text-decoration-none fw-bold">Xem tất cả lịch sử nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
