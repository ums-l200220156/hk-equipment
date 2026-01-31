<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Alat - {{ $item->name }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- PREMIUM STYLE -->
<link href="{{ asset('assets/css/customer/catalog.css') }}" rel="stylesheet">
</head>

<body>

<!-- ================= HERO ================= -->
<section class="detail-hero">
    <div class="container">

        <!-- TOP BAR -->
        <div class="hero-top d-flex justify-content-between align-items-start">

                <a href="{{ route('customer.catalog') }}" class="btn btn-back mt-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Katalog
                </a>

                <div class="hero-right">

                    <!-- LABEL-->
                    <span class="detail-badge badge-offset">
                        <i class="bi bi-gear-wide-connected"></i>
                        {{ ucfirst($item->category) }}
                    </span>

                    <!-- ANIMASI -->
                    <div class="hero-anim hero-anim-lg">
                        <i class="bi bi-gear-fill gear gear-xl"></i>
                        <i class="bi bi-gear-fill gear gear-lg"></i>
                        <span class="pulse-line"></span>
                    </div>

                </div>
            </div>


        <!-- TITLE -->
        <div class="detail-hero-inner">
            <h1 class="detail-title">{{ $item->name }}</h1>
            <p class="detail-subtitle">
                Informasi lengkap unit alat berat dan status ketersediaan
            </p>
        </div>

    </div>
</section>

<!-- ================= CONTENT ================= -->
<div class="container detail-wrapper">

    <div class="detail-card">

        <div class="row g-5 align-items-start">

            <!-- IMAGE -->
            <div class="col-lg-6">
                <div class="detail-image zoomable"
                     data-bs-toggle="modal"
                     data-bs-target="#imageZoomModal">

                    <img src="{{ asset('uploads/equipment/'.$item->image) }}"
                         alt="{{ $item->name }}">

                    <div class="zoom-hint">
                        <i class="bi bi-zoom-in"></i> Klik untuk memperbesar
                    </div>
                </div>
            </div>

            <!-- INFO -->
            <div class="col-lg-6 detail-info">

                <div id="statusBox" class="mb-4"></div>

                <div class="detail-meta-grid mb-4">
                    <div class="meta-item">
                        <i class="bi bi-building"></i>
                        <div>
                            <span class="meta-label">Merk</span>
                            <span class="meta-value">{{ $item->brand ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="meta-item">
                        <i class="bi bi-calendar-event"></i>
                        <div>
                            <span class="meta-label">Tahun</span>
                            <span class="meta-value">{{ $item->year ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-price">
                    Rp {{ number_format($item->price_per_hour) }}
                    <span>/ jam</span>
                </div>

                <div class="detail-desc mb-4">
                    {{ $item->description }}
                </div>

                <div id="actionBox"></div>

            </div>
        </div>

    </div>
</div>

<!-- IMAGE ZOOM MODAL -->
<div class="modal fade" id="imageZoomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">

            <button type="button"
                    class="btn-close btn-close-white ms-auto me-3 mt-3"
                    data-bs-dismiss="modal"></button>

            <img src="{{ asset('uploads/equipment/'.$item->image) }}"
                 class="img-fluid rounded-4 shadow-lg"
                 alt="{{ $item->name }}">
        </div>
    </div>
</div>

@include('partials.footer')

<!-- GLOBAL DATA -->
<script>
window.CURRENT_EQUIPMENT_ID = {{ $item->id }};
window.STATUS_ENDPOINT = "{{ route('customer.catalog.status', $item->id) }}";
window.INITIAL_STATUS = {
    status: "{{ $item->status }}",
    maintenance_end_at: "{{ $item->maintenance_end_at
        ? $item->maintenance_end_at->translatedFormat('d F Y')
        : '' }}"
};
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/customer/catalog.js') }}"></script>

</body>
</html>
