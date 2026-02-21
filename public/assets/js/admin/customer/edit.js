document.addEventListener('DOMContentLoaded', function() {
    
    const form = document.getElementById('hkCustomerEditForm');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Data pelanggan akan diperbarui di sistem.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // Warna Success
                cancelButtonColor: '#64748b',
                confirmButtonText: 'YA, PERBARUI',
                cancelButtonText: 'BATAL',
                background: '#ffffff',
                borderRadius: '20px'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
});