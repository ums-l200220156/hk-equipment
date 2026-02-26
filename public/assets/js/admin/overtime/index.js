/**
 * HK SYSTEM - ADMIN OVERTIME MASTER JS
 * Features: Realtime Timer, Sync Status, Client-side Filtering, & SweetAlert Actions
 */

window.adminIntervals = {}; // Simpan interval per row

function initMasterMonitor() {
    const runningRows = document.querySelectorAll('.hk-ot-row.status-running');

    runningRows.forEach(row => {
        const id = row.dataset.id;
        const startTimeStr = row.dataset.start;
        const ratePerHour = parseFloat(row.dataset.price);
        const ratePerSec = ratePerHour / 3600;

        if (!startTimeStr || isNaN(ratePerHour)) return;

        const startTime = new Date(startTimeStr).getTime();
        const timerEl = document.getElementById(`timer-${id}`);
        const costEl = document.getElementById(`cost-${id}`);

        // 🔥 SIMPAN INTERVAL (PENTING)
        window.adminIntervals[id] = setInterval(() => {
            const now = new Date().getTime();
            const difference = now - startTime;

            if (difference < 0) return;

            const hours = Math.floor(difference / 3600000);
            const minutes = Math.floor((difference % 3600000) / 60000);
            const seconds = Math.floor((difference % 60000) / 1000);

            if (timerEl) {
                timerEl.innerText = 
                    `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
            }

            if (costEl) {
                const totalSeconds = Math.floor(difference / 1000);
                const currentCost = totalSeconds * ratePerSec;
                costEl.innerText = `Rp ${Math.floor(currentCost).toLocaleString('id-ID')}`;
            }
        }, 1000);
    });

    // 🔥 START REALTIME SYNC ADMIN
    startRealtimeAdminSync();
    
    // 🔥 INITIALIZE FILTERING
    initFilteringOT();
}

/**
 * FILTERING TANPA RELOAD (Client-side)
 */
function initFilteringOT() {
    const filterButtons = document.querySelectorAll('.filter-ot-btn');
    const rows = document.querySelectorAll('.hk-ot-row');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const period = btn.dataset.period;

            // Update UI Tombol
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const now = new Date();

            rows.forEach(row => {
                // Ambil data tanggal dari atribut data-date yang ada di <tr>
                const rowDate = new Date(row.dataset.date);
                const diffTime = Math.abs(now - rowDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                let isVisible = false;

                if (period === 'all') {
                    isVisible = true;
                } else if (period === 'weekly' && diffDays <= 7) {
                    isVisible = true;
                } else if (period === 'monthly' && diffDays <= 30) {
                    isVisible = true;
                } else if (period === 'yearly' && diffDays <= 365) {
                    isVisible = true;
                }

                // Efek Transisi Sederhana
                if (isVisible) {
                    row.style.display = ""; 
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
}

/**
 * REALTIME SYNC (Cek apakah customer sudah menekan STOP)
 */
function startRealtimeAdminSync() {
    setInterval(() => {
        document.querySelectorAll('.hk-ot-row').forEach(row => {
            const id = row.dataset.id;
            const currentStatus = row.dataset.status;

            if(currentStatus === 'approved') {
                fetch(`/customer/overtime/${id}/status`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'completed' && currentStatus !== 'completed') {
                            row.dataset.status = 'completed';
                            row.classList.remove('status-running');

                            if (window.adminIntervals[id]) {
                                clearInterval(window.adminIntervals[id]);
                            }

                            const statusCell = row.querySelector('[data-label="Status"]');
                            if (statusCell) {
                                statusCell.innerHTML = `<span class="badge-status completed">Selesai</span>`;
                            }

                            const billingCell = row.querySelector('[data-label="Billing"]');
                            const costEl = document.getElementById(`cost-${id}`);
                            if (billingCell && costEl) {
                                billingCell.innerHTML = `
                                    <div class="hk-billing-final">
                                        <div class="billing-amount text-danger">${costEl.innerText}</div>
                                        <div class="billing-label">TOTAL FINAL</div>
                                    </div>`;
                            }

                            const actionCell = row.querySelector('[data-label="Aksi"]');
                            if (actionCell) {
                                actionCell.querySelectorAll('form').forEach(f => {
                                    if (f.action.includes('stop')) f.remove();
                                });
                            }
                        }
                    })
                    .catch(err => console.error(err));
            }
        });
    }, 3000); 
}

/* ===========================
   GLOBAL ACTION FUNCTIONS
=========================== */

// Preview Struk Pembayaran
function previewProof(url) {
    Swal.fire({
        title: 'BUKTI PEMBAYARAN OVERTIME',
        imageUrl: url,
        imageAlt: 'Struk Overtime',
        showConfirmButton: false,
        width: '600px',
        background: '#ffffff',
        borderRadius: '20px'
    });
}

// Konfirmasi Hapus Data
function confirmDelete(form) {
    Swal.fire({
        title: 'Hapus Data?',
        text: "Data ini akan dihapus permanen dari sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

// Konfirmasi Penolakan Lembur
function confirmReject(form) {
    Swal.fire({
        title: 'Tolak Lembur?',
        text: "Anda akan menolak permintaan lembur dari customer ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

// Jalankan saat halaman siap
document.addEventListener('DOMContentLoaded', initMasterMonitor);