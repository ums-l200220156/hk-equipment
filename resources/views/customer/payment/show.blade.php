<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f8fafc; }
        .card { border-radius: 20px; }
        .price { font-size: 28px; font-weight: 800; color: #16a34a; }
        .pay-btn { border-radius: 16px; padding: 14px; font-weight: 700; }
    </style>
</head>
<body>

<div class="container py-5" style="max-width: 720px">

    <h3 class="fw-bold mb-4">💳 Pembayaran Sewa</h3>

    <div class="card p-4 shadow-sm mb-4">
        <h5 class="fw-bold">{{ $rental->equipment->name }}</h5>
        <p class="mb-1">Tanggal: {{ $rental->rent_date }}</p>
        <p class="mb-1">Durasi: {{ $rental->duration_hours }} Jam</p>
        <p class="mb-1">Lokasi: {{ $rental->location }}</p>

        <hr>

        <div class="price">
            Rp {{ number_format($rental->total_price) }}
        </div>
    </div>

    <form method="POST" action="{{ route('payment.process', $rental->id) }}">
        @csrf

        <label class="fw-semibold mb-2">Pilih Metode Pembayaran</label>

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="transfer" required>
                <label class="form-check-label">Transfer Bank</label>
            </div>

            <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="payment_method" value="cash">
                <label class="form-check-label">Bayar di Tempat (Cash)</label>
            </div>
        </div>

        <button class="btn btn-success w-100 pay-btn">
            Konfirmasi Pembayaran
        </button>
    </form>

</div>

</body>
</html>
