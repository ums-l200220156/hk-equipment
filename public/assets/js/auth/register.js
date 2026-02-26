    /**
     * =========================================
     * 1. TOGGLE PASSWORD (LEGACY SUPPORT)
     * =========================================
     */
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (!input || !icon) return;

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        }
    }

    /**
     * =========================================
     * 2. TOGGLE PASSWORD (MODERN - AUTO DETECT)
     * =========================================
     */
    document.addEventListener('DOMContentLoaded', function () {

        const toggles = document.querySelectorAll('.hk-toggle-password');

        toggles.forEach(toggle => {
            toggle.addEventListener('click', function () {

                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (!input || !icon) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });
        });

    });


    /**
     * =========================================
     * 3. PREVIEW IMAGE
     * =========================================
     */
    function previewImage(input) {
        const preview = document.getElementById('profilePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="animate__animated animate__zoomIn">`;
                preview.style.borderColor = '#f59e0b';
                preview.style.background = '#fff';
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


    /**
     * =========================================
     * 4. INPUT INTERACTION (SAFE DOM READY)
     * =========================================
     */
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.hk-input').forEach(input => {
            input.addEventListener('focus', () => {
                const icon = input.parentElement.querySelector('i:first-child');
                if(icon) icon.style.color = '#f59e0b';
            });

            input.addEventListener('blur', () => {
                const icon = input.parentElement.querySelector('i:first-child');
                if(icon) icon.style.color = '#94a3b8';
            });
        });

    });


    /**
     * =========================================
     * 5. IMAGE CROP FEATURE (REGISTER)
     * =========================================
     */
    document.addEventListener('DOMContentLoaded', function () {

        const input = document.getElementById('image');
        const preview = document.getElementById('profilePreview');
        const cropImage = document.getElementById('cropImage');
        const cropBtn = document.getElementById('cropBtn');
        const modalEl = document.getElementById('cropModal');

        let cropper;
        let modal;

        if (modalEl && typeof bootstrap !== 'undefined') {
            modal = new bootstrap.Modal(modalEl);
        }

        if (input) {
            input.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                // VALIDASI
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar');
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('Maksimal ukuran 2MB');
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (e) {
                    cropImage.src = e.target.result;
                    modal.show();

                    setTimeout(() => {
                        if (cropper) cropper.destroy();

                        cropper = new Cropper(cropImage, {
                            aspectRatio: 1,
                            viewMode: 1,
                            autoCropArea: 1,
                            responsive: true,
                        });
                    }, 200);
                };

                reader.readAsDataURL(file);
            });
        }

        // ACTION CROP
        if (cropBtn) {
            cropBtn.addEventListener('click', function () {
                if (!cropper) return;

                const canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500
                });

                canvas.toBlob((blob) => {
                    const url = URL.createObjectURL(blob);

                    // preview
                    preview.innerHTML = `<img src="${url}" class="animate__animated animate__zoomIn">`;

                    // replace file input
                    const file = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;

                    modal.hide();
                }, 'image/jpeg', 0.9);
            });
        }

        // destroy cropper saat modal ditutup
        modalEl?.addEventListener('hidden.bs.modal', () => {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

    });