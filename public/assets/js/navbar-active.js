document.addEventListener("DOMContentLoaded", function () {

    const links = document.querySelectorAll(".nav-scroll");
    const sections = document.querySelectorAll("section[id]");

    if (!links.length) return;

    /**
     * ===============================
     * SET ACTIVE NAV LINK
     * ===============================
     */
    function setActiveLink(id) {
        links.forEach(link => {
            link.classList.toggle(
                "active",
                link.dataset.target === id
            );
        });
    }

    /**
     * ===============================
     * CLICK NAV (HOME vs OTHER PAGE)
     * ===============================
     */
    links.forEach(link => {
        link.addEventListener("click", function (e) {

            const targetId = this.dataset.target;
            if (!targetId) return;

            const isHome =
                window.location.pathname === "/" ||
                window.location.pathname === "";

            // 👉 JIKA DI HALAMAN HOME → SMOOTH SCROLL
            if (isHome) {
                e.preventDefault();

                const targetEl = document.getElementById(targetId);
                if (!targetEl) return;

                const yOffset = -90; // tinggi navbar
                const y =
                    targetEl.getBoundingClientRect().top +
                    window.pageYOffset +
                    yOffset;

                window.scrollTo({
                    top: y,
                    behavior: "smooth",
                });

                setActiveLink(targetId);
            }
            // 👉 JIKA BUKAN HOME → BIARKAN REDIRECT KE /#section
        });
    });

    /**
     * ===============================
     * SCROLL SPY (ACTIVE ON SCROLL)
     * ===============================
     */
    if (sections.length) {
        window.addEventListener("scroll", () => {
            let current = "";

            sections.forEach(section => {
                const sectionTop = section.offsetTop - 120;
                if (window.scrollY >= sectionTop) {
                    current = section.id;
                }
            });

            if (current) setActiveLink(current);
        });

        // trigger awal
        window.dispatchEvent(new Event("scroll"));
    }
});
