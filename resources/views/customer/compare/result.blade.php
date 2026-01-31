<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hasil Perbandingan Alat</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/css/customer/compare/result.css') }}">
</head>
<body>

<div class="container py-4">

    {{-- ================= HEADER ================= --}}
    <div class="compare-result-header">
        <div class="compare-header-inner">

            {{-- TOMBOL KEMBALI (KIRI) --}}
            <button type="button"
                    class="btn btn-back-modern"
                    onclick="if (history.length > 1) { history.back(); } else { window.location.href='{{ route('customer.compare.select') }}'; }">
                <i class="bi bi-arrow-left-short"></i>
                Ubah Alat
            </button>

            {{-- TEKS HEADER (CENTER) --}}
            <div class="compare-header-text">
                <div class="compare-badge">
                    <i class="bi bi-bar-chart-steps"></i>
                    Mode Perbandingan
                </div>

                <h2 class="compare-title">Perbandingan Alat Berat</h2>

                <p class="compare-subtitle">
                    Bandingkan spesifikasi, status, dan harga alat berat secara berdampingan
                    untuk menentukan pilihan terbaik proyek Anda
                </p>
            </div>

        </div>
    </div>

    {{-- ================= GRID ================= --}}
    <div class="row justify-content-center g-4 compare-grid">

        @foreach($items as $item)
        <div class="col-md-4 compare-col">

            <div class="result-card">

                {{-- GAMBAR --}}
                <div class="image-wrapper">
                    <img src="{{ asset('uploads/equipment/'.$item->image) }}"
                         alt="{{ $item->name }}"
                         class="zoomable-image"
                         data-src="{{ asset('uploads/equipment/'.$item->image) }}">
                    <span class="zoom-hint">
                        <i class="bi bi-zoom-in"></i>
                    </span>
                </div>

                {{-- ISI --}}
                <div class="card-body">

                    <div>
                        <h5 class="title">{{ $item->name }}</h5>
                        <div class="category">{{ ucfirst($item->category) }}</div>

                        <div class="spec-list">
                            <div class="spec-item">
                                <span>Status</span>
                                <span class="value {{ $item->status === 'available' ? 'ok' : 'no' }}">
                                    <i class="bi {{ $item->status === 'available' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                    {{ $item->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>

                            <div class="spec-item">
                                <span>Harga</span>
                                <span class="value price">
                                    Rp {{ number_format($item->price_per_hour) }} / jam
                                </span>
                            </div>
                        </div>

                        <p class="desc">
                            {{ $item->description }}
                        </p>
                    </div>

                    {{-- TOMBOL DETAIL --}}
                    <a href="{{ route('customer.catalog.show',$item->id) }}"
                       class="btn btn-primary w-100">
                        <i class="bi bi-eye"></i>
                        Detail Alat
                    </a>

                </div>

            </div>

        </div>
        @endforeach

    </div>

</div>

{{-- ================= MODAL ZOOM ================= --}}
<div id="imageZoomModal" class="image-zoom-modal">
    <span class="close">&times;</span>
    <img class="zoom-image">
</div>

<script src="{{ asset('assets/js/customer/compare/result.js') }}"></script>

</body>
</html>
