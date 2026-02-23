document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('.rental-row');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const status = btn.dataset.status;

            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            rows.forEach(row => {
                const rowStatus = row.dataset.status;

                if (status === 'all' || rowStatus === status) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        });
    });
});


function previewImage(src, title) {
    const modalEl = document.getElementById('previewModal');

    if (!modalEl || typeof bootstrap === 'undefined') {
        console.error('Bootstrap / modal tidak siap');
        return;
    }

    document.getElementById('imgPreviewSource').src = src;
    document.getElementById('modalTitle').innerText = title;

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

function confirmCancel(button) {
    const form = button.closest('.cancel-form');
    Swal.fire({
        title: 'Batalkan Sewa?',
        text: "Pesanan yang dibatalkan tidak bisa diaktifkan kembali.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Kembali',
        reverseButtons: true,
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}