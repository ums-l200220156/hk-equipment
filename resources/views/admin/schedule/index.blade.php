<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Jadwal Alat</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
#calendar {
    background: #fff;
    padding: 15px;
    border-radius: 12px;
}
.progress {
    height: 18px;
}
</style>
</head>

<body class="bg-light">

<div class="container py-4">

<h3 class="mb-4">🧠 Manajemen Jadwal Penyewaan</h3>

<div class="d-flex gap-3 mb-3">
    <select id="equipmentFilter" class="form-select w-25">
        <option value="">Semua Alat</option>
        @foreach($equipment as $e)
            <option value="{{ $e->id }}">{{ $e->name }}</option>
        @endforeach
    </select>

    <button id="btnHeatmap" class="btn btn-outline-danger">
        📊 Heatmap Pemakaian
    </button>
</div>

<div id="calendar"></div>

<div id="heatmap" class="mt-4 d-none"></div>

</div>

<script>
let calendar;

function loadCalendar(equipmentId = '') {

    if (calendar) calendar.destroy();

    calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'timeGridWeek',
        editable: true,
        height: 650,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        events: `/admin/schedule/events?equipment_id=${equipmentId}`,

        eventDrop(info) {
            fetch('{{ route("admin.schedule.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: info.event.id,
                    start: info.event.start,
                    end: info.event.end
                })
            })
            .then(res => {
                if (!res.ok) throw res;
                Swal.fire('Berhasil', 'Jadwal berhasil diperbarui', 'success');
            })
            .catch(() => {
                info.revert();
                Swal.fire('Bentrok!', 'Jadwal tidak bisa dipindahkan', 'error');
            });
        }
    });

    calendar.render();
}

loadCalendar();

document.getElementById('equipmentFilter').addEventListener('change', e => {
    loadCalendar(e.target.value);
});

// 📊 HEATMAP
document.getElementById('btnHeatmap').onclick = () => {
    fetch('{{ route("admin.schedule.heatmap") }}')
        .then(res => res.json())
        .then(data => {
            let html = `<h5>📊 Intensitas Pemakaian Alat</h5>`;
            data.forEach(d => {
                html += `
                <div class="mb-2">
                    <strong>${d.name}</strong>
                    <div class="progress">
                        <div class="progress-bar bg-danger"
                             style="width:${Math.min(d.total * 12, 100)}%">
                             ${d.total}x sewa
                        </div>
                    </div>
                </div>`;
            });
            document.getElementById('heatmap').innerHTML = html;
            document.getElementById('heatmap').classList.toggle('d-none');
        });
};
</script>

</body>
</html>
