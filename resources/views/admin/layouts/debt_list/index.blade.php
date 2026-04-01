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
                <div class="debt-card overflow-hidden text-nowrap">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Khách hàng</th>
                                <th>Thông tin liên hệ</th>
                                <th>Tổng nợ</th>
                                <th class="text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- ✅ Vòng lặp hiển thị danh sách khách nợ --}}
                            @forelse($debtors as $debtor)
                                <tr class="debtor-item cursor-pointer"
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
                                        <button class="btn btn-sm btn-light border"><i
                                                class="fa-solid fa-eye text-primary"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Không tìm thấy khách hàng nào đang có
                                        nợ.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Phân trang --}}
                    <div class="p-3">
                        {{ $debtors->links() }}
                    </div>
                </div>
            </div>

            <!-- Chi tiết nợ của khách được chọn -->
            <div class="col-lg-5">
                @php
                    // Logic hiển thị chi tiết: Lấy khách được chọn qua ID, hoặc lấy khách đầu tiên trong danh sách
                    $selectedId = request('selected_id');
                    $selectedCustomer = $selectedId
                        ? $debtors->firstWhere('id', $selectedId)
                        : $debtors->first();
                @endphp

                @if($selectedCustomer)
                    <div class="debt-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">Chi tiết: {{ $selectedCustomer->name }}</h5>
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Đang nợ</span>
                            </div>
                            <!-- Nút này sẽ mở Modal trả nợ (cần JS xử lý sau) -->
                            <button class="btn btn-sm btn-success rounded-pill px-3" data-bs-toggle="modal"
                                data-bs-target="#modalPayDebt" data-id="{{ $selectedCustomer->id }}"
                                data-name="{{ $selectedCustomer->name }}"
                                data-debt="{{ $selectedCustomer->debt->total_debt ?? 0 }}">
                                Trả bớt nợ
                            </button>
                        </div>

                        <!-- Thông tin mở rộng -->
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="info-label">Địa chỉ</div>
                                <div class="small fw-medium">{{ $selectedCustomer->address ?? 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Người thân liên hệ</div>
                                <div class="small fw-medium">{{ $selectedCustomer->relative_name ?? 'Không có' }}</div>
                            </div>
                        </div>

                        <hr class="text-light-subtle">

                        <!-- Lịch sử giao dịch -->
                        {{-- Lưu ý: Phần lịch sử này hiện tại chưa có dữ liệu từ Controller,
                        Cần dùng AJAX hoặc load thêm relation 'transactions' để hiển thị thật --}}
                        <h6 class="fw-bold mb-3">Lịch sử giao dịch</h6>
                        <div class="history-timeline">
                            {{-- Placeholder mẫu để giữ layout --}}
                            <!-- Mua hàng (Ghi nợ) -->
                            <div class="timeline-event plus">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold small">Dữ liệu mẫu (Đang cập nhật...)</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">---</div>
                                    </div>
                                    <div class="text-danger fw-bold">---</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold small text-uppercase">Số dư nợ hiện tại:</span>
                            <span
                                class="fs-5 fw-bold text-danger">{{ number_format($selectedCustomer->debt->total_debt ?? 0) }}đ</span>
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
@endsection