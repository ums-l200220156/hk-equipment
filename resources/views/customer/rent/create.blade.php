<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sewa Alat - {{ $equipment->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-soft: #e5e7eb;
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #16a34a;
        }

        body {
            background: var(--bg-main);
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--text-main);
        }

        .container {
            max-width: 1100px;
        }

        /* BACK BUTTON */
        .btn-back {
            background: #e5e7eb;
            color: #1f2937;
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 18px;
            border: none;
            text-decoration: none;
        }

        .btn-back:hover {
            background: #d1d5db;
        }

        /* CARD */
        .rent-card {
            background: var(--bg-card);
            border-radius: 22px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
            padding: 28px;
        }

        /* IMAGE */
        .image-box img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 18px;
            background: #e5e7eb;
        }

        /* EQUIPMENT INFO */
        .equipment-title {
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .info-text {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .price {
            font-size: 26px;
            font-weight: 800;
            color: var(--success);
            margin-top: 12px;
        }

        .price span {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-muted);
        }

        .badge-available {
            background: #dcfce7;
            color: #166534;
            font-weight: 600;
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 12px;
            display: inline-block;
        }

        /* FORM */
        .form-label {
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.15rem rgba(37,99,235,.25);
        }

        /* TOTAL PRICE */
        .total-price {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            background: #f1f5f9;
        }

        /* SUBMIT BUTTON */
        .btn-submit {
            background: var(--primary);
            border: none;
            border-radius: 16px;
            padding: 14px;
            font-weight: 700;
            font-size: 16px;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>

<div class="container py-4">

    <a href="{{ route('customer.catalog') }}" class="btn-back mb-4 d-inline-block">
        ← Kembali ke Katalog
    </a>

    <h2 class="mb-4">Form Penyewaan Alat</h2>

    <div class="rent-card">
        <div class="row g-4">

            <!-- LEFT: EQUIPMENT INFO -->
            <div class="col-md-4">
                <div class="image-box mb-3">
                    @if($equipment->image)
                        <img src="{{ asset('uploads/equipment/'.$equipment->image) }}">
                    @endif
                </div>

                <h5 class="equipment-title">{{ $equipment->name }}</h5>

                <div class="info-text">
                    Kategori: <strong>{{ ucfirst($equipment->category) }}</strong>
                </div>

                <div class="price">
                    Rp {{ number_format($equipment->price_per_hour) }}
                    <span>/ jam</span>
                </div>

                <div class="mt-2">
                    <span class="badge-available">Tersedia</span>
                </div>
            </div>

            <!-- RIGHT: FORM -->
            <div class="col-md-8">
                <form method="POST" action="{{ route('rent.store', $equipment->id) }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Sewa</label>
                            <input type="date" name="rent_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Durasi (Jam)</label>
                            <input type="number" name="duration_hours" class="form-control" min="1" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Total Harga</label>
                            <input type="text" id="totalPrice" class="form-control total-price" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Lokasi Proyek</label>
                            <input type="text" name="location" class="form-control"
                                   placeholder="Contoh: Jl. Sudirman No. 32, Bandung" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-submit w-100 mt-2">
                                Kirim Permintaan Sewa
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<script>
    const durationInput = document.querySelector('[name="duration_hours"]');
    const totalPriceInput = document.getElementById('totalPrice');
    const pricePerHour = {{ $equipment->price_per_hour }};

    durationInput.addEventListener('input', () => {
        const hours = durationInput.value || 0;
        const total = hours * pricePerHour;
        totalPriceInput.value = 'Rp ' + total.toLocaleString('id-ID');
    });
</script>

</body>
</html>
