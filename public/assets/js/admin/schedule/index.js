let calendar;

// 1. FUNGSI GLOBAL UNTUK MENUTUP HEATMAP (Dapat dipanggil atribut onclick)
function toggleHeatmap() {
    const section = document.getElementById('heatmapSection');
    if (section) {
        // Tambahkan animasi keluar sebelum disembunyikan
        section.classList.add('animate__fadeOutDown');
        
        setTimeout(() => {
            section.classList.add('d-none');
            section.classList.remove('animate__fadeOutDown');
        }, 500); 
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadCalendar();
    initHeatmapListener();
});

// 2. FUNGSI UNTUK MENGAMBIL DATA HEATMAP BERDASARKAN PERIODE (Fitur Reset/Filter)
function fetchHeatmapData(period) {
    const content = document.getElementById('heatmapContent');
    // State loading untuk UX yang lebih baik
    content.innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-arrow-repeat animate-spin"></i> Memuat data...</div>';

    fetch(`${HEATMAP_ROUTE}?period=${period}`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach(d => {
                // LOGIKA PROPORSIONAL: (Sewa Alat / Total Semua Sewa) * 100
                let percentage = 0;
                if (d.total_all > 0) {
                    percentage = Math.round((d.total / d.total_all) * 100);
                }

                html += `
                    <div class="hk-heatmap-item animate__animated animate__fadeInLeft">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="d-block text-muted small fw-bold">PANGSA PENGGUNAAN</span>
                                <h6 class="m-0 fw-bold text-navy text-uppercase">${d.name}</h6>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger rounded-pill px-3">${d.total} KALI</span>
                            </div>
                        </div>
                        <div class="progress-wrapper">
                            <div class="progress-fill" style="width: ${percentage}%"></div>
                        </div>
                        <div class="text-end mt-1">
                            <small class="fw-bold text-danger">${percentage}% kontribusi periode ini</small>
                        </div>
                    </div>`;
            });
            // Tampilkan hasil atau pesan kosong jika tidak ada data
            content.innerHTML = html || '<div class="text-center py-4 text-muted">Tidak ada aktivitas pada periode ini.</div>';
        });
}

// 3. INISIALISASI LISTENER HEATMAP & FILTER PERIODE
function initHeatmapListener() {
    const btnHeatmap = document.getElementById('btnHeatmap');
    const periodSelect = document.getElementById('heatmapPeriod');

    if (btnHeatmap) {
        btnHeatmap.onclick = () => {
            const section = document.getElementById('heatmapSection');
            
            // Toggle Logic: Jika sudah terbuka, maka tutup. Jika tertutup, buka dan ambil data.
            if (!section.classList.contains('d-none')) {
                toggleHeatmap();
                return;
            }

            section.classList.remove('d-none');
            fetchHeatmapData('all'); // Default ambil semua saat pertama kali buka
            section.scrollIntoView({ behavior: 'smooth' });
        };
    }

    // Listener untuk perubahan periode (Reset/Filter Data secara Real-time)
    if (periodSelect) {
        periodSelect.onchange = (e) => fetchHeatmapData(e.target.value);
    }
}

// 4. KONFIGURASI KALENDER UTAMA
function loadCalendar(equipmentId = '') {
    const calendarEl = document.getElementById('calendar');
    if (calendar) calendar.destroy();

    calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'id',
        initialView: 'timeGridWeek',
        timeZone: 'Asia/Jakarta', // Kunci ke zona waktu Jakarta agar sinkron dengan server
        slotLabelFormat: {
            hour: '2-digit', minute: '2-digit', hour12: false // Format 24 Jam Indonesia
        },
        eventTimeFormat: {
            hour: '2-digit', minute: '2-digit', hour12: false
        },
        firstDay: 1, // Minggu dimulai hari Senin
        editable: true,
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hari Ini', month: 'Bulan', week: 'Minggu', day: 'Hari'
        },
        events: `${EVENTS_FETCH_URL}?equipment_id=${equipmentId}`,
        
        eventDidMount: function(info) {
            const status = info.event.extendedProps.status;
            if (status) {
                info.el.classList.add(`hk-event-${status}`);
            }
            info.el.title = `Customer: ${info.event.extendedProps.customer} (${status})`;
        },

        eventDrop: (info) => handleEventUpdate(info),
        eventResize: (info) => handleEventUpdate(info)
    });

    calendar.render();
}

// 5. UPDATE JADWAL VIA DRAG & DROP
function handleEventUpdate(info) {
    fetch(UPDATE_ROUTE, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: info.event.id,
            start: info.event.startStr,
            end: info.event.endStr
        })
    })
    .then(async res => {
        if (!res.ok) throw await res.json();
        Swal.fire({
            icon: 'success',
            title: 'Jadwal Diperbarui',
            text: 'Perubahan waktu armada telah disimpan.',
            timer: 2000,
            showConfirmButton: false
        });
    })
    .catch(err => {
        info.revert();
        Swal.fire('Gagal!', err.message || 'Terjadi bentrok jadwal.', 'error');
    });
}

// 6. LISTENER UNTUK FILTER UNIT ARMADA
document.getElementById('equipmentFilter').addEventListener('change', e => {
    loadCalendar(e.target.value);
});