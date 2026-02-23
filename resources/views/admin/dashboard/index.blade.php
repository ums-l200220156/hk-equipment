@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/admin-dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
<div class="hk-dashboard-wrapper container-fluid">

    {{-- HEADER SECTION: ANIMATED WELCOME & LARGE CLOCK --}}
    <div class="hk-hero-banner animate__animated animate__fadeInDown">
        <div class="hk-hero-content">
            <h1 class="animate__animated animate__lightSpeedInLeft">Welcome back, <span class="text-danger">HK Admin</span></h1>
            <p>Sistem sedang berjalan optimal. Memantau <span class="fw-bold text-order">{{ $totalEquipment }} armada</span> secara real-time.</p>
            {{-- Tombol Cetak Laporan --}}
            <a href="{{ route('admin.report.export') }}" class="btn btn-danger btn-sm mt-2 rounded-pill px-4 shadow-sm">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>CETAK LAPORAN
            </a>
        </div>
        
        {{-- JAM & TANGGAL BESAR DI KANAN --}}
        <div class="hk-time-large-container text-end animate__animated animate__fadeInRight">
            {{-- TOMBOL MUTE/UNMUTE --}}
            <div class="mb-2">
                <button id="btnToggleMute" onclick="toggleMute()" title="Atur Notifikasi">
                    <i class="bi bi-bell-fill"></i>
                </button>
            </div>
            <div id="liveClock" class="hk-clock-big">00:00:00</div>
            <div class="hk-date-big">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
    </div>

    {{-- QUICK ACCESS GRID --}}
    <div class="hk-quick-access-grid mb-4">
        <a href="{{ route('admin.rentals.create') }}" class="qa-item item-primary animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Entry Rental</span>
        </a>
        <a href="{{ route('admin.schedule.index') }}" class="qa-item item-danger animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <i class="bi bi-calendar3"></i>
            <span>Monitor Jadwal</span>
        </a>
        <a href="{{ route('admin.finance.index') }}" class="qa-item item-warning animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <i class="bi bi-bank"></i>
            <span>Cek Keuangan</span>
        </a>
        <a href="{{ route('admin.equipment.index') }}" class="qa-item item-success animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <i class="bi bi-truck-front"></i>
            <span>Kelola Armada</span>
        </a>
    </div>

    {{-- MAIN ROW: ANALYTICS & MONITORING --}}
    <div class="row g-4">
        
        {{-- LEFT COLUMN: THE CHART & COMPARISON --}}
        <div class="col-xl-8">
            <div class="hk-card-glass p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-800 m-0"><i class="bi bi-bar-chart-line text-danger me-2"></i>REVENUE & GROWTH</h5>
                    <div class="hk-growth-badge {{ $growth >= 0 ? 'up' : 'down' }}">
                        <i class="bi bi-graph-{{ $growth >= 0 ? 'up' : 'down' }}"></i>
                        {{ abs(round($growth)) }}% vs Last Month
                    </div>
                </div>
                <div class="chart-container" style="height: 350px;">
                    <canvas id="revenueChartHK"></canvas>
                </div>
                <div class="row mt-4 text-center">
                    <div class="col-6 border-end border-secondary">
                        <small class="text-muted d-block text-uppercase">Total Profit (Month)</small>
                        <h4 class="fw-bold text-profit">IDR {{ number_format($netProfit, 0, ',', '.') }}</h4>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase">Total Transactions</small>
                        <h4 class="fw-bold text-order">{{ $thisMonthCount }} Orders</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: UNIT STATUS BENTO --}}
        <div class="col-xl-4">
            <div class="hk-card-glass p-4 h-100">
                <h5 class="fw-800 mb-4"><i class="bi bi-broadcast text-danger me-2"></i>UNIT MONITOR</h5>
                <div class="hk-bento-grid">
                    <div class="bento-box ready">
                        <div class="bento-icon"><i class="bi bi-check2-circle"></i></div>
                        <div class="bento-val">{{ $available }}</div>
                        <div class="bento-label">READY</div>
                    </div>
                    <div class="bento-box rented">
                        <div class="bento-icon"><i class="bi bi-key"></i></div>
                        <div class="bento-val">{{ $rented }}</div>
                        <div class="bento-label">RENTED</div>
                    </div>
                    <div class="bento-box maint">
                        <div class="bento-icon"><i class="bi bi-tools"></i></div>
                        <div class="bento-val">{{ $underMaintenance }}</div>
                        <div class="bento-label">SERVICE</div>
                    </div>
                    <div class="bento-box total">
                        <div class="bento-icon"><i class="bi bi-boxes"></i></div>
                        <div class="bento-val">{{ $totalEquipment }}</div>
                        <div class="bento-label">TOTAL</div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="small text-muted fw-bold">TINGKAT PEMAKAIAN ARMADA ({{ $utilizationRate }}%)</label>
                    <div class="progress" style="height: 10px; border-radius: 20px;">
                        <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" style="width: {{ $utilizationRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT TRANSACTIONS TABLE --}}
    <div class="mt-4 animate__animated animate__fadeInUp">
        <div class="hk-card-glass p-4">
            <h5 class="fw-800 mb-4 text-uppercase">Latest Rental Activities</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle hk-table-dark">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Equipment</th>
                            <th>Rent Date</th>
                            <th class="text-end">Total Price</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRentals as $r)
                        <tr>
                            <td data-label="User">
                                <div class="d-flex align-items-center gap-3">
                                    @if($r->user->image)
                                        <img src="{{ asset('uploads/users/'.$r->user->image) }}" class="hk-avatar-mini" style="object-fit: cover;">
                                    @else
                                        <div class="hk-avatar-mini">{{ strtoupper(substr($r->user->name, 0, 1)) }}</div>
                                    @endif
                                    <div class="fw-bold">{{ $r->user->name }}</div>
                                </div>
                            </td>
                            <td data-label="Equipment">
                                <div class="equipment-wrapper">
                                    <span class="hk-id-number">#{{ $r->equipment->id }}</span>
                                    <span class="hk-equipment-name">{{ $r->equipment->name }}</span>
                                </div>
                            </td>
                            <td data-label="Rent Date">
                                <span>{{ date('d M Y', strtotime($r->rent_date)) }}</span>
                            </td>
                            <td class="text-end fw-bold harga" data-label="Total Price">Rp {{ number_format($r->total_price, 0, ',', '.') }}</td>
                            <td class="text-center" data-label="Status">
                                <span class="badge hk-badge-cyber {{ $r->status }}">
                                    {{ strtoupper(str_replace('_',' ', $r->status)) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.hkData = {
        months: {!! json_encode($months) !!},
        income: {!! json_encode($incomeData) !!},
        alerts: {!! json_encode($alerts) !!}
    };
</script>
<script src="{{ asset('assets/js/admin/admin-dashboard.js') }}"></script>
@endpush