@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard-container">
    
    <div class="admin-welcome-header mb-4">
        <div class="header-overlay"></div>
        <div class="header-content d-flex justify-content-between align-items-center">
            <div class="text-white">
                <h2 class="fw-800 mb-1">COMMAND CENTER <span class="badge badge-live">LIVE</span></h2>
                <p class="opacity-75 mb-0">HK Equipment Management System & Fleet Monitor</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-glass btn-sm me-2" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                </button>
                <div class="digital-clock" id="digitalClock">00:00:00</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="mini-stat-card border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">TOTAL ARMADA</span>
                        <h3 class="mb-0 fw-800">{{ $totalEquipment }}</h3>
                    </div>
                    <div class="icon-box bg-primary-soft"><i class="bi bi-truck"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mini-stat-card border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">READY TO WORK</span>
                        <h3 class="mb-0 fw-800 text-success">{{ $available }}</h3>
                    </div>
                    <div class="icon-box bg-success-soft"><i class="bi bi-check-circle-fill"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mini-stat-card border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">ON PROJECT</span>
                        <h3 class="mb-0 fw-800 text-warning">{{ $rented }}</h3>
                    </div>
                    <div class="icon-box bg-warning-soft"><i class="bi bi-hammer"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mini-stat-card border-danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">PENDING ORDERS</span>
                        <h3 class="mb-0 fw-800 text-danger">{{ $pendingRentals }}</h3>
                    </div>
                    <div class="icon-box bg-danger-soft"><i class="bi bi-exclamation-triangle-fill"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="content-card mb-4">
                <div class="card-header-v3">
                    <h5 class="fw-bold m-0 text-dark">Laju Penyewaan Mingguan</h5>
                    <span class="badge bg-light text-dark border">7 Hari Terakhir</span>
                </div>
                <div class="card-body-v3">
                    <canvas id="mainChart" style="height: 250px;"></canvas>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="content-card h-100">
                        <div class="card-header-v3">
                            <h6 class="fw-bold m-0 text-dark">Status Lokasi Unit</h6>
                        </div>
                        <div class="card-body-v3">
                            <div class="location-list">
                                <div class="location-item">
                                    <span>Workshop HK Wonogiri</span>
                                    <span class="badge bg-primary">12 Unit</span>
                                </div>
                                <div class="location-item">
                                    <span>Site Project Solo</span>
                                    <span class="badge bg-warning text-dark">5 Unit</span>
                                </div>
                                <div class="location-item">
                                    <span>Site Project Jogja</span>
                                    <span class="badge bg-warning text-dark">3 Unit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-card h-100">
                        <div class="card-header-v3">
                            <h6 class="fw-bold m-0 text-dark">Jadwal Perawatan Terdekat</h6>
                        </div>
                        <div class="card-body-v3 p-0">
                            <table class="table table-sm table-maintenance m-0">
                                <thead>
                                    <tr>
                                        <th>Armada</th>
                                        <th>Target</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Excavator PC200</td>
                                        <td>20 Feb</td>
                                        <td><span class="text-primary fw-bold">Ganti Oli</span></td>
                                    </tr>
                                    <tr>
                                        <td>Bulldozer D65</td>
                                        <td>25 Feb</td>
                                        <td><span class="text-primary fw-bold">Service</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="content-card mb-4 bg-dark text-white">
                <div class="card-body-v3 p-4">
                    <h5 class="fw-bold mb-3">Quick Control</h5>
                    <div class="d-grid gap-2">
                        <button class="btn btn-danger py-2 fw-bold" onclick="launchModal()"><i class="bi bi-plus-lg me-2"></i>BUAT TRANSAKSI BARU</button>
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline-light btn-sm w-100 py-2">Tambah Alat</a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.customer.index') }}" class="btn btn-outline-light btn-sm w-100 py-2">Cek Pelanggan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header-v3">
                    <h6 class="fw-bold m-0">Recent Activity Feed</h6>
                </div>
                <div class="card-body-v3 p-0">
                    <div class="activity-feed">
                        @foreach($recentRentals as $r)
                        <div class="feed-item">
                            <div class="feed-icon {{ $r->status }}"><i class="bi bi-dot"></i></div>
                            <div class="feed-content">
                                <p class="mb-0 fw-bold">{{ $r->user->name }}</p>
                                <small class="text-muted">Sewa {{ $r->equipment->name }}</small>
                                <div class="feed-time">{{ $r->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/admin/admin-dashboard.js') }}"></script>
<script>
    const labels = {!! json_encode(collect($chart)->pluck('date')) !!};
    const values = {!! json_encode(collect($chart)->pluck('total')) !!};
    initV3Chart(labels, values);
</script>
@endpush