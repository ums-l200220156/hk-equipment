<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Sewa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/customer/payment/show.css') }}" rel="stylesheet">
</head>
<body>

<div class="payment-wrapper">
    <div class="payment-card">

        <!-- Header -->
        <div class="payment-header">
            <h2>💳 Pembayaran Sewa Alat</h2>
            <p>Silakan periksa detail dan pilih metode pembayaran</p>
        </div>

        <!-- Detail Sewa -->
            <div class="rental-info-modern">

                <!-- Nama Alat -->
                <div class="equipment-name">
                    {{ $rental->equipment->name }}
                </div>

                <!-- Grid Info -->
                <div class="rental-grid">
                    <div class="rental-item">
                        <span class="label">📅 Tanggal Sewa</span>
                        <span class="value">
                    {{ \Carbon\Carbon::parse($rental->rent_date)->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    <div class="rental-item">
                        <span class="label">⏱ Durasi</span>
                        <span class="value">{{ $rental->duration_hours }} Jam</span>
                    </div>

                    <div class="rental-item full">
                        <span class="label">📍 Lokasi</span>
                        <span class="value">{{ $rental->location }}</span>
                    </div>
                </div>

            </div>

        <!-- Total Harga -->
        <div class="price-box">
            <span>Total Pembayaran</span>
            <div class="price">
                Rp {{ number_format($rental->total_price) }}
            </div>
        </div>

        <!-- Form Pembayaran -->
        <form method="POST"
      action="{{ route('payment.process', $rental->id) }}"
      id="paymentForm">
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


                <!-- Tombol Batalkan Pesanan -->
                <form method="POST"
      action="{{ route('payment.cancel', $rental->id) }}"
      id="cancelForm"
      class="mt-3">
                    @csrf
                    <button type="submit" class="btn-cancel">
                        Batalkan Pesanan
                    </button>
                </form>

    </div>
</div>


<!-- 1. SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- 2. Helper SweetAlert -->
<script src="{{ asset('assets/js/sweetalert/swal-helper.js') }}"></script>

<script src="{{ asset('assets/js/sweetalert/payment-alert.js') }}"></script>

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
