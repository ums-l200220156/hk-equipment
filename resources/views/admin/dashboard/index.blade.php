@extends('layouts.admin')
@section('title', 'Executive Dashboard - HK Equipment')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
<div class="hk-dashboard-container">
    
    {{-- 01. HEADER: BRANDING & PRINT --}}
    <div class="hk-glass-header mb-4 animate__animated animate__fadeIn">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="hk-label text-danger fw-bold">DASHBOARD EKSEKUTIF</span>
                <h2 class="fw-800 text-navy mb-0">HK <span class="text-danger">EQUIPMENT</span> MONITOR</h2>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <button onclick="window.print()" class="btn-hk-print me-2">
                    <i class="bi bi-printer"></i> CETAK LAPORAN RAPAT
                </button>
                <div class="digital-clock d-inline-block shadow-sm">
                    <span id="liveClock">00:00:00</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 02. TOP PERFORMANCE CARDS --}}
    <div class="row g-4 mb-4">
        {{-- Total Profit --}}
        <div class="col-lg-4">
            <div class="card-luxury card-profit animate__animated animate__zoomIn">
                <div class="card-inner">
                    <small>SALDO BERSIH BULAN INI</small>
                    <h3>Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                    <div class="mini-breakdown mt-3">
                        <span>Pemasukan: Rp {{ number_format($rentalIncome + $overtimeIncome, 0, ',', '.') }}</span>
                        <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-white" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
                <i class="bi bi-wallet2 icon-bg"></i>
            </div>
        </div>
        {{-- Fleet Status --}}
        <div class="col-lg-4">
            <div class="card-luxury card-fleet animate__animated animate__zoomIn" style="animation-delay: 0.1s">
                <div class="card-inner">
                    <small>UTILITAS ARMADA</small>
                    <h3>{{ $rented }} <span class="fs-6 fw-normal">Unit Tersewa</span></h3>
                    <div class="d-flex gap-2 mt-3">
                        <span class="badge bg-success rounded-pill">Ready: {{ $available }}</span>
                        <span class="badge bg-warning text-dark rounded-pill">Service: {{ $underMaintenance }}</span>
                    </div>
                </div>
                <i class="bi bi-truck-front icon-bg"></i>
            </div>
        </div>
        {{-- Critical Alerts --}}
        <div class="col-lg-4">
            <div class="card-luxury card-alert animate__animated animate__zoomIn" style="animation-delay: 0.2s">
                <div class="card-inner">
                    <small>PERHATIAN SISTEM</small>
                    <h3>{{ $overdueRentals }} <span class="fs-6 fw-normal">Kasus Overdue</span></h3>
                    <p class="mb-0 mt-3 small text-white-50">Segera hubungi penyewa yang melewati batas waktu pengembalian.</p>
                </div>
                <i class="bi bi-exclamation-octagon icon-bg"></i>
            </div>
        </div>
    </div>

    {{-- 03. ANALYTICS GRID --}}
    <div class="row g-4">
        {{-- Finansial Chart --}}
        <div class="col-lg-8">
            <div class="hk-card shadow-sm p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-800 m-0"><i class="bi bi-bar-chart-line text-danger me-2"></i>TREN PENDAPATAN</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border" type="button">6 Bulan Terakhir</button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="financeChart" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- Quick Transaction Table --}}
        <div class="col-lg-4">
            <div class="hk-card shadow-sm h-100">
                <div class="card-header-v4 p-4 border-0">
                    <h5 class="fw-800 m-0">TRANSAKSI TERBARU</h5>
                </div>
                <div class="p-4 pt-0">
                    <div class="hk-recent-list">
                        @foreach($recentRentals as $r)
                        <div class="recent-item d-flex align-items-center gap-3 mb-3 p-3 rounded-4 bg-light shadow-sm">
                            <div class="recent-icon {{ $r->status }}"><i class="bi bi-dot"></i></div>
                            <div class="recent-info flex-grow-1">
                                <h6 class="m-0 fw-bold">{{ Str::limit($r->user->name, 15) }}</h6>
                                <small class="text-muted">{{ $r->equipment->name }}</small>
                            </div>
                            <div class="recent-val text-end">
                                <div class="fw-bold text-navy">Rp {{ number_format($r->total_price, 0, ',', '.') }}</div>
                                <span class="badge-status-mini {{ $r->status }}">{{ $r->status }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('admin.rentals.index') }}" class="btn btn-navy w-100 py-2 mt-2 rounded-pill">Lihat Semua Transaksi</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Live Clock
    function updateClock() {
        const now = new Date();
        document.getElementById('liveClock').innerText = now.toLocaleTimeString('id-ID');
    }
    setInterval(updateClock, 1000); updateClock();

    // 2. Finance Chart
    const ctx = document.getElementById('financeChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(220, 53, 69, 0.4)');
    gradient.addColorStop(1, 'rgba(220, 53, 69, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Pendapatan (Rental + Overtime)',
                data: {!! json_encode($incomeData) !!},
                borderColor: '#dc3545',
                borderWidth: 4,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#dc3545',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush