@extends('admin.layout')

@section('title', 'Quản lý nợ khách hàng')

@section('content')
<div class="debt-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Danh sách khách nợ</h4>
            <p class="text-muted small mb-0">Theo dõi công nợ và lịch sử thanh toán</p>
        </div>
        <div class="search-box position-relative">
            <input type="text" class="form-control rounded-pill px-4" placeholder="Tìm tên, SĐT khách...">
        </div>
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
                        <!-- Khách hàng 1 -->
                        <tr class="debtor-item" data-bs-toggle="collapse" data-bs-target="#detailCustomer1">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">Nguyễn Văn Đại</div>
                                <div class="small text-muted">Người thân: Chị Lan (Vợ)</div>
                            </td>
                            <td>
                                <div class="small"><i class="fa-solid fa-phone me-1"></i> 0987.xxx.xxx</div>
                                <div class="small text-truncate" style="max-width: 150px;"><i class="fa-solid fa-location-dot me-1"></i> Thôn 5, Xã Hòa Khánh</div>
                            </td>
                            <td><span class="fw-bold text-danger">2,450,000đ</span></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border"><i class="fa-solid fa-eye text-primary"></i></button>
                            </td>
                        </tr>

                        <!-- Khách hàng 2 -->
                        <tr class="debtor-item">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">Lê Thị Hồng</div>
                                <div class="small text-muted">Người thân: Anh Tuấn (Con)</div>
                            </td>
                            <td>
                                <div class="small"><i class="fa-solid fa-phone me-1"></i> 0345.xxx.xxx</div>
                                <div class="small"><i class="fa-solid fa-location-dot me-1"></i> Ngõ 12, Phố Mới</div>
                            </td>
                            <td><span class="fw-bold text-danger">850,000đ</span></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border"><i class="fa-solid fa-eye text-primary"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chi tiết nợ của khách được chọn -->
        <div class="col-lg-5">
            <div class="debt-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Chi tiết: Nguyễn Văn Đại</h5>
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Đang nợ</span>
                    </div>
                    <button class="btn btn-sm btn-success rounded-pill px-3">Trả bớt nợ</button>
                </div>

                <!-- Thông tin mở rộng -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="info-label">Địa chỉ</div>
                        <div class="small fw-medium">Thôn 5, Xã Hòa Khánh, Buôn Ma Thuột</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Người thân liên hệ</div>
                        <div class="small fw-medium">Chị Lan - 0912.xxx.xxx</div>
                    </div>
                </div>

                <hr class="text-light-subtle">

                <!-- Lịch sử giao dịch -->
                <h6 class="fw-bold mb-3">Lịch sử giao dịch</h6>
                <div class="history-timeline">
                    <!-- Mua hàng (Ghi nợ) -->
                    <div class="timeline-event plus">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold small">Mua Nồi cơm Sharp 1.8L</div>
                                <div class="text-muted" style="font-size: 0.75rem;">14:30 - 15/05/2024</div>
                            </div>
                            <div class="text-danger fw-bold">+1,200,000đ</div>
                        </div>
                    </div>

                    <!-- Trả nợ -->
                    <div class="timeline-event minus">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold small">Khách trả bớt tiền mặt</div>
                                <div class="text-muted" style="font-size: 0.75rem;">09:00 - 18/05/2024</div>
                            </div>
                            <div class="text-success fw-bold">-500,000đ</div>
                        </div>
                    </div>

                    <!-- Mua hàng tiếp -->
                    <div class="timeline-event plus">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold small">Mua Ấm siêu tốc + Vợt muỗi</div>
                                <div class="text-muted" style="font-size: 0.75rem;">17:15 - 20/05/2024</div>
                            </div>
                            <div class="text-danger fw-bold">+470,000đ</div>
                        </div>
                    </div>
                </div>

                <div class="bg-light p-3 rounded-3 d-flex justify-content-between align-items-center mt-3">
                    <span class="fw-bold small text-uppercase">Số dư nợ hiện tại:</span>
                    <span class="fs-5 fw-bold text-danger">2,450,000đ</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
