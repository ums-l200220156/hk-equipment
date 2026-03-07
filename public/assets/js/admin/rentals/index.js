document.addEventListener('DOMContentLoaded', function () {

    /**
     * 1. LIVE SEARCH SYSTEM (MOBILE SAFE FIX)
     */
    const searchInput = document.getElementById('hk-live-search');
    const rows = document.querySelectorAll('.hk-tr-item');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const filter = this.value.toLowerCase();

            rows.forEach(row => {
                const text = (row.getAttribute('data-search') || '').toLowerCase();

                if (text.includes(filter)) {
                    row.classList.remove('hk-hidden');
                } else {
                    row.classList.add('hk-hidden');
                }
            });
        });
    }

    /**
     * 2. PREVIEW UNIT IMAGE
     */
    window.previewUnit = function (url, name) {
        Swal.fire({
            title: name,
            imageUrl: url,
            imageWidth: 400,
            showConfirmButton: false,
            background: '#ffffff',
            padding: '1rem',
            customClass: { title: 'fs-6 fw-bold' }
        });
    };

    /**
     * 3. PREVIEW PROOF IMAGE (STRUK)
     */
    window.previewProof = function (url) {
        Swal.fire({
            title: 'BUKTI PEMBAYARAN SEWA',
            imageUrl: url,
            imageAlt: 'Struk Pembayaran',
            showConfirmButton: false,
            background: '#ffffff',
            width: '600px',
            padding: '1rem',
            customClass: { 
                title: 'fs-6 fw-bold text-warning',
                image: 'rounded shadow-sm'
            }
        });
    };

    /**
     * 4. DELETE CONFIRMATION
     */
    window.confirmDelete = function (form) {
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
    };

});