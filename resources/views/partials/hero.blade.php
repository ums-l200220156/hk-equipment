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
     class="carousel slide carousel-fade"
     data-bs-ride="carousel"
     data-bs-interval="7000">

    {{-- Indicators --}}
    <div class="carousel-indicators">
        @foreach($slides as $i => $slide)
        <button type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide-to="{{ $i }}"
                class="{{ $i === 0 ? 'active' : '' }}">
        </button>
        @endforeach
    </div>

    {{-- Slides --}}
    <div class="carousel-inner">

        @foreach($slides as $i => $slide)
        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">

            {{-- IMAGE (Builder style) --}}
            <img src="{{ $slide['img'] }}"
                 class="d-block w-100 hero-img"
                 alt="Slide">

            {{-- Overlay --}}
            <div class="hero-overlay"></div>

            {{-- Caption --}}
            <div class="carousel-caption">

                <div class="hero-premium">

                    <div class="hero-badge-wrapper">
                        <span class="hero-badge">
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
                           class="btn btn-danger btn-lg">
                           Lihat Armada
                        </a>

                        <a href="https://wa.me/628123456789"
                           target="_blank"
                           class="btn btn-outline-light btn-lg ms-2">
                           Konsultasi
                        </a>
                    </div>

                </div>

            </div>

        </div>
        @endforeach

    </div>

    {{-- Controls --}}
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
