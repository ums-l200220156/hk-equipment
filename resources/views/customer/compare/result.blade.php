@extends('layouts.base')

@section('title', 'Hasil Perbandingan - HK Equipment')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/customer/compare/result.css') }}">
@endpush

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="compare-result-header">
        <div class="compare-header-inner">
            <button type="button" class="btn-back-modern"
                    onclick="if (history.length > 1) { history.back(); } else { window.location.href='{{ route('customer.compare.select') }}'; }">
                <i class="bi bi-arrow-left-short fs-5"></i>
                <span>Ubah Pilihan</span>
            </button>

            <div class="compare-header-text">
                <div class="compare-badge">
                    <i class="bi bi-bar-chart-steps me-2"></i>Mode Perbandingan
                </div>
                <h2 class="compare-title">Hasil Analisis Alat</h2>
                <p class="compare-subtitle">
                    Perbandingan spesifikasi mendalam untuk membantu Anda mengambil keputusan yang tepat.
                </p>
            </div>
        </div>
    </div>

    {{-- GRID HASIL --}}
    <div class="row justify-content-center g-4 compare-grid">
        @foreach($items as $item)
        <div class="col-md-6 col-lg-4 d-flex">
            <div class="result-card">
                {{-- GAMBAR --}}
                <div class="image-wrapper">
                    <img src="{{ asset('uploads/equipment/'.$item->image) }}" 
                         alt="{{ $item->name }}" 
                         class="zoomable-image"
                         data-src="{{ asset('uploads/equipment/'.$item->image) }}">
                    <span class="zoom-hint"><i class="bi bi-zoom-in"></i></span>
                </div>

                {{-- KONTEN --}}
                <div class="card-body">
                    <div class="mb-auto">
                        <h5 class="fw-bold mb-1">{{ $item->name }}</h5>
                        <div class="text-primary small fw-semibold mb-3">{{ ucfirst($item->category) }}</div>

                        <div class="spec-list">
                            <div class="spec-item">
                                <span class="text-muted">Status</span>
                                <span class="value {{ $item->status === 'available' ? 'ok' : 'no' }}">
                                    <i class="bi {{ $item->status === 'available' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                    {{ $item->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>

                            <div class="spec-item">
                                <span class="text-muted">Biaya Sewa</span>
                                <span class="value price text-danger">
                                    Rp {{ number_format($item->price_per_hour) }} <small class="text-muted fw-normal">/ jam</small>
                                </span>
                            </div>
                        </div>

                        <p class="desc">
                            {{ $item->description }}
                        </p>
                    </div>

                    <a href="{{ route('customer.catalog.show', $item->id) }}" class="btn btn-primary w-100 rounded-pill fw-bold py-2 mt-3">
                        <i class="bi bi-info-circle me-2"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- MODAL ZOOM --}}
<div id="imageZoomModal" class="image-zoom-modal">
    <span class="close">&times;</span>
    <img class="zoom-image">
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/customer/compare/result.js') }}"></script>
@endpush