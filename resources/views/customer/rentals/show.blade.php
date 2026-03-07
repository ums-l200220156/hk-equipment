@extends('layouts.base')

@section('title', 'Detail Transaksi #' . $rental->id)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/customer/rentals/show.css') }}">
@endpush

@section('content')
<section class="rental-detail-premium">
    <div class="container">

        {{-- 1. PAGE HEADER --}}
        <div class="page-main-header animate__animated animate__fadeIn">
            <h6 class="text-danger fw-bold text-uppercase tracking-widest">Customer Portal</h6>
            <h2 class="fw-black">DETAIL <span class="text-danger">TRANSAKSI</span></h2>
            <div class="header-line"></div>
        </div>

        {{-- 2. TOP NAVIGATION & ACTION --}}
        <div class="top-nav-wrapper animate__animated animate__fadeInDown">
            {{-- Tombol Kembali yang sudah diubah ke Route statis agar sinkron --}}
            <a href="{{ route('customer.rentals') }}" class="btn-back-fancy text-decoration-none">
                <div class="icon-wrap">
                    <i class="bi bi-chevron-left"></i>
                </div>
                <span>Kembali ke Daftar Sewa</span>
            </a>

            <div class="action-top">
                @if($rental->status === 'waiting_payment')
                    <form method="POST" action="{{ route('customer.rentals.cancel', $rental->id) }}" id="cancelForm">
                        @csrf
                        <button type="button" class="btn-danger-glass" onclick="confirmCancel()">
                            <i class="bi bi-x-lg"></i> Batalkan Pesanan
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="row g-4">
            {{-- ================= LEFT COLUMN: UNIT INFO & OVERTIME ================= --}}
            <div class="col-lg-5 animate__animated animate__fadeInLeft">
                <div class="premium-glass-card main-info">

                    {{-- UNIT IMAGE --}}
                    <div class="unit-image-showcase">
                        @if($rental->equipment->image)
                            <img src="{{ asset('uploads/equipment/'.$rental->equipment->image) }}" class="img-fluid unit-img-real">
                        @else
                            <div class="unit-img-placeholder">
                                <i class="bi bi-truck-flatbed"></i>
                                <p>Foto tidak tersedia</p>
                            </div>
                        @endif
                        <div class="unit-badge-overlay">
                            {{ $rental->equipment->category }}
                        </div>
                    </div>

                    {{-- UNIT NAME --}}
                    <div class="unit-name-wrapper text-center mt-3">
                        <h3 class="fw-bold mb-1">{{ $rental->equipment->name }}</h3>
                        <p class="text-muted small">Unit Alat Berat Konstruksi</p>
                    </div>

                    {{-- PRICE SHOWCASE --}}
                    <div class="price-showcase shadow-sm mt-3">
                        <p class="label-sm">Total Investasi Sewa</p>
                        <h1 class="total-amount">
                            Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                        </h1>
                        <div class="badge-status-glow status-{{ $rental->status }}">
                            {{ strtoupper(str_replace('_', ' ', $rental->status)) }}
                        </div>
                    </div>

                    {{-- OVERTIME SECTION --}}
                    @php $ot = $rental->overtime; @endphp
                    @if($rental->status === 'on_progress' || ($ot && $ot->status === 'completed'))
                        <div class="overtime-panel-high-notice mt-4">
                            <div class="ot-header-glow">
                                <i class="bi bi-lightning-charge-fill animate-flicker"></i>
                                <span>LAYANAN LEMBUR (OVERTIME)</span>
                            </div>

                            <div class="ot-content-body p-4" id="otWrapper">
                                {{-- 1. BELUM ADA OVERTIME --}}
                                @if(!$ot)
                                    <div class="alert alert-warning border-0 shadow-sm small">
                                        <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Info</h6>
                                        Tarif lembur dihitung real-time per detik setelah disetujui.
                                    </div>
                                    <form method="POST" action="{{ route('customer.overtime.store', $rental->id) }}">
                                        @csrf
                                        <button type="submit" class="btn-apply-ot-premium-glow">
                                            <span>Ajukan Lembur Sekarang</span>
                                            <i class="bi bi-arrow-right-circle-fill"></i>
                                        </button>
                                    </form>

                                {{-- 2. PENDING --}}
                                @elseif($ot->status === 'pending')
                                    <div class="text-center py-3" id="otApp" data-status="pending" data-id="{{ $ot->id }}">
                                        <div class="spinner-border text-danger mb-2" role="status"></div>
                                        <h6 class="fw-bold">Menunggu Persetujuan Admin</h6>
                                        <div class="d-flex gap-2 mt-3">
                                            <a href="https://wa.me/6281230054652111" class="btn btn-success flex-grow-1 fw-bold rounded-3" target="_blank">
                                                <i class="bi bi-whatsapp"></i> Chat Admin
                                            </a>
                                            <form action="{{ route('customer.overtime.cancel', $ot->id) }}" method="POST" id="cancelOtForm">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger rounded-3" onclick="confirmCancelOt()">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>


                                {{-- 3. JIKA DITOLAK --}}
                                @elseif($ot->status === 'rejected')
                                    <div class="text-center py-2 animate__animated animate__shakeX">
                                        <div class="icon-rejected-bg mb-3">
                                            <i class="bi bi-exclamation-octagon-fill text-danger fs-1"></i>
                                        </div>
                                        <h6 class="fw-bold text-danger">PENGAJUAN LEMBUR DITOLAK</h6>
                                        <p class="text-muted small">Maaf, permintaan lembur Anda saat ini tidak dapat disetujui oleh Admin.</p>
                                        
                                        <div class="d-flex gap-2 mt-3">
                                            <a href="https://wa.me/6281230054652111?text=Halo%20Admin%20HK%20Equipment,%20saya%20ingin%20menanyakan%20terkait%20penolakan%20lembur%20unit%20{{ $rental->equipment->name }}" 
                                            class="btn btn-dark w-100 fw-bold rounded-3" target="_blank">
                                                <i class="bi bi-chat-dots-fill me-2"></i> Hubungi Admin
                                            </a>
                                            {{-- Tombol untuk hapus status rejected agar customer bisa ajukan lagi jika perlu --}}
                                            <form action="{{ route('customer.overtime.cancel', $ot->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-secondary rounded-3 d-flex align-items-center gap-2" title="Bersihkan">
                                                    <i class="bi bi-trash"></i> 
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                {{-- 4. APPROVED / COMPLETED --}}
                                @elseif(in_array($ot->status, ['approved', 'completed']))
                                    <div id="realtimeOtApp" 
                                         data-id="{{ $ot->id }}"
                                         data-status="{{ $ot->status }}"
                                         data-start="{{ $ot->started_at ? $ot->started_at->toIso8601String() : '' }}" 
                                         data-price="{{ $ot->price_per_hour }}">
                                        
                                        <div class="ot-active-display shadow-sm rounded-4 p-3 border border-danger mb-3">
                                            <div class="row text-center">
                                                <div class="col-6 border-end">
                                                    <small class="text-muted d-block">⏱ DURASI LEMBUR</small>
                                                    <h4 class="fw-bold" id="displayTimer">
                                                        @if($ot->status === 'completed' && $ot->started_at && $ot->ended_at)
                                                        
                                                            @php
                                                                $diff = $ot->started_at->diffInSeconds($ot->ended_at);

                                                                $h = floor($diff / 3600);
                                                                $m = floor(($diff % 3600) / 60);
                                                                $s = $diff % 60;
                                                            @endphp

                                                            {{ sprintf('%02d:%02d:%02d', $h, $m, $s) }}

                                                        @else
                                                            00:00:00
                                                        @endif
                                                    </h4>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">💰 BIAYA LEMBUR</small>
                                                    <h4 class="fw-bold text-danger" id="displayCost">
                                                        Rp {{ number_format($ot->price, 0, ',', '.') }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>

                                    
                                    @if($ot->status === 'approved')
                                        <form action="{{ route('customer.overtime.stop', $ot->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-dark w-100 fw-bold py-2 rounded-3">HENTIKAN LEMBUR</button>
                                        </form>
                                    @else
                                        {{-- LOGIKA PEMBAYARAN DISINI --}}
                                        @if($ot->payment_status === 'paid')
                                            <div class="badge bg-success w-100 py-2 rounded-3"><i class="bi bi-check-circle"></i> PEMBAYARAN SELESAI</div>
                                        @elseif($ot->payment_method === 'cash')
                                            <div class="badge bg-warning text-dark w-100 py-2 rounded-3">MENUNGGU PEMBAYARAN CASH</div>
                                        @else
                                            <a href="{{ route('payment.overtime.show', $ot->id) }}" class="btn btn-danger w-100 fw-bold py-2 rounded-3 shadow-pulse">
                                                <i class="bi bi-wallet2"></i> BAYAR OVERTIME
                                            </a>
                                        @endif
                                    @endif

                                    
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ================= RIGHT COLUMN: SPECS ================= --}}
            <div class="col-lg-7 animate__animated animate__fadeInRight">
                <div class="premium-glass-card detail-specs">
                    <h4 class="specs-title"><i class="bi bi-info-circle-fill text-danger me-2"></i>Spesifikasi Penyewaan</h4>
                    <div class="specs-grid">
                        <div class="specs-item">
                            <div class="spec-icon-box"><i class="bi bi-calendar3"></i></div>
                            <div class="specs-content">
                                <label>Tanggal Sewa</label>
                                <p>{{ \Carbon\Carbon::parse($rental->rent_date)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="specs-item">
                            <div class="spec-icon-box"><i class="bi bi-clock"></i></div>
                            <div class="specs-content">
                                <label>Waktu Mulai</label>
                                {{-- Menggunakan date() untuk memotong detik --}}
                                <p>{{ date('H:i', strtotime($rental->start_time)) }} WIB</p>
                            </div>
                        </div>
                        <div class="specs-item">
                            <div class="spec-icon-box"><i class="bi bi-hourglass-split"></i></div>
                            <div class="specs-content">
                                <label>Durasi Kontrak</label>
                                <p>{{ $rental->duration_hours }} Jam</p>
                            </div>
                        </div>
                        <div class="specs-item">
                            <div class="spec-icon-box"><i class="bi bi-cash-stack"></i></div>
                            <div class="specs-content">
                                <label>Tarif Pokok</label>
                                <p>Rp {{ number_format($rental->equipment->price_per_hour, 0, ',', '.') }}/Jam</p>
                            </div>
                        </div>
                    </div>
                    <div class="location-container-modern mt-4">
                        <div class="location-header"><i class="bi bi-geo-alt-fill me-2"></i>Lokasi Operasional</div>
                        <div class="location-body"><p>{{ $rental->location }}</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/customer/rentals/show.js') }}"></script>
@endpush