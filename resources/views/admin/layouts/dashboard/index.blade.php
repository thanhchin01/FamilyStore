@extends('admin.layout')

@section('title', 'Dashboard Tổng Quan')

@section('content')
    <div class="dashboard-wrapper py-4">
        <!-- Header -->
        <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold font-luxury text-primary">Tổng quan kinh doanh</h3>
                <p class="text-muted small mb-0">Báo cáo tình hình cửa hàng thời gian thực</p>
            </div>
            <div class="date-display small fw-bold text-muted bg-white px-3 py-2 rounded-pill shadow-sm">
                <i class="far fa-calendar-alt me-2 text-primary"></i> Hôm nay, {{ date('d/m/Y') }}
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4 mb-5">
            <!-- Tổng doanh thu -->
            <div class="col-md-6 col-xl-3">
                <div class="stat-card p-4 rounded-4 shadow-sm bg-white border-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-coins fs-4"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 small">Theo tích lũy</span>
                    </div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Tổng doanh thu
                    </p>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalRevenue) }}đ</h3>
                </div>
            </div>

            <!-- Sản phẩm đã bán -->
            <div class="col-md-6 col-xl-3">
                <div class="stat-card p-4 rounded-4 shadow-sm bg-white border-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-cart-shopping fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Giao dịch thành
                        công</p>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalSalesCount) }} đơn hàng</h4>
                </div>
            </div>

            <!-- Tổng số sản phẩm -->
            <div class="col-md-6 col-xl-3">
                <div class="stat-card p-4 rounded-4 shadow-sm bg-white border-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-box-archive fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Mặt hàng đang
                        kinh doanh</p>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalProducts) }} sản phẩm</h4>
                </div>
            </div>

            <!-- Tổng nợ chưa thu hồi -->
            <div class="col-md-6 col-xl-3">
                <div class="stat-card p-4 rounded-4 shadow-sm bg-white border-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-hand-holding-dollar fs-4"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 small">Cần thu hồi</span>
                    </div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Tổng nợ khách
                        hàng</p>
                    <h3 class="fw-bold mb-0 text-danger">{{ number_format($totalCustomerDebt) }}đ</h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Giao dịch mới nhất -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h6 class="fw-bold mb-0"><i class="fas fa-list me-2 text-primary"></i>Giao dịch vừa thực hiện</h6>
                        <a href="{{ route('admin.history') }}"
                            class="btn btn-sm btn-light text-primary fw-bold rounded-pill px-3">Tất cả lịch sử</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="extra-small text-uppercase fw-bold text-muted">
                                    <th class="ps-4">Sản phẩm</th>
                                    <th>Khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Thời gian</th>
                                    <th class="text-end pe-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSales as $sale)
                                    <tr>
                                        <td class="ps-4">
                                            @if($sale->items->count() === 1)
                                                <div class="fw-bold text-dark">{{ $sale->items->first()->product->name }}</div>
                                                <div class="extra-small text-muted">SL: {{ $sale->items->first()->quantity }}</div>
                                            @else
                                                <div class="fw-bold text-dark">{{ $sale->items->count() }} mặt hàng</div>
                                                <div class="extra-small text-muted">
                                                    {{ $sale->items->take(2)->map(function($i){ return $i->product->name; })->join(', ') }}...
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small fw-semibold text-dark">
                                                {{ $sale->customer?->name ?? 'Khách lẻ' }}</div>
                                            <div class="extra-small text-muted">{{ $sale->customer?->phone ?? '---' }}
                                            </div>
                                        </td>
                                        <td><span
                                                class="fw-bold text-primary">{{ number_format($sale->grand_total) }}đ</span>
                                        </td>
                                        <td class="small">{{ $sale->sold_at->diffForHumans() }}</td>
                                        <td class="text-end pe-4">
                                            @php
                                                $saleData = $sale->toArray();
                                                $saleData['formatted_date'] = $sale->sold_at->format('d/m/Y H:i');
                                                $saleData['items'] = $sale->items->load('product');
                                                $saleData['customer'] = $sale->customer
                                                    ? $sale->customer->load('debtBalance')
                                                    : null;
                                                $saleData['total'] = $sale->grand_total;
                                                $saleData['debt'] = $sale->debt_amount;
                                            @endphp
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                onclick="openSaleDetailModal({{ json_encode($saleData) }})">
                                                Xem
                                            </button>
                                        </td>
                                    </tr>
                                @empty

                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted small">Chưa có giao dịch nào
                                            phát sinh.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Khách nợ nhiều nhất -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0"><i class="fas fa-user-clock me-2 text-danger"></i>Khách hàng nợ nhiều nhất
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($topDebtors as $debtor)
                                <div class="list-group-item p-3 border-bottom border-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px;">
                                                <span class="fw-bold">{{ mb_substr($debtor->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-bold small text-dark">{{ $debtor->name }}</div>
                                                <div class="extra-small text-muted">{{ $debtor->phone }}</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-danger">{{ number_format($debtor->total_debt) }}đ
                                            </div>
                                            <a href="{{ route('admin.debt') }}?selected_id={{ $debtor->id }}"
                                                class="extra-small text-decoration-none">Đối soát nợ</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted small">Không có khách hàng nợ.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-center">
                        <a href="{{ route('admin.debt') }}" class="small fw-bold text-decoration-none">Xem toàn bộ danh
                            sách nợ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.components.sale-history-detail-modal')

@endsection

<style>
    .stat-card {
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .extra-small {
        font-size: 0.72rem;
    }

    .form-label-custom {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        display: block;
    }
</style>
