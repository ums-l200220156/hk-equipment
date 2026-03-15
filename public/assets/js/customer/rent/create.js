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
    const dateInput = document.getElementById('rent_date');
    const errorText = document.querySelector('.time-error');
    const regex = /^([01]\d|2[0-3]):[0-5]\d$/;

    if (timeInput) {
        /* Auto-format saat mengetik */
        timeInput.addEventListener('input', () => {
            let v = timeInput.value.replace(/\D/g, '');

            if (v.length > 4) v = v.slice(0, 4);
            if (v.length >= 3) v = v.slice(0, 2) + ':' + v.slice(2);

            timeInput.value = v;

            errorText?.classList.add('d-none');
            timeInput.classList.remove('is-invalid');
        });

        /* Validasi format */
        timeInput.addEventListener('blur', () => {
            if (timeInput.value && !regex.test(timeInput.value)) {
                errorText?.classList.remove('d-none');
                timeInput.classList.add('is-invalid');
            }
        });
    }


    /* =====================================================
       3. LOGIKA CEK WAKTU LAMPAU
    ===================================================== */
    function isPastTime() {
        if (!dateInput || !timeInput || !timeInput.value) return false;

        const today = new Date().toISOString().split("T")[0];
        if (dateInput.value !== today) return false;

        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();

        const [h, m] = timeInput.value.split(':').map(Number);
        const inputMinutes = h * 60 + m;

        return inputMinutes < currentMinutes;
    }


    /* =====================================================
       4. VALIDASI FORM SAAT SUBMIT
    ===================================================== */
    const form = document.querySelector('.styled-form');
    const submitBtn = document.querySelector('.btn-main-submit');

    if (form) {
        form.addEventListener('submit', (e) => {
            // Cek format jam
            if (!regex.test(timeInput.value)) {
                errorText?.classList.remove('d-none');
                timeInput.classList.add('is-invalid');
                e.preventDefault();
                return;
            }

            // Cek apakah jam sudah lewat
            if (isPastTime()) {
                alert('Jam mulai tidak boleh lebih awal dari waktu sekarang.');
                timeInput.classList.add('is-invalid');
                e.preventDefault();
                return;
            }
        });
    }


    /* =====================================================
       5. SET MIN TIME JIKA TANGGAL = HARI INI
    ===================================================== */
    function updateTimeLimit() {
        if (!dateInput || !timeInput) return;

        const today = new Date().toISOString().split("T")[0];

        if (dateInput.value === today) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2,'0');
            const minutes = String(now.getMinutes()).padStart(2,'0');
            timeInput.min = `${hours}:${minutes}`;
        } else {
            timeInput.min = "00:00";
        }
    }

    if (dateInput) {
        dateInput.addEventListener('change', updateTimeLimit);
        updateTimeLimit();
    }


    /* =====================================================
       6. ANTI-TIKUNG: CEK STATUS ALAT SECARA REALTIME
    ===================================================== */
    const formContainer = document.querySelector('.form-container');

    function checkLiveAvailability() {
        // Pastikan variabel statusUrl didefinisikan di blade
        if (typeof STATUS_URL === 'undefined') return;

        fetch(STATUS_URL)
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'available') {
                    // Kunci tombol submit
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span>Alat Sudah Disewa Orang Lain</span>';
                        submitBtn.style.background = '#6c757d';
                    }

                    // Tampilkan pesan peringatan jika belum ada
                    if (!document.getElementById('live-alert')) {
                        const alertDiv = document.createElement('div');
                        alertDiv.id = 'live-alert';
                        alertDiv.className = 'alert alert-danger border-0 shadow-sm mb-4';
                        alertDiv.style.borderRadius = '12px';
                        alertDiv.innerHTML = `
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Maaf, Anda Keduluan!</strong><br>
                                    Baru saja ada customer lain yang berhasil menyewa alat ini. 
                                    Silakan kembali ke katalog untuk memilih unit lain.
                                    <br><a href="${CATALOG_URL}" class="btn btn-sm btn-danger mt-2 fw-bold text-white shadow-sm">Kembali Ke Katalog</a>
                                </div>
                            </div>
                        `;
                        formContainer.insertBefore(alertDiv, form.previousElementSibling);
                    }
                    // Stop interval karena sudah tidak tersedia
                    clearInterval(availabilityInterval);
                }
            })
            .catch(error => console.error('Gagal mengecek status:', error));
    }

    // Jalankan pengecekan setiap 5 detik
    const availabilityInterval = setInterval(checkLiveAvailability, 5000);

});