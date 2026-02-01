/**
 * SweetAlert handler khusus halaman payment
 */
document.addEventListener('DOMContentLoaded', function () {

    /* Konfirmasi pembayaran */
    confirmFormSubmit('paymentForm', {
        title: 'Konfirmasi Pembayaran',
        text: 'Pastikan metode pembayaran sudah benar.',
        icon: 'question',
        confirmText: 'Ya, Bayar',
        confirmColor: '#16a34a'
    });

    /* Konfirmasi pembatalan */
    confirmFormSubmit('cancelForm', {
        title: 'Batalkan Pesanan?',
        text: 'Pesanan yang dibatalkan tidak dapat dikembalikan.',
        icon: 'warning',
        confirmText: 'Ya, Batalkan',
        confirmColor: '#dc2626'
    });

});
