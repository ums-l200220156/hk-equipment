/**
 * FOOTER INTERACTION - HK EQUIPMENT
 */
document.addEventListener("DOMContentLoaded", () => {
    // Animasi sederhana untuk link saat di-scroll
    const footerContent = document.querySelector('.main-footer');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeIn');
            }
        });
    }, { threshold: 0.1 });

    if (footerContent) observer.observe(footerContent);
});