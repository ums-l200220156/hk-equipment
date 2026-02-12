document.addEventListener('DOMContentLoaded', function() {
    const equipmentSelect = document.getElementById('equipmentSelect');
    const durationInput = document.getElementById('durationInput');
    const priceDisplay = document.getElementById('autoTotalPrice');
    const form = document.getElementById('editRentalForm');

    /**
     * 01. FUNGSI KALKULASI HARGA OTOMATIS
     */
    function calculateTotal() {
        // Ambil harga dari atribut data-price pada option yang dipilih
        const selectedOption = equipmentSelect.options[equipmentSelect.selectedIndex];
        const pricePerHour = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const duration = parseInt(durationInput.value) || 0;

        const total = pricePerHour * duration;

        // Update tampilan harga dengan format ribuan Indonesia
        priceDisplay.innerText = total.toLocaleString('id-ID');
    }

    // Jalankan kalkulasi saat durasi diubah atau alat diganti
    durationInput.addEventListener('input', calculateTotal);
    equipmentSelect.addEventListener('change', calculateTotal);

    /**
     * 02. KONFIRMASI SIMPAN DATA (SWEETALERT2)
     */
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi Perubahan?',
            text: "Pastikan data sewa sudah sesuai dengan permintaan pelanggan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, UPDATE SEKARANG',
            cancelButtonText: 'CEK KEMBALI',
            background: '#ffffff',
            customClass: {
                title: 'fw-bold',
                confirmButton: 'rounded-pill px-4 py-2',
                cancelButton: 'rounded-pill px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Data...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                form.submit();
            }
        });
    });

    /**
     * 03. VALIDASI REAL-TIME DURASI
     */
    durationInput.addEventListener('change', function() {
        if (this.value < 1) {
            this.value = 1;
            calculateTotal(); // Update harga kembali ke 1 jam
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Durasi minimal 1 jam',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });

    /**
     * 04. ANIMASI INPUT FOKUS
     */
    document.querySelectorAll('.hk-input, .hk-input-warning').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('is-focused');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('is-focused');
        });
    });
});