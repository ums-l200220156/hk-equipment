document.addEventListener('DOMContentLoaded', function() {
    const equipmentSelect = document.getElementById('equipmentSelect');
    const userSelect = document.getElementById('userSelect');
    const durationInput = document.getElementById('durationInput');
    const pricePreview = document.getElementById('totalPricePreview');
    const form = document.getElementById('createRentalForm');
    const btnReset = document.getElementById('btnReset');
    const notesArea = document.querySelector('textarea[name="notes"]');

    // 01. INISIALISASI SELECT2
    $(userSelect).select2({ placeholder: "-- Ketik Nama atau No. HP Pelanggan --", allowClear: true, width: '100%' });
    $(equipmentSelect).select2({ placeholder: "-- Pilih Unit Tersedia --", allowClear: true, width: '100%' });

    // SUNTIK PLACEHOLDER KE SEARCH BOX
    $(document).on('select2:open', () => {
        const searchField = document.querySelector('.select2-search__field');
        if (searchField) searchField.placeholder = "Ketik di sini untuk mencari...";
    });

    // 02. KALKULASI HARGA LIVE
    function calculateLivePrice() {
        const selectedOption = equipmentSelect.options[equipmentSelect.selectedIndex];
        if (!selectedOption || selectedOption.disabled || selectedOption.value === "") {
            pricePreview.innerText = '0';
            return;
        }
        const pricePerHour = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const duration = parseInt(durationInput.value) || 0;
        const total = pricePerHour * duration;
        pricePreview.innerText = total.toLocaleString('id-ID');
    }

    $(equipmentSelect).on('select2:select select2:unselect', calculateLivePrice);
    durationInput.addEventListener('input', calculateLivePrice);

    // 03. LOGIKA RESET DATA
    btnReset.addEventListener('click', function() {
        setTimeout(() => {
            $(userSelect).val(null).trigger('change');
            $(equipmentSelect).val(null).trigger('change');
            if (notesArea) notesArea.value = '';
            pricePreview.innerText = '0';
            Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: 'Form telah dibersihkan', showConfirmButton: false, timer: 1500 });
        }, 10);
    });

    // 04. KONFIRMASI SIMPAN
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!userSelect.value || !equipmentSelect.value) {
            Swal.fire({ icon: 'error', title: 'Data Belum Lengkap', text: 'Harap pilih Pelanggan dan Unit Alat Berat.', confirmButtonColor: '#f59e0b' });
            return;
        }
        Swal.fire({
            title: 'Konfirmasi Transaksi?',
            text: "Pastikan data penyewaan sudah dicek bersama pelanggan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, SIMPAN SEKARANG',
            cancelButtonText: 'CEK LAGI'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menyimpan Data...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                form.submit();
            }
        });
    });

    // 05. VALIDASI DURASI
    durationInput.addEventListener('change', function() {
        if (this.value < 1 && this.value !== "") {
            this.value = 1;
            calculateLivePrice();
            Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Durasi minimal 1 jam', showConfirmButton: false, timer: 2000 });
        }
    });
});