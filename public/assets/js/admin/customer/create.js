document.addEventListener('DOMContentLoaded', function() {
    
    // 1. LOGIKA TOGGLE PASSWORD (LIHAT/SEMBUNYIKAN)
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            
            // Ubah tipe input
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            // Ubah icon mata
            toggleIcon.classList.toggle('bi-eye-fill');
            toggleIcon.classList.toggle('bi-eye-slash-fill');
        });
    }

    // 2. KONFIRMASI SWEETALERT SAAT SIMPAN
    const form = document.getElementById('hkCustomerForm');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // --- VALIDASI PANJANG PASSWORD ---
            // Memastikan input tidak kosong dan minimal 8 karakter
            if (passwordInput.value.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Keamanan Kurang',
                    text: 'Password wajib memiliki minimal 8 karakter.',
                    confirmButtonColor: '#ef4444',
                    background: '#ffffff',
                    borderRadius: '20px'
                });
                return; // Berhenti di sini, jangan lanjut ke konfirmasi simpan
            }

            // --- KONFIRMASI SIMPAN (JIKA VALIDASI LOLOS) ---
            Swal.fire({
                title: 'Simpan Pelanggan?',
                text: "Pastikan data email dan identitas sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'YA, SIMPAN',
                cancelButtonText: 'CEK KEMBALI',
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