document.addEventListener("DOMContentLoaded", () => {

    /* =========================================
       HERO CAROUSEL INIT
    ========================================= */
    const heroEl = document.getElementById("heroCarousel");
    if (heroEl) {
        new bootstrap.Carousel(heroEl, {
            interval: 7000,
            ride: "carousel",
            pause: false,
            touch: false,
            wrap: true
        });
    }
});
