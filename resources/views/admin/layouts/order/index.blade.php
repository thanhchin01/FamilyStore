@extends('admin.layout')

@section('title', 'Quản lý đơn hàng trực tuyến')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="glass-panel p-4 shadow-lg rounded-4 bg-white border-0">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Danh sách Đơn hàng</h4>
                    <p class="text-muted small mb-0">Quản lý và xử lý các đơn hàng từ website của khách hàng.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fas fa-download me-2"></i> Xuất Excel
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 border-0">Mã đơn</th>
                            <th class="py-3 border-0">Khách hàng</th>
                            <th class="py-3 border-0">Ngày đặt</th>
                            <th class="py-3 border-0">Tổng tiền</th>
                            <th class="py-3 border-0">Thanh toán</th>
                            <th class="py-3 border-0">Trạng thái</th>
                            <th class="py-3 px-4 border-0 text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="px-4 fw-bold text-primary-color">#{{ $order->order_code }}</td>
                            <td>
                                <div class="fw-600">{{ $order->customer->name ?? $order->shipping_name }}</div>
                                <small class="text-muted">{{ $order->shipping_phone }}</small>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold">{{ number_format($order->grand_total) }}đ</td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success-light text-success rounded-pill px-3 py-2">Đã trả</span>
                                @else
                                    <span class="badge bg-warning-light text-warning rounded-pill px-3 py-2">Chưa trả</span>
                                @endif
                                <div class="extra-small text-muted mt-1">{{ $order->payment_method }}</div>
                            </td>
                            <td>
                                <x-admin.status-badge :status="$order->status" />
                            </td>
                            <td class="px-4 text-end">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    <a href="#" class="btn btn-white btn-sm" title="Xem chi tiết"><i class="fas fa-eye text-primary"></i></a>
                                    <button class="btn btn-white btn-sm" title="Cập nhật trạng thái"><i class="fas fa-edit text-warning"></i></button>
                                    <button class="btn btn-white btn-sm" title="In hóa đơn"><i class="fas fa-print text-secondary"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                                <p>Chưa có đơn hàng nào được đặt.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.custom-table { border-collapse: separate; border-spacing: 0 8px; }
.custom-table tr { box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
.custom-table tbody tr { transition: all 0.2s ease; }
.custom-table tbody tr:hover { transform: scale(1.005); background-color: #f8fafc; }
.bg-success-light { background-color: #dcfce7; }
.bg-warning-light { background-color: #fef9c3; }
.btn-white { background: white; border: 1px solid #f1f5f9; }
.btn-white:hover { background: #f8fafc; }
.extra-small { font-size: 11px; }
</style>
@endsection
