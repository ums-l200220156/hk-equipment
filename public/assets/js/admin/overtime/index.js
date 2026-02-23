/**
 * REALTIME ADMIN OVERTIME (FINAL - NO RELOAD)
 */

window.adminIntervals = {}; // simpan interval per row

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
}

/* =========================
   REALTIME SYNC (AUTO STOP)
========================= */
function startRealtimeAdminSync() {

    setInterval(() => {

        document.querySelectorAll('.hk-ot-row').forEach(row => {

            const id = row.dataset.id;
            const currentStatus = row.dataset.status;

            fetch(`/customer/overtime/${id}/status`)
                .then(res => res.json())
                .then(data => {

                    // 🔥 JIKA CUSTOMER SUDAH STOP
                    if (data.status === 'completed' && currentStatus !== 'completed') {

                        row.dataset.status = 'completed';
                        row.classList.remove('status-running');

                        /* =========================
                           STOP TIMER
                        ========================= */
                        if (window.adminIntervals[id]) {
                            clearInterval(window.adminIntervals[id]);
                        }

                        /* =========================
                           UPDATE STATUS UI
                        ========================= */
                        const statusCell = row.querySelector('[data-label="Status"]');
                        if (statusCell) {
                            statusCell.innerHTML =
                                `<span class="badge-status completed">Selesai</span>`;
                        }

                        /* =========================
                           UPDATE BILLING FINAL
                        ========================= */
                        const billingCell = row.querySelector('[data-label="Billing"]');
                        const costEl = document.getElementById(`cost-${id}`);

                        if (billingCell && costEl) {
                            billingCell.innerHTML = `
                                <div class="hk-billing-final">
                                    <div class="billing-amount text-danger">
                                        ${costEl.innerText}
                                    </div>
                                    <div class="billing-label">TOTAL FINAL</div>
                                </div>
                            `;
                        }

                        /* =========================
                           REMOVE STOP BUTTON
                        ========================= */
                        const actionCell = row.querySelector('[data-label="Aksi"]');
                        if (actionCell) {
                            actionCell.querySelectorAll('form').forEach(f => {
                                if (f.action.includes('stop')) {
                                    f.remove();
                                }
                            });
                        }

                    }

                })
                .catch(err => console.error(err));

        });

    }, 3000); // tiap 3 detik
}


/* ===========================
   GLOBAL FUNCTIONS
=========================== */

// ✅ PREVIEW STRUK
function previewProof(url) {
    Swal.fire({
        title: 'BUKTI PEMBAYARAN OVERTIME',
        imageUrl: url,
        imageAlt: 'Struk Overtime',
        showConfirmButton: false,
        width: '600px'
    });
}

// ✅ DELETE CONFIRM
function confirmDelete(form) {
    Swal.fire({
        title: 'Hapus Data?',
        text: "Data ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

// INIT
document.addEventListener('DOMContentLoaded', initMasterMonitor);