document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.STATUS_ENDPOINT !== 'undefined') {
        const statusBox = document.getElementById('statusBox');
        const actionBox = document.getElementById('actionBox');

        function renderStatus(data) {
            if (!statusBox || !actionBox) return;

            statusBox.innerHTML = '';
            actionBox.innerHTML = '';

            if (data.status === 'available') {
                statusBox.innerHTML = `
                    <div class="status-box status-available animate__animated animate__fadeIn">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <div>Unit Tersedia</div>
                            <small>Siap dikirim ke lokasi proyek</small>
                        </div>
                    </div>`;

                actionBox.innerHTML = `
                    <a href="/rent/${window.CURRENT_EQUIPMENT_ID}" 
                    class="btn btn-danger w-100 fw-bold py-3 rounded-pill shadow-lg animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-lightning-charge-fill me-2"></i> SEWA SEKARANG
                    </a>`;

            } else if (data.status === 'rented') {
                statusBox.innerHTML = `
                    <div class="status-box status-rented animate__animated animate__fadeIn">
                        <i class="bi bi-x-circle-fill"></i>
                        <div>
                            <div>Sedang Digunakan</div>
                            <small>Unit sedang dalam penyewaan</small>
                        </div>
                    </div>`;

                actionBox.innerHTML = `
                    <button class="btn btn-secondary w-100 rounded-pill py-3 fw-bold" disabled>
                        TIDAK TERSEDIA
                    </button>`;

            } else {
                statusBox.innerHTML = `
                    <div class="status-box status-maintenance animate__animated animate__fadeIn">
                        <i class="bi bi-tools"></i>
                        <div>
                            <div>Dalam Perawatan</div>
                            <small>${data.maintenance_end_at ? 'Sampai: ' + data.maintenance_end_at : 'Maintenance rutin'}</small>
                        </div>
                    </div>`;

        actionBox.innerHTML = `
            <button class="btn btn-warning w-100 rounded-pill py-3 text-dark fw-bold" disabled>
                MAINTENANCE
            </button>`;
    }
}

        async function refreshStatus() {
            try {
                const res = await fetch(window.STATUS_ENDPOINT);
                const data = await res.json();
                renderStatus(data);
            } catch (e) { console.error("Status check failed"); }
        }

        renderStatus(window.INITIAL_STATUS);
        setInterval(refreshStatus, 15000);
    }
});