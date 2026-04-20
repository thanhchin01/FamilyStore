@extends('admin.layout')

@section('title', 'Quản lý nợ khách hàng')

@section('content')
    <div class="debt-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold">Danh sách khách nợ</h4>
                <p class="text-muted small mb-0">Theo dõi công nợ và lịch sử thanh toán</p>
            </div>
            <!-- ✅ Form tìm kiếm -->
            <form action="{{ route('admin.debt') }}" method="GET" class="search-box position-relative">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control rounded-pill px-4"
                    placeholder="Tìm tên, SĐT khách..." onchange="this.form.submit()">
            </form>
        </div>

        <div class="row g-4">
            <!-- Bảng danh sách khách nợ -->
            <div class="col-lg-7">
                <div class="debt-card overflow-hidden text-nowrap shadow-sm border-0 rounded-4 bg-white">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Khách hàng</th>
                                <th class="py-3">Thông tin liên hệ</th>
                                <th class="py-3">Tổng nợ</th>
                                <th class="text-end pe-4 py-3">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debtors as $debtor)
                                <tr class="debtor-item cursor-pointer {{ ($selectedCustomer && $selectedCustomer->id == $debtor->id) ? 'bg-primary-light active' : '' }}"
                                    onclick="window.location.href='?keyword={{ request('keyword') }}&selected_id={{ $debtor->id }}'">
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $debtor->name }}</div>
                                        @if($debtor->relative_name)
                                            <div class="small text-muted">Người thân: {{ $debtor->relative_name }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small"><i class="fa-solid fa-phone me-1 text-secondary"></i>
                                            {{ $debtor->phone ?? '---' }}</div>
                                        <div class="small text-truncate" style="max-width: 150px;"
                                            title="{{ $debtor->address }}">
                                            <i class="fa-solid fa-location-dot me-1 text-secondary"></i>
                                            {{ $debtor->address ?? '---' }}
                                        </div>
                                    </td>
                                    <td><span
                                            class="fw-bold text-danger">{{ number_format($debtor->debt->total_debt ?? 0) }}đ</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-white border rounded-circle shadow-sm">
                                            <i class="fa-solid fa-eye text-primary"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Không tìm thấy khách hàng nào đang có nợ.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="p-3">
                        {{ $debtors->links() }}
                    </div>
                </div>
            </div>

            <!-- Chi tiết nợ của khách được chọn -->
            <div class="col-lg-5">
                @if($selectedCustomer)
                    <div class="debt-card p-4 shadow-sm border-0 rounded-4 bg-white overflow-hidden">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">Chi tiết: {{ $selectedCustomer->name }}</h5>
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Đang nợ</span>
                            </div>
                            <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm fw-bold" data-bs-toggle="modal"
                                data-bs-target="#modalPayDebt" data-id="{{ $selectedCustomer->id }}"
                                data-name="{{ $selectedCustomer->name }}"
                                data-debt="{{ $selectedCustomer->debt->total_debt ?? 0 }}">
                                <i class="fas fa-money-bill-wave me-1"></i> Trả bớt nợ
                            </button>
                        </div>

                        <!-- Thông tin mở rộng -->
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="info-label text-muted extra-small text-uppercase fw-bold mb-1">Địa chỉ</div>
                                <div class="small fw-medium text-dark text-wrap">{{ $selectedCustomer->address ?? 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="info-label text-muted extra-small text-uppercase fw-bold mb-1">Người thân</div>
                                <div class="small fw-medium text-dark">{{ $selectedCustomer->relative_name ?? 'Không có' }}</div>
                            </div>
                        </div>

                        <hr class="text-light-subtle my-4">

                        <!-- Lịch sử giao dịch thật -->
                        <h6 class="fw-bold mb-3 d-flex align-items-center text-primary">
                            <i class="fas fa-history me-2"></i> Lịch sử giao dịch
                        </h6>
                        <div class="history-timeline overflow-auto pe-2" style="max-height: 420px;">
                            @forelse($history as $item)
                                @php 
                                    $isPayment = ($item->type ?? '') === 'payment';
                                    $isPurchase = ($item->type ?? '') === 'purchase';
                                @endphp
                                <div class="timeline-event mb-3 pb-3 border-bottom {{ $isPayment ? 'payment' : 'purchase' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex">
                                            <div class="event-icon me-3 mt-1 {{ $isPayment ? 'text-success' : 'text-danger' }}">
                                                <i class="fas {{ $isPayment ? 'fa-circle-check' : 'fa-square-plus' }}"></i>
                                            </div>
                                            <div class="pe-3">
                                                <div class="fw-bold small text-dark">
                                                    @if($isPayment)
                                                        Khách trả bớt nợ
                                                    @elseif($isPurchase)
                                                        Mua hàng: {{ $item->invoice_code ?? '#INV' }}
                                                    @else
                                                        Ghi nợ phát sinh
                                                    @endif
                                                </div>
                                                <div class="text-muted text-wrap" style="font-size: 0.75rem;">{{ $item->description }}</div>
                                                <div class="extra-small text-muted fst-italic">{{ \Carbon\Carbon::parse($item->occurred_at)->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                        <div class="fw-bold {{ $isPayment ? 'text-success' : 'text-danger' }}">
                                            {{ $isPayment ? '-' : '+' }}{{ number_format($item->amount) }}đ
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-alt fa-2x mb-2 opacity-25"></i>
                                    <p class="small mb-0">Chưa có lịch sử giao dịch.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="bg-light p-3 rounded-4 d-flex justify-content-between align-items-center mt-3 border border-dashed">
                            <span class="fw-bold small text-uppercase text-muted">Số dư nợ hiện tại:</span>
                            <span class="fs-4 fw-bold text-danger">{{ number_format($selectedCustomer->debt->total_debt ?? 0) }}đ</span>
                        </div>
                    </div>
                @else
                    <div class="debt-card p-5 text-center text-muted">
                        <i class="fa-solid fa-user-slash fs-1 mb-3 opacity-25"></i>
                        <p>Chưa chọn khách hàng nào hoặc danh sách trống.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.components.debt-pay-modal')
@endsection

<style>
.bg-primary-light { background-color: #f0f9ff !important; }
.debtor-item.active { border-left: 4px solid #0ea5e9; }
.extra-small { font-size: 0.7rem; }
.btn-white { background: white; }
</style>