<footer class="main-footer">
    <div class="container">
        <div class="row g-4 text-center text-lg-start"> {{-- Center di mobile, Start di desktop --}}
            
            {{-- Kolom 1: Profil --}}
            <div class="col-lg-4 col-md-12 footer-brand">
                <h4 class="text-danger mb-3">HK EQUIPMENT <span class="text-accent">🚧</span></h4>
                <p class="small mx-auto mx-lg-0" style="max-width: 400px;">
                    Partner terpercaya penyedia alat berat berkualitas tinggi untuk kebutuhan konstruksi, perkebunan, dan pertambangan di seluruh wilayah Jawa Tengah.
                </p>
                <div class="social-icons mt-4 justify-content-center justify-content-lg-start">
                    <a href="https://www.facebook.com/share/1GJeyU8eEG/" target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-facebook"></i>
                    </a>
                </div>
            </div>

            {{-- Kolom 2: Navigasi Cepat --}}
            <div class="col-lg-2 col-6"> {{-- Sejajar berdua saat mobile --}}
                <h6 class="footer-title">Navigasi</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('customer.home') }}#home">Beranda</a></li>
                    <li><a href="{{ route('customer.home') }}#keunggulan">Keunggulan</a></li>
                    <li><a href="{{ route('customer.home') }}#alat">Armada</a></li>
                    <li><a href="{{ route('customer.home') }}#testimoni">Testimoni</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Layanan --}}
            <div class="col-lg-2 col-6"> {{-- Sejajar berdua saat mobile --}}
                <h6 class="footer-title">Layanan</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('customer.home') }}#proses">Sewa Unit</a></li>
                    <li><a href="{{ route('customer.home') }}#alat">Perawatan</a></li>
                    <li><a href="https://wa.me/6281230054652111" target="_blank" rel="noopener noreferrer">Konsultasi</a></li>
                </ul>
            </div>

            {{-- Kolom 4: Kontak --}}
            <div class="col-lg-4 col-md-12">
                <h6 class="footer-title">Hubungi Kami</h6>
                <div class="footer-contact-card">
                    <p class="small mb-2 text-white-50">Layanan Pelanggan 24/7:</p>
                    <a href="https://wa.me/6281230054652111" class="text-danger fw-bold fs-5 text-decoration-none d-block">
                        <i class="bi bi-whatsapp me-2"></i> 081230054652111111
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom Copyright --}}
        <div class="footer-bottom text-center">
            <p class="mb-0 opacity-75">&copy; {{ date('Y') }} <strong>HK Equipment</strong>. All rights reserved.</p>
        </div>
    </div>
</footer>