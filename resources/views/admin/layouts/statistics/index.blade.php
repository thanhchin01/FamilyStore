@extends('admin.layout')

@section('title', 'Báo cáo & Thống kê doanh thu')

@section('content')
<div class="statistics-wrapper py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Báo cáo doanh thu</h4>
            <p class="text-muted small mb-0">Theo dõi hiệu quả kinh doanh theo thời gian</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-white border rounded-3 small fw-bold text-muted">
                <i class="fa-solid fa-calendar-days me-2"></i>Chọn khoảng ngày
            </button>
            <button class="btn btn-primary rounded-3 px-4">
                <i class="fa-solid fa-download me-2"></i>Xuất Excel
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div class="stat-value">45.2M</div>
                <div class="stat-label">Doanh thu hôm nay</div>
                <div class="stat-trend text-success">
                    <i class="fa-solid fa-arrow-up me-1"></i>+12% so với hôm qua
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="stat-value">128</div>
                <div class="stat-label">Đơn hàng mới</div>
                <div class="stat-trend text-success">
                    <i class="fa-solid fa-arrow-up me-1"></i>+5% so với tháng trước
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div class="stat-value">12</div>
                <div class="stat-label">Khách hàng mới</div>
                <div class="stat-trend text-danger">
                    <i class="fa-solid fa-arrow-down me-1"></i>-2% mục tiêu
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div class="stat-value">8.4M</div>
                <div class="stat-label">Tiền nợ mới</div>
                <div class="stat-trend text-muted">
                    Phát sinh trong tháng
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0">Biểu đồ doanh thu (VNĐ)</h6>
                    <select class="form-select form-select-sm w-auto border-0 bg-light">
                        <option>7 ngày gần nhất</option>
                        <option>30 ngày gần nhất</option>
                        <option>Theo tháng (Năm 2024)</option>
                    </select>
                </div>
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-card h-100">
                <h6 class="fw-bold mb-4">Tỷ trọng ngành hàng</h6>
                <canvas id="categoryChart" height="300"></canvas>
                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span><i class="fa-solid fa-circle text-primary me-2"></i>Nồi cơm điện</span>
                        <span class="fw-bold">45%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span><i class="fa-solid fa-circle text-success me-2"></i>Ấm siêu tốc</span>
                        <span class="fw-bold">30%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span><i class="fa-solid fa-circle text-warning me-2"></i>Đồ gia dụng khác</span>
                        <span class="fw-bold">25%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="revenue-table-card">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Chi tiết doanh thu theo thời gian</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Số đơn hàng</th>
                        <th>Doanh thu lẻ</th>
                        <th>Khách nợ thêm</th>
                        <th>Thực thu</th>
                        <th class="text-end">Tăng trưởng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-medium">Hôm nay (05/02)</td>
                        <td>24</td>
                        <td class="fw-bold text-dark">15,200,000đ</td>
                        <td class="text-danger">1,200,000đ</td>
                        <td class="text-success fw-bold">14,000,000đ</td>
                        <td class="text-end text-success"><i class="fa-solid fa-caret-up me-1"></i>4.5%</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Hôm qua (04/02)</td>
                        <td>18</td>
                        <td class="fw-bold text-dark">12,800,000đ</td>
                        <td class="text-danger">0đ</td>
                        <td class="text-success fw-bold">12,800,000đ</td>
                        <td class="text-end text-danger"><i class="fa-solid fa-caret-down me-1"></i>1.2%</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Tháng 01/2024</td>
                        <td>450</td>
                        <td class="fw-bold text-dark">215,000,000đ</td>
                        <td class="text-danger">15,400,000đ</td>
                        <td class="text-success fw-bold">199,600,000đ</td>
                        <td class="text-end text-success"><i class="fa-solid fa-caret-up me-1"></i>12.0%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="[https://cdn.jsdelivr.net/npm/chart.js](https://cdn.jsdelivr.net/npm/chart.js)"></script>
<script>
    // Biểu đồ doanh thu
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: ['01/02', '02/02', '03/02', '04/02', '05/02', '06/02', '07/02'],
            datasets: [{
                label: 'Doanh thu (triệu VNĐ)',
                data: [12, 19, 15, 12, 22, 18, 25],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                x: { grid: { display: false } }
            }
        }
    });

    // Biểu đồ hình tròn tỷ trọng
    const ctxCat = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: ['Nồi cơm', 'Ấm siêu tốc', 'Khác'],
            datasets: [{
                data: [45, 30, 25],
                backgroundColor: ['#4f46e5', '#10b981', '#f59e0b'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection
