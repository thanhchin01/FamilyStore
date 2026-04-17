@extends('client.layout')

@section('title', 'Tất cả sản phẩm - K-Q Store')

@section('content')
    <div class="container mb-5 py-5">
        <div class="row g-5">
            <!-- Premium Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="glass-panel p-4 sticky-top shadow-md border-0" style="top: 110px;">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-sliders-h text-primary-color me-2"></i>
                        <h5 class="fw-bold mb-0">Bộ lọc thông minh</h5>
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label fw-800 extra-small text-uppercase tracking-wider mb-3">Danh mục hàng</label>
                        <div class="form-check custom-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="cat1" checked>
                            <label class="form-check-label small fw-500" for="cat1">Tất cả danh mục</label>
                        </div>
                        <div class="form-check custom-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="cat2">
                            <label class="form-check-label small fw-500" for="cat2">Điện lạnh - Điều hòa</label>
                        </div>
                        <div class="form-check custom-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="cat3">
                            <label class="form-check-label small fw-500" for="cat3">Tivi & Nghe nhìn</label>
                        </div>
                        <div class="form-check custom-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="cat4">
                            <label class="form-check-label small fw-500" for="cat4">Gia dụng cao cấp</label>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-800 extra-small text-uppercase tracking-wider mb-3">Mức giá ưu tiên</label>
                        <select class="form-select border-light border-2 rounded-3 py-2 small shadow-sm">
                            <option selected>Mọi mức giá</option>
                            <option value="1">Dưới 5.000.000đ</option>
                            <option value="2">5 - 15 triệu</option>
                            <option value="3">15 - 30 triệu</option>
                            <option value="4">Trên 30 triệu</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-800 extra-small text-uppercase tracking-wider mb-3">Thương hiệu</label>
                        <div class="brands-grid d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark py-2 px-3 border border-light-subtle rounded-3" style="cursor: pointer;">Panasonic</span>
                            <span class="badge bg-light text-dark py-2 px-3 border border-light-subtle rounded-3" style="cursor: pointer;">Samsung</span>
                            <span class="badge bg-light text-dark py-2 px-3 border border-light-subtle rounded-3" style="cursor: pointer;">Bosch</span>
                            <span class="badge bg-light text-dark py-2 px-3 border border-light-subtle rounded-3" style="cursor: pointer;">Dyson</span>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button class="btn btn-primary-custom py-3">ÁP DỤNG LỌC</button>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
                    <div>
                        <h2 class="fw-800 display-6 mb-1">Cửa hàng</h2>
                        <p class="text-muted small mb-0">Hiển thị 8 trong số 124 sản phẩm cao cấp</p>
                    </div>
                    <div class="d-flex gap-3 align-items-center bg-white p-2 rounded-4 shadow-sm border border-light">
                        <span class="small text-muted ps-2"><i class="fas fa-sort-amount-down me-2"></i>Sắp xếp:</span>
                        <select class="form-select border-0 bg-transparent shadow-none w-auto fw-600 extra-small" style="cursor: pointer;">
                            <option selected>MỚI NHẤT</option>
                            <option value="1">GIÁ THẤP ĐẾN CAO</option>
                            <option value="2">GIÁ CAO ĐẾN THẤP</option>
                            <option value="3">XEM NHIỀU NHẤT</option>
                        </select>
                    </div>
                </div>

                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-6 col-md-4">
                            <div class="product-card">
                                <div class="product-img-wrapper">
                                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                                    <div class="product-actions">
                                        <button class="btn btn-md btn-white rounded-circle shadow-md p-2"><i class="far fa-heart text-danger"></i></button>
                                        <a href="{{ route('client.products.show', $product['slug']) }}" class="btn btn-md btn-white rounded-circle shadow-md p-2"><i class="fas fa-expand text-primary-color"></i></a>
                                    </div>
                                </div>
                                <div class="product-details text-center">
                                    <span class="category-label">LỰA CHỌN CAO CẤP</span>
                                    <h5 class="fw-bold mb-2 h6"><a href="{{ route('client.products.show', $product['slug']) }}" class="text-dark text-decoration-none">{{ $product['name'] }}</a></h5>
                                    <div class="product-price mt-3">
                                        {{ number_format($product['price']) }}đ
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-5 text-center bg-white rounded-5 shadow-sm">
                            <i class="fas fa-box-open fa-3x text-light mb-4"></i>
                            <h4 class="fw-bold">Rất tiếc!</h4>
                            <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp với bộ lọc của bạn.</p>
                            <a href="{{ route('client.products.index') }}" class="btn btn-primary-custom mt-3">XEM TẤT CẢ SẢN PHẨM</a>
                        </div>
                    @endforelse
                </div>

                <!-- Premium Pagination -->
                <nav class="mt-7">
                    <ul class="pagination pagination-luxury justify-content-center border-0 gap-2">
                        <li class="page-item disabled"><a class="page-link shadow-sm border-0" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link shadow-md border-0" href="#">1</a></li>
                        <li class="page-item"><a class="page-link shadow-sm border-0" href="#">2</a></li>
                        <li class="page-item"><a class="page-link shadow-sm border-0" href="#">3</a></li>
                        <li class="page-item"><a class="page-link shadow-sm border-0" href="#"><i class="fas fa-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    
    <style>
        .fw-800 { font-weight: 800; }
        .fw-700 { font-weight: 700; }
        .fw-600 { font-weight: 600; }
        .fw-500 { font-weight: 500; }
        .text-primary-color { color: #009688; }
        .extra-small { font-size: 0.7rem; }
        .tracking-wider { letter-spacing: 0.05em; }
        .mt-7 { margin-top: 5rem; }
        
        .custom-check .form-check-input {
            width: 1.25em;
            height: 1.25em;
            border-width: 2px;
            cursor: pointer;
        }
        
        .custom-check .form-check-input:checked {
            background-color: #009688;
            border-color: #009688;
        }
        
        .pagination-luxury .page-link {
            width: 45px;
            height: 45px;
            border-radius: 12px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            color: #1e293b;
            font-weight: 600;
        }
        
        .pagination-luxury .page-item.active .page-link {
            background-color: #009688;
            color: white;
        }
        
        .btn-white {
            background-color: white;
            color: #333;
        }
        
        .btn-white:hover {
            background-color: #f8fafc;
        }
        
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
    </style>
@endsection
