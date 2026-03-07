@extends('layouts.base')

@section('title', $item->name . ' - HK Equipment')

@push('styles')
    <link href="{{ asset('assets/css/customer/catalog/show.css') }}" rel="stylesheet">
@endpush

@section('content')
<section class="detail-hero-compact">
    <div class="hero-overlay"></div>
    <div class="container position-relative">
        
       
        <div class="d-flex flex-column gap-2">
            
            
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-back-modern" onclick="if (history.length > 1) { history.back(); } else { window.location.href='{{ route('customer.catalog') }}'; }">
                    <i class="bi bi-arrow-left-short fs-5"></i> <span>Kembali</span>
                </button>

                <div class="header-right d-none d-md-block">
                    <span class="badge-category-small">
                        <i class="bi bi-tags-fill me-2"></i>{{ ucfirst($item->category) }}
                    </span>
                </div>
            </div>

            
            <div class="hero-title-center text-center mt-2 mt-md-0">
                <h1 class="hero-title-big text-white mb-0">{{ $item->name }}</h1>
            </div>
            
        </div>

    </div>
</section>

<div class="container detail-wrapper">
    <div class="detail-card shadow-sm border-0">
        <div class="row g-0 align-items-stretch">
            
            <div class="col-lg-6 border-end border-light bg-light-subtle">
                <div class="image-section-wrapper">
                    <div class="equipment-view-box" data-bs-toggle="modal" data-bs-target="#imgModal">
                        <img src="{{ asset('uploads/equipment/'.$item->image) }}" class="main-img" alt="{{ $item->name }}">
                        <div class="zoom-hint">
                            <i class="bi bi-arrows-fullscreen me-2"></i> Klik untuk Zoom
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="info-section-wrapper p-4 p-lg-5">
                    
                    <div id="statusBox" class="mb-4">
                        {{-- Akan diisi oleh JS --}}
                    </div>

                    <div class="content-header mb-4">
                        <h4 class="fw-bold text-dark mb-1">Spesifikasi Unit</h4>
                        <p class="text-muted small">Detail teknis armada operasional</p>
                    </div>
                    
                    <div class="detail-meta-grid mb-4">
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-gear"></i></div>
                            <div><span class="meta-label">Merk</span><span class="meta-value">{{ $item->brand ?? 'HK Standard' }}</span></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-calendar3"></i></div>
                            <div><span class="meta-label">Tahun</span><span class="meta-value">{{ $item->year ?? '-' }}</span></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-tag"></i></div>
                            <div><span class="meta-label">Kategori</span><span class="meta-value">{{ ucfirst($item->category) }}</span></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-shield-check"></i></div>
                                <div>
                                    <span class="meta-label">Kondisi</span>

                                    @if($item->status === 'maintenance')
                                        <span class="meta-value text-warning">Dalam Perawatan</span>
                                    @elseif($item->status === 'rented')
                                        <span class="meta-value text-secondary">Sedang Digunakan</span>
                                    @else
                                        <span class="meta-value text-success">Prima</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    <div class="price-box-premium mb-4 shadow-sm border">
                        <div class="row align-items-center">
                            <div class="col-md-6 text-center text-md-start">
                                <span class="text-muted small fw-bold d-block">HARGA SEWA</span>
                                <div class="price-wrapper">
                                    <span class="h3 fw-black text-danger mb-0">Rp {{ number_format($item->price_per_hour) }}</span>
                                    <span class="text-muted small">/ jam</span>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div id="actionBox">
                                    {{-- Tombol Sewa Muncul di Sini --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="description-area p-3 bg-light rounded-4">
                        <h6 class="fw-bold text-dark mb-2">Deskripsi Alat</h6>
                        <p class="text-muted small lh-lg mb-0">{{ $item->description }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="imgModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body text-center p-0">
                <img src="{{ asset('uploads/equipment/'.$item->image) }}" class="img-fluid rounded-4 shadow-lg border border-light border-opacity-10">
                <button type="button" class="btn btn-light rounded-pill mt-4 px-5 fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        window.CURRENT_EQUIPMENT_ID = {{ $item->id }};
        window.STATUS_ENDPOINT = "{{ route('customer.catalog.status', $item->id) }}";
        window.INITIAL_STATUS = {
            status: "{{ $item->status }}",
            maintenance_end_at: "{{ $item->maintenance_end_at ? $item->maintenance_end_at->translatedFormat('d F Y') : '' }}"
        };
    </script>
    <script src="{{ asset('assets/js/customer/catalog/show.js') }}"></script>
@endpush