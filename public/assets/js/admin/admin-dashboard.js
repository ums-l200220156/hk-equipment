// 1. Digital Clock Header
function updateClock() {
    const now = new Date();
    const clock = document.getElementById('digitalClock');
    if(clock) {
        clock.innerText = now.toLocaleTimeString('id-ID');
    }
}
setInterval(updateClock, 1000);

// 2. Chart Inisialisasi
function initV3Chart(labels, data) {
    const ctx = document.getElementById('mainChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Transaksi Sewa',
                data: data,
                backgroundColor: '#dc3545',
                borderRadius: 5,
                barThickness: 20
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { drawBorder: false } },
                x: { grid: { display: false } }
            }
        }
    });
}

// 3. SweetAlert Action Baru
function launchModal() {
    Swal.fire({
        title: 'Input Transaksi Baru',
        text: 'Sistem akan membuka form penyewaan kilat.',
        icon: 'question',
        confirmButtonColor: '#dc3545',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/admin/rentals/create";
        }
    });
}