@extends('admin.layout')

@section('title', 'Quản lý sản phẩm điện gia dụng')

@section('content')
    <div class="product-wrapper py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Danh sách sản phẩm</h4>
                <p class="text-muted small mb-0">Quản lý thông tin – giá bán – bảo hành</p>
            </div>
            <button class="btn btn-primary rounded-3 px-4 shadow-sm">
                <i class="fa-solid fa-plus me-2"></i>Thêm sản phẩm
            </button>
        </div>

        <!-- Bộ lọc -->
        <div class="card filter-card mb-4 p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Loại sản phẩm</label>
                    <select class="form-select border-light">
                        <option>Tất cả danh mục</option>
                        <option>Nồi cơm điện</option>
                        <option>Ấm siêu tốc</option>
                        <option>Vợt muỗi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Hãng sản xuất</label>
                    <select class="form-select border-light">
                        <option>Tất cả hãng</option>
                        <option>Sharp</option>
                        <option>Sunhouse</option>
                        <option>Comet</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Tìm kiếm nhanh</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 border-light text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 border-light"
                            placeholder="Nhập tên sản phẩm...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100 border-2 fw-bold">
                        Lọc dữ liệu
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="product-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá bán lẻ</th>
                            <th>Bảo hành</th>
                            <th>Trạng thái kho</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- Item 1: Nồi cơm -->
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="product-thumb me-3">
                                        <i class="fa-solid fa-bowl-rice"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">Nồi cơm điện cao tần 1.8L</div>
                                        <div class="text-muted small">Hãng: Sharp | Model: KS-IH191</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-secondary">Nồi cơm điện</span></td>
                            <td><span class="fw-bold text-primary">1.200.000đ</span></td>
                            <td>12 tháng</td>
                            <td>
                                <span class="badge bg-success-subtle text-success fw-semibold">12 cái</span>
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <button class="btn btn-sm btn-outline-primary me-1 px-3">Sửa</button>
                                <button class="btn btn-sm btn-light border text-muted">Ngừng bán</button>
                            </td>
                        </tr>

                        <!-- Item 2: Ấm siêu tốc -->
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="product-thumb me-3">
                                        <i class="fa-solid fa-bolt"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">Ấm siêu tốc Inox 2L</div>
                                        <div class="text-muted small">Hãng: Sunhouse | SHD1182</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-secondary">Ấm siêu tốc</span></td>
                            <td><span class="fw-bold text-primary">350.000đ</span></td>
                            <td>6 tháng</td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning fw-semibold">2 cái</span>
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <button class="btn btn-sm btn-outline-primary me-1 px-3">Sửa</button>
                                <button class="btn btn-sm btn-light border text-muted">Ngừng bán</button>
                            </td>
                        </tr>

                        <!-- Item 3: Sản phẩm hết hàng -->
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="product-thumb me-3">
                                        <i class="fa-solid fa-bug-slash"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">Vợt muỗi Comet</div>
                                        <div class="text-muted small">Hãng: Comet | CP123</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-secondary">Vợt muỗi</span></td>
                            <td><span class="fw-bold text-primary">120.000đ</span></td>
                            <td>3 tháng</td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger fw-semibold">Hết hàng</span>
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <button class="btn btn-sm btn-outline-primary me-1 px-3">Sửa</button>
                                <button class="btn btn-sm btn-light border text-muted">Ngừng bán</button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination (Phân trang giả lập) -->
            <div class="p-4 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">Hiển thị 1 - 3 trên tổng số 45 sản phẩm</span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
