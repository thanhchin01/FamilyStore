<!-- Update Profile Modal -->
<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold">Cập nhật hồ sơ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-12 text-center mb-3">
                            <div class="position-relative d-inline-block">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" id="previewAvatar" class="rounded-circle border border-4 border-light shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div id="previewAvatarPlaceholder" class="rounded-circle border border-4 border-light shadow-sm d-flex align-items-center justify-content-center bg-primary text-white fw-bold" style="width: 100px; height: 100px; font-size: 2rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; cursor: pointer;">
                                    <i class="fas fa-camera small"></i>
                                </label>
                                <input type="file" id="avatarInput" name="avatar" class="d-none" accept="image/*">
                            </div>
                            <div class="form-text small mt-2">Dung lượng tối đa 2MB (JPG, PNG)</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">HỌ VÀ TÊN</label>
                            <input type="text" name="name" class="form-control rounded-3 py-3 bg-light border-0" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">ĐỊA CHỈ EMAIL</label>
                            <input type="email" name="email" class="form-control rounded-3 py-3 bg-light border-0" value="{{ $user->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">SỐ ĐIỆN THOẠI</label>
                            <input type="text" name="phone" class="form-control rounded-3 py-3 bg-light border-0" value="{{ $user->phone }}" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">GIỚI TÍNH</label>
                            <select name="gender" class="form-select rounded-3 py-3 bg-light border-0">
                                <option value="">Chọn giới tính</option>
                                <option value="male" {{ ($user->gender == 'male') ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ ($user->gender == 'female') ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ ($user->gender == 'other') ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold">NGÀY SINH</label>
                            <input type="date" name="birthday" class="form-control rounded-3 py-3 bg-light border-0" value="{{ $user->birthday }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold">ĐỊA CHỈ THƯỜNG TRÚ</label>
                            <textarea name="address" class="form-control rounded-3 py-3 bg-light border-0" rows="3" placeholder="Ví dụ: Thôn Khoa Quyên, Xã X...">{{ $user->address }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-600" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-600 shadow-sm">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
