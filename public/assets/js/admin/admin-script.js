/**
 * 1. INITIAL STATE CHECK
 * Mencegah efek flicker/kedip saat refresh halaman
 */
(function() {
    const savedState = localStorage.getItem('hk_sidebar_state');
    if (savedState === 'closed') {
        document.body.classList.add('sidebar-closed');
    }
})();


document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const mobileToggle = document.getElementById('mobileToggle');
    const body = document.body;

    /**
     * 2. SIDEBAR DESKTOP TOGGLE
     */
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('sidebar-closed');
            const isClosed = body.classList.contains('sidebar-closed');
            localStorage.setItem('hk_sidebar_state', isClosed ? 'closed' : 'open');
        });
    }

    /**
     * 3. MOBILE MENU TOGGLE
     */
    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            body.classList.toggle('mobile-open');
        });
    }

    /**
     * 4. AUTO-CLOSE MOBILE MENU
     * Menutup sidebar jika pengguna klik di luar menu pada layar mobile
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
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}