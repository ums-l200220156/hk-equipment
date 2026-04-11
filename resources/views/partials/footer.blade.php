<footer class="main-footer">
    @php
        // Mengambil data user dengan role admin untuk mendapatkan nomor HP dinamis
        $admin = \App\Models\User::where('role', 'admin')->first();
        $adminPhone = $admin ? $admin->phone : '081230054652'; // Nomor default jika data di database kosong
        
        // Membersihkan karakter selain angka (menghapus spasi, strip, dll) untuk link wa.me
        $cleanPhone = preg_replace('/[^0-9]/', '', $adminPhone);
        
        // Memastikan format link WA diawali dengan 62 (jika admin input mulai dari 0)
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
    @endphp

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
                    {{-- Link Konsultasi Dinamis --}}
                    <li><a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer">Konsultasi</a></li>
                </ul>
            </div>

            {{-- Kolom 4: Kontak --}}
            <div class="col-lg-4 col-md-12">
                <h6 class="footer-title">Hubungi Kami</h6>
                <div class="footer-contact-card">
                    <p class="small mb-2 text-white-50">Layanan Pelanggan 24/7:</p>
                    {{-- Link dan Tampilan Nomor HP Dinamis --}}
                    <a href="https://wa.me/{{ $cleanPhone }}" class="text-danger fw-bold fs-5 text-decoration-none d-block" target="_blank">
                        <i class="bi bi-whatsapp me-2"></i> {{ $adminPhone }}
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