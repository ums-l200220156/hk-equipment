@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pilih Alat untuk Dibandingkan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/css/customer/compare/select.css') }}">
</head>
<body>

{{-- HEADER --}}
<div class="compare-header">
    <div class="container">

        <div class="compare-header-inner">

            {{-- BACK BUTTON (LEFT) --}}
            <button type="button"
                    class="btn btn-back-modern"
                    onclick="if (history.length > 1) { history.back(); } else { window.location.href='{{ route('customer.catalog') }}'; }">
                <i class="bi bi-arrow-left-short"></i>
                Kembali ke Katalog
            </button>

            {{-- TITLE (CENTER) --}}
            <div class="compare-header-text">
                <h2>
                    <i class="bi bi-bar-chart-steps"></i>
                    Bandingkan Alat Berat
                </h2>
                <p>Pilih minimal 2 alat untuk melihat perbandingan spesifikasi</p>
            </div>

        </div>

    </div>
</div>


<div class="container py-4 mb-5">

    {{-- FILTER PANEL (CENTERED & COMPACT) --}}
    <div class="compare-filter-wrapper d-flex justify-content-center mb-4">
        <div class="compare-filter-compact">

            {{-- SEARCH --}}
            <div class="search-field">
                <i class="bi bi-search"></i>
                <input id="searchInput"
                       type="text"
                       class="form-control"
                       placeholder="Cari nama alat…">
            </div>

            {{-- CATEGORY --}}
            <select id="categoryFilter" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($equipment->pluck('category')->unique() as $cat)
                    <option value="{{ strtolower($cat) }}">
                        {{ ucfirst($cat) }}
                    </option>
                @endforeach
            </select>

            {{-- STATUS --}}
            <select id="statusFilter" class="form-select">
                <option value="">Semua Status</option>
                <option value="available">Tersedia</option>
                <option value="rented">Tidak Tersedia</option>
            </select>

        </div>
    </div>

    {{-- FORM --}}
    <form id="compareForm" method="POST" action="{{ route('customer.compare.result') }}">
        @csrf
        <input type="hidden" name="items" id="compareItems">

        <div class="row g-4" id="equipmentGrid">
            @foreach($equipment as $item)
            <div class="col-md-4 col-lg-3 eq-item"
                 data-id="{{ $item->id }}"
                 data-name="{{ strtolower($item->name) }}"
                 data-category="{{ strtolower($item->category) }}"
                 data-status="{{ $item->status }}">

                <div class="compare-card">

                    <span class="status-badge {{ $item->status }}">
                        {{ $item->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>

                    <span class="check-mark">
                        <i class="bi bi-check-lg"></i>
                    </span>

                    <img src="{{ asset('uploads/equipment/'.$item->image) }}" alt="{{ $item->name }}">

                    <div class="card-body">
                        <h6>{{ $item->name }}</h6>
                        <small class="text-muted">
                            {{ ucfirst($item->category) }}
                        </small>

                        <div class="price">
                            Rp {{ number_format($item->price_per_hour) }} / jam
                        </div>

                        <p>
                            {{ Str::limit($item->description, 60) }}
                        </p>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </form>
</div>

{{-- COMPARE TRAY --}}
<div class="compare-tray">
    <div>
        <strong><span id="compareCount">0</span></strong> alat dipilih
    </div>
    <button id="compareBtn" class="btn btn-primary" disabled>
        Bandingkan Sekarang
    </button>
</div>

<script src="{{ asset('assets/js/customer/compare/select.js') }}"></script>
</body>
</html>
