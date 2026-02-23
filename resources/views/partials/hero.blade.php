@php
$slides = [
    [
        'img' => asset('uploads/slide/slider1.jpg'),
        'badge' => 'SEWA UNIT TANPA OPERATOR',
        'title' => 'Alat Berat Siap Pakai, Kendali di Tangan Anda',
        'subtitle' => 'Unit prima, langsung kerja di proyek Anda. Tanpa ribet, tanpa ketergantungan operator.'
    ],
    [
        'img' => asset('uploads/slide/slider2.jpg'),
        'badge' => 'EFISIEN & TERPERCAYA',
        'title' => 'Performa Tinggi dengan Biaya Lebih Terkontrol',
        'subtitle' => 'Maintenance rutin memastikan alat selalu siap. Anda fokus target, kami siapkan unit terbaik.'
    ],
    [
        'img' => asset('uploads/slide/slider3.jpg'),
        'badge' => 'PROSES CEPAT & MUDAH',
        'title' => 'Sewa Alat Berat Tanpa Ribet',
        'subtitle' => 'Booking cepat, transparan, dan siap kirim ke lokasi proyek Anda kapan saja.'
    ],
];
@endphp

<section id="home">
    <div id="heroCarousel" class="carousel slide carousel-fade">
        <div class="carousel-indicators">
            @foreach($slides as $i => $slide)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach($slides as $i => $slide)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                <img src="{{ $slide['img'] }}" class="d-block w-100 hero-img" alt="HK Equipment">
                <div class="carousel-caption">
                    <div class="hero-premium">
                        <div class="hero-badge-wrapper">
                            <span class="hero-badge">{{ $slide['badge'] }}</span>
                        </div>
                        <h1 class="hero-title">{{ $slide['title'] }}</h1>
                        <p class="hero-subtitle">{{ $slide['subtitle'] }}</p>
                        <div class="hero-cta">
                            <a href="#alat" class="btn btn-danger btn-lg shadow">Pilih Armada</a>
                            <a href="https://wa.me/628123456789" target="_blank" class="btn btn-outline-light btn-lg ms-2">Konsultasi Unit</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>