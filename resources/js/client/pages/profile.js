document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatarInput');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(ex) {
                    const preview = document.getElementById('previewAvatar');
                    if (preview) {
                        preview.src = ex.target.result;
                    } else {
                        const placeholder = document.getElementById('previewAvatarPlaceholder');
                        if (placeholder) {
                            placeholder.innerHTML =
                                `<img src="${ex.target.result}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">`;
                        }
                    }
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
});
