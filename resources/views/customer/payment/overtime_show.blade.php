<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Overtime</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/customer/payment/show.css') }}" rel="stylesheet">
</head>
<body>

<div class="payment-wrapper">
    <div class="payment-card">
        <div class="payment-header">
            <h2>⚡ Pembayaran Overtime</h2>
            <p>Selesaikan pembayaran biaya lembur unit</p>
        </div>

        <div class="rental-info-modern">
            <div class="equipment-name">
                {{ $overtime->rental->equipment->name }}
            </div>

            <div class="rental-grid">
                <div class="rental-item">
                    <span class="label">⏱ Total Durasi Lembur</span>
                    <span class="value">
                        @php
                            $h = floor($overtime->extra_hours);
                            $m = round(($overtime->extra_hours - $h) * 60);
                        @endphp
                        {{ $h }} Jam {{ $m }} Menit
                    </span>
                </div>

                <div class="rental-item">
                    <span class="label">💰 Tarif per Jam</span>
                    <span class="value">Rp {{ number_format($overtime->price_per_hour, 0, ',', '.') }}</span>
                </div>

                <div class="rental-item full">
                    <span class="label">📍 Lokasi Operasional</span>
                    <span class="value">{{ $overtime->rental->location }}</span>
                </div>
            </div>
        </div>

        <div class="price-box">
            <span>Total Biaya Lembur</span>
            <div class="price">
                Rp {{ number_format($overtime->price, 0, ',', '.') }}
            </div>
        </div>

        <form method="POST" action="{{ route('payment.overtime.process', $overtime->id) }}" id="paymentForm">
            @csrf
            <div class="payment-method">
                <label class="section-title">Metode Pembayaran</label>

                <label class="method-card">
                    <input type="radio" name="payment_method" value="transfer" required>
                    <div>
                        <strong>Transfer Bank</strong>
                        <p>Upload bukti transfer setelah ini</p>
                    </div>
                </label>

                <label class="method-card">
                    <input type="radio" name="payment_method" value="cash">
                    <div>
                        <strong>Bayar Cash (COD)</strong>
                        <p>Bayar tunai kepada petugas lapangan</p>
                    </div>
                </label>
            </div>

            <button type="submit" class="btn-pay">
                Konfirmasi Pembayaran Overtime
            </button>
        </form>

        <a href="{{ route('customer.rentals.show', $overtime->rental_id) }}" class="btn btn-link w-100 text-muted mt-2 text-decoration-none small">
            Kembali ke Detail Sewa
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>