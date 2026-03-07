@extends('layouts.base')

@section('title', 'Pilih Alat untuk Dibandingkan - HK Equipment')

@push('styles')
    {{-- Memanggil file CSS eksternal yang baru dibuat --}}
    <link rel="stylesheet" href="{{ asset('assets/css/customer/compare/select.css') }}">
@endpush

@section('content')
{{-- HEADER --}}
<div class="compare-header">
    <div class="container">
        <div class="compare-header-inner">
            <button type="button" class="btn btn-back-modern" 
                    onclick="if (history.length > 1) { history.back(); } else { window.location.href='{{ route('customer.catalog') }}'; }">
                <i class="bi bi-arrow-left-short fs-5"></i> <span>Kembali ke Katalog</span>
            </button>

            <div class="compare-header-text">
                <h2>Bandingkan Alat Berat</h2>
                <p>Pilih minimal 2 alat untuk dibandingkan</p>
            </div>
            <div style="width: 140px;" class="d-none d-md-block"></div>
        </div>
    </div>
</div>

<div class="container py-4">
    {{-- FILTER PANEL --}}
    <div class="compare-filter-wrapper">
        <div class="compare-filter-compact">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-primary"></i>
                <input id="searchInput" type="text" class="form-control ps-5 border-0 bg-light" placeholder="Cari nama alat…">
            </div>

            <select id="categoryFilter" class="form-select border-0 bg-light">
                <option value="">Semua Kategori</option>
                @foreach($equipment->pluck('category')->unique() as $cat)
                    <option value="{{ strtolower($cat) }}">{{ ucfirst($cat) }}</option>
                @endforeach
            </select>

            <select id="statusFilter" class="form-select border-0 bg-light">
                <option value="">Semua Status</option>
                <option value="available">Tersedia</option>
                <option value="rented">Tidak Tersedia</option>
            </select>
        </div>
    </div>

    {{-- GRID ALAT --}}
    <form id="compareForm" method="POST" action="{{ route('customer.compare.result') }}">
        @csrf
        <input type="hidden" name="items" id="compareItems">

        <div class="row g-3 g-md-4" id="equipmentGrid">
            @foreach($equipment as $item)
            <div class="col-6 col-md-4 col-lg-3 eq-item" 
                 data-id="{{ $item->id }}" 
                 data-name="{{ strtolower($item->name) }}" 
                 data-category="{{ strtolower($item->category) }}" 
                 data-status="{{ $item->status }}">
                
                <div class="compare-card">
                    <span class="status-badge {{ $item->status }}">
                        {{ $item->status === 'available' ? 'Tersedia' : 'Rented' }}
                    </span>
                    <span class="check-mark"><i class="bi bi-check-lg"></i></span>
                    
                    <img src="{{ asset('uploads/equipment/'.$item->image) }}" alt="{{ $item->name }}">
                    
                    <div class="card-body">
                        <h6>{{ $item->name }}</h6>
                        <div class="text-danger fw-bold small">Rp {{ number_format($item->price_per_hour) }} <span class="text-muted fw-normal">/jam</span></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </form>
</div>

{{-- BOTTOM TRAY --}}
<div class="compare-tray" id="compareTray">
    <div>
        <i class="bi bi-layers text-primary me-2"></i>
        <strong><span id="compareCount">0</span></strong> alat dipilih
    </div>
    <button id="compareBtn" class="btn btn-primary px-5 fw-bold rounded-pill shadow" disabled>
        Bandingkan Sekarang
    </button>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/customer/compare/select.js') }}"></script>
@endpush