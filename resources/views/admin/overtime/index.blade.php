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
            <button type="button" class="nav-link-custom filter-ot-btn active" data-period="all">
                <i class="bi bi-grid-1x2"></i> Semua Data
            </button>
            <button type="button" class="nav-link-custom filter-ot-btn" data-period="weekly">
                <i class="bi bi-calendar-event"></i> Mingguan
            </button>
            <button type="button" class="nav-link-custom filter-ot-btn" data-period="monthly">
                <i class="bi bi-calendar-month"></i> Bulanan
            </button>
            <button type="button" class="nav-link-custom filter-ot-btn" data-period="yearly">
                <i class="bi bi-calendar-check"></i> Tahunan
            </button>
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
                        <th class="text-center">WAKTU MULAI</th>
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
                        data-date="{{ $ot->created_at->toIso8601String() }}"
                        @if($ot->status === 'approved' && $ot->started_at)
                            data-start="{{ $ot->started_at->toIso8601String() }}"
                            data-price="{{ $ot->price_per_hour }}"
                        @endif>
                        
                        {{-- 1. INFORMASI UNIT --}}
                        <td class="ps-4" data-label="Unit">
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
                        <td class="text-center" data-label="Status">
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

                        {{-- 3. KOLOM BARU: WAKTU MULAI --}}
                        <td class="text-center" data-label="Mulai">
                            @if($ot->started_at)
                                <div class="fw-bold text-dark" style="font-size: 12px;">
                                    {{ \Carbon\Carbon::parse($ot->started_at)
                                        ->timezone('Asia/Jakarta')
                                        ->format('d/m/Y') }}
                                </div>
                                <div class="text-muted" style="font-size: 11px;">
                                    {{ \Carbon\Carbon::parse($ot->started_at)
                                        ->timezone('Asia/Jakarta')
                                        ->format('H:i') }} WIB
                                </div>
                            @else
                                <span class="text-muted small">Belum mulai</span>
                            @endif
                        </td>

                        {{-- 4. MANAJEMEN TARIF --}}
                        <td class="text-center" data-label="Tarif">
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

                        {{-- 5. LIVE BILLING --}}
                        <td class="text-center" data-label="Billing">
                            @if($ot->status === 'approved')
                                <div class="hk-billing-live">
                                    <div class="billing-amount text-primary" id="cost-{{ $ot->id }}">Rp 0</div>
                                </div>
                            @elseif($ot->status === 'completed')
                                <div class="hk-billing-final">
                                    <div class="billing-amount text-danger">
                                        Rp {{ number_format($ot->price, 0, ',', '.') }}
                                    </div>
                                    <div class="billing-label">TOTAL FINAL</div>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- 6. STATUS BAYAR --}}
                        <td class="text-center" data-label="Bayar">
                            @if($ot->status === 'completed')

                                @if($ot->payment_status === 'paid')
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <span class="badge bg-success" style="font-size: 0.7rem;">
                                            TERBAYAR
                                        </span>

                                        @if($ot->proof)
                                            <div class="hk-proof-mini"
                                                onclick="previewProof('{{ asset('storage/'.$ot->proof) }}')"
                                                style="cursor:pointer; font-size: 11px;">
                                                
                                                <i class="bi bi-receipt"></i>
                                                Lihat Struk
                                            </div>
                                        @endif
                                    </div>

                                @else
                                    <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                                        MENUNGGU
                                    </span>
                                @endif

                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>

                        {{-- 7. KONTROL (AKSI) --}}
                        <td class="text-end pe-4" data-label="Aksi">
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
                                    <form action="{{ route('admin.overtime.reject', $ot->id) }}" method="POST" class="d-inline" onsubmit="event.preventDefault(); confirmReject(this)">
                                        @csrf
                                        <button type="submit" class="btn-action btn-reject" title="Tolak">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                @elseif($ot->status === 'approved')
                                    <form action="{{ route('admin.overtime.stop', $ot->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn-action btn-stop" title="Stop Operasi"><i class="bi bi-stop-circle-fill"></i></button>
                                    </form>
                                @endif

                                @if($ot->status !== 'approved')
                                    <form action="{{ route('admin.overtime.delete', $ot->id) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="event.preventDefault(); confirmDelete(this)">
                                        @csrf 
                                        @method('DELETE')

                                        <button class="btn-action btn-delete" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/admin/overtime/index.js') }}"></script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "{{ session('success') }}",
    timer: 2500,
    showConfirmButton: false,
    background: '#ffffff',
    borderRadius: '12px'
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Terjadi Kesalahan',
    text: "{{ session('error') }}",
    confirmButtonColor: '#ef4444'
});
</script>
@endif

@if(session('info'))
<script>
Swal.fire({
    icon: 'info',
    title: 'Informasi',
    text: "{{ session('info') }}",
    confirmButtonColor: '#3b82f6'
});
</script>
@endif

@endpush