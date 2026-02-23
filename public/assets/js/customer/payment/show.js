/**
 * PAYMENT SHOW PAGE LOGIC - HK EQUIPMENT
 */

function handleCancelOrder() {
    Swal.fire({
        title: 'Batalkan Pesanan?',
        text: "Tindakan ini tidak bisa dibatalkan dan alat akan tersedia untuk orang lain.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545', // Merah HK
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Kembali',
        reverseButtons: true,
        borderRadius: '15px',
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Animasi loading saat proses batal
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            // Submit form
            document.getElementById('cancelForm').submit();
        }
    });
}

// Tambahan: Konfirmasi saat pilih metode pembayaran dan tekan tombol bayar
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const method = document.querySelector('input[name="payment_method"]:checked').value;
    if (method === 'transfer') {
        // Biarkan form jalan ke halaman instruksi transfer
    } else {
        // Jika cash, bisa diberi loading sebentar
        Swal.fire({
            title: 'Memproses Pesanan...',
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
});