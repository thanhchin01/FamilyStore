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
                    <tbody>
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
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
