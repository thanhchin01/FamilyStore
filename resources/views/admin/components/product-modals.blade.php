{{-- MODAL THÊM DANH MỤC --}}
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold">Tạo danh mục sản phẩm</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Ví dụ: Đồ gia dụng, Điện tử..." required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Lưu danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL THÊM SẢN PHẨM --}}

<div class="modal fade" id="createProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold">Thêm sản phẩm mới</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="Ví dụ: Nồi cơm điện Sharp 1.8L" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select rounded-3" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hãng sản xuất <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control rounded-3" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Model / Mã SP</label>
                            <input type="text" name="model" class="form-control rounded-3" placeholder="Ví dụ: KS-IH191">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bảo hành (Tháng)</label>
                            <div class="input-group">
                                <input type="number" name="warranty_months" class="form-control rounded-start-3" value="12" min="0">
                                <span class="input-group-text bg-light text-muted">Tháng</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="price" class="form-control fw-bold text-primary rounded-start-3 money-input" placeholder="0" required>
                                <span class="input-group-text fw-bold">đ</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control rounded-3" value="1" min="0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Hình ảnh sản phẩm</label>
                            <input type="file" name="image" class="form-control rounded-3" accept="image/*" onchange="previewImage(this, 'create_image_preview')">
                            <div class="mt-2" id="create_image_preview" style="display: none;">
                                <img src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Mô tả chi tiết</label>
                            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Nhập mô tả sản phẩm..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i>LƯU SẢN PHẨM
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL SỬA SẢN PHẨM (Dùng chung 1 Modal duy nhất) --}}
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header bg-warning text-dark py-3">
                    <h5 class="modal-title fw-bold">Chỉnh sửa thông tin sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_product_name" class="form-control rounded-3" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" id="edit_product_category" class="form-select rounded-3" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hãng sản xuất <span class="text-danger">*</span></label>
                            <input type="text" name="brand" id="edit_product_brand" class="form-control rounded-3" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Model</label>
                            <input type="text" name="model" id="edit_product_model" class="form-control rounded-3">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bảo hành</label>
                            <div class="input-group">
                                <input type="number" name="warranty_months" id="edit_product_warranty" class="form-control rounded-start-3" min="0">
                                <span class="input-group-text">Tháng</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary">Giá bán (đ)</label>
                            <input type="text" name="price" id="edit_product_price" class="form-control fw-bold text-primary rounded-3 money-input" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số dư kho</label>
                            <input type="number" name="stock" id="edit_product_stock" class="form-control rounded-3" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Thay đổi hình ảnh</label>
                            <input type="file" name="image" class="form-control rounded-3" accept="image/*" onchange="previewImage(this, 'edit_image_preview_box')">
                            <div class="mt-2" id="edit_image_preview_container">
                                <img id="edit_image_preview_box" src="" alt="Current Image" class="img-thumbnail" style="max-height: 150px; display: none;">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Mô tả sản phẩm</label>
                            <textarea name="description" id="edit_product_description" class="form-control rounded-3" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold shadow-sm">
                        CẬP NHẬT THÔNG TIN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditProductModal(data) {
        // Cập nhật Action của Form
        document.getElementById('editProductForm').action = `{{ url('admin/products') }}/${data.slug}`;
        
        // Điền dữ liệu vào form
        document.getElementById('edit_product_name').value = data.name;
        document.getElementById('edit_product_category').value = data.category_id;
        document.getElementById('edit_product_brand').value = data.brand;
        document.getElementById('edit_product_model').value = data.model || '';
        document.getElementById('edit_product_warranty').value = data.warranty_months;
        document.getElementById('edit_product_price').value = formatCurrency(data.price);
        document.getElementById('edit_product_stock').value = data.stock;
        document.getElementById('edit_product_description').value = data.description || '';

        // Xử lý preview ảnh cũ
        const previewBox = document.getElementById('edit_image_preview_box');
        if (data.image) {
            previewBox.src = `{{ asset('storage') }}/${data.image}`;
            previewBox.style.display = 'block';
        } else {
            previewBox.src = '';
            previewBox.style.display = 'none';
        }

        // Mở Modal
        new bootstrap.Modal(document.getElementById('editProductModal')).show();
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const container = preview.parentElement;
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (container.id === 'create_image_preview') container.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
