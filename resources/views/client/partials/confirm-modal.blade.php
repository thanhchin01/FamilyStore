{{-- Global Confirmation Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="confirmModalTitle">Xác nhận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0 text-secondary" id="confirmModalMessage">Bạn có chắc chắn muốn thực hiện thao tác này?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-600" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirmModalActionBtn" class="btn btn-danger rounded-pill px-4 fw-600">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
