document.addEventListener('DOMContentLoaded', () => {

    /* =====================================================
       KATALOG INDEX (FILTER)
    ===================================================== */
    const searchInput    = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const statusFilter   = document.getElementById('statusFilter');
    const items          = document.querySelectorAll('.catalog-item');
    const lockedCategory = document.getElementById('lockedCategory')?.value ?? null;

    function filterItems() {
        if (!items.length) return;

        const keyword  = searchInput?.value.toLowerCase() ?? '';
        const category = lockedCategory ?? categorySelect?.value ?? '';
        const status   = statusFilter?.value ?? '';

        items.forEach(item => {
            const match =
                item.dataset.name.includes(keyword) &&
                (!category || item.dataset.category === category) &&
                (!status || item.dataset.status === status);

            item.style.display = match ? 'block' : 'none';
        });
    }

    searchInput?.addEventListener('input', filterItems);
    categorySelect?.addEventListener('change', filterItems);
    statusFilter?.addEventListener('change', filterItems);

    filterItems();

    /* =====================================================
       KATALOG SHOW (STATUS LIVE)
    ===================================================== */
    if (typeof window.STATUS_ENDPOINT !== 'undefined') {

        const statusBox  = document.getElementById('statusBox');
        const actionBox  = document.getElementById('actionBox');

        function renderStatus(data) {
            if (!statusBox || !actionBox) return;

            statusBox.innerHTML = '';
            actionBox.innerHTML = '';

            if (data.status === 'available') {
                statusBox.innerHTML = `
                    <div class="status-box status-available">
                        <i class="bi bi-check-circle-fill"></i>
                        Alat tersedia dan siap disewa
                    </div>`;
                actionBox.innerHTML = `
                    <a href="/rent/${window.CURRENT_EQUIPMENT_ID}"
                    class="btn btn-primary w-100">
                        <i class="bi bi-cart-check"></i> Sewa Sekarang
                    </a>`;

            }
            else if (data.status === 'rented') {
                statusBox.innerHTML = `
                    <div class="status-box status-rented">
                        <i class="bi bi-x-circle-fill"></i>
                        Alat sedang disewa oleh customer lain
                    </div>`;
                actionBox.innerHTML = `
                    <button class="btn btn-danger w-100" disabled>
                        Sedang Disewa
                    </button>`;
            }
            else {
                statusBox.innerHTML = `
                    <div class="status-box status-maintenance">
                        <i class="bi bi-tools"></i>
                        Alat sedang maintenance
                        ${data.maintenance_end_at
                            ? `<div class="small mt-1">
                                Estimasi selesai:
                                <strong>${data.maintenance_end_at}</strong>
                               </div>` : ``}
                    </div>`;
                actionBox.innerHTML = `
                    <button class="btn btn-warning w-100 text-dark" disabled>
                        Sedang Maintenance
                    </button>`;
            }
        }

        async function refreshStatus() {
            try {
                const res = await fetch(window.STATUS_ENDPOINT);
                if (!res.ok) return;
                const data = await res.json();
                renderStatus(data);
            } catch (e) {}
        }

        // initial render
        renderStatus(window.INITIAL_STATUS);
        setInterval(refreshStatus, 10000);
    }

});
