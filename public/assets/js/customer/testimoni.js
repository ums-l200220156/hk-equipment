document.addEventListener("DOMContentLoaded", () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const modalEl = document.getElementById("modalEditTestimoni");
    const modal = modalEl ? new bootstrap.Modal(modalEl) : null;

    /* ============================================================
       1. HANDLER KIRIM TESTIMONI BARU (Mencegah Layar Putih JSON)
    ============================================================ */
    const createForm = document.getElementById("form-testimoni");
    if (createForm) {
        createForm.addEventListener("submit", async e => {
            e.preventDefault(); // Menghentikan reload halaman

            const submitBtn = createForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Tampilkan status loading pada tombol
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengirim...';

            const formData = new FormData(createForm);

            try {
                const res = await fetch(createForm.getAttribute('action'), {
                    method: "POST",
                    headers: { 
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": csrf 
                    },
                    body: formData
                });

                const result = await res.json();

                if (res.ok && result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 1800
                    }).then(() => location.reload()); // Reload agar testimoni muncul di carousel
                } else {
                    // Menangkap error jika user sudah pernah kirim testimoni
                    throw new Error(result.message || "Terjadi kesalahan.");
                }
            } catch (error) {
                Swal.fire("Gagal", error.message, "error");
                // Kembalikan tombol ke semula jika gagal
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    /* ============================================================
       2. GLOBAL CLICK HANDLER (EDIT & DELETE)
    ============================================================ */
    document.body.addEventListener("click", async e => {
        // --- LOGIKA EDIT ---
        const editBtn = e.target.closest(".btn-edit-testimoni");
        if (editBtn) {
            e.preventDefault();
            document.getElementById("edit-testimoni-id").value = editBtn.dataset.id;
            document.getElementById("edit-rating").value = editBtn.dataset.rating;
            document.getElementById("edit-content").value = editBtn.dataset.content;
            if(modal) modal.show();
            return;
        }

        // --- LOGIKA DELETE ---
        const deleteBtn = e.target.closest(".btn-delete-testimoni");
        if (deleteBtn) {
            e.preventDefault();
            Swal.fire({
                title: "Hapus Testimoni?",
                text: "Data tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonText: "Batal",
                confirmButtonText: "Ya, Hapus"
            }).then(async r => {
                if (!r.isConfirmed) return;
                const res = await fetch(`/testimoni/${deleteBtn.dataset.id}`, {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": csrf, "Content-Type": "application/json" },
                    body: JSON.stringify({ _method: "DELETE" })
                });
                if (res.ok) {
                    Swal.fire("Dihapus", "Testimoni berhasil dihapus", "success").then(() => location.reload());
                }
            });
        }
    });

    /* ============================================================
       3. UPDATE TESTIMONI HANDLER
    ============================================================ */
    const editForm = document.getElementById("form-edit-testimoni");
    if (editForm) {
        editForm.addEventListener("submit", async e => {
            e.preventDefault();
            const id = document.getElementById("edit-testimoni-id").value;
            const data = new FormData(editForm);
            data.append("_method", "PUT");

            const res = await fetch(`/testimoni/${id}`, {
                method: "POST",
                headers: { "X-Requested-With": "XMLHttpRequest", "X-CSRF-TOKEN": csrf },
                body: data
            });

            if (res.ok) {
                Swal.fire("Berhasil", "Testimoni diperbarui", "success").then(() => location.reload());
            } else {
                Swal.fire("Gagal", "Update gagal", "error");
            }
        });
    }


    /* =============================
   STORE TESTIMONI (CREATE)
============================= */
const formTestimoni = document.getElementById("form-testimoni");

if (formTestimoni) {
    formTestimoni.addEventListener("submit", async e => {
        e.preventDefault();

        const data = new FormData(formTestimoni);

        try {
            const res = await fetch(formTestimoni.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf
                },
                body: data
            });

            const json = await res.json();

            if (!res.ok) {
                throw json;
            }

            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: json.message
            }).then(() => location.reload());

        } catch (err) {

            let msg = "Terjadi kesalahan.";

            if (err?.message) msg = err.message;

            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: msg
            });
        }
    });
}

});