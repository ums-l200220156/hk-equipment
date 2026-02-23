document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const statusFilter = document.getElementById('statusFilter');
    const items = document.querySelectorAll('.catalog-item');
    const lockedCategory = document.getElementById('lockedCategory')?.value ?? null;

    // --- 1. FILTER LOGIC ---
    function filterItems() {
        if (!items.length) return;
        const keyword = searchInput?.value.toLowerCase() ?? '';
        const category = lockedCategory ?? categorySelect?.value ?? '';
        const status = statusFilter?.value ?? '';

        items.forEach(item => {
            const match = item.dataset.name.includes(keyword) &&
                (!category || item.dataset.category === category) &&
                (!status || item.dataset.status === status);
            
            item.style.display = match ? 'block' : 'none';
            if(match) item.classList.add('animate__animated', 'animate__fadeIn');
        });
    }

    searchInput?.addEventListener('input', filterItems);
    categorySelect?.addEventListener('change', filterItems);
    statusFilter?.addEventListener('change', filterItems);

    // --- 2. AUTO-UPDATE STATUS (TANPA RELOAD) ---
    // Fungsi untuk memperbarui status badge tiap card secara otomatis
    async function updateAllStatuses() {
        const itemIds = Array.from(items).map(item => item.dataset.id);
        if (itemIds.length === 0) return;

        items.forEach(async (item) => {
            const id = item.dataset.id;
            try {
                // Menggunakan endpoint status yang sudah ada di CatalogController
                const response = await fetch(`/catalog/${id}/status`);
                const data = await response.json();

                const badgeContainer = document.getElementById(`status-badge-${id}`);
                if (!badgeContainer) return;

                // Update data-status di element untuk filter
                item.dataset.status = data.status;

                // Render Badge Baru
                let badgeHTML = '';
                if (data.status === 'available') {
                    badgeHTML = `<span class="status-pill available"><i class="bi bi-circle-fill pulse-dot me-1"></i> Tersedia</span>`;
                } else if (data.status === 'rented') {
                    badgeHTML = `<span class="status-pill rented"><i class="bi bi-circle-fill pulse-dot me-1"></i> Disewa</span>`;
                } else {
                    badgeHTML = `<span class="status-pill maintenance"><i class="bi bi-circle-fill pulse-dot me-1"></i> Maintenance</span>`;
                }
                
                badgeContainer.innerHTML = badgeHTML;

            } catch (error) {
                console.error(`Gagal update status unit ${id}:`, error);
            }
        });
    }

    // Jalankan update otomatis setiap 15 detik
    setInterval(updateAllStatuses, 15000);
});