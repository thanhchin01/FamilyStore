@extends('admin.layout')

@section('title', 'Quản lý kho hàng - Gia dụng Khoa Quyên')

@section('content')
    <div class="inventory-wrapper py-4">
        @php
            $countOutOfStock = $products->where('stock', '<=', 0)->count();
            $countLowStock = $products->where('stock', '>', 0)->where('stock', '<=', 5)->count();
            $countInStock = $products->where('stock', '>', 5)->count();
        @endphp

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold font-luxury text-primary">Kho hàng điện gia dụng</h4>
                <div class="d-flex gap-2 mt-2">
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Hết hàng: {{ $countOutOfStock }}</span>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Sắp hết: {{ $countLowStock }}</span>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Ổn định: {{ $countInStock }}</span>
                </div>
            </div>
            <a href="{{ route('admin.products') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-plus me-2"></i>Thêm SP mới
            </a>
        </div>

        <!-- Tối ưu xem từng sản phẩm bằng Tabs -->
        <div class="card shadow-sm border-0 rounded-4 bg-white mb-4 overflow-hidden">
            <div class="d-flex flex-wrap bg-light bg-opacity-50 p-2 overflow-auto" id="inventoryTabs">
                <button class="nav-link-custom active" data-filter="all">Tất cả sản phẩm</button>
                @foreach ($categories as $category)
                    <button class="nav-link-custom" data-filter="{{ $category->id }}">{{ $category->name }}</button>
                @endforeach
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="inventoryTable">
                    <thead class="table-light">
                        <tr class="small text-uppercase fw-bold text-muted">
                            <th class="ps-4 py-3">Sản phẩm</th>
                            <th>Hãng / Model</th>
                            <th>Số lượng tồn</th>
                            <th>Trạng thái kho</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $stock = $product->stock ?? 0;
                                $statusClass = 'success';
                                $statusText = 'Còn hàng';

                                if ($stock <= 0) {
                                    $statusClass = 'danger';
                                    $statusText = 'Hết hàng';
                                } elseif ($stock <= 5) {
                                    $statusClass = 'warning';
                                    $statusText = 'Sắp hết hàng';
                                }
                            @endphp
                            <tr class="product-row" data-category="{{ $product->category_id }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center me-3"
                                            style="width: 48px; height: 48px;">
                                            <i class="fa-solid fa-box-open text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                                            <span class="extra-small text-muted">Mã:
                                                {{ $product->model ?? 'SP-' . str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="small fw-medium text-secondary">{{ $product->brand ?? '—' }}</span></td>
                                <td><span class="fs-5 fw-bold text-dark">{{ $stock }}</span> <small class="text-muted">cái</small></td>
                                <td>
                                    @if($stock <= 0)
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Cần nhập kho</span>
                                    @elseif($stock <= 5)
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Cần nhập thêm</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Kho ổn định</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                        @if ($stock <= 5)
                                            <a href="{{ route('admin.stock') }}" class="btn btn-white btn-sm px-3" title="Nhập hàng ngay">
                                                <i class="fa-solid fa-truck-ramp-box text-danger"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.products') }}?keyword={{ urlencode($product->name) }}"
                                            class="btn btn-white btn-sm px-3" title="Sửa thông tin">
                                            <i class="fa-solid fa-pen-to-square text-primary"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Chưa có sản phẩm nào trong kho.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabs = document.querySelectorAll('#inventoryTabs .nav-link-custom');
                const rows = document.querySelectorAll('.product-row');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Active state
                        tabs.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');

                        const catId = this.getAttribute('data-filter');

                        rows.forEach(row => {
                            if (catId === 'all') {
                                row.style.display = '';
                            } else {
                                if (row.getAttribute('data-category') === catId) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection

<style>
.nav-link-custom {
    padding: 10px 20px;
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 50px;
    margin-right: 5px;
    transition: all 0.2s;
    white-space: nowrap;
}
.nav-link-custom:hover { background: #f1f5f9; color: #0ea5e9; }
.nav-link-custom.active { background: #0ea5e9; color: white; box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.2); }
.product-row { transition: all 0.2s; }
.extra-small { font-size: 0.72rem; }
.btn-white { background: white; border: 1px solid #f1f5f9; }
.btn-white:hover { background: #f8fafc; }
</style>