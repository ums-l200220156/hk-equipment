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
    // Decode HTML entities agar &quot; menjadi "
    const doc = new DOMParser().parseFromString(message, 'text/html');
    const decodedMessage = doc.documentElement.textContent;

    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: decodedMessage,
        background: '#111827', 
        color: '#fff',
        confirmButtonColor: '#f59e0b',
        timer: 3000,             
        timerProgressBar: true,  // Bar waktu berjalan di bawah alert
        showConfirmButton: true
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
        background: '#111827',
        color: '#fff',
        confirmButtonColor: '#ef4444',
        timer: 4000,             
        timerProgressBar: true
    });
}