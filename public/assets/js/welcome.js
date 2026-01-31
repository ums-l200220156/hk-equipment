document.addEventListener("DOMContentLoaded", () => {
    const heroEl = document.getElementById("heroCarousel");
    if (!heroEl) return;

    new bootstrap.Carousel(heroEl, {
        interval: 7000, 
        ride: "carousel",
        pause: false,
        touch: false,
        wrap: true
    });
});
