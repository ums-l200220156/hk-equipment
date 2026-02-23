/**
 * NAVBAR INTERACTION LOGIC - HK EQUIPMENT
 */
document.addEventListener("DOMContentLoaded", () => {
    const navbar = document.querySelector('.navbar');
    const dropdowns = document.querySelectorAll('.dropdown');
    
    // 1. Scroll Effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('shadow-lg');
            navbar.style.padding = "8px 0";
        } else {
            navbar.classList.remove('shadow-lg');
            navbar.style.padding = "15px 0";
        }
    });

    // 2. Dropdown Animation Trigger
    dropdowns.forEach(dd => {
        dd.addEventListener('show.bs.dropdown', function () {
            const menu = this.querySelector('.custom-dropdown-animate');
            if(window.innerWidth > 991) {
                menu.classList.add('animate__animated', 'animate__fadeInUp', 'animate__faster');
            }
        });
    });

    // 3. Handle Active Link Logic
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    /**
 * CONFIRM LOGOUT WITH SWEETALERT2
 */
window.confirmLogout = function() {
    Swal.fire({
        title: 'Ingin Keluar?',
        text: "Pastikan semua pesanan sewa Anda sudah selesai.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545', // Warna merah HK Equipment
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Keluar!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        borderRadius: '15px',
        customClass: {
            popup: 'rounded-4 shadow-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Jalankan form logout jika dikonfirmasi
            document.getElementById('logout-form').submit();
        }
    });
}
});