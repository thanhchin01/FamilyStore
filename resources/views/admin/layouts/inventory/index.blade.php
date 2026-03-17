@extends('admin.layout')

@section('title', 'Quản lý kho hàng')

@section('content')
    <div class="inventory-wrapper py-4">
        @php
            $countOutOfStock = $products->where('stock_quantity', '<=', 0)->count();
            $countLowStock = $products->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5)->count();
            $countInStock = $products->where('stock_quantity', '>', 5)->count();
        @endphp
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Kho hàng điện gia dụng</h4>
                <p class="text-muted small mb-0">Phân loại:
                    <span class="badge bg-danger">Hết ({{ $countOutOfStock }})</span>
                    <span class="badge bg-warning text-dark">Sắp hết ({{ $countLowStock }})</span>
                    <span class="badge bg-success">Ổn định ({{ $countInStock }})</span>
                </p>
            </div>
            <a href="{{ route('admin.products') }}" class="btn btn-primary rounded-3">
                <i class="fa-solid fa-plus me-2"></i>Thêm SP mới
            </a>
        </div>

        <!-- Tối ưu xem từng sản phẩm bằng Tabs -->
        <div class="inventory-tabs mb-4 shadow-sm rounded-3">
            <a href="#" class="tab-item active" data-filter="all">Tất cả</a>
            @foreach ($categories as $category)
                <a href="#" class="tab-item" data-filter="{{ $category->id }}">{{ $category->name }}</a>
            @endforeach
        </div>

        <!-- Inventory Table -->
        <div class="inventory-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="inventoryTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Hãng / Model</th>
                            <th>Tổng tồn</th>
                            <th>Cảnh báo kho</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $stock = $product->stock_quantity ?? 0;
                                $statusClass = 'success';
                                $statusText = 'Còn hàng';
                                $signalClass = 'signal-success';

                                if ($stock <= 0) {
                                    $statusClass = 'danger';
                                    $statusText = 'Hết hàng';
                                    $signalClass = 'signal-danger';
                                } elseif ($stock <= 5) {
                                    $statusClass = 'warning';
                                    $statusText = 'Sắp hết hàng';
                                    $signalClass = 'signal-warning';
                                }
                            @endphp
                            <tr class="product-row" data-category="{{ $product->category_id }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-3 p-2 me-3"
                                            style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                    style="max-width: 100%; max-height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fa-solid fa-box text-primary"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                                            <span class="text-muted small">Mã:
                                                SP-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->brand ?? '—' }}</td>
                                <td><span class="fs-5 fw-bold">{{ $stock }}</span> cái</td>
                                <td>
                                    <ul class="stock-indicator-list">
                                        <li>
                                            <span class="status-signal {{ $signalClass }}"></span>
                                            <span class="stock-label text-{{ $statusClass }}">{{ $statusText }}</span>
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-end pe-4">
                                    @if ($stock <= 0)
                                        <a href="{{ route('admin.stock') }}" class="btn btn-sm btn-danger me-1">Nhập
                                            hàng</a>
                                    @elseif($stock <= 5)
                                        <a href="{{ route('admin.stock') }}"
                                            class="btn btn-sm btn-outline-warning text-dark border-warning me-1">Nhập
                                            thêm</a>
                                    @else
                                        <button class="btn btn-sm btn-outline-primary me-1">Chi tiết</button>
                                    @endif
                                    <a href="{{ route('admin.products') }}?keyword={{ urlencode($product->name) }}"
                                        class="btn btn-sm btn-light border"><i class="fa-solid fa-pen-to-square"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Chưa có sản phẩm nào trong kho.</td>
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
                const tabs = document.querySelectorAll('.inventory-tabs .tab-item');
                const rows = document.querySelectorAll('.product-row');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Remove active from all
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