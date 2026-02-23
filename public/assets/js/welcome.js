/**
 * GLOBAL ENGINE - HK EQUIPMENT
 * Mengatur animasi Scroll Reveal untuk seluruh halaman
 */
document.addEventListener("DOMContentLoaded", () => {

    // Targetkan semua elemen yang ingin diberikan efek muncul saat di-scroll
    const revealElements = document.querySelectorAll(
        "section, .card, .keunggulan-card, .alat-premium-card, .step-card, .testimoni-premium-card"
    );

    // Tambahkan class awal 'reveal' secara otomatis
    revealElements.forEach(el => el.classList.add("reveal"));

    // Logic Detektor Scroll (Intersection Observer)
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Tambahkan class 'active' agar CSS menjalankan animasinya
                entry.target.classList.add("active");
                
                // Berhenti mengawasi elemen yang sudah muncul (agar lebih hemat RAM)
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15 // Elemen akan muncul saat 15% bagiannya terlihat di layar
    });

    // Jalankan pengawasan pada setiap elemen
    revealElements.forEach(el => observer.observe(el));

});