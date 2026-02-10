/**
 * ==========================================================================
 * MASTER OVERTIME MONITORING ENGINE 
 * Perhitungan presisi tinggi per milidetik
 * ==========================================================================
 */

function initMasterMonitor() {
    const runningRows = document.querySelectorAll('.ot-master-row.row-running');

    runningRows.forEach(row => {
        const id = row.dataset.id;
        const startTimeStr = row.dataset.start;
        const ratePerHour = parseFloat(row.dataset.price);
        const ratePerSec = ratePerHour / 3600;

        if (!startTimeStr || isNaN(ratePerHour)) return;

        const startTime = new Date(startTimeStr).getTime();
        const timerEl = document.getElementById(`timer-${id}`);
        const costEl = document.getElementById(`cost-${id}`);

        setInterval(() => {
            const now = new Date().getTime();
            const difference = now - startTime;

            if (difference < 0) return;

            // 1. LOGIKA KONVERSI DURASI
            const hours = Math.floor(difference / 3600000);
            const minutes = Math.floor((difference % 3600000) / 60000);
            const seconds = Math.floor((difference % 60000) / 1000);

            // 2. UPDATE TAMPILAN TIMER
            if (timerEl) {
                timerEl.innerText = 
                    `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
            }

            // 3. LOGIKA KALKULASI TAGIHAN LIVE
            if (costEl) {
                const totalSeconds = Math.floor(difference / 1000);
                const currentCost = totalSeconds * ratePerSec;
                costEl.innerText = `Rp ${Math.floor(currentCost).toLocaleString('id-ID')}`;
            }
        }, 1000);
    });
}

// EKSEKUSI SETELAH HALAMAN SIAP
document.addEventListener('DOMContentLoaded', initMasterMonitor);