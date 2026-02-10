@extends('layouts.admin')

@section('title', 'HK Overtime Command Center')

{{-- 01. IMPORT CSS KHUSUS --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/overtime/index.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4 hk-dashboard">
    {{-- 02. HK PREMIUM HEADER (IDENTITAS PERUSAHAAN) --}}
    <div class="hk-header-banner animate__animated animate__fadeIn">
        <div class="hk-banner-inner d-flex justify-content-between align-items-center">
            <div class="hk-title-area">
                <div class="hk-badge"><i class="bi bi-shield-check"></i> HK SYSTEM ACTIVE</div>
                <h1 class="hk-main-title">COMMAND CENTER <span class="text-yellow">OVERTIME</span></h1>
                <p class="hk-subtitle">Pemantauan real-time operasional lembur unit alat berat HK Equipment secara presisi.</p>
            </div>
            <div class="hk-stats d-none d-lg-flex gap-4">
                <div class="hk-stat-box">
                    <span class="hk-stat-label">UNIT BERJALAN</span>
                    <span class="hk-stat-value text-yellow">{{ $overtimes->where('status', 'approved')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 03. INDUSTRIAL FILTER PERIOD (SINKRONISASI DATA) --}}
    <div class="hk-filter-bar mt-4 mb-4 animate__animated animate__fadeIn">
        <div class="hk-filter-container shadow-sm border">
            <span class="hk-filter-label"><i class="bi bi-funnel-fill"></i> FILTER PERIODE:</span>
            <div class="hk-filter-actions">
                <a href="{{ request()->fullUrlWithQuery(['period' => 'all']) }}" class="hk-tab {{ request('period') == 'all' || !request('period') ? 'active' : '' }}">SEMUA DATA</a>
                <a href="{{ request()->fullUrlWithQuery(['period' => 'weekly']) }}" class="hk-tab {{ request('period') == 'weekly' ? 'active' : '' }}">MINGGUAN</a>
                <a href="{{ request()->fullUrlWithQuery(['period' => 'monthly']) }}" class="hk-tab {{ request('period') == 'monthly' ? 'active' : '' }}">BULANAN</a>
                <a href="{{ request()->fullUrlWithQuery(['period' => 'yearly']) }}" class="hk-tab {{ request('period') == 'yearly' ? 'active' : '' }}">TAHUNAN</a>
            </div>
        </div>
    </div>

    {{-- 04. MONITORING TABLE (KONTROL UTAMA) --}}
    <div class="hk-table-wrapper animate__animated animate__fadeInUp shadow-lg">
        <div class="table-responsive">
            <table class="table table-borderless align-middle hk-main-table text-center">
                <thead>
                    <tr>
                        <th class="ps-4 text-start">UNIT & DETAIL PENYEWA</th>
                        <th>STATUS OPERASI</th>
                        <th>MANAJEMEN TARIF</th>
                        <th>ESTIMASI BILLING</th>
                        <th class="pe-4 text-end">SISTEM KONTROL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overtimes as $ot)
                    <tr class="hk-row @if($ot->status === 'approved') row-running @endif"
                        data-id="{{ $ot->id }}" data-status="{{ $ot->status }}"
                        @if($ot->status === 'approved' && $ot->started_at)
                            data-start="{{ $ot->started_at->toIso8601String() }}"
                            data-price="{{ $ot->price_per_hour }}"
                        @endif>

                        {{-- INFORMASI UNIT --}}
                        <td class="ps-4 text-start">
                            <div class="hk-unit-box">
                                <div class="hk-unit-img">
                                    <img src="{{ asset('uploads/equipment/'.$ot->rental->equipment->image) }}" alt="Unit">
                                    <div class="hk-unit-id">#{{ $ot->rental_id }}</div>
                                </div>
                                <div class="hk-unit-meta">
                                    <h6 class="hk-unit-name">{{ $ot->rental->equipment->name }}</h6>
                                    <span class="hk-user-name"><i class="bi bi-person-circle"></i> {{ $ot->rental->user->name }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- TIMER STATUS --}}
                        <td>
                            @if($ot->status === 'pending')
                                <div class="hk-status pending">MENUNGGU KONFIRMASI</div>
                            @elseif($ot->status === 'approved')
                                <div class="hk-timer-display">
                                    <div class="hk-timer-label">DURASI BERJALAN</div>
                                    <div class="hk-timer-val" id="timer-{{ $ot->id }}">00:00:00</div>
                                </div>
                            @else
                                <div class="hk-status completed">LEMBUR SELESAI</div>
                            @endif
                        </td>

                        {{-- INPUT TARIF --}}
                        <td>
                            <div class="hk-pricing-box">
                                @if($ot->status === 'pending')
                                    <div class="hk-normal-hint">HARGA SEWA: Rp {{ number_format($ot->rental->equipment->price_per_hour, 0, ',', '.') }}</div>
                                    <form action="{{ route('admin.overtime.approve', $ot->id) }}" method="POST">
                                        @csrf
                                        <div class="hk-input-group">
                                            <input type="number" name="price_per_hour" placeholder="Input Tarif" required>
                                            <button type="submit"><i class="bi bi-play-fill"></i></button>
                                        </div>
                                    </form>
                                @else
                                    <div class="hk-fixed-price">
                                        <span class="label">TARIF FIXED</span>
                                        <span class="value">Rp {{ number_format($ot->price_per_hour, 0, ',', '.') }}<span>/Jam</span></span>
                                    </div>
                                @endif
                            </div>
                        </td>

                        {{-- BILLING LIVE --}}
                        <td>
                            <div class="hk-billing-box">
                                @if($ot->status === 'approved')
                                    <div class="hk-billing-val text-danger" id="cost-{{ $ot->id }}">Rp 0</div>
                                    <div class="hk-billing-sub pulse-danger">TAGIHAN AKTIF</div>
                                @elseif($ot->status === 'completed')
                                    <div class="hk-billing-val">Rp {{ number_format($ot->price, 0, ',', '.') }}</div>
                                    <div class="hk-billing-sub">TOTAL AKHIR</div>
                                @else
                                    <span class="text-muted small">---</span>
                                @endif
                            </div>
                        </td>

                        {{-- TOMBOL AKSI --}}
                        <td class="pe-4 text-end">
                            <div class="hk-action-group">
                                @if($ot->status === 'pending')
                                    <form action="{{ route('admin.overtime.reject', $ot->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="hk-btn reject" title="Reject Request"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @elseif($ot->status === 'approved')
                                    <form action="{{ route('admin.overtime.stop', $ot->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="hk-btn stop" title="Hentikan Paksa"><i class="bi bi-stop-fill"></i></button>
                                    </form>
                                @endif

                                @if($ot->status !== 'approved')
                                    <form action="{{ route('admin.overtime.delete', $ot->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus Log Data?')">
                                        @csrf @method('DELETE')
                                        <button class="hk-btn delete" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
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

{{-- 05. IMPORT JS KHUSUS --}}
@push('scripts')
    <script src="{{ asset('assets/js/admin/overtime/index.js') }}"></script>
@endpush