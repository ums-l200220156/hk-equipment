/**
 * HK EQUIPMENT DASHBOARD CORE ENGINE
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. LIVE CLOCK UPDATE
    function updateClock() {
        const now = new Date();
        const clockEl = document.getElementById('liveClock');
        if (clockEl) {
            clockEl.innerText = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. RENTAL TREND CHART (Chart.js)
    const ctx = document.getElementById('rentalChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Unit Tersewa',
                    data: chartData.values,
                    fill: true,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { stepSize: 1, font: { weight: '600' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: '600' } }
                    }
                }
            }
        });
    }

    // 3. AUTO REFRESH STATS (Optional - Setiap 5 Menit)
    setTimeout(() => {
        // window.location.reload();
    }, 300000);
});