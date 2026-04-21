@extends('admin.layout')

@section('title', 'Quản lý sản phẩm điện gia dụng')

@section('content')
    <div class="product-wrapper py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 font-luxury text-primary">Danh sách sản phẩm</h4>
                <p class="text-muted small mb-0">Quản lý thông tin – giá bán – bảo hành</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal"
                    data-bs-target="#createCategoryModal">
                    <i class="fa-solid fa-folder-plus me-2"></i>Thêm danh mục
                </button>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal"
                    data-bs-target="#createProductModal">
                    <i class="fa-solid fa-plus me-2"></i>Thêm sản phẩm mới
                </button>
            </div>

        </div>

        <!-- Bộ lọc -->
        <div class="card shadow-sm border-0 rounded-4 mb-4 p-4 bg-white">
            <form method="GET" action="{{ route('admin.products') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label-custom fw-bold small text-uppercase">Loại sản phẩm</label>
                        <select name="category_id" class="form-select border-light shadow-sm">
                            <option value="">Tất cả danh mục</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom fw-bold small text-uppercase">Hãng sản xuất</label>
                        <select name="brand" class="form-select border-light shadow-sm">
                            <option value="">Tất cả hãng</option>
                            @foreach($products->pluck('brand')->unique() as $brand)
                                <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom fw-bold small text-uppercase">Tìm kiếm nhanh</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 border-light text-muted">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="keyword" class="form-control border-start-0 border-light"
                                placeholder="Nhập tên sản phẩm..." value="{{ request('keyword') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                            Lọc dữ liệu
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light">
                        <tr class="small text-uppercase fw-bold text-muted">
                            <th class="ps-4 py-3">Sản phẩm</th>
                            <th class="py-3">Danh mục</th>
                            <th class="py-3">Giá bán lẻ</th>
                            <th class="py-3">Bảo hành</th>
                            <th class="py-3">Kho hàng</th>
                            <th class="text-end pe-4 py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="product-thumb me-3 bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; color: #0ea5e9;">
                                            <i class="fa-solid fa-box-open fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                                            <div class="text-muted extra-small"> Hãng: {{ $product->brand }}
                                                @if ($product->model) | Model: {{ $product->model }} @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-secondary small fw-medium">{{ $product->category?->name ?? '—' }}</span></td>
                                <td><span class="fw-bold text-primary">{{ number_format($product->price) }}đ</span></td>
                                <td><span class="small">{{ $product->warranty_months }} tháng</span></td>
                                <td>
                                    @if ($product->stock > 5)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                            {{ $product->stock }} cái
                                        </span>
                                    @elseif($product->stock > 0)
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">
                                            {{ $product->stock }} cái
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                            Hết hàng
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                    <button class="btn btn-sm btn-outline-primary shadow-sm rounded-pill px-3 me-1" 
                                        onclick="openEditProductModal({{ json_encode($product) }})">
                                        <i class="fas fa-edit me-1"></i> Sửa
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger shadow-sm rounded-pill px-3"
                                        onclick="confirmDelete('{{ route('admin.products.destroy', $product->id) }}', 'Bạn có chắc chắn muốn xóa sản phẩm {{ $product->name }} không?')">
                                        <i class="fas fa-trash me-1"></i> Xóa
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-top d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                <span class="text-muted small">
                    Hiển thị <strong>{{ $products->firstItem() }} - {{ $products->lastItem() }}</strong>
                    trên tổng <strong>{{ $products->total() }}</strong> sản phẩm
                </span>

                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    @include('admin.components.product-modals')
@endsection

<style>
.product-thumb { transition: all 0.2s; }
tr:hover .product-thumb { transform: scale(1.1); background-color: #0ea5e9 !important; color: white !important; }
.extra-small { font-size: 0.72rem; }
.form-label-custom { font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem; display: block; }
</style>
