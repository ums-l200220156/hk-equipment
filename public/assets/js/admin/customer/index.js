document.addEventListener('DOMContentLoaded', function() {
    // 1. LIVE SEARCH
    const searchInput = document.getElementById('hkSearchInput');
    const tableRows = document.querySelectorAll('#hkCustomerTable tbody .hk-tr-item');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();

            tableRows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const email = row.getAttribute('data-email') || '';
                const address = row.getAttribute('data-address') || '';

                if (name.includes(query) || email.includes(query) || address.includes(query)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }
});

/**
 * 2. KONFIRMASI HAPUS (MENGGUNAKAN HELPER ANDA)
 */
function confirmDelete(form, customerName) {
    Swal.fire({
        title: 'Hapus Pelanggan?',
        html: `Apakah Anda yakin ingin menghapus <b>${customerName}</b>? Semua riwayat transaksi mungkin terdampak.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'YA, HAPUS',
        cancelButtonText: 'BATAL',
        background: '#111827',
        color: '#fff',
        borderRadius: '20px'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

/**
 * 3. HELPER DECODE HTML (Untuk notifikasi dari Laravel)
 */
function swalSuccess(message) {
    const parser = new DOMParser();
    const decoded = parser.parseFromString(message, 'text/html').body.textContent;

    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: decoded,
        background: '#111827',
        color: '#fff',
        confirmButtonColor: '#f59e0b',
        timer: 3000,
        timerProgressBar: true
    });
}

/**
 * 4. PREVIEW FOTO CUSTOMER (SweetAlert2)
 */
function previewImage(imageUrl, customerName) {
    Swal.fire({
        title: customerName,
        imageAlt: 'Foto ' + customerName,
        imageUrl: imageUrl,
        imageWidth: 400,
        imageHeight: 400,
        background: '#111827',
        color: '#fff',
        confirmButtonColor: '#f59e0b',
        confirmButtonText: 'TUTUP',
        showClass: {
            popup: 'animate__animated animate__zoomIn'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOut'
        }
    });
}