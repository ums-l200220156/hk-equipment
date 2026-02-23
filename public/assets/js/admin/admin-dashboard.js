/**
 * HK DASHBOARD CORE ENGINE - COMMAND CENTER
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // --- STATE & MUTE SYSTEM ---
    let isMuted = localStorage.getItem('hk_alerts_muted') === 'true';

    // Fungsi untuk mengubah status mute
    window.toggleMute = function() {
        isMuted = !isMuted;
        localStorage.setItem('hk_alerts_muted', isMuted);
        updateMuteUI();
        
        const status = isMuted ? "Notifikasi Dimatikan" : "Notifikasi Diaktifkan";
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: isMuted ? 'warning' : 'success',
            title: status,
            showConfirmButton: false,
            timer: 2000,
            background: '#161e2d',
            color: '#fff'
        });
    };

    function updateMuteUI() {
        const btn = document.getElementById('btnToggleMute');
        if (btn) {
            btn.innerHTML = isMuted ? '<i class="bi bi-bell-slash-fill"></i>' : '<i class="bi bi-bell-fill"></i>';
            btn.className = isMuted ? 'btn btn-secondary btn-sm rounded-pill' : 'btn btn-danger btn-sm rounded-pill';
        }
    }
    updateMuteUI();

    // 1. LIVE SYSTEM CLOCK
    function updateClock() {
        const now = new Date();
        const clockEl = document.getElementById('liveClock');
        if (clockEl) {
            clockEl.innerText = now.toLocaleTimeString('id-ID', { hour12: false });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. MAIN GROWTH CHART (Kode Anda tetap sama...)
    const ctx = document.getElementById('revenueChartHK');
    if (ctx && window.hkData) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: hkData.months,
                datasets: [{
                    label: "Revenue",
                    lineTension: 0.4,
                    backgroundColor: "rgba(239, 68, 68, 0.15)",
                    borderColor: "#ef4444",
                    borderWidth: 4,
                    pointRadius: 5,
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "#ef4444",
                    pointHoverRadius: 7,
                    data: hkData.income,
                }],
            },
            options: {
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    xAxes: [{ gridLines: { display: false }, ticks: { fontColor: "#ffffff", fontSize: 12, fontStyle: 'bold' } }],
                    yAxes: [{ ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID'), fontColor: "#ffffff", fontSize: 11, fontStyle: 'bold' }, gridLines: { color: "rgba(255, 255, 255, 0.05)", drawBorder: false } }]
                },
                tooltips: { backgroundColor: '#1e293b', titleFontColor: '#ffffff', bodyFontColor: '#ffffff', displayColors: false, cornerRadius: 8, padding: 12 }
            }
        });
    }

    // 3. REVOLUTIONARY NOTIFICATION SYSTEM (INITIAL LOAD)
    if (window.hkData && hkData.alerts.length > 0 && !isMuted) {
        hkData.alerts.forEach((alert, index) => {
            setTimeout(() => {
                if (isMuted) return; // Cek ulang status sebelum muncul

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: alert.type || 'warning',
                    title: alert.msg || alert,
                    showConfirmButton: true,
                    confirmButtonText: 'Mute <i class="bi bi-bell-slash"></i>',
                    confirmButtonColor: '#475569',
                    timer: 10000,
                    timerProgressBar: true,
                    background: '#161e2d',
                    color: '#ffffff',
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.toggleMute();
                    }
                });
            }, (index * 10500) + 500);
        });
    }

    // ===== AUTO ALERT SYSTEM (REALTIME) =====
    function fetchAlerts() {
        if (isMuted) return; // Jangan fetch/tampil jika di-mute

        fetch('/admin/dashboard/alerts')
            .then(res => res.json())
            .then(alerts => {
                if (!alerts || alerts.length === 0) return;

                alerts.forEach((alert, index) => {
                    setTimeout(() => {
                        if (isMuted) return;
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: alert.type || 'warning',
                            title: alert.msg,
                            showConfirmButton: true,
                            confirmButtonText: '<i class="bi bi-bell-slash"></i>',
                            timer: 5000,
                            background: '#161e2d',
                            color: '#fff'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.toggleMute();
                            }
                        });
                    }, index * 800);
                });
            })
            .catch(err => console.error('Alert error:', err));
    }

    setInterval(fetchAlerts, 15000);

    setTimeout(() => {
        fetchAlerts();
    }, 4000);

});