/**
 * MAPS INTERACTION - HK EQUIPMENT
 */
document.addEventListener("DOMContentLoaded", () => {
    const mapsWindow = document.querySelector('.maps-window');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    if (mapsWindow) {
        observer.observe(mapsWindow);
    }
});