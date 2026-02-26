document.addEventListener('DOMContentLoaded', function () {

    /* =========================
       STAGGER ANIMATION
    ========================= */
    const items = document.querySelectorAll('.detail-item, .address-box-modern, .action-group-modern');

    items.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(25px)';
        item.style.transition = 'all 0.6s cubic-bezier(0.23, 1, 0.32, 1)';

        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, 120 * index);
    });

    /* =========================
       PARALLAX EFFECT (SAFE)
    ========================= */
    const brandSide = document.querySelector('.profile-side-brand');

    if (brandSide) {
        document.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth / 2 - e.pageX) / 40;
            const y = (window.innerHeight / 2 - e.pageY) / 40;

            brandSide.style.backgroundPosition = `${x}px ${y}px`;
        });
    }

});