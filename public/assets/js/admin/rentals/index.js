/**
 * 1. LIVE SEARCH SYSTEM
 */
document.getElementById('hk-live-search').addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('.hk-tr-item');
    rows.forEach(row => {
        const text = row.getAttribute('data-search');
        row.style.display = text.includes(filter) ? "" : "none";
    });
});

/**
 * 2. PREVIEW UNIT IMAGE
 */
function previewUnit(url, name) {
    Swal.fire({
        title: name,
        imageUrl: url,
        imageWidth: 400,
        showConfirmButton: false,
        background: '#ffffff',
        padding: '1rem',
        customClass: { title: 'fs-6 fw-bold' }
    });
}

/**
 * 3. PREVIEW PROOF IMAGE (STRUK)
 */
function previewProof(url) {
    Swal.fire({
        title: 'BUKTI PEMBAYARAN SEWA',
        imageUrl: url, // URL dari asset('storage/...')
        imageAlt: 'Struk Pembayaran',
        showConfirmButton: false,
        background: '#ffffff',
        width: '600px', // Perkecil sedikit agar proporsional
        padding: '1rem',
        customClass: { 
            title: 'fs-6 fw-bold text-warning',
            image: 'rounded shadow-sm'
        }
    });
}

/**
 * 4. DELETE CONFIRMATION
 */
function confirmDelete(form) {
    Swal.fire({
        title: 'Hapus Transaksi?',
        text: "Data ini akan hilang permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0f172a',
        confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
}