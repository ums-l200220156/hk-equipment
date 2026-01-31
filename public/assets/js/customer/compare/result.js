document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("imageZoomModal");
    const modalImg = modal.querySelector(".zoom-image");
    const closeBtn = modal.querySelector(".close");

    document.querySelectorAll(".zoomable-image").forEach(img => {
        img.addEventListener("click", () => {
            modal.style.display = "flex";
            modalImg.src = img.dataset.src;
            modalImg.classList.remove("zoomed");
        });
    });

    modalImg.addEventListener("click", () => {
        modalImg.classList.toggle("zoomed");
    });

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    modal.addEventListener("click", e => {
        if (e.target === modal) modal.style.display = "none";
    });
});
