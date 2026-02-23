<section id="alat" class="section-padding">
    <div class="container">
        {{-- HEADER --}}
        <div class="text-center mb-5">
            <span class="section-label">Armada Kami</span>
            <h2 class="display-5 fw-bold">Unit Tangguh & Siap Tempur</h2>
            <p class="text-muted mx-auto" style="max-width:640px">
                Kami menyediakan unit excavator pilihan dalam kondisi prima. Sistem sewa lepas kunci untuk efisiensi penuh proyek Anda.
            </p>
        </div>

        {{-- ARMADA GRID --}}
        <div class="row g-4 justify-content-center">
            @php
            $armada = [
                [
                    'name' => 'Excavator Standard',
                    'category' => 'excavator',
                    'img' => 'excavator.png',
                    'price' => 'Rp 500.000 / Jam',
                    'tag' => 'Kualitas Prima',
                    'desc' => 'Ideal untuk pengerjaan galian tanah, pemuatan, dan perataan lahan.'
                ],
                [
                    'name' => 'Excavator Breaker',
                    'category' => 'excavator breaker',
                    'img' => 'breaker.png',
                    'price' => 'Rp 350.000 / Jam',
                    'tag' => 'Heavy Duty',
                    'desc' => 'Khusus pengerjaan penghancuran beton, aspal, dan material keras lainnya.'
                ]
            ];
            @endphp

            @foreach($armada as $alat)
            <div class="col-lg-5 col-md-6">
                <div class="alat-premium-card">
                    <div class="alat-premium-image">
                        <span class="alat-premium-badge">{{ $alat['tag'] }}</span>
                        <img src="{{ asset('uploads/'.$alat['img']) }}" alt="{{ $alat['name'] }}">
                    </div>

                    <div class="alat-premium-body">
                        <h4 class="alat-premium-title">{{ $alat['name'] }}</h4>
                        <p class="small text-muted mb-3">{{ $alat['desc'] }}</p>

                        <ul class="alat-premium-spec">
                            <li><i class="bi bi-shield-check-fill"></i> Maintenance Rutin Berkala</li>
                            <li><i class="bi bi-fuel-pump-fill"></i> Efisiensi Konsumsi BBM</li>
                            <li><i class="bi bi-gear-fill"></i> Sistem Hidrolik Responsif</li>
                        </ul>

                        <div class="alat-premium-footer">
                            <div class="alat-premium-price">{{ $alat['price'] }}</div>
                            @auth
                                <a href="{{ route('customer.catalog', ['category' => strtolower($alat['category']), 'lock' => 1]) }}"
                                   class="btn btn-danger w-100 fw-bold py-2 shadow-sm">
                                    Cek Ketersediaan Unit
                                </a>
                            @endauth
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-secondary w-100">
                                    Login untuk Sewa
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="text-center mt-5">
            <p class="text-muted small mb-4">*Harga di atas belum termasuk biaya mobilisasi dan BBM</p>
            
            {{-- BUTTON KEMBALI --}}
            <a href="{{ route('customer.catalog') }}"
               class="btn btn-outline-danger btn-lg fw-bold px-5 rounded-pill shadow-sm">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                Lihat Semua Armada
            </a>
        </div>
    </div>
</section>