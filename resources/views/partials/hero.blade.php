@php
$slides = [
    [
        'img' => asset('uploads/slide/slider1.jpg'),
        'title' => 'Solusi Alat Berat Profesional',
        'subtitle' => 'Armada Berkualitas • Operator Berpengalaman • Harga Transparan'
    ],
    [
        'img' => asset('uploads/slide/slider2.jpg'),
        'title' => 'Dukung Proyek Tanpa Hambatan',
        'subtitle' => 'Performa Maksimal untuk Target Tepat Waktu'
    ],
    [
        'img' => asset('uploads/slide/slider3.jpg'),
        'title' => 'Partner Konstruksi Terpercaya',
        'subtitle' => 'Dipercaya Berbagai Proyek Skala Kecil hingga Besar'
    ],
];
@endphp

<section id="home">
    <div id="heroCarousel"
     class="carousel slide"
     data-bs-ride="carousel"
     data-bs-interval="7000"
     data-bs-pause="false"
     data-bs-touch="false">


        {{-- INDICATORS --}}
        <div class="carousel-indicators">
            @foreach($slides as $i => $slide)
                <button type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="{{ $i }}"
                        class="{{ $i === 0 ? 'active' : '' }}"
                        aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $i + 1 }}">
                </button>
            @endforeach
        </div>

        {{-- SLIDES --}}
        <div class="carousel-inner">

            @foreach($slides as $i => $slide)
                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}"
                     style="background-image:url('{{ $slide['img'] }}')">

                    <div class="hero-overlay"></div>

                    <div class="carousel-caption">
                        <div class="hero-premium animate__animated animate__fadeInUp">

                            <div class="hero-badge-wrapper">
                                <span class="hero-badge">
                                    <i class="bi bi-shield-check"></i>
                                    HK Equipment Official
                                </span>
                            </div>

                            <h1 class="hero-title">
                                {{ $slide['title'] }}
                            </h1>

                            <p class="hero-subtitle">
                                {{ $slide['subtitle'] }}
                            </p>

                            <div class="hero-cta">
                                <a href="#alat"
                                   class="btn btn-danger btn-lg shadow">
                                    <i class="bi bi-truck-front-fill me-2"></i>
                                    Lihat Armada
                                </a>

                                <a href="https://wa.me/628123456789"
                                   target="_blank"
                                   class="btn btn-outline-light btn-lg ms-2">
                                    <i class="bi bi-whatsapp me-2"></i>
                                    Konsultasi
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- CONTROLS --}}
        <button class="carousel-control-prev"
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next"
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>
</section>
