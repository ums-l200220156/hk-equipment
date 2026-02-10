/**
 * Riwayat Penyewaan JS - Symmetrical Filter & Confirm
 */

function filterStatus(status) {
    const rows = document.querySelectorAll('.rental-row');
    const buttons = document.querySelectorAll('.filter-btn');

    // 1. Update Aktif Button
    buttons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('onclick').includes(`'${status}'`)) {
            btn.classList.add('active');
        }
    });

    // 2. Jalankan Filter Row
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        
        if (status === 'all' || rowStatus === status) {
            // JANGAN menyetel display: table-row atau block secara manual
            // Cukup kosongkan style display agar CSS Media Query yang bekerja
            row.style.display = ""; 
        } else {
            row.style.display = 'none';
        }
    });
}

function confirmCancel(button) {
    const form = button.closest('.cancel-form');
    Swal.fire({
        title: 'Batalkan Sewa?',
        text: "Pesanan yang dibatalkan tidak bisa diaktifkan kembali.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Kembali',
        reverseButtons: true,
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}