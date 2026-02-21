/**
 * HK FINANCE CONTROL SYSTEM
 */

function confirmDelete(form) {
    Swal.fire({
        title: 'Hapus Log Keuangan?',
        text: "Catatan pengeluaran ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#0f172a',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        borderRadius: '25px',
        background: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

// Format input angka saat diketik (Opsional jika ingin tampilan Rp langsung)
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.querySelector('input[name="amount"]');
    if(amountInput) {
        amountInput.addEventListener('input', function(e) {
            // Logika tambahan jika diperlukan untuk formatting currency
        });
    }
});