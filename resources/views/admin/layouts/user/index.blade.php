@extends('admin.layout')

@section('title', 'Quản lý người dùng - Gia dụng Khoa Quyên')

@section('content')
<div class="user-management-wrapper py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary font-luxury">Quản lý người dùng</h4>
            <p class="text-muted small mb-0">Xem thông tin và quản lý trạng thái tài khoản khách hàng</p>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="card shadow-sm border-0 rounded-4 mb-4 p-4 bg-white">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold small text-uppercase text-muted">Tìm kiếm</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="keyword" class="form-control bg-light border-0" placeholder="Tên, Email, SĐT, Username..." value="{{ request('keyword') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Trạng thái</label>
                    <select name="status" class="form-select bg-light border-0">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Đã khóa</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Lọc</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light w-100 rounded-pill fw-bold">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Danh sách người dùng -->
    <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr class="small text-uppercase fw-bold text-muted">
                        <th class="ps-4 py-3">Người dùng</th>
                        <th>Thông tin liên hệ</th>
                        <th>Trạng thái</th>
                        <th>Ngày tham gia</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 45px; height: 45px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <div class="extra-small text-muted">@ {{ $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-medium">{{ $user->email }}</div>
                            <div class="extra-small text-muted">{{ $user->phone ?? 'Chưa có SĐT' }}</div>
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hoạt động</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Đã khóa</span>
                            @endif
                        </td>
                        <td class="small">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-end pe-4 no-wrap">
                            <div class="d-flex align-items-center justify-content-end">
                                <button class="btn btn-sm btn-light border-0 rounded-circle me-1" style="width: 32px; height: 32px;" onclick="viewUserDetails({{ $user->id }})" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </button>

                                <button class="btn btn-sm btn-light border-0 rounded-circle me-3" style="width: 32px; height: 32px;" 
                                    onclick="confirmDelete('{{ route('admin.users.destroy', $user->id) }}', 'Xóa tài khoản của {{ $user->name }}? Hành động này không thể hoàn tác.')" title="Xóa người dùng">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </button>

                                
                                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" id="toggle-form-{{ $user->id }}">
                                    @csrf
                                    <div class="form-check form-switch p-0 m-0">
                                        <input class="form-check-input ms-0 custom-toggle-switch" type="checkbox" role="switch" 
                                            {{ $user->status === 'active' ? 'checked' : '' }}
                                            onchange="document.getElementById('toggle-form-{{ $user->id }}').submit()">
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
@empty

                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted small">Không tìm thấy người dùng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-top">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Chi tiết người dùng -->
<div class="modal fade" id="userDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">Thông tin chi tiết người dùng</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="userDetailContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function viewUserDetails(userId) {
        const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
        const contentDiv = document.getElementById('userDetailContent');
        
        contentDiv.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
        modal.show();

        fetch(`{{ url('admin/users') }}/${userId}`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const user = data.user;
                    contentDiv.innerHTML = `
                        <div class="d-flex align-items-center mb-4">
                            <img src="${user.avatar ? '{{ asset('storage') }}/' + user.avatar : 'https://ui-avatars.com/api/?name=' + user.name}" class="rounded-circle shadow-sm me-3" style="width: 70px; height: 70px; object-fit: cover;">
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">${user.name}</h5>
                                <div class="badge ${user.status === 'active' ? 'bg-success' : 'bg-danger'} rounded-pill px-3">${user.status === 'active' ? 'Đang hoạt động' : 'Đã khóa'}</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="small text-muted text-uppercase fw-bold">Email</label>
                                <div class="fw-medium">${user.email}</div>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted text-uppercase fw-bold">Số điện thoại</label>
                                <div class="fw-medium">${user.phone || 'N/A'}</div>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted text-uppercase fw-bold">Username</label>
                                <div class="fw-medium">@ ${user.username}</div>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted text-uppercase fw-bold">Giới tính</label>
                                <div class="fw-medium">${user.gender === 'male' ? 'Nam' : (user.gender === 'female' ? 'Nữ' : 'Khác')}</div>
                            </div>
                            <div class="col-12">
                                <label class="small text-muted text-uppercase fw-bold">Địa chỉ</label>
                                <div class="fw-medium">${user.address || 'Chưa cập nhật'}</div>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted text-uppercase fw-bold">Đơn hàng đã đặt</label>
                                <div class="fw-bold text-primary fs-5">${user.orders_count}</div>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted text-uppercase fw-bold">Lần đăng nhập cuối</label>
                                <div class="fw-medium small">${user.last_login_at ? new Date(user.last_login_at).toLocaleString('vi-VN') : 'N/A'}</div>
                            </div>
                        </div>
                    `;
                }
            });
    }
</script>
@endpush

<style>
.font-luxury { font-family: 'Playfair Display', serif; }
.extra-small { font-size: 0.72rem; }
.no-wrap { white-space: nowrap; }

/* Custom Premium Switch */
.custom-toggle-switch {
    width: 40px !important;
    height: 20px !important;
    cursor: pointer;
    background-color: #cbd5e1;
    border-color: #cbd5e1;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='white'/%3e%3c/svg%3e") !important;
}
.custom-toggle-switch:checked {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
}
.custom-toggle-switch:focus {
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
}
.btn-light:hover {
    background-color: #e2e8f0 !important;
    transform: scale(1.1);
    transition: all 0.2s;
}
</style>

@endsection
