/**
 * PROSES SEWA ANIMATION ENGINE
 */
document.addEventListener("DOMContentLoaded", () => {
    const steps = document.querySelectorAll('.step-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const index = Array.from(steps).indexOf(entry.target);
                
                // Animasi berurutan (Staggered)
                setTimeout(() => {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    entry.target.style.opacity = "1";
                }, index * 250); 
                
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    steps.forEach(step => {
        step.style.opacity = "0"; // Sembunyikan awal
        observer.observe(step);
    });
});