@extends('layouts.base')

@section('title', 'Katalog Armada - HK Equipment')

@push('styles')
    <link href="{{ asset('assets/css/customer/catalog/index.css') }}" rel="stylesheet">
@endpush

@section('content')
@php
    $selectedCategory = request('category');
    $lockCategory = request('lock') == 1;
@endphp

<section class="catalog-hero-premium">
    <div class="hero-overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 text-start">
                <button type="button" class="btn btn-back-glass mb-4" 
                        onclick="if (history.length > 1) { history.back(); } else { window.location.href='{{ route('customer.home') }}'; }">
                    <i class="bi bi-arrow-left-circle me-2"></i> Beranda
                </button>
                @php
                    $titleCategory = $selectedCategory ? ucwords(str_replace('-', ' ', $selectedCategory)) : 'SEMUA ARMADA';
                @endphp
                <h1 class="catalog-title-main fw-black">
                    <span class="text-warning">KATALOG</span> 
                    <span class="text-white">{{ $titleCategory }}</span>
                </h1>
            </div>
            <div class="col-lg-5 text-center text-lg-end mt-4 mt-lg-0">
                <div class="compare-badge-box shadow-lg">
                    <i class="bi bi-gear-wide-connected gear-spin"></i>
                    <h6 class="text-white mb-2">Ingin membandingkan spesifikasi?</h6>
                    <a href="{{ route('customer.compare.select') }}" class="btn btn-danger rounded-pill px-4 fw-bold">
                        <i class="bi bi-shuffle me-2"></i> Bandingkan Alat
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container catalog-content-wrapper">
    <div class="filter-panel-premium shadow-lg mb-5">
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="filter-group">
                    <label class="small fw-bold text-muted mb-2 text-uppercase">Pencarian Alat</label>
                    <div class="filter-input-wrapper">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" class="form-control-custom" placeholder="Cari nama unit...">
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="filter-group">
                    <label class="small fw-bold text-muted mb-2 text-uppercase">Kategori Unit</label>
                    <div class="filter-input-wrapper">
                        <i class="bi bi-tags"></i>
                        <select id="categorySelect" class="form-select-custom" {{ $lockCategory ? 'disabled' : '' }}>
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ strtolower($cat->category) }}" {{ strtolower($cat->category) == $selectedCategory ? 'selected' : '' }}>
                                    {{ ucfirst($cat->category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if($lockCategory)
                    <input type="hidden" id="lockedCategory" value="{{ $selectedCategory }}">
                @endif
            </div>
            <div class="col-lg-4">
                <div class="filter-group">
                    <label class="small fw-bold text-muted mb-2 text-uppercase">Filter Status</label>
                    <div class="filter-input-wrapper">
                        <i class="bi bi-activity"></i>
                        <select id="statusFilter" class="form-select-custom">
                            <option value="">Semua Status</option>
                            <option value="available">Unit Tersedia</option>
                            <option value="rented">Sedang Disewa</option>
                            <option value="maintenance">Dalam Perawatan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="catalog-grid mb-5" id="equipment-container">
        @forelse($equipment as $item)
        <div class="catalog-item" 
             data-id="{{ $item->id }}"
             data-name="{{ strtolower($item->name) }}" 
             data-category="{{ strtolower($item->category) }}" 
             data-status="{{ $item->status }}">
            
            <div class="equipment-card-premium h-100 shadow-sm">
                {{-- Dynamic Status Label --}}
                <div class="status-badge-container" id="status-badge-{{ $item->id }}">
                    <span class="status-pill {{ $item->status }}">
                        <i class="bi bi-circle-fill pulse-dot me-1"></i>
                        {{ $item->status === 'available' ? 'Tersedia' : ($item->status === 'rented' ? 'Disewa' : 'Maintenance') }}
                    </span>
                </div>

                <div class="equipment-image-box">
                    <img src="{{ asset('uploads/equipment/'.$item->image) }}" class="img-fluid" alt="{{ $item->name }}">
                </div>

                <div class="equipment-details-box">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="category-tag">{{ strtoupper($item->category) }}</span>
                        <div class="year-label">{{ $item->year ?? '-' }}</div>
                    </div>
                    <h5 class="equipment-name text-dark fw-bold mb-3">{{ $item->name }}</h5>
                    
                    <div class="price-section-mini mb-3">
                        <span class="text-muted small d-block">Harga Sewa</span>
                        <div class="d-flex align-items-baseline">
                            <h4 class="text-danger fw-black mb-0">Rp {{ number_format($item->price_per_hour) }}</h4>
                            <span class="ms-1 text-muted small">/ jam</span>
                        </div>
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('customer.catalog.show', $item->id) }}" class="btn btn-premium-action py-2">
                            Lihat Detail <i class="bi bi-arrow-right-short ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="bi bi-search display-1 text-muted opacity-25"></i>
                    <p class="mt-4 lead text-muted">Maaf, unit yang Anda cari belum tersedia.</p>
                    <button class="btn btn-outline-danger btn-sm" onclick="location.reload()">Reset Filter</button>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
    {{-- 1. Library SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 2. Konfigurasi Variabel Laravel untuk JS --}}
    <script>
        const CATALOG_URL = "{{ route('customer.catalog') }}";

        // Notifikasi Error (Gagal Sewa)
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memesan',
                text: "{{ session('error') }}",
                showCloseButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Tutup'
            });
        @endif

        // Notifikasi Sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                showCloseButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Mantap'
            });
        @endif
    </script>

    {{-- 3. File JS Utama dengan Anti-Cache (?v=...) --}}
    <script src="{{ asset('assets/js/customer/catalog/index.js') }}?v={{ time() }}"></script>
@endpush