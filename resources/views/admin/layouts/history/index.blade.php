@extends('admin.layout')

@section('title', 'Lịch sử bán hàng - Gia dụng Khoa Quyên')

@section('content')
    <div class="sales-history-container py-4">
        <div class="mb-4">
            <h4 class="fw-bold font-luxury text-primary">Lịch sử bán hàng</h4>
            <p class="text-muted small">Tra cứu và quản lý hóa đơn đã phát sinh</p>
        </div>

        <!-- Bộ lọc cao cấp -->
        <div class="card shadow-sm border-0 rounded-4 mb-4 p-4 bg-white">
            <form method="GET" action="{{ route('admin.history') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label-custom fw-bold small text-uppercase">Khoảng thời gian</label>
                        <div class="input-group">
                            <input type="date" name="from" class="form-control border-light shadow-sm bg-light-subtle" value="{{ request('from') }}">
                            <span class="input-group-text bg-light border-light">đến</span>
                            <input type="date" name="to" class="form-control border-light shadow-sm bg-light-subtle" value="{{ request('to') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom fw-bold small text-uppercase">Trạng thái nợ</label>
                        <select name="debt_status" class="form-select border-light shadow-sm">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="paid" {{ request('debt_status') == 'paid' ? 'selected' : '' }}>Đã trả đủ</option>
                            <option value="debt" {{ request('debt_status') == 'debt' ? 'selected' : '' }}>Còn nợ lại</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom fw-bold small text-uppercase">Lọc theo khách</label>
                        <select name="customer_type" class="form-select border-light shadow-sm">
                            <option value="">-- Tất cả loại khách --</option>
                            <option value="guest" {{ request('customer_type') == 'guest' ? 'selected' : '' }}>Khách vãng lai</option>
                            <option value="customer" {{ request('customer_type') == 'customer' ? 'selected' : '' }}>Khách quen (Có nợ)</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                            <i class="fas fa-filter me-2"></i>Áp dụng lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bảng lịch sử -->
        <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-uppercase fw-bold text-muted">
                            <th class="ps-4 py-3">Mã đơn</th>
                            <th>Ngày bán</th>
                            <th>Sản phẩm</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            @php 
                                // Chuẩn bị dữ liệu cho Modal
                                $saleData = $sale->toArray();
                                $saleData['formatted_date'] = $sale->sold_at->format('d/m/Y H:i');
                                $saleData['items'] = $sale->items->load('product');
                                $saleData['customer'] = $sale->customer ? $sale->customer->load('debtBalance') : null;
                                $saleData['total'] = $sale->grand_total;
                                $saleData['debt'] = $sale->debt_amount;
                                // Load lịch sử trả nợ cho hoá đơn này
                                $saleData['transactions'] = $sale->debtTransactions()->latest()->get()->toArray();
                            @endphp
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#{{ $sale->invoice_code }}</td>
                                <td class="small">{{ $sale->sold_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($sale->items->count() === 1)
                                        <div class="fw-medium text-dark">{{ $sale->items->first()->product->name }}</div>
                                        <div class="extra-small text-muted">Số lượng: {{ $sale->items->first()->quantity }}</div>
                                    @else
                                        <div class="fw-medium text-dark">{{ $sale->items->count() }} sản phẩm</div>
                                        <div class="extra-small text-muted">
                                            {{ $sale->items->take(2)->map(function($i){ return $i->product->name; })->join(', ') }}...
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $sale->customer?->name ?? 'Khách lẻ' }}</strong>
                                    @if($sale->customer?->phone)
                                        <div class="extra-small text-muted">{{ $sale->customer->phone }}</div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ number_format($sale->grand_total) }}đ</td>
                                <td>
                                    @if ($sale->debt_amount > 0)
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Còn nợ: {{ number_format($sale->debt_amount) }}đ</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Đã trả đủ</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" 
                                        onclick="openSaleDetailModal({{ json_encode($saleData) }})">
                                        <i class="fa-solid fa-eye me-1"></i> Chi tiết
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        @if($sales->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Không tìm thấy dữ liệu hóa đơn nào.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            @if($sales->hasPages())
                <div class="p-4 border-top d-flex justify-content-center">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('admin.components.sale-history-detail-modal')
@endsection

<style>
.form-label-custom { font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem; display: block; }
.extra-small { font-size: 0.72rem; }
.font-luxury { font-family: 'Playfair Display', serif; }
</style>