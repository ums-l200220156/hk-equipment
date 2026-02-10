@extends('layouts.base')

@section('title', 'Detail Transaksi #' . $rental->id)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/customer/rentals/show.css') }}">
@endpush

@section('content')
<section class="rental-detail-premium">
    <div class="container">
        
        {{-- PAGE HEADER --}}
        <div class="page-main-header animate__animated animate__fadeIn">
            <h6 class="text-danger fw-bold text-uppercase tracking-widest">Customer Portal</h6>
            <h2 class="fw-black">DETAIL <span class="text-danger">TRANSAKSI</span></h2>
            <div class="header-line"></div>
        </div>

      {{-- TOP NAVIGATION & ACTION --}}
        <div class="top-nav-wrapper animate__animated animate__fadeInDown">
            
            {{-- LOGIKA: Jika baru saja bayar, paksa ke index. Jika tidak, gunakan history back --}}
            <button type="button" 
                    class="btn-back-fancy" 
                    onclick="@if(session('just_paid')) 
                                window.location.href='{{ route('customer.rentals') }}'; 
                            @else 
                                if (history.length > 1) { history.back(); } else { window.location.href='{{ route('customer.rentals') }}'; } 
                            @endif">
                <div class="icon-wrap"><i class="bi bi-chevron-left"></i></div>
                <span>Kembali</span>
            </button>
            
            <div class="action-top">
                @if($rental->status === 'waiting_payment')
                    <form method="POST" action="{{ route('customer.rentals.cancel',$rental->id) }}" id="cancelForm">
                        @csrf
                        <button type="button" class="btn-danger-glass" onclick="confirmCancel()">
                            <i class="bi bi-x-lg"></i> Batalkan Pesanan
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="row g-4">
            {{-- KIRI: FOTO UNIT & HARGA --}}
            <div class="col-lg-5 animate__animated animate__fadeInLeft">
                <div class="premium-glass-card main-info">
                    
                    <div class="unit-image-showcase">
                        @if($rental->equipment->image)
                            <img src="{{ asset('uploads/equipment/' . $rental->equipment->image) }}" alt="{{ $rental->equipment->name }}" class="img-fluid unit-img-real">
                        @else
                            <div class="unit-img-placeholder">
                                <i class="bi bi-truck-flatbed"></i>
                                <p>Foto tidak tersedia</p>
                            </div>
                        @endif
                        <div class="unit-badge-overlay">{{ $rental->equipment->category }}</div>
                    </div>

                    <div class="unit-name-wrapper text-center mt-3">
                        <h3 class="fw-bold mb-1">{{ $rental->equipment->name }}</h3>
                        <p class="text-muted small">Unit Alat Berat Konstruksi</p>
                    </div>

                    <div class="price-showcase shadow-sm mt-3">
                        <p class="label-sm">Total Investasi Sewa</p>
                        <h1 class="total-amount">Rp {{ number_format($rental->total_price,0,',','.') }}</h1>
                        <div class="badge-status-glow status-{{ $rental->status }}">
                            {{ strtoupper(str_replace('_',' ',$rental->status)) }}
                        </div>
                    </div>

                    {{-- ================= OVERTIME PANEL (DIUBAH) ================= --}}
                    <div class="overtime-panel-high-notice mt-4">
                        <div class="ot-header-glow">
                            <i class="bi bi-lightning-charge-fill animate-flicker"></i>
                            <span>LAYANAN LEMBUR (OVERTIME)</span>
                        </div>
                        
                        <div class="ot-content-body">
                            @if($rental->status === 'on_progress' && $rental->overtime_hours > 0 && !$rental->overtime)
                                <form method="POST" action="{{ route('customer.overtime.store',$rental->id) }}">
                                    @csrf
                                    <button class="btn-apply-ot-premium-glow">
                                        <div class="btn-ot-text">
                                            <span>Ajukan Lembur Sekarang</span>
                                            <small>Tambahan: {{ $rental->overtime_hours }} Jam</small>
                                        </div>
                                        <i class="bi bi-arrow-right-circle-fill fs-3"></i>
                                    </button>
                                </form>
                            
                            @elseif($rental->overtime)
                                <div class="ot-card-active-modern shadow-sm">
                                    <div class="ot-status-bar">
                                        <span class="pulse-dot-large"></span>
                                        <span>STATUS: <b>{{ strtoupper($rental->overtime->status) }}</b></span>
                                    </div>
                                    
                                    @if($rental->overtime->status === 'approved')
                                        <div class="ot-price-tag-grid mt-3">
                                            <div class="price-tag-item">
                                                <small>Tarif / Jam</small>
                                                <p>Rp {{ number_format($rental->overtime->price_per_hour,0,',','.') }}</p>
                                            </div>
                                            <div class="price-tag-item">
                                                <small>Tarif / Menit</small>
                                                <p>Rp {{ number_format($rental->overtime->price_per_hour / 60,0,',','.') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="ot-btn-group mt-3 d-flex gap-2">
                                        <a target="_blank" href="https://wa.me/6281234567890?text={{ urlencode('Konfirmasi lembur #' . $rental->id) }}" class="btn-wa-modern flex-grow-1">
                                            <i class="bi bi-whatsapp"></i> Hubungi Admin
                                        </a>

                                        @if($rental->overtime->status === 'pending')
                                            <form method="POST" action="{{ route('customer.overtime.cancel',$rental->overtime->id) }}" id="cancelOtForm">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn-cancel-ot-modern" onclick="confirmCancelOt()">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- KETERANGAN DISINI --}}
                                <div class="ot-info-locked">
                                    <div class="locked-icon">
                                        <i class="bi bi-lock-fill"></i>
                                    </div>
                                    <div class="locked-text">
                                        <p>Fitur lembur akan terbuka secara otomatis jika status pengerjaan telah <b>BERJALAN (On Progress)</b>.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN: SPESIFIKASI & LOGISTIK --}}
            <div class="col-lg-7 animate__animated animate__fadeInRight">
                <div class="premium-glass-card detail-specs">
                    <h4 class="specs-title"><i class="bi bi-info-circle-fill text-danger me-2"></i> Spesifikasi Penyewaan</h4>
                    
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
                                <p>{{ $rental->start_time }} WIB</p>
                            </div>
                        </div>
                        <div class="specs-item">
                            <div class="spec-icon-box"><i class="bi bi-hourglass-split"></i></div>
                            <div class="specs-content">
                                <label>Durasi Kontrak</label>
                                <p>{{ $rental->duration_hours }} Jam Kerja</p>
                            </div>
                        </div>
                        <div class="specs-item">
                            <div class="spec-icon-box"><i class="bi bi-cash-stack"></i></div>
                            <div class="specs-content">
                                <label>Tarif Sewa Pokok</label>
                                <p>Rp {{ number_format($rental->equipment->price_per_hour,0,',','.') }} / Jam</p>
                            </div>
                        </div>
                    </div>

                    <div class="location-container-modern mt-4">
                        <div class="location-header">
                            <div class="loc-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <span>Lokasi Operasional</span>
                        </div>
                        <div class="location-body">
                            <p>{{ $rental->location }}</p>
                        </div>
                    </div>

                    <div class="notes-container-modern mt-4">
                        <div class="notes-header">
                            <i class="bi bi-chat-left-text-fill"></i> Catatan Pemesanan
                        </div>
                        <div class="notes-body">
                            <p>{{ $rental->notes ?? 'Tidak ada catatan tambahan untuk pesanan ini.' }}</p>
                        </div>
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