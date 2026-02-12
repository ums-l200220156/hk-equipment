@extends('layouts.admin')

@section('title', 'HK Overtime Management')

{{-- 01. IMPORT CSS KHUSUS --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/overtime/index.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="hk-overtime-wrapper">
    {{-- 02. MODERN HEADER SECTION --}}
    <div class="hk-glass-card mb-4 animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Overtime</li>
                    </ol>
                </nav>
                <h2 class="hk-page-title">Overtime <span class="fw-light text-muted">Command Center</span></h2>
                <p class="text-secondary small mb-0">Monitor dan kelola lembur unit alat berat secara real-time.</p>
            </div>
            <div class="hk-header-stats">
                <div class="stat-item">
                    <div class="stat-icon"><i class="bi bi-activity"></i></div>
                    <div class="stat-content">
                        <span class="label">UNIT AKTIF</span>
                        <span class="value">{{ $overtimes->where('status', 'approved')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 03. INTERACTIVE FILTER NAVIGATION --}}
    <div class="hk-filter-nav mb-4 animate__animated animate__fadeIn">
        <div class="nav-scroll-wrapper">
            <a href="{{ request()->fullUrlWithQuery(['period' => 'all']) }}" class="nav-link-custom {{ request('period') == 'all' || !request('period') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Semua Data
            </a>
            <a href="{{ request()->fullUrlWithQuery(['period' => 'weekly']) }}" class="nav-link-custom {{ request('period') == 'weekly' ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> Mingguan
            </a>
            <a href="{{ request()->fullUrlWithQuery(['period' => 'monthly']) }}" class="nav-link-custom {{ request('period') == 'monthly' ? 'active' : '' }}">
                <i class="bi bi-calendar-month"></i> Bulanan
            </a>
            <a href="{{ request()->fullUrlWithQuery(['period' => 'yearly']) }}" class="nav-link-custom {{ request('period') == 'yearly' ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Tahunan
            </a>
        </div>
    </div>

    {{-- 04. MAIN DATA TABLE CARD --}}
    <div class="hk-main-card animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">INFORMASI UNIT</th>
                        <th class="text-center">STATUS & DURASI</th>
                        <th class="text-center">MANAJEMEN TARIF</th>
                        <th class="text-center">LIVE BILLING</th>
                        <th class="text-center">STATUS BAYAR</th>
                        <th class="text-end pe-4">KONTROL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overtimes as $ot)
                    <tr class="hk-ot-row @if($ot->status === 'approved') status-running @endif"
                        data-id="{{ $ot->id }}" 
                        data-status="{{ $ot->status }}"
                        @if($ot->status === 'approved' && $ot->started_at)
                            data-start="{{ $ot->started_at->toIso8601String() }}"
                            data-price="{{ $ot->price_per_hour }}"
                        @endif>
                        
                        {{-- 1. INFORMASI UNIT --}}
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="hk-unit-thumbnail">
                                    <img src="{{ asset('uploads/equipment/'.$ot->rental->equipment->image) }}" alt="Unit">
                                    <span class="unit-badge">#{{ $ot->rental_id }}</span>
                                </div>
                                <div>
                                    <div class="hk-unit-name-link">{{ $ot->rental->equipment->name }}</div>
                                    <div class="hk-client-name"><i class="bi bi-person me-1"></i> {{ $ot->rental->user->name }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- 2. STATUS & DURASI --}}
                        <td class="text-center">
                            @if($ot->status === 'pending')
                                <span class="badge-status pending">Menunggu</span>
                            @elseif($ot->status === 'approved')
                                <div class="hk-live-timer">
                                    <span class="dot-pulse"></span>
                                    <span class="timer-text" id="timer-{{ $ot->id }}">00:00:00</span>
                                </div>
                            @else
                                <span class="badge-status completed">Selesai</span>
                            @endif
                        </td>

                        {{-- 3. MANAJEMEN TARIF --}}
                        <td class="text-center">
                            @if($ot->status === 'pending')
                                <form action="{{ route('admin.overtime.approve', $ot->id) }}" method="POST" class="hk-inline-form">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price_per_hour" class="form-control" placeholder="Tarif/Jam" required>
                                        <button class="btn btn-primary" type="submit"><i class="bi bi-check-lg"></i></button>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Base: Rp {{ number_format($ot->rental->equipment->price_per_hour, 0, ',', '.') }}</small>
                                </form>
                            @else
                                <div class="hk-price-tag">
                                    <span class="amount">Rp {{ number_format($ot->price_per_hour, 0, ',', '.') }}</span>
                                    <span class="unit">/ jam</span>
                                </div>
                            @endif
                        </td>

                        {{-- 4. LIVE BILLING --}}
                        <td class="text-center">
                            @if($ot->status === 'approved')
                                <div class="hk-billing-live">
                                    <div class="billing-amount text-primary" id="cost-{{ $ot->id }}">Rp 0</div>
                                    <div class="billing-label">ESTIMASI BERJALAN</div>
                                </div>
                            @elseif($ot->status === 'completed')
                                <div class="hk-billing-final">
                                    <div class="billing-amount">Rp {{ number_format($ot->price, 0, ',', '.') }}</div>
                                    <div class="billing-label">TOTAL FINAL</div>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- 5. STATUS BAYAR (KOLOM BARU) --}}
                        <td class="text-center">
                            @if($ot->status === 'completed')
                                @if($ot->payment_status === 'paid')
                                    <span class="badge bg-success" style="font-size: 0.7rem;">TERBAYAR</span>
                                    @if($ot->proof)
                                        <a href="{{ asset('storage/'.$ot->proof) }}" target="_blank" class="d-block small text-primary mt-1 text-decoration-none">
                                            <i class="bi bi-image"></i> Bukti Struk
                                        </a>
                                    @endif
                                @else
                                    <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">MENUNGGU</span>
                                @endif
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>

                        {{-- 6. KONTROL (AKSI) --}}
                        <td class="text-end pe-4">
                            <div class="btn-group-custom">
                                {{-- Tombol Verifikasi Pembayaran (HANYA MUNCUL JIKA STATUS COMPLETED & BELUM PAID) --}}
                                @if($ot->status === 'completed' && $ot->payment_status !== 'paid')
                                    <form action="{{ route('admin.overtime.verify_payment', $ot->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn-action btn-stop" style="background: var(--success); color: white; border: none;" title="Verifikasi Pembayaran">
                                            <i class="bi bi-check-all"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($ot->status === 'pending')
                                    <form action="{{ route('admin.overtime.reject', $ot->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn-action btn-reject" title="Tolak"><i class="bi bi-x"></i></button>
                                    </form>
                                @elseif($ot->status === 'approved')
                                    <form action="{{ route('admin.overtime.stop', $ot->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn-action btn-stop" title="Stop Operasi"><i class="bi bi-stop-circle-fill"></i></button>
                                    </form>
                                @endif

                                @if($ot->status !== 'approved')
                                    <form action="{{ route('admin.overtime.delete', $ot->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen log data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn-action btn-delete" title="Hapus"><i class="bi bi-trash3"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/overtime/index.js') }}"></script>
@endpush