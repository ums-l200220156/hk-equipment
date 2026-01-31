<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perbandingan Alat Berat</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f1f5f9;
            font-family: Inter, system-ui, -apple-system, sans-serif;
        }

        .compare-wrapper {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
        }

        .compare-header {
            font-weight: 800;
            font-size: 22px;
        }

        .compare-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            transition: .25s;
            background: #fff;
        }

        .compare-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(0,0,0,.12);
        }

        .compare-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
            background: #e2e8f0;
        }

        .spec-label {
            font-size: 13px;
            color: #64748b;
        }

        .spec-value {
            font-weight: 600;
            font-size: 15px;
        }

        .price {
            font-size: 20px;
            font-weight: 800;
            color: #16a34a;
        }

        .status-available {
            color: #16a34a;
            font-weight: 600;
        }

        .status-unavailable {
            color: #dc2626;
            font-weight: 600;
        }

        .description {
            font-size: 14px;
            color: #475569;
        }
    </style>
</head>
<body>

<div class="container py-4">

    <div class="compare-wrapper">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="compare-header">🔍 Perbandingan Alat Berat</h3>

            <a href="{{ route('customer.compare.select') }}"
               class="btn btn-outline-secondary btn-sm">
                Ganti Pilihan
            </a>
        </div>

        <div class="row g-4">

            @foreach($items as $item)
                <div class="col-md-4">

                    <div class="compare-card h-100">

                        {{-- FOTO --}}
                        @if($item->image)
                            <img src="{{ asset('uploads/equipment/'.$item->image) }}"
                                 class="compare-image">
                        @else
                            <div class="compare-image d-flex align-items-center justify-content-center">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif

                        <div class="p-3">

                            {{-- NAMA --}}
                            <h5 class="fw-bold mb-1">
                                {{ $item->name }}
                            </h5>

                            {{-- KATEGORI --}}
                            <div class="spec-label">
                                {{ ucfirst($item->category) }}
                            </div>

                            <hr>

                            {{-- STATUS --}}
                            <div class="mb-2">
                                <span class="spec-label">Status</span><br>
                                <span class="{{ $item->status === 'available' ? 'status-available' : 'status-unavailable' }}">
                                    {{ $item->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>

                            {{-- HARGA --}}
                            <div class="mb-3">
                                <span class="spec-label">Harga Sewa</span><br>
                                <span class="price">
                                    Rp {{ number_format($item->price_per_hour) }} / jam
                                </span>
                            </div>

                            {{-- DESKRIPSI --}}
                            <div class="description mb-3">
                                {{ $item->description }}
                            </div>

                            {{-- CTA --}}
                            <a href="{{ route('customer.catalog.show', $item->id) }}"
                               class="btn btn-primary btn-sm w-100">
                                Lihat Detail Alat
                            </a>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

</div>

</body>
</html>
