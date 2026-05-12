@extends('client.layout')

@section('title', 'Hồ sơ của tôi - Khoa Quyen Store')

@section('content')
    <div class="profile-page py-5 bg-light-subtle">
        <div class="container">
            <div class="row g-4">
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top"
                        style="top: 100px; z-index: 11;">
                        <div class="card-header profile-sidebar-header text-white text-center py-5 border-0">
                            <div class="avatar-wrapper mb-3 position-relative d-inline-block">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                        class="rounded-circle border border-4 border-white shadow-lg" alt="Avatar"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="avatar-placeholder rounded-circle border border-4 border-white shadow-lg d-flex align-items-center justify-content-center bg-white text-primary fw-bold"
                                        style="width: 120px; height: 120px; font-size: 3rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <h5 class="mb-1 fw-bold">{{ $user->name }}</h5>
                            <p class="mb-0 small opacity-75">Thành viên từ {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush profile-nav">
                                <a href="#info" class="list-group-item list-group-item-action py-3 px-4 active border-0">
                                    <i class="fas fa-id-card me-3"></i>Thông tin cá nhân
                                </a>
                                <a href="#orders" class="list-group-item list-group-item-action py-3 px-4 border-0">
                                    <i class="fas fa-history me-3"></i>Lịch sử mua hàng
                                </a>
                                <a href="#address" class="list-group-item list-group-item-action py-3 px-4 border-0">
                                    <i class="fas fa-map-marked-alt me-3"></i>Địa chỉ nhận hàng
                                </a>
                                <a href="#"
                                    class="list-group-item list-group-item-action py-3 px-4 text-danger border-0"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-power-off me-3"></i>Đăng xuất
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="profile-content-area rounded-4 p-1">
                        <!-- Profile Info (Read-only) -->
                        <div id="info"
                            class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden animate__animated animate__fadeIn">
                            <div
                                class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0 text-dark">Thông tin cá nhân</h5>
                                <button class="btn btn-primary rounded-pill px-4 fw-600 shadow-sm btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                                    <i class="fas fa-edit me-2"></i>Cập nhật thông tin
                                </button>
                            </div>
                            <div class="card-body p-4 p-md-5 bg-white">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="info-group">
                                            <label class="text-muted small fw-bold mb-2 d-block">HỌ VÀ TÊN</label>
                                            <div
                                                class="p-3 bg-light rounded-3 fw-bold text-dark border-start border-primary border-4">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-group">
                                            <label class="text-muted small fw-bold mb-2 d-block">ĐỊA CHỈ EMAIL</label>
                                            <div class="p-3 bg-light rounded-3 fw-medium text-dark">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-group">
                                            <label class="text-muted small fw-bold mb-2 d-block">SỐ ĐIỆN THOẠI</label>
                                            <div class="p-3 bg-light rounded-3 fw-medium text-dark">
                                                {{ $user->phone ?: 'Chưa cập nhật' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-group">
                                            <label class="text-muted small fw-bold mb-2 d-block">GIỚI TÍNH</label>
                                            <div class="p-3 bg-light rounded-3 fw-medium text-dark text-capitalize">
                                                @php
                                                    $genderMap = ['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'];
                                                    echo $genderMap[$user->gender] ?? 'Chưa cập nhật';
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-group">
                                            <label class="text-muted small fw-bold mb-2 d-block">NGÀY SINH</label>
                                            <div class="p-3 bg-light rounded-3 fw-medium text-dark">
                                                {{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : 'Chưa cập nhật' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="info-group">
                                            <label class="text-muted small fw-bold mb-2 d-block">ĐỊA CHỈ THƯỜNG TRÚ</label>
                                            <div class="p-3 bg-light rounded-3 fw-medium text-dark">
                                                {{ $user->address ?: 'Chưa cập nhật' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders -->
                        <div id="orders" class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeIn"
                            style="animation-delay: 0.1s">
                            <div class="card-header bg-white border-0 py-4 px-4">
                                <h5 class="fw-bold mb-0">Lịch sử mua hàng</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-4 py-3 border-0 small fw-bold">MÃ ĐƠN</th>
                                                <th class="py-3 border-0 small fw-bold">NGÀY ĐẶT</th>
                                                <th class="py-3 border-0 small fw-bold text-center">TRẠNG THÁI</th>
                                                <th class="py-3 border-0 small fw-bold text-end px-4">TỔNG TIỀN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $order)
                                                <tr class="border-bottom border-light">
                                                    <td class="px-4 py-3 fw-bold text-primary">{{ $order['id'] }}</td>
                                                    <td class="py-3 text-muted">
                                                        {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}</td>
                                                    <td class="py-3 text-center">
                                                        <span
                                                            class="badge {{ $order['status'] === 'Đã hoàn thành' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-3">
                                                            {{ $order['status'] }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 text-end fw-bold px-4 text-dark">
                                                        {{ number_format($order['total']) }}đ</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-5 text-center text-muted">
                                                        <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                                                        <p>Bạn chưa có đơn hàng nào.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('client.layouts.profile.partials.update-modal')


    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    @push('styles')
        @vite(['resources/scss/client/pages/profile.scss'])
    @endpush

    @push('scripts')
        @vite(['resources/js/client/pages/profile.js'])
    @endpush

@endsection
