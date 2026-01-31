@php
    $selectedCategory = request('category');
    $lockCategory = request('lock') == 1;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Katalog Alat Berat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- PREMIUM STYLE -->
<link href="{{ asset('assets/css/customer/catalog.css') }}" rel="stylesheet">
</head>

<body>

<!-- ===== PAGE HEADER ===== -->
<section class="catalog-hero">
    <div class="container">

        <!-- TOP BAR (SAMA POLA DENGAN SHOW) -->
        <div class="hero-top d-flex justify-content-between align-items-start">

            <!-- LEFT -->
            <div class="hero-left">

                <a href="{{ route('customer.home') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Beranda
                </a>

                @php
                    $titleCategory = 'Alat Berat';
                    if($selectedCategory){
                        $titleCategory = ucwords(str_replace('-', ' ', $selectedCategory));
                    }
                @endphp

                <h1 class="hero-title mt-5">
                    Katalog Armada {{ $titleCategory }}
                </h1>

                <p class="hero-subtitle">
                    Menampilkan unit {{ strtolower($titleCategory) }} yang tersedia
                    untuk mendukung kebutuhan proyek Anda
                </p>

            </div>

            <!-- RIGHT : ANIMASI (IDENTIK DENGAN SHOW) -->
            <div class="hero-right">

                <div class="hero-anim hero-anim-lg">
                    <i class="bi bi-gear-fill gear gear-xl"></i>
                    <i class="bi bi-gear-fill gear gear-lg"></i>
                    <span class="pulse-line"></span>
                </div>

            </div>

        </div>

    </div>
</section>



<!-- ===== CONTENT ===== -->
<div class="container catalog-wrapper">

    <!-- FILTER PANEL -->
    <div class="filter-panel">
        <div class="row g-3 align-items-center">

            <div class="col-lg-4">
                <div class="filter-input filter-search">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text"
                        id="searchInput"
                        class="form-control"
                        placeholder="Cari alat berat...">
                </div>
            </div>

            <div class="col-lg-4">
                <div class="filter-input">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <select id="categorySelect"
                            class="form-select"
                            {{ $lockCategory ? 'disabled' : '' }}>
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ strtolower($cat->category) }}"
                                {{ strtolower($cat->category) == $selectedCategory ? 'selected' : '' }}>
                                {{ ucfirst($cat->category) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($lockCategory)
                    <input type="hidden" id="lockedCategory" value="{{ $selectedCategory }}">
                @endif
            </div>

            <div class="col-lg-4">
                <div class="filter-input">
                    <i class="bi bi-activity"></i>
                    <select id="statusFilter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="available">Tersedia</option>
                        <option value="rented">Disewa</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    <!-- GRID -->
    <div class="catalog-grid mb-5">
        @foreach($equipment as $item)
        <div class="catalog-item"
             data-name="{{ strtolower($item->name) }}"
             data-category="{{ strtolower($item->category) }}"
             data-status="{{ $item->status }}">

            <div class="equipment-card">

            <!-- STATUS BAR -->
            <div class="status-bar {{ $item->status }}"></div>

            <!-- STATUS LABEL -->
            <span class="status-label {{ $item->status }}">
                @if($item->status === 'available')
                    Tersedia
                @elseif($item->status === 'rented')
                    Disewa
                @else
                    Maintenance
                @endif
            </span>


                <div class="equipment-image">
                    <img src="{{ asset('uploads/equipment/'.$item->image) }}">
                </div>

                <div class="equipment-body">
                    <span class="equipment-category">
                        {{ ucfirst($item->category) }}
                    </span>

                    <h5>{{ $item->name }}</h5>

                    <div class="price">
                        Rp {{ number_format($item->price_per_hour) }}
                        <span>/ jam</span>
                    </div>

                    <a href="{{ route('customer.catalog.show',$item->id) }}"
                       class="btn btn-outline-primary w-100 mt-3">
                        <i class="bi bi-eye"></i> Detail Alat
                    </a>
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>

@include('partials.footer')

<script src="{{ asset('assets/js/customer/catalog.js') }}"></script>
</body>
</html>
