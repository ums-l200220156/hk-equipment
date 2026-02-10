/**
 * REALT-TIME LOGIC & SYNC FOR OVERTIME
 */

function confirmCancel() {
    Swal.fire({
        title: 'Batalkan Pesanan?',
        text: "Pesanan tidak dapat dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Batalkan!',
        reverseButtons: true
    }).then((result) => { if (result.isConfirmed) document.getElementById('cancelForm').submit(); });
}

function confirmCancelOt() {
    Swal.fire({
        title: 'Batalkan Lembur?',
        text: "Anda dapat mengajukan kembali nanti.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Hapus',
        reverseButtons: true
    }).then((result) => { if (result.isConfirmed) document.getElementById('cancelOtForm').submit(); });
}

function initOvertimeSync() {
    const app = document.getElementById('realtimeOtApp') || document.getElementById('otApp');
    if (!app) return;

    const otId = app.dataset.id;
    const currentStatus = app.dataset.status;

    // 1. Logika Counter (Hanya jika Status Approved)
    if (currentStatus === 'approved' && app.dataset.start) {
        const startTime = new Date(app.dataset.start).getTime();
        const pricePerHour = parseFloat(app.dataset.price);
        const pricePerSecond = pricePerHour / 3600;

        setInterval(() => {
            const now = new Date().getTime();
            const diff = now - startTime;
            if (diff < 0) return;

            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);

            const totalSec = Math.floor(diff / 1000);
            const cost = totalSec * pricePerSecond;

            const timerEl = document.getElementById('displayTimer');
            const costEl = document.getElementById('displayCost');

            if(timerEl) timerEl.innerText = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
            if(costEl) costEl.innerText = `Rp ${Math.floor(cost).toLocaleString('id-ID')}`;
        }, 1000);
    }

    // 2. Polling Status (Sinkronisasi Otomatis dengan Admin setiap 4 detik)
    setInterval(() => {
        fetch(`/customer/overtime/${otId}/status`)
            .then(res => res.json())
            .then(data => {
                if (data.status !== currentStatus) {
                    window.location.reload(); 
                }
            })
            .catch(err => console.error("Polling error:", err));
    }, 4000);
}

document.addEventListener('DOMContentLoaded', initOvertimeSync);