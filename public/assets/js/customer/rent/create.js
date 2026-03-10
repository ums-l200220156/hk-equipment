document.addEventListener('DOMContentLoaded', () => {

    /* =====================================================
       1. HITUNG TOTAL HARGA OTOMATIS
    ===================================================== */
    const duration = document.querySelector('[name="duration_hours"]');
    const total = document.getElementById('totalPrice');

    if (duration && total) {
        duration.addEventListener('input', () => {
            const jam = parseInt(duration.value) || 0;
            total.value = 'Rp ' + (jam * PRICE_PER_HOUR).toLocaleString('id-ID');
        });
    }


    /* =====================================================
       2. INPUT JAM MULAI (FORMAT 24 JAM)
    ===================================================== */
    const timeInput = document.getElementById('start_time');
    const errorText = document.querySelector('.time-error');
    const regex = /^([01]\d|2[0-3]):[0-5]\d$/;

    if (!timeInput) return;

    /* Auto-format saat mengetik: 2100 → 21:00 */
    timeInput.addEventListener('input', () => {
        let v = timeInput.value.replace(/\D/g, '');

        if (v.length > 4) v = v.slice(0, 4);
        if (v.length >= 3) v = v.slice(0, 2) + ':' + v.slice(2);

        timeInput.value = v;

        // Reset error saat user mengetik
        errorText?.classList.add('d-none');
        timeInput.classList.remove('is-invalid');
    });

    /* Validasi saat keluar dari field */
    timeInput.addEventListener('blur', () => {
        if (timeInput.value && !regex.test(timeInput.value)) {
            errorText?.classList.remove('d-none');
            timeInput.classList.add('is-invalid');
        }
    });


    /* =====================================================
       3. BLOK SUBMIT JIKA JAM TIDAK VALID
    ===================================================== */
    const form = timeInput.closest('form');

    if (form) {
        form.addEventListener('submit', (e) => {
            if (!regex.test(timeInput.value)) {
                errorText?.classList.remove('d-none');
                timeInput.classList.add('is-invalid');
                e.preventDefault();
            }
        });
    }


    /* =====================================================
       4. BLOK JAM YANG SUDAH LEWAT JIKA TANGGAL = HARI INI
    ===================================================== */
    const dateInput = document.getElementById('rent_date');

    function updateTimeLimit() {

        if (!dateInput) return;

        const today = new Date().toISOString().split("T")[0];

        if (dateInput.value === today) {

            const now = new Date();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            const currentTime = `${hours}:${minutes}`;

            timeInput.min = currentTime;

        } else {

            timeInput.min = "00:00";

        }
    }

    if (dateInput) {
        dateInput.addEventListener('change', updateTimeLimit);
        updateTimeLimit();
    }

});