@extends('admin.layout')

@section('title', 'Quản lý sản phẩm điện gia dụng')

@section('content')
    <div class="product-wrapper py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Danh sách sản phẩm</h4>
                <p class="text-muted small mb-0">Quản lý thông tin – giá bán – bảo hành</p>
            </div>
            <button class="btn btn-primary rounded-3 px-4 shadow-sm" data-bs-toggle="modal"
                data-bs-target="#createProductModal">
                <i class="fa-solid fa-plus me-2"></i>Thêm sản phẩm
            </button>
        </div>
        {{-- MODAL THÊM SẢN PHẨM --}}
        <div class="modal fade" id="createProductModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- modal-lg để form rộng rãi hơn -->
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.products.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Thêm sản phẩm mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Thông tin chung -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-muted small text-uppercase">Thông tin cơ
                                        bản</label>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Tên sản phẩm <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Ví dụ: Nồi cơm điện Sharp 1.8L" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Danh mục <span
                                            class="text-danger">*</span></label>
                                    {{-- <select name="category" class="form-select" required>
                                        <option value="" selected disabled>-- Chọn danh mục --</option>
                                        <option value="Nồi cơm điện">Nồi cơm điện</option>
                                        <option value="Ấm siêu tốc">Ấm siêu tốc</option>
                                        <option value="Vợt muỗi">Vợt muỗi</option>
                                        <option value="Quạt điện">Quạt điện</option>
                                        <option value="Điều khiển">Điều khiển</option>
                                        <option value="Khác">Khác</option>
                                    </select> --}}
                                    <select name="category_id" class="form-select" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    {{-- <input type="text" name="category_id" class="form-control" required> --}}
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Hãng sản xuất <span
                                            class="text-danger">*</span></label>
                                    {{-- <select name="brand" class="form-select" required>
                                        <option value="" selected disabled>-- Chọn hãng --</option>
                                        <option value="Sharp">Sharp</option>
                                        <option value="Sunhouse">Sunhouse</option>
                                        <option value="Comet">Comet</option>
                                        <option value="Philips">Philips</option>
                                        <option value="Panasonic">Panasonic</option>
                                        <option value="Khác">Khác</option>
                                    </select> --}}
                                    <input type="text" name="brand" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Model / Mã SP</label>
                                    <input type="text" name="model" class="form-control" placeholder="Ví dụ: KS-IH191">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Bảo hành (Tháng)</label>
                                    <div class="input-group">
                                        <input type="number" name="warranty_months" class="form-control" value="12"
                                            min="0">
                                        <span class="input-group-text bg-light text-muted">Tháng</span>
                                    </div>
                                </div>

                                <!-- Giá và Kho -->
                                <div class="col-12 mt-4">
                                    <label class="form-label fw-semibold text-muted small text-uppercase">Thiết lập bán
                                        hàng</label>
                                    <hr class="mt-1 mb-3 text-muted">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Giá bán (VNĐ) <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="price" class="form-control fw-bold text-primary"
                                            placeholder="0" min="0" required>
                                        <span class="input-group-text fw-bold">đ</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Số lượng tồn đầu kỳ <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="stock" class="form-control" value="0" min="0"
                                        required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Mô tả / Ghi chú</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Nhập mô tả chi tiết sản phẩm..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy bỏ</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu sản phẩm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bộ lọc -->
        <div class="card filter-card mb-4 p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Loại sản phẩm</label>
                    <select class="form-select border-light">
                        <option>Tất cả danh mục</option>
                        <option>Nồi cơm điện</option>
                        <option>Ấm siêu tốc</option>
                        <option>Vợt muỗi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Hãng sản xuất</label>
                    <select class="form-select border-light">
                        <option>Tất cả hãng</option>
                        <option>Sharp</option>
                        <option>Sunhouse</option>
                        <option>Comet</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Tìm kiếm nhanh</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 border-light text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 border-light"
                            placeholder="Nhập tên sản phẩm...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100 border-2 fw-bold">
                        Lọc dữ liệu
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="product-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá bán lẻ</th>
                            <th>Bảo hành</th>
                            <th>Trạng thái kho</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <!-- Item 1: Nồi cơm -->
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="product-thumb me-3">
                                            <i class="fa-solid fa-bowl-rice"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                                            <div class="text-muted small"> Hãng: {{ $product->brand }}
                                                @if ($product->model)
                                                    | Model: {{ $product->model }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-secondary">{{ $product->category?->name ?? '—' }}</span></td>
                                <td><span class="fw-bold text-primary">{{ number_format($product->price) }}đ</span></td>
                                <td>{{ $product->warranty_months }} tháng</td>
                                <td>
                                    @if ($product->stock > 5)
                                        <span class="badge bg-success-subtle text-success fw-semibold">
                                            {{ $product->stock }} cái
                                        </span>
                                    @elseif($product->stock > 0)
                                        <span class="badge bg-warning-subtle text-warning fw-semibold">
                                            {{ $product->stock }} cái
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger fw-semibold">
                                            Hết hàng
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                    <button class="btn btn-sm btn-outline-primary me-1 px-3" data-bs-toggle="modal"
                                        data-bs-target="#editProductModal"
                                        onclick="fillEditProduct(1, 'Coca', 10000, 20)">Sửa</button>
                                    {{-- @if ($product->is_active)
                                        <form action="{{ route('admin.products.disable', $product->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-light border text-muted">
                                                Ngừng bán
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary">Ngừng bán</span>
                                    @endif --}}
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDelete({{ $product->id }})">Xóa</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- MODAL THÊM SẢN PHẨM --}}
                            <div class="modal fade" id="editProductModal" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <!-- modal-lg để form rộng rãi hơn -->
                                    <div class="modal-content">
                                        <form method="POST"
                                            action="{{ route('admin.products.update', $product->slug) }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Chỉnh sửa sản phẩm</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <!-- Thông tin chung -->
                                                    <div class="col-12">
                                                        <label
                                                            class="form-label fw-semibold text-muted small text-uppercase">Thông
                                                            tin cơ
                                                            bản</label>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label fw-bold">Tên sản phẩm <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="{{ old('name', $product->name ?? '') }}" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Danh mục <span
                                                                class="text-danger">*</span></label>
                                                        <select name="category_id" class="form-select" required>
                                                            <option value="">-- Chọn danh mục --</option>
                                                            {{-- @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->name }}
                                                                </option>
                                                            @endforeach --}}
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Hãng sản xuất <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="brand" class="form-control"
                                                            value="{{ old('brand', $product->brand ?? '') }}" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Model / Mã SP</label>
                                                        <input type="text" name="model" class="form-control"
                                                            value="{{ old('model', $product->model ?? '') }}">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Bảo hành (Tháng)</label>
                                                        <div class="input-group">
                                                            <input type="number" name="warranty_months"
                                                                class="form-control"
                                                                value="{{ old('warranty_months', $product->warranty_months ?? 0) }}"
                                                                min="0">
                                                            <span class="input-group-text bg-light text-muted">Tháng</span>
                                                        </div>
                                                    </div>

                                                    <!-- Giá và Kho -->
                                                    <div class="col-12 mt-4">
                                                        <label
                                                            class="form-label fw-semibold text-muted small text-uppercase">Thiết
                                                            lập bán
                                                            hàng</label>
                                                        <hr class="mt-1 mb-3 text-muted">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Giá bán (VNĐ) <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="number" name="price"
                                                                class="form-control fw-bold text-primary"
                                                                value="{{ old('price', $product->price ?? 0) }}"
                                                                min="0" required>
                                                            <span class="input-group-text fw-bold">đ</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Số lượng tồn đầu kỳ <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" name="stock" class="form-control"
                                                            value="{{ old('stock', $product->stock ?? 0) }}"
                                                            min="0" required>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Mô tả / Ghi chú</label>
                                                        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-light border"
                                                    data-bs-dismiss="modal">Hủy bỏ</button>
                                                <button type="submit" class="btn btn-primary px-4 fw-bold">
                                                    <i class="fa-solid fa-floppy-disk me-2"></i>Lưu sản phẩm
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Phân trang giả lập) -->
            <div class="p-4 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }}
                    trên tổng {{ $products->total() }} sản phẩm
                </span>

                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
