document.addEventListener("DOMContentLoaded", () => {

    /* =========================================
       HERO CAROUSEL INIT (SMOOTH)
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

    /* =========================================
       SCROLL REVEAL ANIMATION
    ========================================= */

    const revealElements = document.querySelectorAll(
        "section, .card, .feature-box, .alat-card, .alat-premium-card"
    );

    revealElements.forEach(el => el.classList.add("reveal"));

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
            }
        });
    }, {
        threshold: 0.15
    });

    revealElements.forEach(el => observer.observe(el));

});
