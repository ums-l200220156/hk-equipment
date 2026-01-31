@extends('layouts.admin')

@section('content')

<h3 class="mb-4">📊 Admin Dashboard</h3>

{{-- ALERT --}}
@if($overdueRentals > 0)
<div class="alert alert-danger">
    ⚠️ <strong>{{ $overdueRentals }}</strong> sewa belum dikembalikan tepat waktu!
</div>
@endif

{{-- STAT CARDS --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card card-stat bg-gradient-primary">
            <div class="card-body">
                <h6>Total Alat</h6>
                <h3>{{ $totalEquipment }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stat bg-gradient-success">
            <div class="card-body">
                <h6>Tersedia</h6>
                <h3>{{ $available }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stat bg-gradient-warning">
            <div class="card-body">
                <h6>Disewa</h6>
                <h3>{{ $rented }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stat bg-gradient-danger">
            <div class="card-body">
                <h6>Pending</h6>
                <h3>{{ $pendingRentals }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- QUICK ACTION --}}
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card table-card p-3">
            <h5>⚡ Aksi Cepat</h5>
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-primary">📄 Kelola Transaksi</a>
                <a href="{{ route('admin.schedule.index') }}" class="btn btn-outline-success">📅 Jadwal Sewa</a>
                <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline-dark">🔧 Kelola Alat</a>
            </div>
        </div>
    </div>
</div>

{{-- GRAFIK --}}
<div class="card table-card p-3 mb-4">
    <h5>📈 Tren Penyewaan (7 Hari)</h5>
    <canvas id="rentalChart" height="100"></canvas>
</div>

{{-- TABEL --}}
<div class="card table-card p-3">
    <h5>🕒 Transaksi Terbaru</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Alat</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentRentals as $r)
            <tr class="{{ $r->status === 'pending' ? 'table-warning' : '' }}">
                <td>{{ $r->user->name }}</td>
                <td>{{ $r->equipment->name }}</td>
                <td>
                    <span class="badge bg-secondary">{{ $r->status }}</span>
                </td>
                <td>{{ $r->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('rentalChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($chart)->pluck('date')) !!},
        datasets: [{
            label: 'Total Sewa',
            data: {!! json_encode(collect($chart)->pluck('total')) !!},
            borderWidth: 2
        }]
    }
});
</script>

@endsection
