/**
 * SweetAlert2 Helper
 * File ini dipakai ulang di semua halaman
 */

/* ===============================
   Konfirmasi submit form
================================ */
function confirmFormSubmit(formId, options = {}) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: options.title || 'Konfirmasi',
            text: options.text || 'Apakah Anda yakin?',
            icon: options.icon || 'question',
            showCancelButton: true,
            confirmButtonText: options.confirmText || 'Ya',
            cancelButtonText: options.cancelText || 'Batal',
            confirmButtonColor: options.confirmColor || '#2563eb'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
}

/* ===============================
   Alert sukses
================================ */
function swalSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: message,
        confirmButtonColor: '#16a34a'
    });
}

/* ===============================
   Alert error
================================ */
function swalError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: message,
        confirmButtonColor: '#dc2626'
    });
}
