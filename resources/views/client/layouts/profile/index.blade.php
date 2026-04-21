@extends('client.layout')

@section('title', 'Thông tin cá nhân - Khoa Quyen Store')

@section('content')
<div class="profile-page py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-primary-custom text-white text-center py-4 border-0">
                        <div class="avatar-wrapper mb-3 position-relative d-inline-block">
                            @if(optional($user->customerProfile)->avatar)
                                <img src="{{ asset('storage/' . $user->customerProfile->avatar) }}" 
                                     class="rounded-circle border border-4 border-white shadow-sm" alt="Avatar">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=009688&color=fff&size=128" 
                                     class="rounded-circle border border-4 border-white shadow-sm" alt="Avatar">
                            @endif
                        </div>
                        <h5 class="mb-1 fw-bold">{{ $user->name }}</h5>
                        <p class="mb-0 small opacity-75">Thành viên từ {{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="#info" class="list-group-item list-group-item-action py-3 px-4 active border-0">
                                <i class="fas fa-user-circle me-3"></i>Thông tin tài khoản
                            </a>
                            <a href="#orders" class="list-group-item list-group-item-action py-3 px-4 border-0">
                                <i class="fas fa-shopping-bag me-3"></i>Lịch sử mua hàng
                            </a>
                            <a href="#address" class="list-group-item list-group-item-action py-3 px-4 border-0">
                                <i class="fas fa-map-marker-alt me-3"></i>Địa chỉ nhận hàng
                            </a>
                            <a href="#" class="list-group-item list-group-item-action py-3 px-4 text-danger border-0" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-3"></i>Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Profile Form -->
                <div id="info" class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4">Thông tin cá nhân</h4>
                        <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label text-muted small fw-bold">ẢNH ĐẠI DIỆN</label>
                                    <input type="file" name="avatar" class="form-control rounded-3 py-2" accept="image/*">
                                    <div class="form-text small">Dung lượng tối đa 2MB (JPG, PNG, GIF)</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">HỌ VÀ TÊN</label>
                                    <input type="text" name="name" class="form-control rounded-3 py-2" value="{{ $user->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">ĐỊA CHỈ EMAIL</label>
                                    <input type="email" name="email" class="form-control rounded-3 py-2" value="{{ $user->email }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">SỐ ĐIỆN THOẠI</label>
                                    <input type="text" name="phone" class="form-control rounded-3 py-2" value="{{ $user->phone }}" placeholder="Chưa cập nhật">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">GIỚI TÍNH</label>
                                    <select name="gender" class="form-select rounded-3 py-2">
                                        <option value="">Chọn giới tính</option>
                                        <option value="male" {{ (optional($user->customerProfile)->gender == 'male') ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ (optional($user->customerProfile)->gender == 'female') ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ (optional($user->customerProfile)->gender == 'other') ? 'selected' : '' }}>Khác</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">NGÀY SINH</label>
                                    <input type="date" name="birthday" class="form-control rounded-3 py-2" value="{{ optional($user->customerProfile)->birthday }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-muted small fw-bold">ĐỊA CHỈ THƯỜNG TRÚ</label>
                                    <textarea name="address" class="form-control rounded-3 py-2" rows="3" placeholder="Ví dụ: Thôn Khoa Quyên, Xã X... ">{{ $user->address }}</textarea>
                                </div>
                            </div>

                            <div class="mt-4 pt-2 text-end">
                                <button type="submit" class="btn btn-primary-custom px-5 py-2 fw-600 rounded-pill">Cập nhật thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div id="orders" class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4">Đơn hàng gần đây</h4>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0">
                                <thead class="table-light border-0">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Mã đơn</th>
                                        <th class="border-0 py-3">Ngày đặt</th>
                                        <th class="border-0 py-3 text-center">Trạng thái</th>
                                        <th class="border-0 py-3 text-end px-4">Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr class="border-bottom border-light">
                                        <td class="px-4 py-3 fw-bold text-primary-color">{{ $order['id'] }}</td>
                                        <td class="py-3 text-muted">{{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}</td>
                                        <td class="py-3 text-center">
                                            <span class="badge {{ $order['status'] === 'Đã hoàn thành' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-3">
                                                {{ $order['status'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-end fw-bold px-4">{{ number_format($order['total']) }}đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<style>
    .avatar-wrapper img {
        width: 128px;
        height: 128px;
        object-fit: cover;
    }
    .profile-page .card {
        transition: transform 0.3s ease;
    }
    .profile-page .list-group-item.active {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
</style>
@endsection
