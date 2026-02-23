<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Sewa - HK Equipment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('assets/css/customer/payment/show.css') }}" rel="stylesheet">
</head>
<body>

<div class="payment-wrapper">
    <div class="payment-card">

        <div class="payment-header">
            <h2>💳 Pembayaran Sewa Alat</h2>
            <p>Silakan periksa detail dan pilih metode pembayaran</p>
        </div>

        <div class="rental-info-modern">
            <div class="equipment-name">
                {{ $rental->equipment->name }}
            </div>

            <div class="rental-grid">
                <div class="rental-item">
                    <span class="label">📅 Tanggal Sewa</span>
                    <span class="value">
                        {{ \Carbon\Carbon::parse($rental->rent_date)->translatedFormat('d F Y') }}
                    </span>
                </div>

                <div class="rental-item">
                    <span class="label">⏱ Durasi & Jam Mulai</span>
                    <span class="value">
                        {{ $rental->duration_hours }} Jam (Mulai {{ date('H:i', strtotime($rental->start_time)) }})
                    </span>
                </div>

                <div class="rental-item full">
                    <span class="label">📍 Lokasi Pengerjaan</span>
                    <span class="value">{{ $rental->location }}</span>
                </div>

                @if($rental->notes)
                    <div class="rental-item full mt-2">
                        <span class="label">📝 Catatan Khusus</span>
                        <span class="value" style="font-style: italic; color: #666;">"{{ $rental->notes }}"</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="price-box">
            <span>Total Pembayaran</span>
            <div class="price">
                Rp {{ number_format($rental->total_price) }}
            </div>
        </div>

        <form method="POST" action="{{ route('payment.process', $rental->id) }}" id="paymentForm">
            @csrf

            <div class="payment-method">
                <label class="section-title">Metode Pembayaran</label>

                <label class="method-card">
                    <input type="radio" name="payment_method" value="transfer" required>
                    <div>
                        <strong>Transfer Bank</strong>
                        <p>Pembayaran melalui rekening bank</p>
                    </div>
                </label>

                <label class="method-card">
                    <input type="radio" name="payment_method" value="cash">
                    <div>
                        <strong>Bayar di Tempat</strong>
                        <p>Pembayaran tunai saat alat diterima</p>
                    </div>
                </label>
            </div>

            <button type="submit" class="btn-pay">
                Konfirmasi Pembayaran
            </button>
        </form>

        <form method="POST" action="{{ route('payment.cancel', $rental->id) }}" id="cancelForm" class="mt-3">
            @csrf
            <button type="button" class="btn-cancel w-100" onclick="handleCancelOrder()">
                Batalkan Pesanan
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('assets/js/sweetalert/swal-helper.js') }}"></script>

<script src="{{ asset('assets/js/customer/payment/show.js') }}"></script>

{{-- Notifikasi Session --}}
@if (session('success'))
<script>
    swalSuccess("{{ session('success') }}");
</script>
@endif

@if (session('error'))
<script>
    swalError("{{ session('error') }}");
</script>
@endif

</body>
</html>