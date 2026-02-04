@extends('admin.layout')

@section('title', 'Quản lý kho hàng')

@section('content')
    <div class="inventory-wrapper py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Kho hàng điện gia dụng</h4>
                <p class="text-muted small mb-0">Phân loại: <span class="badge bg-danger">Hết</span> <span
                        class="badge bg-warning text-dark">Sắp hết</span> <span class="badge bg-success">Ổn định</span></p>
            </div>
            <button class="btn btn-primary rounded-3">
                <i class="fa-solid fa-plus me-2"></i>Thêm SP mới
            </button>
        </div>

        <!-- Tối ưu xem từng sản phẩm bằng Tabs -->
        <div class="inventory-tabs mb-4 shadow-sm rounded-3">
            <a href="#" class="tab-item active">Tất cả</a>
            <a href="#" class="tab-item">Nồi cơm điện</a>
            <a href="#" class="tab-item">Ấm siêu tốc</a>
            <a href="#" class="tab-item">Vợt muỗi</a>
            <a href="#" class="tab-item">Điều khiển Tivi/ĐH</a>
        </div>

        <!-- Inventory Table -->
        <div class="inventory-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Hãng / Model</th>
                            <th>Tổng tồn</th>
                            <th>Cảnh báo kho</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sản phẩm: Nồi cơm -->
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                        <i class="fa-solid fa-bowl-rice text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">Nồi cơm điện cao tần 1.8L</div>
                                        <span class="text-muted small">Mã: NC-SHARP-01</span>
                                    </div>
                                </div>
                            </td>
                            <td>Sharp</td>
                            <td><span class="fs-5 fw-bold">12</span> cái</td>
                            <td>
                                <ul class="stock-indicator-list">
                                    <li>
                                        <span class="status-signal signal-success"></span>
                                        <span class="stock-label status-green">Còn hàng</span>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary me-1">Chi tiết</button>
                                <button class="btn btn-sm btn-light border"><i
                                        class="fa-solid fa-pen-to-square"></i></button>
                            </td>
                        </tr>

                        <!-- Sản phẩm: Ấm siêu tốc -->
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                        <i class="fa-solid fa-mug-hot text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">Ấm siêu tốc Inox 2L</div>
                                        <span class="text-muted small">Mã: AST-SUN-20</span>
                                    </div>
                                </div>
                            </td>
                            <td>Sunhouse</td>
                            <td><span class="fs-5 fw-bold">2</span> cái</td>
                            <td>
                                <ul class="stock-indicator-list">
                                    <li>
                                        <span class="status-signal signal-warning"></span>
                                        <span class="stock-label status-yellow">Sắp hết hàng</span>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary me-1">Nhập hàng</button>
                                <button class="btn btn-sm btn-light border"><i
                                        class="fa-solid fa-pen-to-square"></i></button>
                            </td>
                        </tr>

                        <!-- Sản phẩm: Vợt muỗi -->
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                        <i class="fa-solid fa-bolt text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">Vợt muỗi cao cấp Comet</div>
                                        <span class="text-muted small">Mã: VM-CM-05</span>
                                    </div>
                                </div>
                            </td>
                            <td>Comet</td>
                            <td><span class="fs-5 fw-bold">0</span> cái</td>
                            <td>
                                <ul class="stock-indicator-list">
                                    <li>
                                        <span class="status-signal signal-danger"></span>
                                        <span class="stock-label status-red">Hết hàng</span>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-danger me-1">Liên hệ NCC</button>
                                <button class="btn btn-sm btn-light border"><i
                                        class="fa-solid fa-pen-to-square"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
