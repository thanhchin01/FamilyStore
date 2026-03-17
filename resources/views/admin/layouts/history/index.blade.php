@extends('admin.layout')

@section('title', 'Lich sử bán hàng')

@section('content')
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
                    {{-- <tbody>
                        <tr>
                            <td>1</td>
                            <td>20/02/2026</td>
                            <td>Nồi cơm Sharp 1.8L</td>
                            <td>
                                <span class="fw-semibold">Nguyễn Văn A</span><br>
                                <small class="text-muted">0909xxx999</small>
                            </td>
                            <td>1</td>
                            <td class="fw-bold text-primary">1.200.000đ</td>
                            <td>
                                <span class="badge bg-success">Đã trả đủ</span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>20/02/2026</td>
                            <td>Ấm Sunhouse Inox</td>
                            <td>
                                <span class="fw-semibold">Trần Thị B</span><br>
                                <small class="text-muted">Còn nợ</small>
                            </td>
                            <td>2</td>
                            <td class="fw-bold text-danger">700.000đ</td>
                            <td>
                                <span class="badge bg-warning text-dark">Còn nợ</span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody> --}}
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
                                    @if ($sale->debt > 0)
                                        <span class="badge bg-warning text-dark">Còn nợ</span>
                                    @else
                                        <span class="badge bg-success">Đã trả đủ</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#saleDetailModal-{{ $sale->id }}">
                                        Xem chi tiết
                                    </button>
                                </td>
                            </tr>
                            <!-- Modal xem chi tiết hoá đơn -->
                            <div class="modal fade" id="saleDetailModal-{{ $sale->id }}" tabindex="-1"
                                aria-labelledby="saleDetailModalLabel-{{ $sale->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold" id="saleDetailModalLabel-{{ $sale->id }}">
                                                Hóa đơn #{{ $sale->id }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <div class="small text-muted">Ngày bán</div>
                                                <div>{{ $sale->created_at->format('d/m/Y H:i') }}</div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="small text-muted">Sản phẩm</div>
                                                <div class="fw-semibold">{{ $sale->product->name }}</div>
                                                <div class="small text-muted">
                                                    Số lượng: {{ $sale->quantity }} |
                                                    Đơn giá: {{ number_format($sale->price) }}đ
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="small text-muted">Thông tin khách hàng</div>
                                                <div>
                                                    <strong>{{ $sale->customer?->name ?? 'Khách lẻ' }}</strong><br>
                                                    @if ($sale->customer?->phone)
                                                        <span class="text-primary fw-semibold">SĐT:
                                                            {{ $sale->customer->phone }}</span><br>
                                                    @endif
                                                    @if ($sale->customer?->address)
                                                        <small class="text-muted">Địa chỉ:
                                                            {{ $sale->customer->address }}</small><br>
                                                    @endif
                                                    @if ($sale->customer?->relative_name)
                                                        <small class="text-muted">Người thân:
                                                            {{ $sale->customer->relative_name }}</small><br>
                                                    @endif
                                                    @if ($sale->customer?->debt && $sale->customer->debt->total_debt > 0)
                                                        <small class="text-danger fw-semibold">Tổng nợ hiện tại:
                                                            {{ number_format($sale->customer->debt->total_debt) }}đ</small>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="small text-muted">Thanh toán</div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Tổng tiền hóa đơn:</span>
                                                    <span class="fw-semibold">{{ number_format($sale->total) }}đ</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Khách đã trả:</span>
                                                    <span>{{ number_format($sale->paid_amount ?? 0) }}đ</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Còn nợ lại:</span>
                                                    <span
                                                        class="{{ $sale->debt > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                                        {{ number_format($sale->debt) }}đ
                                                    </span>
                                                </div>
                                                <div class="mt-2">
                                                    @if ($sale->debt > 0)
                                                        <span class="badge bg-warning text-dark">Đang còn nợ</span>
                                                    @else
                                                        <span class="badge bg-success">Đã thanh toán đủ</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($sale->customer && $sale->customer->debt && $sale->customer->debt->total_debt > 0)
                                                <hr>
                                                <div class="mb-0">
                                                    <div class="small fw-bold text-muted mb-2">Trả nợ</div>
                                                    <form action="{{ route('admin.debt.pay') }}" method="POST"
                                                        class="d-flex gap-2 align-items-end flex-wrap">
                                                        @csrf
                                                        <input type="hidden" name="customer_id" value="{{ $sale->customer->id }}">
                                                        <div class="flex-grow-1">
                                                            <label class="form-label small mb-0">Số tiền trả (đ)</label>
                                                            <input type="number" name="amount" class="form-control form-control-sm"
                                                                min="1" max="{{ $sale->customer->debt->total_debt }}"
                                                                placeholder="Tối đa {{ number_format($sale->customer->debt->total_debt) }}đ"
                                                                required>
                                                        </div>
                                                        <div>
                                                            <label class="form-label small mb-0">&nbsp;</label>
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fa-solid fa-money-bill-transfer me-1"></i> Ghi
                                                                nhận trả nợ
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light border"
                                                data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection