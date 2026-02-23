/**
 * ARMADA SECTION LOGIC
 */
document.addEventListener("DOMContentLoaded", () => {
    const alatCards = document.querySelectorAll('.alat-premium-card');

    alatCards.forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(30px)";
        
        setTimeout(() => {
            card.style.transition = "all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1)";
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, 300 + (index * 150));
    });
});