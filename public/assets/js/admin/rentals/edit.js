document.addEventListener('DOMContentLoaded', function() {
    const equipmentSelect = document.getElementById('equipmentSelect');
    const durationInput = document.getElementById('durationInput');
    const priceDisplay = document.getElementById('autoTotalPrice');
    const form = document.getElementById('editRentalForm');

    /**
     * 01. FUNGSI KALKULASI HARGA OTOMATIS
     */
    function calculateTotal() {
        const selectedOption = equipmentSelect.options[equipmentSelect.selectedIndex];
        const pricePerHour = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const duration = parseInt(durationInput.value) || 0;
        const total = pricePerHour * duration;

        // Update tampilan harga dengan format ribuan Indonesia
        priceDisplay.innerText = total.toLocaleString('id-ID');
    }

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
            background: '#ffffff'
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
            calculateTotal();
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
});