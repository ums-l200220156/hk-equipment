document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarNav = document.querySelector('.sidebar-nav');
    const toggleBtn = document.getElementById('sidebarToggle');
    const mobileToggle = document.getElementById('mobileToggle');
    const html = document.documentElement; // Gunakan HTML level untuk class toggling
    const body = document.body;

    /**
     * 1. SIDEBAR DESKTOP TOGGLE
     * Mengubah class pada elemen HTML agar sinkron dengan pengecekan di HEAD
     */
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            html.classList.toggle('sidebar-closed');
            const isClosed = html.classList.contains('sidebar-closed');
            localStorage.setItem('hk_sidebar_state', isClosed ? 'closed' : 'open');
        });
    }

    /**
     * 2. SMART SCROLL POSITION
     * Mengingat posisi scroll sidebar dan memastikan menu aktif terlihat
     */
    if (sidebarNav) {
        // Kembalikan posisi scroll terakhir
        const savedScroll = localStorage.getItem('hk_sidebar_scroll');
        if (savedScroll) {
            sidebarNav.scrollTop = savedScroll;
        }

        // Simpan posisi scroll saat pengguna melakukan scroll
        sidebarNav.addEventListener('scroll', () => {
            localStorage.setItem('hk_sidebar_scroll', sidebarNav.scrollTop);
        });

        // Auto-scroll ke menu aktif agar tidak tertutup jika di posisi bawah
        const activeLink = document.querySelector('.nav-link.active');
        if (activeLink) {
            activeLink.scrollIntoView({ behavior: 'auto', block: 'nearest' });
        }
    }

    /**
     * 3. MOBILE MENU TOGGLE
     */
    if (mobileToggle) {
        mobileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            body.classList.toggle('mobile-open');
        });
    }

    /**
     * 4. AUTO-CLOSE MOBILE MENU
     */
    document.addEventListener('click', (e) => {
        if (body.classList.contains('mobile-open') && 
            !sidebar.contains(e.target) && 
            !mobileToggle.contains(e.target)) {
            body.classList.remove('mobile-open');
        }
    });
});

/**
 * 5. LOGOUT CONFIRMATION (SWEETALERT2)
 */
function confirmLogout() {
    Swal.fire({
        title: 'Konfirmasi Keluar',
        text: "Apakah Anda yakin ingin keluar dari sistem HK ADMIN?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}