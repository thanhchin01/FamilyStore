@extends('admin.layout')

@section('title', 'Báo cáo & Thống kê doanh thu - Gia dụng Khoa Quyên')

@section('content')
<div class="statistics-wrapper py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold font-luxury text-primary">Báo cáo kinh doanh</h4>
            <p class="text-muted small mb-0">Theo dõi doanh thu và tăng trưởng hệ thống</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-white border rounded-pill px-3 small fw-bold text-muted shadow-sm">
                <i class="fa-solid fa-calendar-days me-2"></i>Chọn khoảng ngày
            </button>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fa-solid fa-download me-2"></i>Xuất Excel
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 bg-white p-4 h-100">
                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div class="fs-4 fw-bold text-dark">{{ number_format($stats['today_revenue']) }}đ</div>
                <div class="extra-small text-muted fw-bold text-uppercase mt-1">Doanh thu hôm nay</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 bg-white p-4 h-100">
                <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-shopping-bag"></i>
                </div>
                <div class="fs-4 fw-bold text-dark">{{ number_format($stats['new_orders_month']) }}</div>
                <div class="extra-small text-muted fw-bold text-uppercase mt-1">Hóa đơn trong tháng</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 bg-white p-4 h-100">
                <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="fs-4 fw-bold text-dark">{{ number_format($stats['total_customers']) }}</div>
                <div class="extra-small text-muted fw-bold text-uppercase mt-1">Tổng khách hàng</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 bg-white p-4 h-100">
                <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div class="fs-4 fw-bold text-danger">{{ number_format($stats['new_debt_month']) }}đ</div>
                <div class="extra-small text-muted fw-bold text-uppercase mt-1">Nợ phát sinh tháng này</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 bg-white p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0 text-dark">Xu hướng doanh thu (7 ngày gần nhất)</h6>
                    <span class="badge bg-light text-primary rounded-pill px-3">VNĐ</span>
                </div>
                <div style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 bg-white p-4 h-100">
                <h6 class="fw-bold mb-4 text-dark">Tỷ trọng ngành hàng (Theo số lượng SP)</h6>
                <div style="height: 250px;">
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="mt-4">
                    @foreach($categoriesStats as $idx => $cat)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span><i class="fa-solid fa-circle me-2" style="color: {{ ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'][$idx] ?? '#cbd5e1' }}"></i>{{ $cat->name }}</span>
                            <span class="fw-bold">{{ $cat->products_count }} SP</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Biểu đồ doanh thu
        const labels = {!! json_encode($labels) !!};
        const revenueData = {!! json_encode($revenueData) !!};

        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu',
                    data: revenueData,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0ea5e9',
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        ticks: { callback: value => new Intl.NumberFormat('vi-VN').format(value) + 'đ' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Biểu đồ hình tròn tỷ trọng
        const catLabels = {!! json_encode($categoriesStats->pluck('name')) !!};
        const catData = {!! json_encode($categoriesStats->pluck('products_count')) !!};

        const ctxCat = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderWidth: 0,
                    cutout: '72%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    });
</script>

<style>
.extra-small { font-size: 0.7rem; }
.btn-white { background: white; }
</style>
@endsection
