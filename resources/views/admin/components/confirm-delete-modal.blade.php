<!-- resources/views/admin/components/confirm-delete-modal.blade.php -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="confirmDeleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-triangle-exclamation text-danger fs-1"></i>
                </div>
                <h5 class="fw-bold mb-2">Bạn có chắc chắn muốn xóa?</h5>
                <p class="text-secondary mb-0" id="deleteModalMessage">Hành động này không thể hoàn tác và dữ liệu liên quan sẽ bị mất.</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                <form id="deleteModalForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Đồng ý xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
