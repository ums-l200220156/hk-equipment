/**
 * Logika Konfirmasi Pembatalan dengan SweetAlert2
 */
function confirmCancel() {
    Swal.fire({
        title: 'Batalkan Penyewaan?',
        text: "Pesanan ini tidak bisa dikembalikan jika sudah dibatalkan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Kembali',
        borderRadius: '15px',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancelForm').submit();
        }
    });
}

/**
 * Konfirmasi Pembatalan Sewa
 */
function confirmCancel() {
    Swal.fire({
        title: 'Batalkan Pesanan?',
        text: "Pesanan yang dibatalkan tidak dapat dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Kembali',
        borderRadius: '15px',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancelForm').submit();
        }
    });
}

/**
 * Konfirmasi Pembatalan Overtime (Lembur)
 */
function confirmCancelOt() {
    Swal.fire({
        title: 'Batalkan Pengajuan Lembur?',
        text: "Anda dapat mengajukan kembali nanti jika diperlukan.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus Pengajuan',
        cancelButtonText: 'Tetap Ajukan',
        borderRadius: '15px',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancelOtForm').submit();
        }
    });
}