document.querySelectorAll('.btn-sewa').forEach(btn => {
    btn.addEventListener('click', e => {

        if (window.isGuest) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Login Diperlukan',
                text: 'Silakan login terlebih dahulu untuk melakukan pemesanan.',
                confirmButtonText: 'Login Sekarang',
                confirmButtonColor: '#dc3545',
                showCloseButton: true
            }).then(res => {
                if (res.isConfirmed) {
                    window.location.href = window.loginUrl;
                }
            });
        }

    });
});
