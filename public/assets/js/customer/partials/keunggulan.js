/**
 * KEUNGGULAN SECTION ANIMATION
 */
document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll('.keunggulan-card');
    
    const observerOptions = {
        threshold: 0.2
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Memberikan delay bertahap untuk efek mengalir
                setTimeout(() => {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    entry.target.style.opacity = "1";
                }, index * 200); 
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    cards.forEach(card => {
        card.style.opacity = "0"; // Sembunyikan dulu sebelum animasi
        observer.observe(card);
    });
});