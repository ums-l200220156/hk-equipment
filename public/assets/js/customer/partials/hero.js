/**
 * HERO CAROUSEL ENGINE - HK EQUIPMENT
 */
document.addEventListener("DOMContentLoaded", () => {
    const heroEl = document.getElementById("heroCarousel");

    if (heroEl) {
        const carousel = new bootstrap.Carousel(heroEl, {
            interval: 8000, // Sedikit lebih lambat agar customer sempat membaca
            ride: "carousel",
            pause: 'hover',
            wrap: true
        });

        // Add parallax effect on scroll (Optional but Cool)
        window.addEventListener('scroll', () => {
            const scroll = window.pageYOffset;
            const heroImg = document.querySelectorAll('.hero-img');
            heroImg.forEach(img => {
                img.style.transform = `translateY(${scroll * 0.4}px)`;
            });
        });
    }
});