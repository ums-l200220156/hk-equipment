document.addEventListener("DOMContentLoaded", () => {

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const modalEl = document.getElementById("modalEditTestimoni");
    const modal = modalEl ? new bootstrap.Modal(modalEl) : null;

    /* =============================
       GLOBAL CLICK HANDLER
    ============================= */
    document.body.addEventListener("click", async e => {

        /* ===== EDIT ===== */
        const editBtn = e.target.closest(".btn-edit-testimoni");
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation(); // 🔥 PENTING

            if (!modal) {
                console.error("Modal Edit tidak ditemukan");
                return;
            }

            document.getElementById("edit-testimoni-id").value = editBtn.dataset.id;
            document.getElementById("edit-rating").value = editBtn.dataset.rating;
            document.getElementById("edit-content").value = editBtn.dataset.content;

            modal.show();
            return;
        }

        /* ===== DELETE ===== */
        const deleteBtn = e.target.closest(".btn-delete-testimoni");
        if (deleteBtn) {
            e.preventDefault();
            e.stopPropagation();

            Swal.fire({
                title: "Hapus Testimoni?",
                text: "Data tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545"
            }).then(async r => {
                if (!r.isConfirmed) return;

                const res = await fetch(`/testimoni/${deleteBtn.dataset.id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ _method: "DELETE" })
                });

                if (res.ok) {
                    Swal.fire("Dihapus", "Testimoni berhasil dihapus", "success")
                        .then(() => location.reload());
                } else {
                    Swal.fire("Gagal", "Tidak dapat menghapus testimoni", "error");
                }
            });
        }
    });

    /* =============================
       UPDATE TESTIMONI
    ============================= */
    const editForm = document.getElementById("form-edit-testimoni");
    if (editForm) {
        editForm.addEventListener("submit", async e => {
            e.preventDefault();

            const id = document.getElementById("edit-testimoni-id").value;
            const data = new FormData(editForm);
            data.append("_method", "PUT");

            const res = await fetch(`/testimoni/${id}`, {
                method: "POST",
                headers: { "X-CSRF-TOKEN": csrf },
                body: data
            });

            if (res.ok) {
                Swal.fire("Berhasil", "Testimoni diperbarui", "success")
                    .then(() => location.reload());
            } else {
                Swal.fire("Gagal", "Update gagal", "error");
            }
        });
    }

});
