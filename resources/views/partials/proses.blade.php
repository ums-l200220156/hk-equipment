<section id="proses" class="section-padding overflow-hidden">
    <div class="container">
        {{-- Header --}}
        <div class="text-center mb-5">
            <span class="text-danger fw-bold text-uppercase letter-spacing-2">How It Works</span>
            <h2 class="display-5 fw-bold mt-2">Alur Sewa Tanpa Ribet</h2>
            <p class="text-muted mx-auto" style="max-width: 550px;">
                Tiga langkah mudah mendapatkan dukungan armada tangguh untuk kesuksesan proyek Anda.
            </p>
        </div>

        <div class="row g-4 process-wrapper">
            {{-- Step 1 --}}
            <div class="col-lg-4 col-md-6">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5 class="fw-bold">Pilih Unit Jagoan</h5>
                    <p>Eksplorasi katalog armada kami. Pilih unit (Standard/Breaker) yang paling pas dengan spesifikasi medan proyek Anda.</p>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="col-lg-4 col-md-6">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5 class="fw-bold">Booking & Konfirmasi</h5>
                    <p>Selesaikan pembayaran secara instan melalui sistem. Admin kami akan melakukan verifikasi data dalam hitungan menit.</p>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="col-lg-4 col-md-6">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5 class="fw-bold">Unit Meluncur</h5>
                    <p>Unit dikirim langsung ke lokasi proyek Anda. Armada siap kerja maksimal, target proyek pun selesai tepat waktu!</p>
                </div>
            </div>
        </div>

        {{-- Final CTA --}}
        <div class="text-center mt-5">
            <a href="{{ route('customer.catalog') }}" class="btn btn-danger btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg transform-hover">
                <i class="bi bi-rocket-takeoff-fill me-2"></i> Mulai Sewa Unit Sekarang
            </a>
        </div>
    </div>
</section>