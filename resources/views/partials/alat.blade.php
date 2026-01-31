<section id="alat" class="section-padding bg-light">
<div class="container">

    {{-- HEADER --}}
    <div class="text-center mb-5">
        <span class="section-label">Armada Unggulan</span>
        <h2 class="display-5 fw-bold">Unit Alat Berat Siap Kerja</h2>
        <p class="text-muted mx-auto" style="max-width:640px">
            Setiap unit dirawat secara berkala dan siap mendukung kelancaran proyek Anda.
        </p>
    </div>

    {{-- ARMADA --}}
    <div class="row g-4">

        @foreach([
            [
                'name'=>'Excavator Standard',
                'category'=>'excavator',
                'img'=>'excavator.png',
                'price'=>'Rp 500.000 / Jam',
                'tag'=>'Best Seller'
            ],
            [
                'name'=>'Excavator Breaker',
                'category'=>'excavator breaker',
                'img'=>'breaker.png',
                'price'=>'Rp 350.000 / Jam',
                'tag'=>'Heavy Duty'
            ],
            [
                'name'=>'Dump Truck 24T',
                'category'=>'dump truck',
                'img'=>'dumptruck.png',
                'price'=>'Rp 500.000 / Hari',
                'tag'=>'Ready'
            ]
        ] as $alat)


        <div class="col-lg-4 col-md-6">
            <div class="alat-premium-card">

                <div class="alat-premium-image">
                    <span class="alat-premium-badge">{{ $alat['tag'] }}</span>
                    <img src="{{ asset('uploads/'.$alat['img']) }}" alt="{{ $alat['name'] }}">
                </div>

                <div class="alat-premium-body">
                    <h4 class="alat-premium-title">{{ $alat['name'] }}</h4>

                    <ul class="alat-premium-spec">
                        <li><i class="bi bi-check-circle-fill"></i> Unit terawat</li>
                        <li><i class="bi bi-check-circle-fill"></i> Operator opsional</li>
                        <li><i class="bi bi-check-circle-fill"></i> Dukungan teknis</li>
                    </ul>

                    <div class="alat-premium-footer">
                        <div class="alat-premium-price">
                            {{ $alat['price'] }}
                        </div>

                       @auth
                        <a href="{{ route('customer.catalog', [
                                'category' => strtolower($alat['category']),
                                'lock' => 1
                            ]) }}"
                        class="btn btn-success w-100 fw-bold">
                            Cek Ketersediaan
                        </a>
                        @endauth

                        @guest
                            <button class="btn btn-secondary w-100" disabled>
                                Login untuk Melanjutkan
                            </button>
                        @endguest
                    </div>
                </div>

            </div>
        </div>

        @endforeach

    </div>

    {{-- CTA --}}
    <div class="text-center mt-5">
        <a href="{{ route('customer.catalog') }}"
           class="btn btn-outline-danger btn-lg fw-bold px-5">
            <i class="bi bi-grid-3x3-gap-fill me-2"></i>
            Lihat Semua Armada
        </a>
    </div>

</div>
</section>
