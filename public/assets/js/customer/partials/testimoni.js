/**
 * TESTIMONI CORE SYSTEM - HK EQUIPMENT (FINAL STABLE VERSION)
 * Fully defensive & production-safe
 */
document.addEventListener("DOMContentLoaded", () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    /* ============================================================
       SAFE JSON PARSER (ANTI CRASH)
    ============================================================ */
    const safeJson = async (res) => {
        try {
            return await res.json();
        } catch {
            return { success: false, message: "Response server tidak valid" };
        }
    };

    /* ============================================================
       SAFE MODAL INIT
    ============================================================ */
    const modalEl = document.getElementById("modalEditTestimoni");
    const modalEdit = (modalEl && typeof bootstrap !== 'undefined')
        ? new bootstrap.Modal(modalEl)
        : null;

    /* ============================================================
       1. UI ANIMATIONS
    ============================================================ */
    const testimonialCards = document.querySelectorAll('.testimoni-premium-card');

    if ('IntersectionObserver' in window && testimonialCards.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__zoomIn');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        testimonialCards.forEach(card => observer.observe(card));
    }

    /* ============================================================
       2. CREATE TESTIMONI
    ============================================================ */
    const formTestimoni = document.getElementById("form-testimoni");

    if (formTestimoni) {
        formTestimoni.addEventListener("submit", async (e) => {
            e.preventDefault();

            const submitBtn = formTestimoni.querySelector('button[type="submit"]');
            if (!submitBtn) return;

            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengirim...';

            try {
                const res = await fetch(formTestimoni.action, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": csrf,
                        "Accept": "application/json"
                    },
                    body: new FormData(formTestimoni)
                });

                const result = await safeJson(res);

                if (res.ok && result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message || 'Berhasil mengirim testimoni',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    throw new Error(result.message || "Gagal mengirim testimoni");
                }

            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message || "Terjadi kesalahan",
                    timer: 1500,
                    showConfirmButton: false
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    /* ============================================================
       3. EDIT & DELETE (GLOBAL HANDLER)
    ============================================================ */
    document.body.addEventListener("click", async (e) => {

        /* ================= EDIT ================= */
        const editBtn = e.target.closest(".btn-edit-testimoni");
        if (editBtn) {
            e.preventDefault();

            const idEl = document.getElementById("edit-testimoni-id");
            const ratingEl = document.getElementById("edit-rating");
            const contentEl = document.getElementById("edit-content");

            if (!idEl || !ratingEl || !contentEl) return;

            idEl.value = editBtn.dataset.id || '';
            ratingEl.value = editBtn.dataset.rating || '';
            contentEl.value = editBtn.dataset.content || '';

            if (modalEdit) modalEdit.show();
            return;
        }

        /* ================= DELETE ================= */
        const deleteBtn = e.target.closest(".btn-delete-testimoni");
        if (deleteBtn) {
            e.preventDefault();

            const id = deleteBtn.dataset.id;
            if (!id) return;

            Swal.fire({
                title: "Hapus Testimoni?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then(async (r) => {

                if (!r.isConfirmed) return;

                try {

                    const res = await fetch(`/testimoni/${id}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": csrf,
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json"
                        }
                    });

                    const result = await safeJson(res);

                    if (res.ok && result.success) {

                        Swal.fire({
                            icon: "success",
                            title: "Dihapus",
                            text: result.message || "Berhasil dihapus",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());

                    } else {

                        throw new Error(result.message || "Gagal menghapus");

                    }

                } catch (error) {

                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: error.message || "Terjadi kesalahan",
                        timer: 1500,
                        showConfirmButton: false
                    });

                }

            });
        }
    });

    /* ============================================================
       4. UPDATE TESTIMONI
    ============================================================ */
    const formEditTestimoni = document.getElementById("form-edit-testimoni");

    if (formEditTestimoni) {
        formEditTestimoni.addEventListener("submit", async (e) => {
            e.preventDefault();

            const id = document.getElementById("edit-testimoni-id")?.value;
            if (!id) return;

            const submitBtn = formEditTestimoni.querySelector('button[type="submit"]');
            if (!submitBtn) return;

            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

            const data = new FormData(formEditTestimoni);
            data.append("_method", "PUT");

            try {

                const res = await fetch(`/testimoni/${id}`, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": csrf
                    },
                    body: data
                });

                const result = await safeJson(res);

                if (res.ok && result.success) {

                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: result.message || "Berhasil diperbarui",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());

                } else {

                    throw new Error(result.message || "Gagal memperbarui");

                }

            } catch (error) {

                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: error.message || "Terjadi kesalahan",
                    timer: 1500,
                    showConfirmButton: false
                });

            } finally {

                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

            }
        });
    }

});