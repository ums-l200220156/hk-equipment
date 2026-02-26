document.addEventListener('DOMContentLoaded', function () {

    const imgUpload = document.getElementById('imageUpload');
    const imgPreview = document.getElementById('imagePreview');
    const initialPrev = document.getElementById('initialPreview');

    const cropModalEl = document.getElementById('cropModal');
    const cropImage = document.getElementById('cropImage');
    const cropBtn = document.getElementById('cropBtn');

    let cropper = null;
    let cropModal = null;

    // INIT MODAL (SAFE)
    if (cropModalEl && typeof bootstrap !== 'undefined') {
        cropModal = new bootstrap.Modal(cropModalEl);
    }

    /* =========================================
       1. IMAGE SELECT → VALIDATION + CROPPER
    ========================================= */
    if (imgUpload && cropImage && cropModal) {
        imgUpload.addEventListener('change', function () {

            const file = this.files[0];
            if (!file) return;

            // VALIDASI TIPE FILE
            if (!file.type.startsWith('image/')) {
                showError('File Tidak Valid', 'Hanya file gambar yang diperbolehkan');
                this.value = "";
                return;
            }

            // VALIDASI SIZE (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showError('File Terlalu Besar', 'Maksimal ukuran foto adalah 2MB');
                this.value = "";
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                cropImage.src = e.target.result;
                cropModal.show();

                setTimeout(() => {
                    try {
                        if (cropper) {
                            cropper.destroy();
                            cropper = null;
                        }

                        cropper = new Cropper(cropImage, {
                            aspectRatio: 1,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 1,
                            responsive: true,
                            background: false,
                        });
                    } catch (err) {
                        console.error('Cropper init error:', err);
                    }
                }, 200);
            };

            reader.readAsDataURL(file);
        });
    }

    /* =========================================
       2. CROP ACTION (IMPROVED QUALITY)
    ========================================= */
    if (cropBtn && imgUpload) {
        cropBtn.addEventListener('click', function () {

            if (!cropper) return;

            try {
                // 🔥 IMPROVEMENT DI SINI
                const canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500,
                    imageSmoothingQuality: 'high',
                });

                if (!canvas) return;

                canvas.toBlob((blob) => {
                    if (!blob) {
                        showError('Gagal Crop', 'Terjadi kesalahan saat memproses gambar');
                        return;
                    }

                    const url = URL.createObjectURL(blob);

                    // UPDATE PREVIEW
                    if (imgPreview) {
                        imgPreview.src = url;
                        imgPreview.style.display = 'block';
                    }

                    if (initialPrev) {
                        initialPrev.style.display = 'none';
                    }

                    // REPLACE FILE INPUT (SAFE)
                    try {
                        const file = new File([blob], "avatar.jpg", { type: "image/jpeg" });

                        if (typeof DataTransfer !== 'undefined') {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            imgUpload.files = dataTransfer.files;
                        } else {
                            console.warn('DataTransfer not supported, fallback used');
                        }
                    } catch (err) {
                        console.warn('File replace error:', err);
                    }

                    cropModal.hide();

                }, 'image/jpeg', 0.9);

            } catch (err) {
                console.error('Crop error:', err);
            }
        });
    }

    /* =========================================
       3. DESTROY CROPPER ON MODAL CLOSE
    ========================================= */
    if (cropModalEl) {
        cropModalEl.addEventListener('hidden.bs.modal', () => {
            if (cropper) {
                try {
                    cropper.destroy();
                } catch (e) {}
                cropper = null;
            }
        });
    }

    /* =========================================
       4. FORM CONFIRMATION (SAFE)
    ========================================= */
    const handleFormSubmit = (formId, title, text) => {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (typeof Swal === 'undefined') {
                form.submit();
                return;
            }

            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ff416c',
                cancelButtonColor: '#2d2d3a',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                background: '#1e1e26',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => Swal.showLoading(),
                        background: '#1e1e26',
                        color: '#fff'
                    });
                    form.submit();
                }
            });
        });
    };

    handleFormSubmit('formUpdateProfile', 'Update Profil?', 'Data pribadi Anda akan diperbarui.');
    handleFormSubmit('formUpdatePassword', 'Ganti Password?', 'Pastikan Anda mengingat password baru Anda.');

    /* =========================================
       5. INPUT INTERACTION (SAFE)
    ========================================= */
    const inputs = document.querySelectorAll('.input-wrapper input, .input-wrapper textarea');

    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            if (input.parentElement) {
                input.parentElement.style.borderColor = '#ff416c';
            }
        });

        input.addEventListener('blur', () => {
            if (input.parentElement) {
                input.parentElement.style.borderColor = 'rgba(255, 255, 255, 0.1)';
            }
        });
    });

    /* =========================================
       UTIL: ERROR HANDLER (NO CRASH)
    ========================================= */
    function showError(title, text) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: text,
                background: '#1e1e26',
                color: '#fff'
            });
        } else {
            alert(title + ' - ' + text);
        }
    }

});


/* =========================================
   6. TOGGLE PASSWORD VISIBILITY
========================================= */
const toggleButtons = document.querySelectorAll('.toggle-password');

toggleButtons.forEach(btn => {
    btn.addEventListener('click', function () {
        const input = this.parentElement.querySelector('input');

        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            this.classList.remove('bi-eye-slash');
            this.classList.add('bi-eye');
        } else {
            input.type = 'password';
            this.classList.remove('bi-eye');
            this.classList.add('bi-eye-slash');
        }
    });
});