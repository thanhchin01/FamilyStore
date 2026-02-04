@extends('admin.layout')

@section('title', 'Dashboard Tổng Quan')

@section('content')
<div class="dashboard-wrapper py-4">
    <!-- Header -->
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold">Tổng quan</h3>
            <p class="text-muted small mb-0">Chào mừng trở lại, quản trị viên!</p>
        </div>
        <div class="actions">
            <select class="form-select form-select-sm shadow-sm border-0 px-3">
                <option value="today">Hôm nay</option>
                <option value="month">Tháng này</option>
                <option value="year">Năm nay</option>
            </select>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        <!-- Doanh thu -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div class="trend-indicator up">
                        <i class="fa-solid fa-caret-up"></i> +12.5%
                    </div>
                </div>
                <p class="card-label">Doanh thu hôm nay</p>
                <h3 class="card-value">12.500.000đ</h3>
            </div>
        </div>

        <!-- Hàng đã bán -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-pager"></i>
                    </div>
                    <div class="trend-indicator up">
                        <i class="fa-solid fa-caret-up"></i> +8%
                    </div>
                </div>
                <p class="card-label">Sản phẩm đã bán</p>
                <h3 class="card-value">86</h3>
            </div>
        </div>

        <!-- Khách hàng -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div class="trend-indicator down">
                        <i class="fa-solid fa-caret-down"></i> -2%
                    </div>
                </div>
                <p class="card-label">Khách mua hôm nay</p>
                <h3 class="card-value">24</h3>
            </div>
        </div>

        <!-- Hàng nhập -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-dolly"></i>
                    </div>
                    <span class="badge rounded-pill bg-light text-dark fw-normal">Vừa về</span>
                </div>
                <p class="card-label">Lô hàng nhập mới</p>
                <h3 class="card-value">05</h3>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="card recent-orders-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Giao dịch mới nhất</h5>
            <a href="#" class="btn btn-sm btn-light text-primary fw-bold">Xem tất cả</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Khách hàng</th>
                        <th>Thời gian</th>
                        <th>Giá trị</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('images/admin/avatar-1.jpg') }}" class="rounded-circle me-2" width="90">
                                <span>Nguyễn Văn A</span>
                            </div>
                        </td>
                        <td>10:45 AM</td>
                        <td>2.450.000đ</td>
                        <td><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Thành công</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('images/admin/avatar-1.jpg') }}" class="rounded-circle me-2" width="90">
                                <span>Lê Thị B</span>
                            </div>
                        </td>
                        <td>09:12 AM</td>
                        <td>890.000đ</td>
                        <td><span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Chờ xử lý</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
